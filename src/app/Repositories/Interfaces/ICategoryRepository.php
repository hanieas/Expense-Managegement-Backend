<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;

interface ICategoryRepository {
        
    /**
     * Check if the user own this category or not.
     *
     * @param  Category $category
     * @return bool
     */
    public function checkOwn(Category $category):bool;
}
