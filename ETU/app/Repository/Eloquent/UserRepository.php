<?php

namespace App\Repository\Eloquent;

use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Repository\Eloquent\BaseRepository;


class UserRepository extends BaseRepository implements UserRepositoryInterface

{
    public function __construct()
    {
        parent::__construct(User::class);
    }
}
?>