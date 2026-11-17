<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Services\Availability;
use App\Services\BookingService;
use App\Services\SlotUnavailable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    /** JSON jam kosong untuk layanan + tanggal (+ petugas opsional). */
    public function slots(Request $request, Availability $availability)
    {
        $data = $request->validate([
            'service_id' => ['required', 'integer'],
            'date' => ['required', 'date_format:Y-m-d'],
            'staff_id' => ['nullable', 'integer'],
        ]);

        $service = Service::active()->find($data['service_id']);
        if (! $service) {
            return response()->json(['slots' => []]);
        }

        $slots = $availability->slots($service, Carbon::parse($data['date']), $data['staff_id'] ?? null);

        return response()->json(['slots' => $slots->values()]);
    }

    public function store(Request $request, BookingService $bookings)
    {
        // Honeypot: bot mengisi kolom tersembunyi ini, manusia tidak.
        if ($request->filled('website')) {
            return redirect()->route('home');
        }

        $data = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'staff_id' => ['nullable', 'integer', 'exists:staff,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if (strlen(normalize_phone($data['phone'])) < 9) {
            return back()->withInput()->withErrors(['phone' => __('booking.phone_invalid')]);
        }

        try {
            $booking = $bookings->create($data + ['locale' => app()->getLocale()]);
        } catch (SlotUnavailable) {
            return back()->withInput()->withErrors(['time' => __('booking.slot_taken')]);
        }

        $request->session()->put('verified_'.$booking->code, true);

        return redirect()->route('booking.show', $booking->code)->with('just_created', true);
    }

    /** Halaman konfirmasi. Kode dikirim pelanggan sendiri; detail sensitif disamarkan kecuali baru dibuat. */
    public function show(Request $request, string $code)
    {
        $booking = Booking::with(['service', 'staff'])->where('code', strtoupper($code))->firstOrFail();

        return view('site.booking', [
            'booking' => $booking,
            'justCreated' => (bool) $request->session()->get('just_created'),
            'verified' => (bool) $request->session()->get('verified_'.$booking->code, false),
        ]);
    }

    /** Cek status pakai kode + nomor HP. */
    public function lookup(Request $request)
    {
        $code = strtoupper(trim((string) $request->query('code')));
        $phone = normalize_phone((string) $request->query('phone'));

        if ($code === '' && $phone === '') {
            return view('site.lookup', ['searched' => false, 'booking' => null]);
        }

        $booking = Booking::with(['service', 'staff'])->where('code', $code)->where('customer_phone', $phone)->first();
        if ($booking) {
            $request->session()->put('verified_'.$booking->code, true);

            return redirect()->route('booking.show', $booking->code);
        }

        return view('site.lookup', ['searched' => true, 'booking' => null]);
    }

    public function cancel(Request $request, string $code)
    {
        $booking = Booking::where('code', strtoupper($code))->firstOrFail();

        if (! $request->session()->get('verified_'.$booking->code)) {
            abort(403);
        }

        if ($booking->isActive() && $booking->starts_at->isFuture()) {
            $booking->update(['status' => 'cancelled']);
        }

        return redirect()->route('booking.show', $booking->code)->with('cancelled', true);
    }
}
