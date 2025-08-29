<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->when($request->q, fn($q) =>
            $q->where('title','like',"%{$request->q}%")
                ->orWhere('message','like',"%{$request->q}%"))
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
            'filters' => $request->only('q')
        ]);
    }

    public function create()
    {
        return Inertia::render('Tickets/Create');

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'reason' => 'required|string',
            'products' => 'nullable|array',
            'priority' => 'required|in:green,orange,red,gold',
            'extra_info' => 'nullable|string|max:255',
            'attachments.*' => 'nullable|file|max:51200',
        ]);

        $ticket = auth()->user()->tickets()->create($data);

        $files = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("tickets/{$ticket->id}", 'public');
                $files[] = $path;
            }
        }

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $data['message'],
            'attachments' => $files,
        ]);

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket wurde erstellt.');
    }


    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        return Inertia::render('Tickets/Show', [
            'ticket'  => $ticket->load('messages.user'),
            'filters' => $request->only('q'), // optional, falls du Suche/Filter auch hier brauchst
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $msg = $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|max:51200', // 50 MB pro Datei
        ]);

        $files = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("tickets/{$ticket->id}", 'public');
                $files[] = $path;
            }
        }

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $msg['message'],
            'attachments' => $files,
        ]);

        return back()->with('success', 'Antwort gesendet.');
    }

}
