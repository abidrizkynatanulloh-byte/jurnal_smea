@props(['paginator', 'perPageOptions' => [30, 50, 100, 150]])

@if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->total() > 0)
    <div class="px-4 py-3 bg-white dark:bg-[#131B2E] border-t border-slate-200 dark:border-slate-800 select-none transition-colors">
        <div class="flex items-center justify-between gap-4 flex-wrap w-full">
            <!-- Left Side: Results per page: [ 30 v ] 1 – 30 of 1504 (PERSIS GAMBAR 2) -->
            <div class="flex items-center space-x-2.5 text-xs text-slate-700 dark:text-slate-300 whitespace-nowrap shrink-0">
                <span class="font-normal text-slate-600 dark:text-slate-400 whitespace-nowrap">Results per page:</span>
                
                <!-- Native Select with Custom Styling (Anti-Clipped by Container Overflows) -->
                <div class="relative inline-block text-left">
                    <select onchange="selectPerPage(this.value)" id="perPageSelect"
                        class="h-7.5 pl-2.5 pr-7 bg-white dark:bg-[#182238] border border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600 text-slate-900 dark:text-slate-100 text-xs font-semibold rounded-md appearance-none cursor-pointer focus:outline-none focus:ring-1 focus:ring-slate-400 transition-colors shadow-2xs">
                        @foreach($perPageOptions as $opt)
                            <option value="{{ $opt }}" {{ $paginator->perPage() == $opt ? 'selected' : '' }} class="bg-white dark:bg-[#182238] text-slate-900 dark:text-slate-100 font-medium">
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-slate-500 dark:text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Counter: 1 – 30 of 1504 -->
                <span class="font-normal text-slate-700 dark:text-slate-300 whitespace-nowrap pl-1">
                    {{ $paginator->firstItem() ?? 0 }} – {{ $paginator->lastItem() ?? 0 }} of {{ number_format($paginator->total(), 0, ',', '.') }}
                </span>
            </div>

            <!-- Right Side: Number Pagination Navigation (PERSIS GAMBAR 2) -->
            <div class="shrink-0">
                {{ $paginator->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

    <script>
        function selectPerPage(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', val);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
    </script>
@endif
