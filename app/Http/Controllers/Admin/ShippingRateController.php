<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index()
    {
        $rates = ShippingRate::orderBy('city')->orderBy('district')->paginate(20);
        return view('admin.shipping.index', compact('rates'));
    }

    public function create()
    {
        return view('admin.shipping.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'city' => 'required|string',
            'district' => 'required|string',
            'rate' => 'required|numeric|min:0',
        ]);
        ShippingRate::create($data);
        return redirect()->route('admin.shipping.index')->with('success','Rate created');
    }

    public function edit(ShippingRate $shipping)
    {
        return view('admin.shipping.edit', ['rate' => $shipping]);
    }

    public function update(Request $request, ShippingRate $shipping)
    {
        $data = $request->validate([
            'city' => 'required|string',
            'district' => 'required|string',
            'rate' => 'required|numeric|min:0',
        ]);
        $shipping->update($data);
        return redirect()->route('admin.shipping.index')->with('success','Rate updated');
    }

    public function destroy(ShippingRate $shipping)
    {
        $shipping->delete();
        return redirect()->route('admin.shipping.index')->with('success','Rate deleted');
    }
}