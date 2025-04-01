<?php

declare(strict_types=1);

namespace App\Enums;

enum StudentStatus: string
{
    case CurrentlyStudying = 'currently_studying';
    case Graduated = 'graduated';
    case ToDropOut = 'to_drop_out';
    case TemporarilySuspended = 'temporarily_suspended';
    case Expelled = 'expelled';
    case Deferred = 'deferred';
    case TransferStudy = 'transfer_study';

    public static function getDescription()
    {
        return [
            self::CurrentlyStudying->value => 'Đang theo học',
            self::Graduated->value => 'Đã tốt nghiệp',
            self::ToDropOut->value => 'Xin thôi học',
            self::TemporarilySuspended->value => 'Tạm ngừng học',
            self::Expelled->value => 'Buộc thôi học',
            self::Deferred->value => 'Bảo lưu',
            self::TransferStudy->value => 'Chuyển ngành học',
        ];
    }

    public function getLabel(): string
    {
        return self::getDescription()[$this->value];
    }
}
