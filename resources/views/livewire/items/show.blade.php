<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $item->name }}
            </h2>
            <a href="{{ route('items') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-200 transition ease-in-out duration-150">
                Back to Items
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Item Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Name</label>
                                <p class="mt-1">{{ $item->name }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Brand</label>
                                <p class="mt-1">{{ $item->merk }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Condition</label>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $item->kondisi === 'Baik' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $item->kondisi }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Serial Number</label>
                                <p class="mt-1">{{ $item->no_seri ?: 'Not available' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Room</label>
                                <p class="mt-1">{{ $item->ruangan->name }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Procurement Year</label>
                                <p class="mt-1">{{ $item->tahun_pengadaan }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Expiration Date</label>
                                <p class="mt-1">{{ $item->masa_berlaku ?: 'Not available' }}</p>
                            </div>

                            @if($item->kondisi === 'Rusak')
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Notes</label>
                                    <p class="mt-1">{{ $item->keterangan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 