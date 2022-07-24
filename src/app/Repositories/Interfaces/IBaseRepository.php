<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{        
    /**
     * create
     *
     * @param  array $request
     * @return Model
     */
    public function create(array $request):Model;
    
    /**
     * find
     *
     * @param  int $id
     * @return Model
     */
    public function find(int $id): ?Model;
}
