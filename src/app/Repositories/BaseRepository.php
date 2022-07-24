<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements IBaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param  Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param  array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        $record =  $this->model->create($attributes);
        return $this->model->find($record->id);
    }
    
    /**
     * @param  int $id
     * @return Model
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }
}
