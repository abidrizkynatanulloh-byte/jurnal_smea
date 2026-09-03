@if ($paginator->hasPages())
    <div class="flex items-center space-x-1 sm:space-x-1.5 text-sm select-none">
        {{-- Previous Page Link: Solid dark button matching Image 5 --}}
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 sm:w-9 sm:h-9 rounded bg-[#1B2533] text-white/60 flex items-center justify-center cursor-not-allowed shadow-xs" title="Halaman Sebelumnya">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded bg-[#1B2533] hover:bg-[#2B374A] text-white flex items-center justify-center transition-colors shadow-xs" title="Halaman Sebelumnya">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-6 text-center text-gray-500 font-bold text-sm">...</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        {{-- Active Page: Solid dark button matching Image 5 [ 1 ] --}}
                        <span class="w-8 h-8 sm:w-9 sm:h-9 rounded bg-[#1B2533] text-white font-bold text-sm flex items-center justify-center shadow-xs">
                            {{ $page }}
                        </span>
                    @else
                        {{-- Inactive Page: Clean text number with no background --}}
                        <a href="{{ $url }}" class="min-w-[2rem] h-8 sm:h-9 px-2 flex items-center justify-center text-sm font-medium text-gray-700 hover:text-black hover:bg-gray-100 rounded transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link: Clean text chevron matching Image 5 > --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-gray-700 hover:text-black font-bold transition-colors" title="Halaman Selanjutnya">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        @else
            <span class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-gray-300 cursor-not-allowed">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </span>
        @endif
    </div>
@endif
