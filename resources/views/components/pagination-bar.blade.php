@props(['paginator', 'perPageOptions' => [15, 30, 50, 100, 150]])

@if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->total() > 0)
    <div class="px-5 py-3 bg-white border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 select-none">
        <!-- Results per page & Counter -->
        <div class="flex items-center space-x-3 text-xs text-slate-600">
            <span class="font-normal text-slate-500">Results per page:</span>
            <div class="relative inline-block">
                <select onchange="changePerPage(this.value)" 
                    class="bg-white border border-slate-300 hover:border-slate-400 text-slate-800 text-xs font-semibold rounded-md px-2.5 py-1 focus:outline-none focus:ring-1 focus:ring-[#405078] cursor-pointer shadow-2xs">
                    @foreach($perPageOptions as $opt)
                        <option value="{{ $opt }}" {{ $paginator->perPage() == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            <span class="font-medium text-slate-600">
                {{ $paginator->firstItem() ?? 0 }} – {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}
            </span>
        </div>

        <!-- Number Pagination Navigation -->
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
