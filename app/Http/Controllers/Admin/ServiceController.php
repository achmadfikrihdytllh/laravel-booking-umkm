<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services.index', ['services' => Service::with('staff')->orderBy('sort_order')->orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service(['duration_minutes' => 30, 'is_active' => true]), 'staff' => Staff::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $service = Service::create($this->data($request));
        $service->staff()->sync($request->input('staff', []));

        return redirect()->route('admin.services.index')->with('ok', __('admin.saved'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', ['service' => $service->load('staff'), 'staff' => Staff::orderBy('name')->get()]);
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->data($request));
        $service->staff()->sync($request->input('staff', []));

        return redirect()->route('admin.services.index')->with('ok', __('admin.saved'));
    }

    public function destroy(Service $service)
    {
        if ($service->bookings()->exists()) {
            // Ada riwayat: jangan hapus, cukup nonaktifkan.
            $service->update(['is_active' => false]);

            return back()->with('ok', __('admin.deactivated_has_history'));
        }
        $service->delete();

        return back()->with('ok', __('admin.deleted'));
    }

    private function data(Request $r): array
    {
        $d = $r->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:600'],
            'price_label' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'staff' => ['array'],
            'staff.*' => ['integer', 'exists:staff,id'],
        ]);
        unset($d['staff']);
        $d['is_active'] = $r->boolean('is_active');
        $d['sort_order'] = $d['sort_order'] ?? 0;

        return $d;
    }
}
