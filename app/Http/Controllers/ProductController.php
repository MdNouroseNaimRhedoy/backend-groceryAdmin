<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10);

        // if it has image then append the full url
        $products->getCollection()->transform(function ($product) {
            if ($product->image) {
                $product->image = url('storage/products/' . $product->image);
            }
            return $product;
        });

        return response()->json($products->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|image',
            'price' => 'required',
            'stock' => 'required',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        $this->imageUpload($request, $product);

        if ($product->image) {
            $product->image = url('storage/products/' . $product->image);
        }

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if ($product->image) {
            $product->image = url('storage/products/' . $product->image);
        }
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'price' => 'required',
            'stock' => 'required',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        $this->imageUpload($request, $product);

        if ($product->image) {
            $product->image = url('storage/products/' . $product->image);
        }

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return void
     */
    public function imageUpload(Request $request, Product $product): void
    {
        if ($request->hasFile('image')) {
            $imageName = time() . Str::random() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/products', $imageName);
            $product->image = $imageName;
            $product->save();
        }
    }
}
