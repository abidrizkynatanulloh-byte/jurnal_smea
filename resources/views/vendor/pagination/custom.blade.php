@if ($paginator->hasPages())
    <div class="flex items-center space-x-1.5 sm:space-x-2 text-xs font-semibold select-none">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2D3748]/30 text-white/50 cursor-not-allowed">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2D3748] hover:bg-[#1A202C] text-white transition-all shadow-xs" title="Halaman Sebelumnya">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="w-6 text-center text-slate-400 font-bold">...</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2D3748] text-white font-bold shadow-xs">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="min-w-7 h-8 px-1.5 flex items-center justify-center text-slate-700 hover:text-[#405078] hover:bg-slate-100/80 rounded-md transition-colors font-medium">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2D3748] hover:bg-[#1A202C] text-white transition-all shadow-xs" title="Halaman Selanjutnya">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        @else
            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2D3748]/30 text-white/50 cursor-not-allowed">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </span>
        @endif
    </div>
@endif
