<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        return Inertia::render('Admin/Products/Index', [
            'products' => Product::query()
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                }))
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn (Product $product) => $this->productPayload($product)),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Form', [
            'product' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_sold_out'] = $request->boolean('is_sold_out');

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Form', [
            'product' => $this->productPayload($product),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('image')) {
            if ($product->image && ! str_starts_with($product->image, 'images/')) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_sold_out'] = $request->boolean('is_sold_out');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && ! str_starts_with($product->image, 'images/')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function toggleSoldOut(Product $product)
    {
        $product->update([
            'is_sold_out' => ! $product->is_sold_out,
        ]);

        return back()->with('success', $product->is_sold_out ? 'Product marked as sold out.' : 'Product is available again.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_sold_out' => ['sometimes', 'boolean'],
        ]);
    }

    private function productPayload(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'image' => $product->image,
            'image_url' => $product->image_url,
            'is_sold_out' => $product->is_sold_out,
            'created_at' => $product->created_at,
        ];
    }
}
