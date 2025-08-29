<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket)
    {
        return $ticket->user_id === $user->id || $user->hasRole('admin');
    }

}
