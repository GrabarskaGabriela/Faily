<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\PhotoServiceInterface;

class PhotoController extends Controller
{

    protected $photoService;

    public function __construct(PhotoServiceInterface $photoService)
    {
        $this->middleware('auth');
        $this->photoService = $photoService;
    }

    public function store(Request $request)
    {
        try {
            $this->photoService->storeEventPhotos($request, Auth::id());
            return redirect()->route('events.show', $request->event_id)
                ->with('success', 'Photos added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('events.show', $request->event_id)
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Photo $photo)
    {
        try {
            $eventId = $photo->event_id;
            $this->photoService->deletePhoto($photo->id, Auth::id());

            return redirect()->route('events.show', $eventId)
                ->with('success', 'Photo deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('events.show', $photo->event_id)
                ->with('error', $e->getMessage());
        }
    }
}
