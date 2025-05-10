<?php

declare(strict_types=1);

namespace App\Enums;

enum FeedbackStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Resolved = 'resolved';
    case Rejected = 'rejected';

    /**
     * Lấy nhãn hiển thị cho trạng thái
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Chờ xử lý',
            self::Processing => 'Đang xử lý',
            self::Resolved => 'Đã giải quyết',
            self::Rejected => 'Từ chối',
        };
    }

    /**
     * Lấy màu badge cho trạng thái
     */
    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'bg-secondary',
            self::Processing => 'bg-primary',
            self::Resolved => 'bg-success',
            self::Rejected => 'bg-danger',
        };
    }
}
