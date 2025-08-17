<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mattiverse\Userstamps\Traits\Userstamps;

class Reservation extends Model
{
    use Userstamps, HasFactory;

    protected $fillable = ['customer_id','room_id','start_date','end_date','status'
    ];

        /**
     * Vérifie si un changement de statut est valide.
     */
    public function canChangeStatus(string $newStatus): bool
    {
        return match ($this->status) {
            'pending' => in_array($newStatus, ['confirmed', 'cancelled']),
            'confirmed' => $newStatus === 'finished',
            'cancelled', 'finished' => false,
            default => false,
        };
    }

    /**
     * Optionnel : message d'erreur si changement impossible
     */
    public function statusChangeMessage(string $newStatus): string
    {
        return match ($this->status) {
            'pending' => "Une réservation en attente peut passer seulement en 'confirmed' ou 'cancelled'.",
            'confirmed' => "Une réservation confirmée ne peut passer qu'en 'finished'.",
            'cancelled' => "Une réservation annulée ne peut plus changer de statut.",
            'finished' => "Une réservation terminée ne peut plus changer de statut.",
            default => "Changement de statut invalide.",
        };
    }
}
