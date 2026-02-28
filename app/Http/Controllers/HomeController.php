<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::active()->featured()->with('primaryImage')->take(8)->get();
        $newArrivals = Product::active()->newArrivals()->with('primaryImage')->take(8)->get();
        $onSale = Product::active()->onSale()->with('primaryImage')->take(8)->get();
        $categories = Category::active()->parents()->withCount('products')->get();

        return view('home.index', compact('featured', 'newArrivals', 'onSale', 'categories'));
    }
}
