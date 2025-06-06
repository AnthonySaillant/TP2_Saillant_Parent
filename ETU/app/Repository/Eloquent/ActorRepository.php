<?php

namespace App\Repository\Eloquent;

use App\Repository\FilmRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Film;
use App\Repository\Eloquent\BaseRepository;


class ActorRepository extends BaseRepository implements FilmRepositoryInterface

{
    public function __construct()
    {
        parent::__construct(Actor::class);
    }
}
?>