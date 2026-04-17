<?php

namespace App\Http\Controllers;

use App\Mail\NoteCreatedMail;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $notes = $user->can('manage notes')
            ? Note::with('user')->latest()->get()
            : $user->notes()->latest()->get();

        return view('notes.index', compact('notes'));
    }

    public function create(): View
    {
        return view('notes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Note::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'content' => ['required', 'string', 'min:5'],
        ]);

        $note = Auth::user()->notes()->create($validated);

        Mail::to(Auth::user()->email)->send(new NoteCreatedMail($note));

        return redirect()
            ->route('notes.index')
            ->with('status', 'Note créé avec succès.');
    }

    public function show(Note $note): View
    {
        Gate::authorize('view', $note);

        return view('notes.show', compact('note'));
    }

    public function edit(Note $note): View
    {
        Gate::authorize('update', $note);

        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        Gate::authorize('update', $note);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'content' => ['required', 'string', 'min:5'],
        ]);

        $note->update($validated);

        return redirect()
            ->route('notes.index')
            ->with('status', 'Note mise à jour avec succès.');
    }

    public function destroy(Note $note): RedirectResponse
    {
        Gate::authorize('delete', $note);

        $note->delete();

        return redirect()
            ->route('notes.index')
            ->with('status', 'Note supprimée avec succès.');
    }
}
