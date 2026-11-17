<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Staff;
use App\Models\TimeOff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Menghitung jam kosong dari jadwal mingguan, cuti/libur, dan reservasi yang sudah ada.
 */
class Availability
{
    /**
     * @return Collection<int,array{time:string,staff_id:int,staff_name:string}>
     *   Satu baris per jam mulai. Bila $staffId kosong, tiap jam diisi petugas pertama yang kosong.
     */
    public function slots(Service $service, Carbon $date, ?int $staffId = null): Collection
    {
        $date = $date->copy()->startOfDay();

        if (! $this->dateBookable($date)) {
            return collect();
        }

        $staffList = $service->staff()->active()
            ->when($staffId, fn ($q) => $q->where('staff.id', $staffId))
            ->with('schedules')
            ->get();

        $byTime = [];
        foreach ($staffList as $staff) {
            foreach ($this->staffSlots($staff, $service, $date) as $time) {
                $byTime[$time] ??= ['time' => $time, 'staff_id' => $staff->id, 'staff_name' => $staff->name];
            }
        }
        ksort($byTime);

        return collect(array_values($byTime));
    }

    public function dateBookable(Carbon $date): bool
    {
        $today = now()->startOfDay();
        $max = $today->copy()->addDays((int) setting('max_advance_days', 60));

        return $date->betweenIncluded($today, $max) && ! $this->businessClosed($date);
    }

    public function businessClosed(Carbon $date): bool
    {
        return TimeOff::query()->whereNull('staff_id')
            ->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)->exists();
    }

    /** @return list<string> daftar "HH:MM" */
    private function staffSlots(Staff $staff, Service $service, Carbon $date): array
    {
        $onLeave = TimeOff::query()->where('staff_id', $staff->id)
            ->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)->exists();
        if ($onLeave) {
            return [];
        }

        $ranges = $staff->schedules->where('weekday', $date->dayOfWeekIso);
        if ($ranges->isEmpty()) {
            return [];
        }

        $busy = Booking::query()->where('staff_id', $staff->id)
            ->whereIn('status', config('booking.blocking_statuses'))
            ->where('starts_at', '<', $date->copy()->endOfDay())
            ->where('ends_at', '>', $date)
            ->get(['starts_at', 'ends_at']);

        $step = max(5, (int) setting('slot_step', 30));
        $duration = max(5, (int) $service->duration_minutes);
        $earliest = now()->addHours((int) setting('min_notice_hours', 2));

        $out = [];
        foreach ($ranges as $r) {
            $start = $this->at($date, $r->start_time);
            $end = $this->at($date, $r->end_time);
            for ($t = $start->copy(); $t->copy()->addMinutes($duration)->lte($end); $t->addMinutes($step)) {
                $slotEnd = $t->copy()->addMinutes($duration);
                if ($t->lt($earliest)) {
                    continue;
                }
                $clash = $busy->contains(fn ($b) => $b->starts_at->lt($slotEnd) && $b->ends_at->gt($t));
                if (! $clash) {
                    $out[] = $t->format('H:i');
                }
            }
        }

        return array_values(array_unique($out));
    }

    private function at(Carbon $date, string $hhmm): Carbon
    {
        [$h, $m] = array_map('intval', explode(':', $hhmm));

        return $date->copy()->setTime($h, $m);
    }
}
