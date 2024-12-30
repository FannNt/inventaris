<?php

namespace App\Http\Controllers;

use App\Models\Item;

class ItemController extends Controller
{
    public function show(Item $item)
    {
        return view('livewire.items.show', compact('item'));
    }
} 