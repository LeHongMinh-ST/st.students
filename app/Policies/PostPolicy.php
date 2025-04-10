<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use App\Services\SsoService;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('post.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('post.index') && $post->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('post.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('post.edit') && $post->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('post.delete') && $post->faculty_id === $userData['faculty_id'];
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Post $post): bool
    {
        $userData = app(SsoService::class)->getDataUser();

        if ($userData['role'] === Role::SuperAdmin->value) {
            return true;
        }

        return $user->hasPermission('post.publish') && $post->faculty_id === $userData['faculty_id'];
    }
}
