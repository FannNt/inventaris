@if ($paginator->hasPages())
    <nav>
        <ul class="flex items-center justify-center gap-1 mt-8">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled">
                    <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="px-3 py-2 bg-white text-gray-700 rounded-lg border hover:bg-gray-50 transition-colors"
                       rel="prev">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled">
                        <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-3 py-2 bg-blue-500 text-white rounded-lg">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                   class="px-3 py-2 bg-white text-gray-700 rounded-lg border hover:bg-gray-50 transition-colors">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="px-3 py-2 bg-white text-gray-700 rounded-lg border hover:bg-gray-50 transition-colors"
                       rel="next">&rsaquo;</a>
                </li>
            @else
                <li class="disabled">
                    <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
