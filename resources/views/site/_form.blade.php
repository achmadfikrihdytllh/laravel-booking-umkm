<form method="post" action="{{ route('booking.store') }}" class="form" id="booking-form" novalidate
      data-slots-url="{{ route('slots') }}"
      data-t-pick="{{ __('booking.pick_first') }}" data-t-loading="{{ __('booking.loading') }}"
      data-t-none="{{ __('booking.no_slots') }}" data-t-fail="{{ __('booking.load_failed') }}"
      data-staff='@json($services->mapWithKeys(fn ($s) => [$s->id => $s->staff->map(fn ($p) => ["id" => $p->id, "name" => $p->name])->values()]))'>
    @csrf
    <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

    <label>{{ __('booking.service') }}
        <select name="service_id" id="f-service" required>
            <option value="">{{ __('booking.choose_service') }}</option>
            @foreach ($services as $s)
                <option value="{{ $s->id }}" @selected(old('service_id') == $s->id)>{{ $s->name }} ({{ __('site.minutes', ['n' => $s->duration_minutes]) }})</option>
            @endforeach
        </select>
    </label>

    <label>{{ staff_label() }}
        <select name="staff_id" id="f-staff">
            <option value="">{{ __('booking.any_staff') }}</option>
        </select>
    </label>

    <label>{{ __('booking.date') }}
        <input type="date" name="date" id="f-date" required value="{{ old('date') }}" min="{{ now()->toDateString() }}" max="{{ $maxDate }}">
    </label>

    <fieldset class="slots">
        <legend>{{ __('booking.time') }}</legend>
        <p class="muted" id="slots-msg">{{ __('booking.pick_first') }}</p>
        <div id="slots" role="radiogroup" aria-label="{{ __('booking.time') }}"></div>
        <input type="hidden" name="time" id="f-time" value="{{ old('time') }}">
    </fieldset>

    <label>{{ __('booking.name') }}
        <input type="text" name="name" required maxlength="100" autocomplete="name" value="{{ old('name') }}">
    </label>
    <label>{{ __('booking.phone') }}
        <input type="tel" name="phone" required inputmode="tel" autocomplete="tel" maxlength="20" value="{{ old('phone') }}">
        <small class="muted">{{ __('booking.phone_hint') }}</small>
    </label>
    <label>{{ __('booking.email') }}
        <input type="email" name="email" maxlength="150" autocomplete="email" value="{{ old('email') }}">
    </label>
    <label>{{ __('booking.notes') }}
        <textarea name="notes" rows="3" maxlength="500" placeholder="{{ __('booking.notes_ph') }}">{{ old('notes') }}</textarea>
    </label>

    <button class="btn" type="submit" id="f-submit">{{ __('booking.submit') }}</button>
    <p class="muted small">{{ __('booking.privacy') }}</p>
</form>
@push('scripts')
<script src="{{ asset('js/booking.js') }}?v=1" defer></script>
@endpush
