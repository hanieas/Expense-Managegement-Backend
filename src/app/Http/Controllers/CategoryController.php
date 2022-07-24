<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Responders\CategoryResponder;
use App\Responders\IResponder;
use App\Responders\Message;
use Illuminate\Http\JsonResponse;

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
        $response = $this->repository->getList();
        return $this->responder->respondCollection($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CategoryStoreRequest  $request
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
     * @param  Category  $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        if ($this->repository->checkOwn($category)) {
            return $this->responder->respondResource($category);
        }
        return $this->responder->failed(Message::ONLY_CATEGORY_OWNER_CAN_GET_IT, 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CategoryUpdateRequest  $request
     * @param  Category  $category
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        if ($this->repository->checkOwn($category)) {
            $category->update($request->validated());
            return $this->responder->respondResource($category);
        }
        return $this->responder->failed(Message::ONLY_CATEGORY_OWNER_CAN_GET_IT, 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        if ($this->repository->checkOwn($category)) {
            $category->delete();
            return $this->responder->message(Message::CATEGORY_DELETED,200);
        }
        return $this->responder->failed(Message::ONLY_CATEGORY_OWNER_CAN_GET_IT, 403);
    }
}
