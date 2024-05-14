<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = $request->validated();
            $request->user()->categories()->create($category);
            return response()->json([
                'message' => 'Successfully created',
                'category' => $category,
            ]);
        }catch(ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ],422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);
        if($category) {
            return new CategoryResource($category);
        }
        return response()->json(['error' => 'Category not found']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request,$id)
    {
        // $validated = $request->validate(['name' => 'required', 'string']);
        try {
            $category = Category::find($id);
            if($request->user()->id === $category->user_id ) {
                $category->update($request->validated());
                return response()->json([
                    'message' => 'Successfully updated',
                    'category' => $category,
                ]);
            }else {
                return response()->json([
                    'message' => 'Unauthorized action',
                ],401);
            }
        }catch(ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ],422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::find($id);
        if($category) {
            if($request->user()->id === $category->user_id) {
                $category->delete();
                return response()->json([
                    'message' => 'Category deleted successfully',
                ]);
            }else {
                return response()->json([
                    'message' => 'Unauthorized action',
                ],401);
            }
            return response()->json(['message' => 'Category has been deleted']);
        }
        return response()->json(['error' => 'Category not found']);
    }


}
