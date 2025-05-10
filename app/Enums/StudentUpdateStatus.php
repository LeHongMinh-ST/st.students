<?php

declare(strict_types=1);

namespace App\Enums;

enum StudentUpdateStatus: string
{
    case Pending = 'pending';
    case ClassOfficerApproved = 'class_officer_approved';
    case TeacherApproved = 'teacher_approved';
    case OfficerApproved = 'officer_approved';
    case Reject = 'reject';

    /**
     * Get all enum values with their labels.
     *
     * @return array<string, string>
     */
    public static function getLabels(): array
    {
        return [
            self::Pending->value => self::Pending->label(),
            self::ClassOfficerApproved->value => self::ClassOfficerApproved->label(),
            self::TeacherApproved->value => self::TeacherApproved->label(),
            self::OfficerApproved->value => self::OfficerApproved->label(),
            self::Reject->value => self::Reject->label(),
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
            self::Pending => 'Yêu cầu đang chờ xử lý, chưa được duyệt bởi bất kỳ ai.',
            self::ClassOfficerApproved => 'Yêu cầu đã được lớp trưởng duyệt, đang chờ giáo viên chủ nhiệm duyệt.',
            self::TeacherApproved => 'Yêu cầu đã được giáo viên chủ nhiệm duyệt, đang chờ cán bộ quản lý duyệt.',
            self::OfficerApproved => 'Yêu cầu đã được duyệt hoàn toàn và thông tin đã được cập nhật.',
            self::Reject => 'Yêu cầu đã bị từ chối bởi một trong các cấp duyệt.',
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
            self::Pending => 'Đang chờ duyệt',
            self::ClassOfficerApproved => 'Lớp trưởng đã duyệt',
            self::TeacherApproved => 'Giáo viên đã duyệt',
            self::OfficerApproved => 'Đã duyệt hoàn tất',
            self::Reject => 'Đã từ chối',
        };
    }

    /**
     * Get the badge color for the status.
     *
     * @return string
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::Pending => 'bg-warning',
            self::ClassOfficerApproved => 'bg-info',
            self::TeacherApproved => 'bg-primary',
            self::OfficerApproved => 'bg-success',
            self::Reject => 'bg-danger',
        };
    }
}
