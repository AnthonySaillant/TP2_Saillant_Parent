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
}
?>