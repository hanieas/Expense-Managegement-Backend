<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\ICategoryRepository;
use Illuminate\Support\Facades\Gate;

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
    
    /**
     * Check if the user own this category or not.
     *
     * @param  Category $category
     * @return bool
     */
    public function checkOwn(Category $category): bool
    {
        if(Gate::allows('check-category-own',$category))
        {
            return true;
        }
        return false;
    }
    
}
