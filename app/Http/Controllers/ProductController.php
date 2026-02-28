<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['primaryImage', 'category']);

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) $query->where('category_id', $category->id);
        }
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        $sort = $request->get('sort', 'created_at_desc');
        match($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            'name_desc'  => $query->orderBy('name', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(12)->appends($request->query());
        $categories = Category::active()->parents()->with('children')->get();
        $brands = Product::active()->distinct()->pluck('brand')->sort();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show(string $id, string $slug)
    {
        $product = Product::active()->with(['images', 'category'])->findOrFail($id);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with('primaryImage')
            ->take(4)->get();

        return view('products.show', compact('product', 'related'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $products = Product::active()
            ->with('primaryImage')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('brand', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%");
            })
            ->paginate(12)
            ->appends(['query' => $query]);

        $categories = Category::active()->parents()->get();
        $brands = Product::active()->distinct()->pluck('brand')->sort();

        return view('products.index', compact('products', 'categories', 'brands', 'query'));
    }

    public function autocomplete(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->select('id', 'name', 'brand', 'price', 'slug')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('brand', 'like', "%{$q}%");
            })
            ->take(6)
            ->get()
            ->map(fn($p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'brand' => $p->brand,
                'price' => number_format($p->price, 2) . ' MAD',
                'url'   => route('products.show', [$p->id, $p->slug]),
            ]);

        return response()->json($products);
    }

    public function byCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        $childIds = $category->children()->pluck('id')->prepend($category->id);

        $products = Product::active()
            ->whereIn('category_id', $childIds)
            ->with(['primaryImage', 'category'])
            ->paginate(12);

        $categories = Category::active()->parents()->with('children')->get();
        $brands = Product::active()->distinct()->pluck('brand')->sort();

        return view('products.index', compact('products', 'categories', 'brands', 'category'));
    }
}
