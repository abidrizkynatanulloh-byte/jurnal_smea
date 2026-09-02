@if ($paginator->hasPages())
    <div class="flex items-center space-x-1 sm:space-x-2 text-xs font-semibold">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-[#D1D9EB] text-[#1E2538] hover:bg-gray-100 transition-colors shadow-2xs">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-6 text-center text-gray-400 font-bold">...</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#1E2538] text-white font-bold shadow-xs">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-[#D1D9EB] text-[#1E2538] hover:bg-gray-100 transition-colors font-medium shadow-2xs">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-[#D1D9EB] text-[#1E2538] hover:bg-gray-100 transition-colors shadow-2xs">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        @else
            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </span>
        @endif
    </div>
@endif
