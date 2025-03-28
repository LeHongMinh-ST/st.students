<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusImport: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
    case PartialyFaild = 'partially_failed';



    public static function getDescription()
    {
        return [
            self::Pending->value => 'Đang chờ',
            self::Processing->value => 'Đang xử lý',
            self::Completed->value => 'Hoành thành',
            self::Failed->value => 'Thất bại',
            self::PartialyFaild->value => 'Có bản ghi lỗi',
        ];
    }

    public function getLabel(): string
    {
        return self::getDescription()[$this->value];
    }
}
