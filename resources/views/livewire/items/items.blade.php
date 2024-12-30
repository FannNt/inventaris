<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if($expirationFilter)
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium text-blue-800">
                            @switch($expirationFilter)
                                @case('expired')
                                    Showing Expired Items
                                    @break
                                @case('expiring_soon')
                                    Showing Items Expiring Soon
                                    @break
                                @case('valid')
                                    Showing Valid Items
                                    @break
                            @endswitch
                        </span>
                    </div>
                    <button wire:click="clearFilters" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear Filter
                    </button>
                </div>
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                           placeholder="Search equipment...">
                </div>

                <select wire:model.live="ruangan_filter"
                        class="rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">All Rooms</option>
                    @foreach($ruangans as $ruangan)
                        <option value="{{ $ruangan->id }}">{{ $ruangan->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="kondisi_filter"
                        class="rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">All Conditions</option>
                    <option value="Baik">Good</option>
                    <option value="Rusak">Damaged</option>
                </select>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $item)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $item->kondisi === 'Baik' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->kondisi }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-gray-600">Ruangan:</span>
                                <span class="ml-2 text-gray-900">{{ $item->ruangan->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-600">Masa Berlaku:</span>
                                @php
                                    $today = \Carbon\Carbon::now();
                                    $threeMonthsFromNow = \Carbon\Carbon::now()->addMonths(3);
                                    $expiryDate = $item->masa_berlaku ? \Carbon\Carbon::parse($item->masa_berlaku) : null;
                                @endphp
                                
                                @if(!$item->masa_berlaku)
                                    <span class="ml-2 text-gray-900">Masa berlaku Tidak tersedia</span>
                                @else
                                    <span class="ml-2 {{ 
                                        !$expiryDate ? 'text-gray-900' : 
                                        ($expiryDate->lt($today) ? 'text-red-600 font-medium' : 
                                        ($expiryDate->lt($threeMonthsFromNow) ? 'text-yellow-600 font-medium' : 'text-green-600 font-medium')) 
                                    }}">
                                        {{ $item->masa_berlaku }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-600">No. Seri:</span>
                                <span class="ml-2 text-gray-900">{{ $item->no_seri ?: 'No. Seri tidak tersedia' }}</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('items.show', $item) }}" 
                            class="inline-flex items-center justify-center p-2 text-sm font-medium text-gray-700 hover:text-primary-500 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
             </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $items->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
