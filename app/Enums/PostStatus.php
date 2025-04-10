<?php

declare(strict_types=1);

namespace App\Enums;

enum PostStatus: string
{
    case PUBLISH = 'publish';
    case DRAFT = 'draft';
    case HIDE = 'hide';

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::PUBLISH->value => self::PUBLISH->label(),
            self::DRAFT->value => self::DRAFT->label(),
            self::HIDE->value => self::HIDE->label(),
        ];
    }

    /**
     * Get the description of the enum value.
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            self::PUBLISH => 'Bài viết đã được xuất bản và hiển thị công khai.',
            self::DRAFT => 'Bài viết đang ở trạng thái nháp, chưa được xuất bản.',
            self::HIDE => 'Bài viết đã bị ẩn khỏi chế độ xem công khai.',
        };
    }

    /**
     * Get the label of the enum value.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::PUBLISH => 'Đã xuất bản',
            self::DRAFT => 'Bản nháp',
            self::HIDE => 'Ẩn',
        };
    }
}
