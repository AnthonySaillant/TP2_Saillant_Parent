<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface{
    public function create(array $attributes): Model;
    public function getById(int $id): ?Model;
    public function delete(int $id): void;
    public function getAll(int $perPage = 0);
}
?>