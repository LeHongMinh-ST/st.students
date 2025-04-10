@if($relationship)
    <span class="badge {{ $relationship->badgeColor() }}">{{ $relationship->label() }}</span>
@else
    <span class="badge bg-light text-dark">N/A</span>
@endif
