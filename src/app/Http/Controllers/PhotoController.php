<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = Event::findOrFail($request->event_id);

        if (Auth::id() !== $event->user_id) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You do not have permission to add photos to this event.');
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('event_photos', 'public');
                $event->photos()->create([
                    'path' => $path,
                    'filename' => $photo->getClientOriginalName()
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Photos added successfully!');
    }

    public function destroy(Photo $photo)
    {
        if (Auth::id() !== $photo->event->user_id) {
            return redirect()->route('events.show', $photo->event)
                ->with('error', 'You do not have permission to delete this photo.');
        }

        Storage::disk('public')->delete($photo->path);

        $photo->delete();

        return redirect()->route('events.show', $photo->event)
            ->with('success', 'Photo deleted successfully!');
    }
}
