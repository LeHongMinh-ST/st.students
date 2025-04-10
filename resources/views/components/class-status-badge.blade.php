@if($status === \App\Enums\Status::Active)
    <span class="badge bg-success">{{ $status->label() }}</span>
@else
    <span class="badge bg-danger">{{ $status->label() }}</span>
@endif
