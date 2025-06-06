<?php

namespace App\Repository\Eloquent;

use App\Repository\CriticRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Critic;
use App\Repository\Eloquent\BaseRepository;


class CriticRepository extends BaseRepository implements CriticRepositoryInterface

{
    public function __construct()
    {
        parent::__construct(Critic::class);
    }

    public function create(array $attributes): Model
    {
        $user = auth()->user();
        $attributes['user_id'] = $user->id;
        return Critic::create($attributes);
    }
}
?>