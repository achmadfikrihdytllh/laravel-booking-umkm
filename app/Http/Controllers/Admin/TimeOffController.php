<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\TimeOff;
use Illuminate\Http\Request;

class TimeOffController extends Controller
{
    public function index()
    {
        return view('admin.timeoffs', [
            'timeOffs' => TimeOff::with('staff')->orderByDesc('start_date')->get(),
            'staff' => Staff::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'staff_id' => ['nullable', 'integer', 'exists:staff,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:120'],
        ]);
        TimeOff::create($d);

        return back()->with('ok', __('admin.saved'));
    }

    public function destroy(TimeOff $timeOff)
    {
        $timeOff->delete();

        return back()->with('ok', __('admin.deleted'));
    }
}
