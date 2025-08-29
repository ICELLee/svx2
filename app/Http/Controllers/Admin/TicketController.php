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
        $tickets = Ticket::with('claimedBy')
            ->when($request->q, fn($q) =>
            $q->where('title', 'like', "%{$request->q}%")
                ->orWhere('message', 'like', "%{$request->q}%")
            )
            ->latest()
            ->paginate(20);

        return Inertia::render('Admin/Tickets/Index', [
            'tickets' => $tickets,
            'filters' => $request->only('q'),
        ]);
    }

    public function show(Request $request, Ticket $ticket)
    {
        $ticket->load(['user', 'messages.user', 'claimedBy']);

        return Inertia::render('Admin/Tickets/Show', [
            'ticket'  => $ticket,
            'filters' => $request->only('q'),
        ]);
    }

    public function claim(Ticket $ticket)
    {
        if ($ticket->claimed_by && $ticket->claimed_by !== auth()->id()) {
            return back()->with('error', 'Ticket ist bereits geclaimt.');
        }

        $ticket->claimed_by = auth()->id();
        $ticket->save();

        // Neue Systemnachricht anlegen
        $ticket->messages()->create([
            'user_id'     => auth()->id(),
            'message'     => 'Ticket wurde von ' . auth()->user()->name . ' geclaimt.',
            'attachments' => [],
        ]);

        return back()->with('success', 'Ticket wurde geclaimt.');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|max:51200',
        ]);

        $files = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $files[] = $file->store("tickets/{$ticket->id}", 'public');
            }
        }

        $message = $ticket->messages()->create([
            'user_id'     => auth()->id(),
            'message'     => $data['message'],
            'attachments' => $files,
        ]);

        // ✅ Wichtig: Kein Redirect, sondern JSON
        return response()->json([
            'success' => true,
            'message' => $message->load('user'),
        ]);
    }




    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'priority' => 'in:green,orange,red,gold',
            'status'   => 'in:offen,geschlossen,warte_info',
        ]);

        $ticket->update($data);

        return back()->with('success', 'Ticket aktualisiert.');
    }
}
