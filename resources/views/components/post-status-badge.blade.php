@if($status === \App\Enums\PostStatus::PUBLISH)
    <span class="badge bg-success">{{ $status->label() }}</span>
@elseif($status === \App\Enums\PostStatus::DRAFT)
    <span class="badge bg-warning">{{ $status->label() }}</span>
@else
    <span class="badge bg-danger">{{ $status->label() }}</span>
@endif
