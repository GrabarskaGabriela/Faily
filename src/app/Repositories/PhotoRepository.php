<?php

namespace App\Repositories;

use App\Models\Photo;
use App\Repositories\Interfaces\PhotoRepositoryInterface;

class PhotoRepository extends BaseRepository implements PhotoRepositoryInterface
{

    public function model()
    {
        return Photo::class;
    }

    public function storeEventPhotos($eventId, array $photos)
    {
        $storedPhotos = [];

        foreach ($photos as $photo) {
            $path = $photo->store('event_photos', 'public');
            $storedPhoto = $this->create([
                'event_id' => $eventId,
                'path' => $path,
                'filename' => $photo->getClientOriginalName()
            ]);

            $storedPhotos[] = $storedPhoto;
        }

        return $storedPhotos;
    }

    public function deletePhotos($photoId)
    {
        return $this->delete($photoId);
    }

    public function getPhotoWithEvent($photoId)
    {
        $photo = $this->find($photoId);
        $photo->load('event');
        return $photo;
    }
}
