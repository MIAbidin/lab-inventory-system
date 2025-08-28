<!-- resources/views/reports/partials/pagination.blade.php -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        <small class="text-muted">
            Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} hasil
        </small>
    </div>
    <div>
        @if ($items->hasPages())
            <nav aria-label="Pagination Navigation">
                <ul class="pagination mb-0">
                    {{-- Previous Page Link --}}
                    @if ($items->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">‹</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadPage({{ $items->currentPage() - 1 }}); return false;" rel="prev">‹</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                        @if ($page == $items->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="#" onclick="loadPage({{ $page }}); return false;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($items->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadPage({{ $items->currentPage() + 1 }}); return false;" rel="next">›</a>
                        </li>
                    @else
                        <li class="page-item disabled"><span class="page-link">›</span></li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
</div>