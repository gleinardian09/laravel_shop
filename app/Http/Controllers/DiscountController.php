<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::latest()->paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:discounts,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
        ]);

        Discount::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount,
            'max_uses' => $request->max_uses,
            'starts_at' => Carbon::parse($request->starts_at),
            'expires_at' => Carbon::parse($request->expires_at),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully!');
    }

    public function show(Discount $discount)
    {
        return view('admin.discounts.show', compact('discount'));
    }

    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'code' => 'required|string|unique:discounts,code,' . $discount->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
        ]);

        $discount->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount,
            'max_uses' => $request->max_uses,
            'starts_at' => Carbon::parse($request->starts_at),
            'expires_at' => Carbon::parse($request->expires_at),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Discount updated successfully!');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'Discount deleted successfully!');
    }

    public function validateDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $discount = Discount::where('code', strtoupper($request->code))->first();

        if (!$discount || !$discount->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired discount code.'
            ]);
        }

        if ($discount->min_order_amount && $request->amount < $discount->min_order_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimum order amount not met. Required: $' . number_format($discount->min_order_amount, 2)
            ]);
        }

        $discountAmount = $discount->calculateDiscount($request->amount);

        return response()->json([
            'valid' => true,
            'discount' => [
                'id' => $discount->id,
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value,
                'amount' => $discountAmount,
                'final_amount' => $request->amount - $discountAmount,
            ],
            'message' => 'Discount applied successfully!'
        ]);
    }
}