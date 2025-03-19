<div class="input-group">
    <span class="input-group-text"><i class="ph-buildings"></i></span>
    <select class="form-select" wire:model.live="facultyId">
        <option value="" @if (!$facultyId) selected @else disabled @endif>Chọn khoa </option>
        @foreach ($faculties as $item)
            <option value="{{ $item['id'] }}" @if ($item['id'] == $facultyId) selected @endif>{{ $item['name'] }} </option>
        @endforeach
    </select>
</div>

@script
    <script>
        window.addEventListener('reloadPage', () => {
            window.location.reload();
        })
    </script>
@endscript
