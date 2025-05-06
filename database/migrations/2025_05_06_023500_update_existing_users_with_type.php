<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing users with the appropriate type based on their role
        $users = User::all();

        foreach ($users as $user) {
            if (!$user->type) {
                $type = match ($user->role) {
                    Role::SuperAdmin->value => UserType::Admin->value,
                    Role::Officer->value => UserType::Officer->value,
                    Role::Student->value => UserType::Student->value,
                    default => UserType::Teacher->value,
                };

                $user->update(['type' => $type]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration
    }
};
