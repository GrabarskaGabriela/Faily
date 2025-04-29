<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Interfaces\PhotoRepositoryInterface;
use App\Services\Interfaces\PhotoServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoService extends BaseService implements PhotoServiceInterface
{
    protected $repository;

    public function __construct(PhotoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function storeEventPhotos(Request $request, $userId)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = Event::findOrFail($request->event_id);

        if ($userId !== $event->user_id) {
            throw new \Exception('You do not have permission to add photos to this event.');
        }

        if ($request->hasFile('photos')) {
            return $this->repository->storeEventPhotos($event->id, $request->file('photos'));
        }

        return [];
    }

    public function deletePhoto($photoId, $userId)
    {
        $photo = $this->repository->getPhotoWithEvent($photoId);

        if ($userId !== $photo->event->user_id) {
            throw new \Exception('You do not have permission to delete this photo.');
        }

        Storage::disk('public')->delete($photo->path);

        return $this->repository->deletePhoto($photoId);
    }
}
