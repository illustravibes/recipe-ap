<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Recipe;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_recipe');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Recipe $recipe): bool
    {
        return $user->can('view_recipe');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_recipe');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->can('update_recipe');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->can('delete_recipe');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_recipe');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Recipe $recipe): bool
    {
        return $user->can('force_delete_recipe');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_recipe');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Recipe $recipe): bool
    {
        return $user->can('restore_recipe');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_recipe');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Recipe $recipe): bool
    {
        return $user->can('replicate_recipe');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_recipe');
    }

    /**
     * Determine whether the user can view secret instructions.
     */
    public function viewSecretInstructions(User $user, Recipe $recipe)
    {
        // Custom logic: owner or admin can view
        return $user->id === $recipe->user_id || $user->isAdmin();
    }
}
