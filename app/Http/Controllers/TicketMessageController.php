<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::query()
            ->when($request->q, fn($q) =>
            $q->where('title','like',"%{$request->q}%")
                ->orWhere('message','like',"%{$request->q}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        return Inertia::render('Admin/Tickets/Index', [
            'tickets' => $tickets,
            'filters' => $request->only('q')
        ]);
    }

    public function show(Ticket $ticket)
    {
        return Inertia::render('Admin/Tickets/Show', [
            'ticket' => $ticket->load('messages.user','claimedBy')
        ]);
    }

    public function claim(Ticket $ticket)
    {
        $ticket->update(['claimed_by' => auth()->id()]);
        return back()->with('success','Ticket geclaimt.');
    }

    public function update(Request $request, Ticket $ticket)
    {
        $ticket->update($request->validate([
            'priority' => 'in:green,orange,red,gold',
            'status'   => 'in:offen,geschlossen,warte_info',
        ]));

        return back()->with('success','Ticket aktualisiert.');
    }
}
