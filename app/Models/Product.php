<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasUuids, HasSlug;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description',
        'price', 'old_price', 'currency', 'stock_quantity',
        'category_id', 'brand', 'specifications', 'features',
        'is_active', 'is_featured', 'is_new', 'is_on_sale',
    ];

    protected $casts = [
        'specifications' => 'array',
        'features'       => 'array',
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
        'is_new'         => 'boolean',
        'is_on_sale'     => 'boolean',
        'price'          => 'decimal:2',
        'old_price'      => 'decimal:2',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true);
    }

    public function getPriceFormattedAttribute(): string
    {
        return number_format($this->price, 2) . ' MAD';
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }
}
