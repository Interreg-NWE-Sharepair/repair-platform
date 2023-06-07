<?php

namespace App\Repository\Eloquent;

use App\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * INFO FOUND AT https://asperbrothers.com/blog/implement-repository-pattern-in-laravel/
 * Class BaseRepository
 */
class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $id
     * @return Model
     */
    public function findOrFail($id): ?Model
    {
        return $this->model->findOrFail($id);
    }
}
