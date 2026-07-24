<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareTargetController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:share-target');
    }

    /**
     * Receive photos shared from another app (Android's share sheet, per the
     * manifest's share_target) and turn them into a new note -- the quickest
     * capture path for a field photo, with converting to a task already
     * available from the note itself if that's what it turns out to be.
     */
    public function receive(Request $request): RedirectResponse
    {
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'image',
        ]);

        $photos = $request->file('photos');
        $count = count($photos);

        $note = Note::make([
            'title' => 'Fotos vom '.now()->format('d.m.Y'),
            'comment' => $count === 1 ? '1 Foto hinzugefügt.' : $count.' Fotos hinzugefügt.',
        ]);
        $note->employee()->associate(Auth::user()->employee);
        $note->save();
        $note->addAttachments($photos);

        return redirect()->route('notes.show', $note)->with('success', 'Die Notiz wurde mit den geteilten Fotos angelegt.');
    }
}
