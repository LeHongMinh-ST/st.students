<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\LogActivity;
use App\Services\SsoService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogActivityHelper
{
    /**
     * Tạo log hoạt động
     *
     * @param string $action Hành động thực hiện
     * @param string $details Chi tiết hành động
     * @return void
     */
    public static function create($action, $details = ''): void
    {
        $userData = app(SsoService::class)->getDataUser();

        LogActivity::create([
            'user_id' => Auth::id(),
            'user_name' => $userData['full_name'],
            'action' => $action,
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Ghi log thay đổi của model
     *
     * @param string $action Hành động thực hiện
     * @param Model $model Model được thay đổi
     * @param array $oldData Dữ liệu cũ
     * @param array $newData Dữ liệu mới
     * @return void
     */
    public static function logChanges(string $action, Model $model, array $oldData, array $newData): void
    {
        $changes = [];
        $modelName = class_basename($model);
        $modelId = $model->id;

        foreach ($newData as $field => $value) {
            if (isset($oldData[$field]) && $oldData[$field] !== $value) {
                $changes[$field] = [
                    'old' => $oldData[$field],
                    'new' => $value
                ];
            }
        }

        if (empty($changes)) {
            return;
        }

        $details = "Thay đổi thông tin {$modelName} (ID: {$modelId}):\n";

        foreach ($changes as $field => $change) {
            $oldValue = null === $change['old'] ? 'N/A' : $change['old'];
            $newValue = null === $change['new'] ? 'N/A' : $change['new'];
            $details .= "- {$field}: {$oldValue} => {$newValue}\n";
        }

        self::create($action, $details);
    }
}
