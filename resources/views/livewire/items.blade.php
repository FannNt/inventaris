    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <button wire:click="createForm()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                    Create Product
                </button>
            </div>
                @if($isOpen)
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                        <div class="bg-white rounded-lg p-8 max-w-md mx-auto">
                            <h2 class="text-xl mb-4">{{ $id ? 'Edit Product' : 'Create Product' }}</h2>

                            <form>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                                    <input type="text" wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div class="mb-4">
                                    <label for="id_ruangan" class="form-label">Ruangan</label>
                                    <select wire:model.live="id_ruangan" id="id_ruangan" class="form-select @error('id_ruangan') is-invalid @enderror">
                                        <option value="">Pilih Ruangan</option>
                                        @foreach($ruangans as $ruangan)
                                            <option value="{{ $ruangan->id }}">{{ $ruangan->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_ruangan') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>


                                <div class="mb-4">
                                    <label for="kondisi" class="form-label">Kondisi</label>
                                    <select wire:model.live="kondisi" id="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                                        <option value="">Pilih Kondisi</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Rusak">Rusak</option>
                                    </select>
                                    @error('kondisi') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                 @if($kondisi === 'Rusak')
                                    <div class="mb-4">
                                        <label for="keterangan" class="form-label">Keterangan Kerusakan</label>
                                        <textarea wire:model="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"></textarea>
                                        @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                @endif


                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Merk:</label>
                                    <input type="text" wire:model="merk" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('merk') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="mb-4">
                                    <label for="tahun_pengadaan" class="form-label">Tahun Pengadaan</label>
                                    <select wire:model="tahun_pengadaan" class="form-select @error('tahun_pengadaan') is-invalid @enderror">
                                        <option value="">Pilih Tahun</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year['value'] }}">{{ $year['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('tahun_pengadaan') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Masa berlaku:</label>
                                    <input type="date" wire:model="masa_berlaku" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('masa_berlaku') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div class="flex justify-end">
                                    <button wire:click.prevent="store()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2 text-black">Save</button>
                                    <button wire:click="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <!-- Search and Filters -->
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
                    <div class="mb-8">
                        <div class="flex gap-4">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search items..."
                                   class="w-full p-2 border rounded">

                            <select wire:model.live="ruangan_filter" class="p-2 border rounded">
                                <option value="">All Rooms</option>
                                @foreach($ruangans as $ruangan)
                                    <option value="{{ $ruangan->id }}">{{ $ruangan->nama }}</option>
                                @endforeach
                            </select>

                            <select wire:model.live="kondisi_filter" class="p-2 border rounded">
                                <option value="">All Conditions</option>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                    </div>
                <table class="min-w-full">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Name</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Merk</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Ruangan</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Tahun Pengadaan</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Masa berlaku</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Action</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $item->merk }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{$item->ruangan->nama }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $item->tahun_pengadaan }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $item->masa_berlaku }}</td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                <button wire:click="edit({{ $item->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                <button wire:click="delete({{ $item->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>

