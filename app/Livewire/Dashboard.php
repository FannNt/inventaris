<?php

namespace App\Livewire;

use App\Models\Item;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalItems;
    public $expiredItems;
    public $expiringSoonItems;
    public $validItems;

    public function mount()
    {
        $this->updateStats();
    }

    public function updateStats()
    {
        $this->totalItems = Item::count();
        
        $this->expiredItems = Item::whereNotNull('masa_berlaku')
            ->where('masa_berlaku', '<', now())
            ->orderBy('masa_berlaku', 'asc')
            ->get();
        
        $this->expiringSoonItems = Item::whereNotNull('masa_berlaku')
            ->where('masa_berlaku', '>=', now())
            ->where('masa_berlaku', '<=', now()->addMonths(3))
            ->orderBy('masa_berlaku', 'asc')
            ->get();
        
        $this->validItems = Item::where(function($query) {
            $query->whereNull('masa_berlaku')
                  ->orWhere('masa_berlaku', '>', now()->addMonths(3));
        })
        ->orderBy('masa_berlaku', 'asc')
        ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
