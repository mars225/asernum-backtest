<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        if (!($user->role === 'admin' || $reservation->customer_id === $user->id)) {
            return 'Vous n’êtes pas autorisé à voir cette réservation.';
        }
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateStatus(User $user, Reservation $reservation, string $newStatus): bool|string
    {
        if (!($user->role === 'admin' || $reservation->customer_id === $user->id)) {
            return 'Vous n’êtes pas autorisé à modifier cette réservation.';
        }

        if (!$reservation->canChangeStatus($newStatus)) {
            return $reservation->statusChangeMessage($newStatus);
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model (dans notre cas c'est cancelled).
     */
    public function delete(User $user, Reservation $reservation, string $newStatus): bool|string
    {
        if (!($user->role === 'admin' || $reservation->customer_id === $user->id)) {
            return 'Vous n’êtes pas autorisé à modifier cette réservation.';
        }

        if (!$reservation->canChangeStatus($newStatus)) {
            return $reservation->statusChangeMessage($newStatus);
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return false;
    }
}
