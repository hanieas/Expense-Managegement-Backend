<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Responders\CategoryResponder;
use App\Responders\IResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /** @var CategoryRepositoy */
    protected ICategoryRepository $repository;

    /** @var CategoryResponder */
    protected IResponder $responder;

    /**
     * CategoryController Constructor
     *
     * @param  CategoryRepository $repository
     * @param  CategoryResponder $responder
     * @return void
     */
    public function __construct(ICategoryRepository $repository, IResponder $responder)
    {
        $this->repository = $repository;
        $this->responder = $responder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $response = $this->repository->create($request->validated());
        return $this->responder->respondResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(Category $category):JsonResponse
    {
        if($this->repository->checkOwn($category))
        {
            return $this->responder->respondResource($category);
        }
        return $this->responder->message('You dont own this category',403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        //
    }
}
