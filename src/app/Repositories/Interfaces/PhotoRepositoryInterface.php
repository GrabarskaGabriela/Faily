<?php

namespace App\Repositories\Interfaces;

interface PhotoRepositoryInterface extends RepositoryInterface
{
    public function storeEventPhotos($eventId, array $photos);
    public function deletePhoto($photoId);
    public function getPhotoWithEvent($photoId);
}
