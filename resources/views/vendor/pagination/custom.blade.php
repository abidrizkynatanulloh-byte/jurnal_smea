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

    <div class="flex items-center space-x-1 sm:space-x-1.5 text-xs select-none whitespace-nowrap shrink-0">
        {{-- Previous Button: Solid Dark Square [ < ] matching Image 2 --}}
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 rounded-md bg-[#202938] dark:bg-slate-800 text-white/30 dark:text-slate-600 flex items-center justify-center cursor-not-allowed shadow-xs shrink-0" title="Halaman Sebelumnya">
                <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 rounded-md bg-[#202938] dark:bg-slate-800 hover:bg-[#2c384c] dark:hover:bg-slate-700 text-white flex items-center justify-center transition-colors shadow-xs shrink-0 cursor-pointer" title="Halaman Sebelumnya">
                <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($pages as $p)
            @if ($p === '...')
                <span class="w-5 text-center text-slate-400 dark:text-slate-500 font-bold text-xs select-none shrink-0">...</span>
            @elseif ($p == $current)
                {{-- Active Page: Solid Dark Square [ 1 ] matching Image 2 --}}
                <span class="w-8 h-8 rounded-md bg-[#202938] dark:bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">
                    {{ $p }}
                </span>
            @else
                {{-- Inactive Page: Clean Text Number with NO background matching Image 2 --}}
                <a href="{{ $paginator->url($p) }}" class="min-w-[2rem] h-8 px-1.5 flex items-center justify-center text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-black dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md transition-colors shrink-0 cursor-pointer">
                    {{ $p }}
                </a>
            @endif
        @endforeach

        {{-- Next Button: Clean text chevron > matching Image 2 --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center text-slate-700 dark:text-slate-300 hover:text-black dark:hover:text-white font-bold transition-colors shrink-0 cursor-pointer" title="Halaman Selanjutnya">
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            </a>
        @else
            <span class="w-8 h-8 flex items-center justify-center text-slate-300 dark:text-slate-700 cursor-not-allowed shrink-0">
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            </span>
        @endif
    </div>
@endif
