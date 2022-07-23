<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\IBaseRepository;

class CategoryRepositoy extends BaseRepository implements IBaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
}
