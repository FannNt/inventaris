<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Ruangan;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;
    public $name;
    public $id;
    public $id_ruangan;
    public $merk;
    public $kondisi = 'Rusak';
    public $keterangan;
    public $tahun_pengadaan;
    public $masa_berlaku;
    public $isOpen = false;

    public $search = '';
    public $kondisi_filter = '';
    public $ruangan_filter = '';
    public $expirationFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'ruangan_filter' => ['except' => ''],
        'kondisi_filter' => ['except' => ''],
        'expirationFilter' => ['except' => '']
    ];

    public function updatingKondisiFilter()
    {
            
    }

    protected $rules = [
        'name' => 'required',
        'merk' => 'required',
        'kondisi' => 'required|in:Baik,Rusak',
        'keterangan' => 'required_if:kondisi,Rusak',
        'tahun_pengadaan' => 'required|numeric|min:1900|max:2100',
        'masa_berlaku' => 'required|date',
        'id_ruangan' => 'required|exists:ruangans,id',
    ];
    protected $messages = [
        'keterangan.required_if' => 'Keterangan wajib diisi jika kondisi rusak.',
        'tahun_pengadaan.min' => 'Tahun tidak valid',
        'tahun_pengadaan.max' => 'Tahun tidak valid'
    ];

    public function mount()
    {
        $this->expirationFilter = request()->get('filter', '');
    }
    public function clearFilters()
    {
        $this->reset(['search', 'ruangan_filter', 'kondisi_filter', 'expirationFilter']);
    }

    public function render()
    {
        $today = Carbon::now();
        $threeMonthsFromNow = Carbon::now()->addMonths(3);

        $query = Item::query()
            ->when($this->search, function($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->ruangan_filter, function($query) {
                $query->where('id_ruangan', 'like', $this->ruangan_filter );
                })
            ->when($this->kondisi_filter, function($query) {
                $query->where('kondisi','like',$this->kondisi_filter);
                    });


        switch ($this->expirationFilter) {
            case 'expired':
                $query->where('masa_berlaku', '<', $today)->orderBy('masa_berlaku', 'asc');
                break;
            case 'expiring_soon':
                $query->whereBetween('masa_berlaku', [$today, $threeMonthsFromNow])->orderBy('masa_berlaku', 'asc');
                break;
            case 'valid':
                $query->where(function ($q) use ($threeMonthsFromNow) {
                    $q->where('masa_berlaku', '>', $threeMonthsFromNow)
                        ->orWhereNull('masa_berlaku')->orderBy('masa_berlaku', 'asc');
                });
                break;
        }


        $years = collect(range(date('Y'), date('Y')-30))->map(function($year) {
            return ['label' => $year, 'value' => $year];
        });
        $items = $query->paginate(12);
        $conditions = Item::distinct()->pluck('kondisi');
        return view('livewire.items',[
            'items' => $items,
            'ruangans' => Ruangan::all(),
            'conditions' => $conditions,
            'years' => $years
        ]);
    }
}
