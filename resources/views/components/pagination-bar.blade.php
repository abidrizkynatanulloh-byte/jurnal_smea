@props(['paginator', 'perPageOptions' => [15, 30, 50, 100, 150]])

@if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->total() > 0)
    <div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4 select-none">
        <!-- Results per page & Counter (Sesuai Gambar 5 QS World University Rankings) -->
        <div class="flex items-center space-x-3 text-sm text-gray-700">
            <span class="font-normal text-gray-600">Results per page:</span>
            <div class="relative inline-block">
                <select onchange="changePerPage(this.value)" 
                    class="appearance-none bg-white border border-gray-300 hover:border-gray-400 text-gray-800 text-sm font-semibold rounded px-3 py-1 pr-7 focus:outline-none focus:border-gray-500 cursor-pointer shadow-2xs">
                    @foreach($perPageOptions as $opt)
                        <option value="{{ $opt }}" {{ $paginator->perPage() == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            <span class="font-normal text-gray-600 pl-1">
                {{ $paginator->firstItem() ?? 0 }} – {{ $paginator->lastItem() ?? 0 }} of {{ number_format($paginator->total(), 0, ',', '.') }}
            </span>
        </div>

        <!-- Number Pagination Navigation (Sesuai Gambar 5) -->
        <div>
            {{ $paginator->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>

    <script>
        if (typeof changePerPage === 'undefined') {
            function changePerPage(val) {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', val);
                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            }
        }
    </script>
@endif
