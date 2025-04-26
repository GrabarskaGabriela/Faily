<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\PhotoCollection;
use App\Models\Photo;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{

    public function index(Event $event)
    {
        return new PhotoCollection($event->photos);
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
            return response()->json([
                'message' => 'Nie masz uprawnień do dodawania zdjęć do tego wydarzenia.'
            ], 403);
        }

        $uploadedPhotos = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('event_photos', 'public');
                $newPhoto = $event->photos()->create([
                    'path' => $path,
                    'filename' => $photo->getClientOriginalName()
                ]);

                $uploadedPhotos[] = $newPhoto;
            }
        }

        return new PhotoCollection(collect($uploadedPhotos));
    }

    public function destroy(Photo $photo)
    {
        if (Auth::id() !== $photo->event->user_id) {
            return response()->json([
                'message' => 'Nie masz uprawnień do usunięcia tego zdjęcia.'
            ], 403);
        }

        Storage::disk('public')->delete($photo->path);

        $photo->delete();

        return response()->json(['message' => 'Zdjęcie zostało usunięte.']);
    }
}
