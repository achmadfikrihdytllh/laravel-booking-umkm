<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index()
    {
        return view('admin.staff.index', ['staffList' => Staff::with('services')->orderBy('sort_order')->orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.staff.form', ['staff' => new Staff(['is_active' => true]), 'services' => Service::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $staff = Staff::create($this->data($request));
        $staff->services()->sync($request->input('services', []));

        // Jadwal bawaan Senin-Jumat 09:00-17:00 supaya langsung bisa dipakai; bisa diubah di halaman edit.
        foreach (range(1, 5) as $day) {
            $staff->schedules()->create(['weekday' => $day, 'start_time' => '09:00', 'end_time' => '17:00']);
        }

        return redirect()->route('admin.staff.edit', $staff)->with('ok', __('admin.saved'));
    }

    public function edit(Staff $staff)
    {
        return view('admin.staff.form', [
            'staff' => $staff->load(['services', 'schedules']),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Staff $staff)
    {
        $staff->update($this->data($request, $staff));
        $staff->services()->sync($request->input('services', []));

        return redirect()->route('admin.staff.edit', $staff)->with('ok', __('admin.saved'));
    }

    public function destroy(Staff $staff)
    {
        if ($staff->bookings()->exists()) {
            $staff->update(['is_active' => false]);

            return back()->with('ok', __('admin.deactivated_has_history'));
        }
        if ($staff->photo_path) {
            Storage::disk('public')->delete($staff->photo_path);
        }
        $staff->delete();

        return back()->with('ok', __('admin.deleted'));
    }

    /** Jadwal mingguan: input berbentuk schedule[hari][] = {start,end}. */
    public function saveSchedule(Request $request, Staff $staff)
    {
        $request->validate([
            'schedule' => ['array'],
            'schedule.*' => ['array'],
            'schedule.*.*.start' => ['nullable', 'date_format:H:i'],
            'schedule.*.*.end' => ['nullable', 'date_format:H:i'],
        ]);

        $rows = [];
        foreach ((array) $request->input('schedule', []) as $day => $ranges) {
            $day = (int) $day;
            if ($day < 1 || $day > 7) {
                continue;
            }
            foreach ((array) $ranges as $r) {
                $s = $r['start'] ?? null;
                $e = $r['end'] ?? null;
                if (! $s || ! $e) {
                    continue;
                }
                if ($e <= $s) {
                    return back()->withErrors(['schedule' => __('admin.schedule_end_after_start')])->withInput();
                }
                $rows[] = ['staff_id' => $staff->id, 'weekday' => $day, 'start_time' => $s, 'end_time' => $e];
            }
        }

        DB::transaction(function () use ($staff, $rows) {
            $staff->schedules()->delete();
            if ($rows) {
                DB::table('staff_schedules')->insert($rows);
            }
        });

        return back()->with('ok', __('admin.saved'));
    }

    private function data(Request $r, ?Staff $staff = null): array
    {
        $d = $r->validate([
            'name' => ['required', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'services' => ['array'],
            'services.*' => ['integer', 'exists:services,id'],
        ]);
        unset($d['services'], $d['photo']);
        $d['is_active'] = $r->boolean('is_active');
        $d['sort_order'] = $d['sort_order'] ?? 0;

        if ($r->hasFile('photo')) {
            if ($staff?->photo_path) {
                Storage::disk('public')->delete($staff->photo_path);
            }
            $d['photo_path'] = $r->file('photo')->store('staff', 'public');
        }

        return $d;
    }
}
