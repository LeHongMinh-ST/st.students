<div>
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bold">
                            <i class="ph-buildings"></i>
                            Thông tin vai trò
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <label for="name" class="col-form-label">
                                        Tên <span class="required">*</span>
                                    </label>
                                    <input wire:model.live="name" type="text" id="name"
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="description" class="col-form-label">
                                        Mô tả
                                    </label>
                                    <textarea wire:model.live="description" id="description"
                                              class="form-control @error('description') is-invalid @enderror"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bold">
                            <i class="ph-buildings"></i>
                            Quyền
                        </div>
                        <div class="card-body">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" wire:model.live="selectAll" id="selectAll">
                                <label class="form-check-label fw-bold" for="selectAll">Tất cả các quyền</label>
                            </div>
                            @foreach ($groupPermissions as $group)
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" wire:model.live="groupIds" value="{{ $group->id }}" id="group-{{ $group->id }}"
                                                           @if (in_array($group->id, $groupIds)) checked @endif>
                                                    <label class="form-check-label" for="group-{{ $group->id }}">{{ $group->name }}</label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($group->permissions as $item)
                                                        <div class="col-12 col-sm-6 col-md-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" wire:model.live="permissionIds" class="form-check-input" value="{{ $item->id }}" id="permission-{{ $item->id }}"
                                                                       @if (in_array($item->id, $permissionIds)) checked @endif>
                                                                <label class="form-check-label" for="permission-{{ $item->id }}">{{ $item->name }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card">
                <div class="card-header bold">
                    Hành động
                </div>
                <div class="gap-2 card-body d-flex justify-content-center">
                    <button wire:loading wire:target="submit" class="shadow btn btn-primary fw-semibold flex-fill">
                        <i class="ph-circle-notch spinner fw-semibold"></i>
                        Lưu
                    </button>
                    <button wire:click="submit" wire:loading.remove class="shadow btn btn-primary fw-semibold flex-fill">
                        <i class="ph-floppy-disk fw-semibold"></i>
                        Lưu
                    </button>
                    <a href="{{ route('roles.index') }}" type="button" class="btn btn-warning flex-fill fw-semibold"><i
                           class="ph-arrow-counter-clockwise fw-semibold"></i> Trở lại</a>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        window.addEventListener('setGroupIndeterminate', function(state) {
            document.getElementById(`group-${state.detail.groupId}`).indeterminate = state.detail.indeterminate;

        });
    </script>
@endscript
