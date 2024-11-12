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
                    $q->where('merk', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%');
                });
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

    public function updateKondisi($value)
    {
        if ($value == 'Baik') {
            $this->keterangan = null;
        }
    }
    public function resetInput()
    {
        $this->name = null;
        $this->merk = null;
        $this->kondisi = null;
        $this->keterangan = null;
        $this->tahun_pengadaan = date('Y');
        $this->masa_berlaku = null;
        $this->id_ruangan = null;

    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function createForm()
    {
        $this->resetInput();
        $this->openModal();
    }
    public function store(){
        $this->validate();

        Item::updateOrCreate(['id'=> $this->id],[
            'name' => $this->name,
            'id_ruangan' => $this->id_ruangan,
            'merk' => $this -> merk,
            'kondisi' => $this -> kondisi,
            'keterangan' => $this ->keterangan,
            'tahun_pengadaan' => $this ->tahun_pengadaan,
            'masa_berlaku' => $this -> masa_berlaku
        ]);

        session()->flash('message', $this->id ? 'Item updated successfully.' : 'Item created successfully.');
        $this->closeModal();
        $this->resetInput();

    }
    public function edit($id){
        $item = Item::findOrFail($id);
        $this->id = $id;
        $this->name = $item->name;
        $this->id_ruangan = $item->id_ruangan;
        $this->merk = $item->merk;
        $this->kondisi = $item->kondisi;
        $this->keterangan = $item->keterangan;
        $this->tahun_pengadaan = $item->tahun_pengadaan;
        $this->masa_berlaku = $item->masa_berlaku;

        $this->openModal();
    }

    public function delete($id)
    {
        Item::find($id)->delete();
        session()->flash('message', 'Item deleted successfully.');
    }
}
