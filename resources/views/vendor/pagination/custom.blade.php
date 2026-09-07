@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        
        // Buat daftar halaman presisi seperti Image 5 QS World University Rankings
        $pages = [];
        if ($last <= 7) {
            for ($i = 1; $i <= $last; $i++) {
                $pages[] = $i;
            }
        } else {
            if ($current <= 3) {
                $pages = [1, 2, 3, '...', $last - 1, $last];
            } elseif ($current >= $last - 2) {
                $pages = [1, 2, '...', $last - 2, $last - 1, $last];
            } else {
                $pages = [1, '...', $current - 1, $current, $current + 1, '...', $last];
            }
        }
    @endphp

    <div class="flex items-center space-x-1.5 sm:space-x-2 text-sm select-none whitespace-nowrap shrink-0">
        {{-- Previous Button: Solid Dark Square [ < ] matching Image 2 --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 rounded-md bg-[#202938] text-white/40 flex items-center justify-center cursor-not-allowed shadow-xs shrink-0" title="Halaman Sebelumnya">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-9 h-9 rounded-md bg-[#202938] hover:bg-[#2c384c] text-white flex items-center justify-center transition-colors shadow-xs shrink-0" title="Halaman Sebelumnya">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($pages as $p)
            @if ($p === '...')
                <span class="w-6 text-center text-slate-400 font-bold text-sm select-none shrink-0">...</span>
            @elseif ($p == $current)
                {{-- Active Page: Solid Dark Square [ 1 ] matching Image 2 --}}
                <span class="w-9 h-9 rounded-md bg-[#202938] text-white font-bold text-sm flex items-center justify-center shadow-xs shrink-0">
                    {{ $p }}
                </span>
            @else
                {{-- Inactive Page: Clean Text Number with NO background matching Image 2 --}}
                <a href="{{ $paginator->url($p) }}" class="min-w-[2.25rem] h-9 px-2 flex items-center justify-center text-sm font-medium text-slate-700 hover:text-black hover:bg-slate-100 rounded-md transition-colors shrink-0">
                    {{ $p }}
                </a>
            @endif
        @endforeach

        {{-- Next Button: Clean text chevron > matching Image 2 --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center text-slate-700 hover:text-black font-bold transition-colors shrink-0" title="Halaman Selanjutnya">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        @else
            <span class="w-9 h-9 flex items-center justify-center text-slate-300 cursor-not-allowed shrink-0">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </span>
        @endif
    </div>
@endif
