<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $products = Product::with('category')
            ->paginate(10);

        return $this->ok(
            'Products retrieved successfully',
            ProductResource::collection($products)
        );
    }

    public function show(Product $product)
    {
        $product->load('category');

        return $this->ok(
            'Product retrieved successfully',
            new ProductResource($product)
        );
    }

    
public function store(Request $request)
{
    $validated = $request->validate([
        'category_id' => ['required', 'integer', 'exists:categories,id'],
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'price' => ['required', 'numeric', 'min:0'],
    ]);

    $product = Product::create($validated);
    $product->load('category');

    return $this->ok(
        'Product created successfully',
        new ProductResource($product)
    );
}

public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'category_id' => ['required', 'integer', 'exists:categories,id'],
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'price' => ['required', 'numeric', 'min:0'],
    ]);

    $product->update($validated);
    $product->load('category');

    return $this->ok(
        'Product updated successfully',
        new ProductResource($product)
    );
}

public function destroy(Product $product)
{
    $product->delete();

    return $this->ok(
        'Product deleted successfully',
        null
    );
}


    public function queries()
    {
    // A. QUERYING TABLES

        $nameAndPrice = Product::select('name', 'price')->get();

        $productsWithCategory = Product::with('category')->get();

        $productsOrderedByPrice = Product::select('id', 'name')
            ->orderBy('price', 'asc')
            ->get();

        $firstFiveProducts = Product::limit(5)->get();


    // B. FILTERING DATA

        $priceGreaterThanOrEqual = Product::where('price', '>=', 3)->get();

        $electronics = Product::whereHas('category', function ($q) {
            $q->where('name', 'Electronics');
            })->get();


    // C. AGGREGATING DATA

        $totalPrice = round(Product::sum('price'), 2);

        $averagePrice = round(Product::avg('price'), 2);

        $highestPrice = round(Product::max('price'), 2);

        $lowestPrice = round(Product::min('price'), 2);


    // D. GROUPING, FILTERING, AND SORTING

        $totalPricePerCategory = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
        ->select(
            'categories.name',
            DB::raw('Round(SUM(products.price),2) as total_price')
        )
        ->groupBy('categories.name')
        ->get();

        $averagePricePerCategory = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
        ->select(
            'categories.name',
            DB::raw('ROUND(AVG(products.price),2) as avg_price')
        )
        ->groupBy('categories.name')
        ->get();

        $lowestPricePerCategory = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
        ->select(
            'categories.name',
            DB::raw('ROUND(MIN(products.price),2) as min_price')
        )
        ->groupBy('categories.name')
        ->get();

        $averagePriceSorted = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
        ->select(
            'categories.name',
            DB::raw('ROUND(AVG(products.price),2) as avg_price')
        )
        ->groupBy('categories.name')
        ->orderBy('avg_price', 'asc')
        ->get();

        $electronicsAndBooks = Product::join(
            'categories',
            'products.category_id',
            '=',
            'categories.id'
        )
        ->whereIn('categories.name', ['Electronics', 'Books'])
        ->select(
            'categories.name',
            DB::raw('ROUND(AVG(products.price), 2) as avg_price')
        )
        ->groupBy('categories.name')
        ->get();


    // Return all results as JSON
    $queryResults = [
    'name_and_price' => $nameAndPrice,
    'products_with_category' => $productsWithCategory,
    'products_ordered_by_price' => $productsOrderedByPrice,
    'first_five_products' => $firstFiveProducts,
    'price_greater_than_or_equal' => $priceGreaterThanOrEqual,
    'electronics' => $electronics,
    'total_price' => $totalPrice,
    'average_price' => $averagePrice,
    'highest_price' => $highestPrice,
    'lowest_price' => $lowestPrice,
    'total_price_per_category' => $totalPricePerCategory,
    'average_price_per_category' => $averagePricePerCategory,
    'lowest_price_per_category' => $lowestPricePerCategory,
    'average_price_sorted' => $averagePriceSorted,
    'electronics_and_books' => $electronicsAndBooks,
];

        return $this->success(
            'Query results retrieved successfully',
            $queryResults
        );
    }
}