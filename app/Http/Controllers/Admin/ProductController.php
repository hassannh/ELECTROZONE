<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->paginate(20)->appends($request->query());
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->with('children')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'short_description' => 'required|string|max:500',
            'price'             => 'required|numeric|min:0',
            'old_price'         => 'nullable|numeric|min:0',
            'stock_quantity'    => 'required|integer|min:0',
            'category_id'       => 'nullable|exists:categories,id',
            'brand'             => 'required|string|max:100',
            'specifications'    => 'nullable|string',
            'features'          => 'nullable|string',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'is_new'            => 'boolean',
            'is_on_sale'        => 'boolean',
            'images.*'          => 'nullable|image|max:5120',
        ]);

        $data['is_active']  = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new']     = $request->boolean('is_new');
        $data['is_on_sale'] = $request->boolean('is_on_sale');

        // Parse specs and features from textarea
        if (!empty($data['specifications'])) {
            $lines = array_filter(explode("\n", trim($data['specifications'])));
            $specs = [];
            foreach ($lines as $line) {
                if (str_contains($line, ':')) {
                    [$k, $v] = explode(':', $line, 2);
                    $specs[trim($k)] = trim($v);
                }
            }
            $data['specifications'] = $specs;
        } else {
            $data['specifications'] = null;
        }

        if (!empty($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        } else {
            $data['features'] = null;
        }

        $product = Product::create($data);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created!');
    }

    public function edit(Product $product)
    {
        $product->load('images', 'category');
        $categories = Category::active()->with('children')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'short_description' => 'required|string|max:500',
            'price'             => 'required|numeric|min:0',
            'old_price'         => 'nullable|numeric|min:0',
            'stock_quantity'    => 'required|integer|min:0',
            'category_id'       => 'nullable|exists:categories,id',
            'brand'             => 'required|string|max:100',
            'specifications'    => 'nullable|string',
            'features'          => 'nullable|string',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'is_new'            => 'boolean',
            'is_on_sale'        => 'boolean',
            'images.*'          => 'nullable|image|max:5120',
        ]);

        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new']      = $request->boolean('is_new');
        $data['is_on_sale']  = $request->boolean('is_on_sale');

        if (!empty($data['specifications'])) {
            $lines = array_filter(explode("\n", trim($data['specifications'])));
            $specs = [];
            foreach ($lines as $line) {
                if (str_contains($line, ':')) {
                    [$k, $v] = explode(':', $line, 2);
                    $specs[trim($k)] = trim($v);
                }
            }
            $data['specifications'] = $specs;
        } else {
            $data['specifications'] = null;
        }

        if (!empty($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        } else {
            $data['features'] = null;
        }

        $product->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $path,
                    'sort_order' => $product->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        \Log::info('Admin deleting product: ' . $product->id . ' (' . $product->name . ')');
        
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return back()->with('success', 'Image removed.');
    }
}
