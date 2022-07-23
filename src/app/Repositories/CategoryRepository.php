<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\ICategoryRepository;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{    
    /**
     * WalletRepository Constructor
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
    
}
