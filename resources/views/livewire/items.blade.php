<div class="min-h-screen bg-gray-100">
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                @if($expirationFilter)
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex justify-between items-center">
                        <div class="flex items-center">
                                <span class="font-medium">
                                    Showing:
                                    @switch($expirationFilter)
                                        @case('expired')
                                            Expired Items
                                            @break
                                        @case('expiring_soon')
                                            Items Expiring Soon
                                            @break
                                        @case('valid')
                                            Valid Items
                                            @break
                                    @endswitch
                                </span>
                        </div>
                        <button wire:click="clearFilters"
                                class="text-blue-600 hover:text-blue-800 font-medium">
                            Clear Filter
                        </button>
                    </div>
                @endif
            {{-- Filters --}}
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search products..."
                               class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <select wire:model.live="ruangan_filter" class="w-full p-2 border rounded-lg">
                            <option value="">All Rooms</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id }}">{{ $ruangan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select wire:model.live="kondisi_filter" class="w-full p-2 border rounded-lg">
                            <option value="">All Conditions</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($items as $item)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $item->name }}</h3>
                                <span class="px-2 py-1 text-sm rounded-full {{ $item->kondisi === 'Baik' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->kondisi }}
                                </span>
                            </div>
                            <div class="space-y-2 text-gray-600">
                                <p><span class="font-medium">Merk:</span> {{ $item->merk }}</p>
                                <p><span class="font-medium">Room:</span> {{ $item->ruangan->nama }}</p>
                                <p><span class="font-medium">Year:</span> {{ $item->tahun_pengadaan }}</p>
                                <p><span class="font-medium">Valid Until:</span> {{ $item->masa_berlaku }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
