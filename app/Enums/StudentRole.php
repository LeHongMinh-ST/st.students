<?php

declare(strict_types=1);

namespace App\Enums;

enum StudentRole: string
{
    case President = 'president';
    case VicePresident = 'vice_president';
    case Secretary = 'secretary';
    case ViceSecretary = 'vice_secretary';
    case Basic = 'basic';

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::President->value => self::President->label(),
            self::VicePresident->value => self::VicePresident->label(),
            self::Secretary->value => self::Secretary->label(),
            self::ViceSecretary->value => self::ViceSecretary->label(),
            self::Basic->value => self::Basic->label(),
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
            self::President => 'Lớp trưởng, người đại diện chính thức của lớp học.',
            self::VicePresident => 'Phó lớp trưởng, hỗ trợ lớp trưởng trong các công việc quản lý lớp.',
            self::Secretary => 'Bí thư, phụ trách các hoạt động đoàn và công tác chính trị.',
            self::ViceSecretary => 'Phó bí thư, hỗ trợ bí thư trong các hoạt động đoàn.',
            self::Basic => 'Sinh viên thông thường, không giữ chức vụ đặc biệt trong lớp.',
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
            self::President => 'Lớp trưởng',
            self::VicePresident => 'Phó lớp trưởng',
            self::Secretary => 'Bí thư',
            self::ViceSecretary => 'Phó bí thư',
            self::Basic => 'Sinh viên',
        };
    }

    /**
     * Get the badge color for the role.
     *
     * @return string
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::President => 'bg-primary',
            self::Secretary => 'bg-info',
            self::VicePresident, self::ViceSecretary => 'bg-secondary',
            self::Basic => 'bg-light text-dark',
        };
    }
}
