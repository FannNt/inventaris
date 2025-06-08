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
    public $no_seri = '';

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
        $this->expirationFilter = request()->query('filter', '');
    }

    public function updatedExpirationFilter($value)
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'ruangan_filter', 'kondisi_filter', 'expirationFilter']);
    }

    public function render()
    {
        $query = Item::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->ruangan_filter, function($query) {
                $query->where('id_ruangan', 'like', $this->ruangan_filter );
            })
            ->when($this->kondisi_filter, function($query) {
                $query->where('kondisi','like',$this->kondisi_filter);
            });

        switch ($this->expirationFilter) {
            case 'expired':
                $query->whereNotNull('masa_berlaku')
                      ->where('masa_berlaku', '<', now())
                      ->orderBy('masa_berlaku', 'asc');
                break;
            case 'expiring_soon':
                $query->whereNotNull('masa_berlaku')
                      ->where('masa_berlaku', '>=', now())
                      ->where('masa_berlaku', '<=', now()->addMonths(3))
                      ->orderBy('masa_berlaku', 'asc');
                break;
            case 'valid':
                $query->where(function($q) {
                    $q->whereNull('masa_berlaku')
                      ->orWhere('masa_berlaku', '>', now()->addMonths(3));
                })->orderBy('masa_berlaku', 'asc');
                break;
        }

        $items = $query->paginate(12);
        
        return view('livewire.items.items', [
            'items' => $items,
            'ruangans' => Ruangan::all(),
            'conditions' => Item::distinct()->pluck('kondisi'),
            'years' => collect(range(date('Y'), date('Y')-30))->map(function($year) {
                return ['label' => $year, 'value' => $year];
            })
        ]);
    }
}
