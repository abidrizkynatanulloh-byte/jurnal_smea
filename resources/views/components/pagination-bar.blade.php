@props(['paginator', 'perPageOptions' => [30, 50, 100, 150]])

@if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->total() > 0)
    <div class="px-5 py-3.5 bg-white border-t border-slate-200 overflow-x-auto select-none">
        <div class="flex items-center justify-between gap-6 min-w-max w-full">
            <!-- Left Side: Results per page: [ 30 ^ ] 1 – 30 of 1504 (PERSIS GAMBAR 2) -->
            <div class="flex items-center space-x-3 text-sm text-slate-700 whitespace-nowrap shrink-0">
                <span class="font-normal text-slate-600 whitespace-nowrap">Results per page:</span>
                
                <!-- Custom Dropdown Popup (Sesuai Gambar 2) -->
                <div class="relative inline-block text-left" id="perPageDropdownWrapper">
                    <button type="button" onclick="togglePerPageMenu()" id="perPageBtn"
                        class="h-9 px-3 bg-white border border-slate-300 hover:border-slate-400 text-slate-900 text-sm font-medium rounded flex items-center justify-between space-x-2.5 focus:outline-none transition-colors shadow-2xs cursor-pointer min-w-[62px]">
                        <span id="currentPerPageVal">{{ $paginator->perPage() }}</span>
                        <i data-lucide="chevron-up" id="perPageChevron" class="w-4 h-4 text-slate-500 transition-transform"></i>
                    </button>

                    <!-- Popup Menu (Opens upwards matching Image 2) -->
                    <div id="perPageMenu" class="hidden absolute bottom-full mb-1 left-0 w-full min-w-[62px] bg-white border border-slate-300 rounded shadow-md z-50 py-1 divide-y divide-slate-100">
                        @foreach($perPageOptions as $opt)
                            <button type="button" onclick="selectPerPage({{ $opt }})"
                                class="w-full py-1.5 px-3 text-center text-sm font-medium hover:bg-slate-100 transition-colors {{ $paginator->perPage() == $opt ? 'font-bold text-slate-900 bg-slate-50' : 'text-slate-700' }}">
                                {{ $opt }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Counter: 1 – 30 of 1504 -->
                <span class="font-normal text-slate-700 whitespace-nowrap pl-1">
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
        function togglePerPageMenu() {
            const menu = document.getElementById('perPageMenu');
            const chevron = document.getElementById('perPageChevron');
            if (menu) {
                const isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    menu.classList.remove('hidden');
                    if (chevron) chevron.style.transform = 'rotate(180deg)';
                } else {
                    menu.classList.add('hidden');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                }
            }
        }

        function selectPerPage(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', val);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('perPageDropdownWrapper');
            const menu = document.getElementById('perPageMenu');
            const chevron = document.getElementById('perPageChevron');
            if (wrapper && !wrapper.contains(e.target) && menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        });
    </script>
@endif
