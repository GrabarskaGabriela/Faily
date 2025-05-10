<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Interfaces\PhotoRepositoryInterface;
use App\Services\Interfaces\CacheServiceInterface;
use App\Services\Interfaces\PhotoServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoService extends BaseService implements PhotoServiceInterface
{
    protected $repository;
    protected $cacheService;

    public function __construct(
        PhotoRepositoryInterface $repository,
        ?CacheServiceInterface $cacheService = null
    ) {
        parent::__construct($repository, $cacheService);

        $this->cacheTags = ['photos', 'events'];
        $this->cachePrefix = 'photo';
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

        $result = [];
        if ($request->hasFile('photos')) {
            $result = $this->repository->storeEventPhotos($event->id, $request->file('photos'));
        }

        if ($this->useCache()) {
            $this->cacheService->forget("event.{$event->id}.with_relations");
            $this->cacheService->flushTags(['photos', 'events']);
        }

        return $result;
    }

    public function deletePhoto($photoId, $userId)
    {
        $photo = $this->repository->getPhotoWithEvent($photoId);

        if ($userId !== $photo->event->user_id) {
            throw new \Exception('You do not have permission to delete this photo.');
        }

        Storage::disk('public')->delete($photo->path);

        $result = $this->repository->deletePhoto($photoId);

        if ($this->useCache()) {
            $this->cacheService->forget("event.{$photo->event_id}.with_relations");
            $this->cacheService->flushTags(['photos', 'events']);
        }

        return $result;
    }
}
