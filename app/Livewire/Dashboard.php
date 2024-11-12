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

        $today = Carbon::now();
        $threeMonthsFromNow = Carbon::now()->addMonths(3);

        $this->expiredItems = Item::where('masa_berlaku', '<', $today)->orderBy('masa_berlaku', 'asc')->get();
        $this->expiringSoonItems = Item::whereBetween('masa_berlaku', [$today, $threeMonthsFromNow])->orderBy('masa_berlaku', 'asc')->get();
        $this->validItems = Item::where('masa_berlaku', '>', $threeMonthsFromNow)
            ->orWhereNull('masa_berlaku')
            ->orderBy('masa_berlaku', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
