<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Staff;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    private function filtered(Request $r)
    {
        return Booking::query()->with(['service', 'staff'])
            ->when($r->filled('status'), fn ($q) => $q->where('status', $r->query('status')))
            ->when($r->filled('staff_id'), fn ($q) => $q->where('staff_id', $r->query('staff_id')))
            ->when($r->filled('from'), fn ($q) => $q->whereDate('starts_at', '>=', $r->query('from')))
            ->when($r->filled('to'), fn ($q) => $q->whereDate('starts_at', '<=', $r->query('to')))
            ->when($r->filled('q'), function ($q) use ($r) {
                $term = trim((string) $r->query('q'));
                $digits = preg_replace('/\D+/', '', $term);
                $q->where(function ($w) use ($term, $digits) {
                    $w->where('customer_name', 'like', "%$term%")->orWhere('code', 'like', '%'.strtoupper($term).'%');
                    if ($digits !== '') {
                        $w->orWhere('customer_phone', 'like', '%'.ltrim($digits, '0').'%');
                    }
                });
            });
    }

    public function index(Request $request)
    {
        return view('admin.bookings.index', [
            'bookings' => $this->filtered($request)->orderByDesc('starts_at')->paginate(20)->withQueryString(),
            'staff' => Staff::orderBy('name')->get(),
        ]);
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', ['booking' => $booking->load(['service', 'staff'])]);
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', config('booking.statuses'))],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $booking->update($data);

        return back()->with('ok', __('admin.saved'));
    }

    /** Ekspor CSV yang bisa langsung dibuka di Excel (UTF-8 + BOM, pemisah titik koma ala Indonesia). */
    public function export(Request $request): StreamedResponse
    {
        $query = $this->filtered($request)->orderBy('starts_at');
        $name = 'riwayat-reservasi-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Kode', 'Tanggal', 'Jam', 'Selesai', 'Layanan', staff_label(), 'Nama', 'WhatsApp', 'Email', 'Status', 'Catatan pelanggan', 'Catatan admin', 'Dibuat'], ';');
            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $b) {
                    fputcsv($out, [
                        $b->code,
                        $b->starts_at->format('Y-m-d'),
                        $b->starts_at->format('H:i'),
                        $b->ends_at->format('H:i'),
                        $b->service->name,
                        $b->staff->name,
                        $this->safe($b->customer_name),
                        "'".$b->customer_phone,
                        $b->customer_email,
                        __('booking.status.'.$b->status),
                        $this->safe($b->notes),
                        $this->safe($b->admin_notes),
                        $b->created_at->format('Y-m-d H:i'),
                    ], ';');
                }
            });
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Cegah "CSV injection": sel yang diawali = + - @ dianggap rumus oleh Excel. */
    private function safe(?string $v): ?string
    {
        if ($v !== null && $v !== '' && in_array($v[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$v;
        }

        return $v;
    }
}
