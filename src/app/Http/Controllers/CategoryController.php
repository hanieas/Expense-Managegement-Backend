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
     * @group Category CRUD
     * @authenticated
     */
    public function __construct(ICategoryRepository $repository, IResponder $responder)
    {
        $this->repository = $repository;
        $this->responder = $responder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @group Category CRUD
     * @authenticated
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
     * @return \Illuminate\Http\Response
     * @group Category CRUD
     * @authenticated
     */
    public function store(CategoryStoreRequest $request)
    {
        $response = $this->repository->create($request->validated());
        return $this->responder->respondResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     * @group Category CRUD
     * @authenticated
     */
    public function show(Category $category)
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
     * @return \Illuminate\Http\Response
     * @group Category CRUD
     * @authenticated
     */
    public function update(CategoryUpdateRequest $request, Category $category)
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
     * @return \Illuminate\Http\Response
     * @group Category CRUD
     * @authenticated
     */
    public function destroy(Category $category)
    {
        if ($this->repository->checkOwn($category)) {
            $category->delete();
            return $this->responder->message(Message::CATEGORY_DELETED,200);
        }
        return $this->responder->failed(Message::ONLY_CATEGORY_OWNER_CAN_GET_IT, 403);
    }
}
