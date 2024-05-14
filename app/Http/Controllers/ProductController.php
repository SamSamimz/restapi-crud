<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
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
    public function store(ProductRequest $request)
    {
        try {
            $product = $request->validated();
            $request->user()->products()->create($product);
            return response()->json([
                'message' => 'Successfully created',
                'product' => $product,
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
        $product = Product::find($id);
        if($product) {
            return new ProductResource($product);
        }
        return response()->json(['error' => 'Product not found']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request,$id)
    {
        try {
            $product = Product::find($id);
            $validated = $request->validated();
            if($product) {
                if(auth()->user()->id === $product->user_id ) {
                    $product->update($validated);
                return response()->json([
                    'message' => 'Successfully updated',
                    'product' => $product,
                ]);
            }else {
                return response()->json([
                    'message' => 'Unauthorized action',
                ],401);
            }
        }else {
            return response()->json([
                'error' => 'Produt not found',
            ],404);
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
        $product = Product::find($id);
        if($product) {
            if($request->user()->id === $product->user_id) {
                $product->delete();
                return response()->json([
                    'message' => 'Product deleted successfully',
                ]);
            }else {
                return response()->json([
                    'message' => 'Unauthorized action',
                ],401);
            }
            return response()->json(['message' => 'Product has been deleted']);
        }
        return response()->json(['error' => 'Product not found']);
    }
}
