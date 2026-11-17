<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(private Availability $availability) {}

    /**
     * @param array{service_id:int,staff_id?:?int,date:string,time:string,name:string,phone:string,email?:?string,notes?:?string,locale?:string} $d
     * @throws SlotUnavailable
     */
    public function create(array $d): Booking
    {
        return DB::transaction(function () use ($d) {
            $service = Service::query()->active()->findOrFail($d['service_id']);
            $date = Carbon::parse($d['date'])->startOfDay();

            $slot = $this->availability
                ->slots($service, $date, $d['staff_id'] ?? null)
                ->firstWhere('time', $d['time']);

            if (! $slot) {
                throw new SlotUnavailable('Slot tidak tersedia.');
            }

            [$h, $m] = array_map('intval', explode(':', $d['time']));
            $start = $date->copy()->setTime($h, $m);
            $end = $start->copy()->addMinutes($service->duration_minutes);

            // Kunci baris yang bertabrakan lalu cek ulang (melindungi dari dua orang yang menekan "kirim" bersamaan).
            $clash = Booking::query()->where('staff_id', $slot['staff_id'])
                ->whereIn('status', config('booking.blocking_statuses'))
                ->where('starts_at', '<', $end)->where('ends_at', '>', $start)
                ->lockForUpdate()->exists();
            if ($clash) {
                throw new SlotUnavailable('Slot baru saja terisi.');
            }

            return Booking::create([
                'code' => Booking::makeCode(),
                'service_id' => $service->id,
                'staff_id' => $slot['staff_id'],
                'customer_name' => trim($d['name']),
                'customer_phone' => normalize_phone($d['phone']),
                'customer_email' => $d['email'] ?? null,
                'starts_at' => $start,
                'ends_at' => $end,
                'status' => (int) setting('auto_confirm', 0) ? 'confirmed' : 'pending',
                'notes' => $d['notes'] ?? null,
                'locale' => $d['locale'] ?? app()->getLocale(),
            ]);
        });
    }
}
