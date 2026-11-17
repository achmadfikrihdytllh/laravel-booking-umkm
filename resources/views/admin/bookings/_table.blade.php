<div class="tablewrap">
<table>
    <thead><tr><th>{{ __('admin.when') }}</th><th>{{ __('admin.customer') }}</th><th>{{ __('admin.service') }}</th><th>{{ staff_label() }}</th><th>{{ __('admin.status') }}</th><th></th></tr></thead>
    <tbody>
    @forelse ($bookings as $b)
        <tr>
            <td>{{ $b->starts_at->format('d/m/Y') }}<br><span class="muted">{{ $b->starts_at->format('H:i') }}</span></td>
            <td>{{ $b->customer_name }}<br><span class="muted">{{ $b->customer_phone }}</span></td>
            <td>{{ $b->service->name }}</td>
            <td>{{ $b->staff->name }}</td>
            <td><span class="badge badge--{{ $b->status }}">{{ __('booking.status.'.$b->status) }}</span></td>
            <td><a href="{{ route('admin.bookings.show', $b) }}">{{ __('admin.open') }}</a></td>
        </tr>
    @empty
        <tr><td colspan="6" class="muted">{{ __('admin.none') }}</td></tr>
    @endforelse
    </tbody>
</table>
</div>
