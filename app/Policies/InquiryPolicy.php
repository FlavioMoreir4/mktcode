<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InquiryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_inquiry');
    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        return $user->can('view_inquiry');
    }

    public function create(User $user): bool
    {
        return $user->can('create_inquiry');
    }

    public function update(User $user, Inquiry $inquiry): bool
    {
        return $user->can('update_inquiry');
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return $user->can('delete_inquiry');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_inquiry');
    }

    public function forceDelete(User $user, Inquiry $inquiry): bool
    {
        return $user->can('force_delete_inquiry');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_inquiry');
    }

    public function restore(User $user, Inquiry $inquiry): bool
    {
        return $user->can('restore_inquiry');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_inquiry');
    }

    public function replicate(User $user, Inquiry $inquiry): bool
    {
        return $user->can('replicate_inquiry');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_inquiry');
    }
}
