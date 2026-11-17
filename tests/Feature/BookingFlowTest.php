<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\TimeOff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private Service $service;
    private Staff $a;
    private Staff $b;
    private Carbon $monday;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('Accept-Language', 'id-ID,id;q=0.9'); // klien tes bawaan mengirim en-US
        Carbon::setTestNow('2026-03-02 08:00:00'); // Senin
        $this->monday = Carbon::parse('2026-03-09'); // Senin depan

        $this->service = Service::create(['name' => 'Umum', 'duration_minutes' => 30]);
        $this->a = Staff::create(['name' => 'Dr A']);
        $this->b = Staff::create(['name' => 'Dr B']);
        $this->service->staff()->sync([$this->a->id, $this->b->id]);
        $this->a->schedules()->create(['weekday' => 1, 'start_time' => '09:00', 'end_time' => '11:00']);
        $this->b->schedules()->create(['weekday' => 1, 'start_time' => '10:00', 'end_time' => '12:00']);
    }

    private function payload(array $o = []): array
    {
        return array_merge([
            'service_id' => $this->service->id, 'staff_id' => $this->a->id,
            'date' => $this->monday->toDateString(), 'time' => '09:00',
            'name' => 'Budi', 'phone' => '0812-3456-7890',
        ], $o);
    }

    public function test_home_page_loads_in_indonesian_by_default(): void
    {
        $this->get('/')->assertOk()->assertSee('Buat reservasi');
    }

    public function test_browser_english_switches_language_and_manual_toggle_wins(): void
    {
        $this->withHeader('Accept-Language', 'en-US,en;q=0.9')->get('/')->assertSee('Make a booking');
        $this->get('/bahasa/id')->assertRedirect();
        $this->withHeader('Accept-Language', 'en-US')->get('/')->assertSee('Buat reservasi');
    }

    public function test_slots_respect_each_doctors_schedule(): void
    {
        $r = $this->getJson('/slot?service_id='.$this->service->id.'&date='.$this->monday->toDateString().'&staff_id='.$this->a->id)->assertOk();
        $this->assertSame(['09:00', '09:30', '10:00', '10:30'], collect($r->json('slots'))->pluck('time')->all());

        $r = $this->getJson('/slot?service_id='.$this->service->id.'&date='.$this->monday->toDateString())->assertOk();
        $times = collect($r->json('slots'))->pluck('time')->all();
        $this->assertContains('11:30', $times); // hanya dokter B
        $this->assertContains('09:00', $times);
    }

    public function test_guest_can_book_without_login_and_phone_is_normalized(): void
    {
        $this->post('/reservasi', $this->payload())->assertRedirect();
        $b = Booking::firstOrFail();
        $this->assertSame('6281234567890', $b->customer_phone);
        $this->assertSame('pending', $b->status);
        $this->assertSame('2026-03-09 09:00:00', $b->starts_at->format('Y-m-d H:i:s'));
        $this->get('/reservasi/'.$b->code)->assertOk()->assertSee($b->code)->assertSee('Budi');
    }

    public function test_double_booking_is_rejected(): void
    {
        $this->post('/reservasi', $this->payload())->assertRedirect();
        $this->from('/')->post('/reservasi', $this->payload(['name' => 'Siti']))->assertSessionHasErrors('time');
        $this->assertSame(1, Booking::count());
    }

    public function test_overlapping_times_are_rejected_but_adjacent_ok(): void
    {
        $this->post('/reservasi', $this->payload(['time' => '09:30']));
        $this->post('/reservasi', $this->payload(['time' => '09:00', 'name' => 'Xi']))->assertRedirect(); // 09:00-09:30 pas sebelum
        $this->assertSame(2, Booking::count());
    }

    public function test_any_staff_assigns_free_doctor(): void
    {
        $this->post('/reservasi', $this->payload(['time' => '10:00']));
        $this->post('/reservasi', $this->payload(['time' => '10:00', 'staff_id' => '', 'name' => 'Dua']))->assertRedirect();
        $this->assertEqualsCanonicalizing([$this->a->id, $this->b->id], Booking::pluck('staff_id')->all());
    }

    public function test_cancelled_slot_becomes_available_again(): void
    {
        $this->post('/reservasi', $this->payload());
        Booking::first()->update(['status' => 'cancelled']);
        $this->post('/reservasi', $this->payload(['name' => 'Baru']))->assertRedirect();
        $this->assertSame(2, Booking::count());
    }

    public function test_time_off_and_business_closure_block_slots(): void
    {
        TimeOff::create(['staff_id' => $this->a->id, 'start_date' => '2026-03-09', 'end_date' => '2026-03-09']);
        $r = $this->getJson('/slot?service_id='.$this->service->id.'&date=2026-03-09&staff_id='.$this->a->id);
        $this->assertSame([], $r->json('slots'));

        TimeOff::create(['staff_id' => null, 'start_date' => '2026-03-09', 'end_date' => '2026-03-09']);
        $r = $this->getJson('/slot?service_id='.$this->service->id.'&date=2026-03-09');
        $this->assertSame([], $r->json('slots'));
    }

    public function test_min_notice_and_max_advance(): void
    {
        Carbon::setTestNow('2026-03-09 08:30:00'); // hari-H, notice 2 jam => 10:30 ke atas
        $r = $this->getJson('/slot?service_id='.$this->service->id.'&date=2026-03-09&staff_id='.$this->a->id);
        $this->assertSame(['10:30'], collect($r->json('slots'))->pluck('time')->all());

        Setting::write('max_advance_days', 7);
        $r = $this->getJson('/slot?service_id='.$this->service->id.'&date=2026-03-23');
        $this->assertSame([], $r->json('slots'));
    }

    public function test_honeypot_blocks_bots(): void
    {
        $this->post('/reservasi', $this->payload(['website' => 'http://spam']))->assertRedirect(route('home'));
        $this->assertSame(0, Booking::count());
    }

    public function test_validation_messages(): void
    {
        $this->post('/reservasi', [])->assertSessionHasErrors(['service_id', 'date', 'time', 'name', 'phone']);
    }

    public function test_lookup_requires_code_and_phone_then_allows_cancel(): void
    {
        $this->post('/reservasi', $this->payload());
        $b = Booking::first();
        $this->get('/cek?code='.$b->code.'&phone=0899999999')->assertSee('tidak ditemukan');
        $this->flushSession();
        $this->post('/reservasi/'.$b->code.'/batal')->assertForbidden();

        $this->get('/cek?code='.$b->code.'&phone=081234567890')->assertRedirect('/reservasi/'.$b->code);
        $this->post('/reservasi/'.$b->code.'/batal')->assertRedirect();
        $this->assertSame('cancelled', $b->fresh()->status);
    }

    public function test_admin_pages_require_login_and_export_is_safe_csv(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/bookings/export')->assertRedirect('/admin/login');

        $this->post('/reservasi', $this->payload(['name' => '=HYPERLINK("x")']));
        $user = User::create(['name' => 'A', 'email' => 'a@a.test', 'password' => 'secret-pass']);
        $this->actingAs($user)->get('/admin')->assertOk();
        foreach (['bookings', 'services', 'staff', 'time-offs', 'posts', 'settings'] as $p) {
            $this->actingAs($user)->get("/admin/$p")->assertOk();
        }
        $csv = $this->actingAs($user)->get('/admin/bookings/export')->assertOk()->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString("'=HYPERLINK", $csv);
        $this->assertStringContainsString('Menunggu konfirmasi', $csv);
    }

    public function test_admin_login_and_settings_update_changes_site_brand(): void
    {
        User::create(['name' => 'A', 'email' => 'a@a.test', 'password' => 'secret-pass']);
        $this->post('/admin/login', ['email' => 'a@a.test', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => 'a@a.test', 'password' => 'secret-pass'])->assertRedirect('/admin');

        $this->put('/admin/settings', [
            'business_name' => 'Barber Keren', 'staff_label_id' => 'Barber', 'staff_label_en' => 'Barber',
            'service_label_id' => 'Layanan', 'service_label_en' => 'Service', 'brand_color' => '#aa0000',
            'slot_step' => 30, 'min_notice_hours' => 1, 'max_advance_days' => 30, 'whatsapp' => '0811111111',
        ])->assertSessionHasNoErrors();

        $this->get('/')->assertSee('Barber Keren')->assertSee('#aa0000', false)->assertSee('wa.me/62811111111', false);
    }

    public function test_posts_publish_and_hide_drafts(): void
    {
        $user = User::create(['name' => 'A', 'email' => 'a@a.test', 'password' => 'secret-pass']);
        $this->actingAs($user)->post('/admin/posts', ['title' => 'Promo Besar', 'body' => 'isi', 'status' => 'published'])->assertRedirect();
        $this->actingAs($user)->post('/admin/posts', ['title' => 'Rahasia', 'body' => 'x', 'status' => 'draft']);
        $this->get('/blog')->assertSee('Promo Besar')->assertDontSee('Rahasia');
        $this->get('/blog/promo-besar')->assertOk();
        $this->get('/blog/rahasia')->assertNotFound();
    }

    public function test_service_with_history_is_deactivated_not_deleted(): void
    {
        $this->post('/reservasi', $this->payload());
        $user = User::create(['name' => 'A', 'email' => 'a@a.test', 'password' => 'secret-pass']);
        $this->actingAs($user)->delete('/admin/services/'.$this->service->id)->assertRedirect();
        $this->assertFalse($this->service->fresh()->is_active);
    }
}
