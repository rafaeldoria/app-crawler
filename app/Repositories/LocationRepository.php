<?php

namespace App\Repositories;

use App\Models\Location;

class LocationRepository extends BaseRepository
{
    public function store($data)
    {
        $location = new Location();
        return $location->create($data);
    }
}