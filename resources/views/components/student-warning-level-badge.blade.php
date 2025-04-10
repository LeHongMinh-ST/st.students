@if($student->warningLevel)
    <span class="badge {{ $student->warningLevel->badgeColor() }}">{{ $student->warningLevel->label() }}</span>
@endif
