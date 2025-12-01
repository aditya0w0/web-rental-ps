<?php

namespace App\Http\Controllers;

use App\Models\PlaystationType;
use App\Models\Accessory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function playstation()
    {
        $playstationTypes = PlaystationType::where('is_active', true)->latest()->paginate(9);
        return view('products.playstation', compact('playstationTypes'));
    }

    public function accessories()
    {
        $accessories = Accessory::where('is_active', true)->where('stock', '>', 0)->latest()->paginate(12);
        return view('products.accessories', compact('accessories'));
    }
}