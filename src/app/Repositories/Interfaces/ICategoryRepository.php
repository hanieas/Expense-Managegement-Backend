<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface ICategoryRepository {
        
    /**
     * Check if the user own this category or not.
     *
     * @param  Category $category
     * @return bool
     */
    public function checkOwn(Category $category):bool;
    
    /**
     * Get list of categories of the current user.
     *
     * @return Collection
     */
    public function getList():Collection;
}
