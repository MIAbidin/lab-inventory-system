@extends('layouts.app')

@section('title', 'Inventaris')
@section('page-title', 'Daftar Inventaris')
@section('page-subtitle', 'Kelola semua item inventaris laboratorium')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Inventaris</li>
@endsection

@section('page-actions')
<a href="{{ route('inventory.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Tambah Item Baru
</a>
@endsection

@push('styles')
<style>
    .search-form {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .filter-badge {
        display: inline-block;
        margin: 2px;
    }
    
    /* Modern Pagination Container */
    .modern-pagination {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }
    
    /* Pagination Info */
    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .pagination-stats {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    /* Per Page Selector */
    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .per-page-selector select {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 0.9rem;
        background: white;
        min-width: 65px;
    }
    
    .per-page-selector select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Pagination Navigation */
    .pagination-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
    }
    
    .page-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    .page-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
        transform: translateY(-1px);
        text-decoration: none;
    }
    
    .page-btn:active {
        transform: translateY(0);
    }
    
    .page-btn.active {
        background: #3b82f6;
        color: white;
        font-weight: 600;
    }
    
    .page-btn.active:hover {
        background: #2563eb;
        transform: none;
        color: white;
    }
    
    .page-btn:disabled,
    .page-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none;
    }
    
    .page-btn:disabled:hover,
    .page-btn.disabled:hover {
        background: transparent;
        transform: none;
    }
    
    /* Navigation arrows */
    .page-btn.nav-btn {
        min-width: 32px;
        height: 32px;
        margin: 0 4px;
    }
    
    .page-btn.nav-btn i {
        font-size: 0.8rem;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .modern-pagination {
            padding: 16px;
            margin: 20px 0 0 0;
        }
        
        .pagination-info {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
            gap: 16px;
        }
        
        .pagination-controls {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .pagination-nav {
            gap: 2px;
        }
        
        .page-btn {
            min-width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .pagination-nav .page-btn:not(.active):not(.nav-btn) {
            display: none;
        }
        
        .pagination-nav .page-btn:not(.active):not(.nav-btn):first-of-type,
        .pagination-nav .page-btn:not(.active):not(.nav-btn):last-of-type {
            display: flex;
        }
    }
</style>
@endpush

@section('content')
<!-- Search and Filter Form -->
<div class="search-form">
    <form method="GET" action="{{ route('inventory.index') }}" id="filterForm">
        <div class="row g-3">
            <!-- Search Input -->
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nama, kode, atau serial number...">
                </div>
            </div>
            
            <!-- Category Filter -->
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Condition Filter -->
            <div class="col-md-2">
                <select name="condition" class="form-select">
                    <option value="">Semua Kondisi</option>
                    <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>
                        Baik
                    </option>
                    <option value="need_repair" {{ request('condition') == 'need_repair' ? 'selected' : '' }}>
                        Perlu Perbaikan
                    </option>
                    <option value="broken" {{ request('condition') == 'broken' ? 'selected' : '' }}>
                        Rusak
                    </option>
                </select>
            </div>
            
            <!-- Status Filter -->
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                        Tersedia
                    </option>
                    <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>
                        Sedang Digunakan
                    </option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                        Maintenance
                    </option>
                    <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>
                        Tidak Digunakan
                    </option>
                </select>
            </div>
            
            <!-- Search Button -->
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search', 'category', 'condition', 'status']))
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Hidden field for per_page -->
        <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
    </form>
</div>

<!-- Active Filters -->
@php
    $hasActiveFilters = request()->filled('search') || 
                       request()->filled('category') || 
                       request()->filled('condition') || 
                       request()->filled('status');
@endphp

@if($hasActiveFilters)
<div class="mb-3">
    <div class="d-flex align-items-center flex-wrap">
        <span class="me-2 fw-medium">Filter aktif:</span>
        
        @if(request()->filled('search'))
            <span class="badge bg-primary filter-badge">
                Pencarian: "{{ request('search') }}"
            </span>
        @endif
        
        @if(request()->filled('category'))
            @php
                $categoryName = $categories->find(request('category'))->name ?? 'Unknown';
            @endphp
            <span class="badge bg-info filter-badge">
                Kategori: {{ $categoryName }}
            </span>
        @endif
        
        @if(request()->filled('condition'))
            <span class="badge bg-warning filter-badge">
                Kondisi: {{ ucfirst(str_replace('_', ' ', request('condition'))) }}
            </span>
        @endif
        
        @if(request()->filled('status'))
            <span class="badge bg-success filter-badge">
                Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
            </span>
        @endif
        
        <!-- Clear all filters button -->
        <a href="{{ route('inventory.index') }}" class="badge bg-secondary filter-badge text-decoration-none">
            <i class="fas fa-times me-1"></i>Clear all
        </a>
    </div>
</div>
@endif

<!-- Results Summary -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <p class="text-muted mb-0">
            Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} 
            dari {{ $items->total() }} item
        </p>
    </div>
</div>

<!-- Inventory Table -->
<div class="card">
    <div class="card-body p-0">
        @if($items->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Foto</th>
                            <th>Kode & Nama</th>
                            <th>Kategori</th>
                            <th>Brand/Model</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th>Lokasi</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" 
                                             alt="{{ $item->name }}" 
                                             class="item-image">
                                    @else
                                        <div class="item-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <code class="small">{{ $item->inventory_code }}</code>
                                        <br>
                                        <a href="{{ route('inventory.show', $item) }}" 
                                           class="text-decoration-none fw-medium">
                                            {{ $item->name }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $item->category->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="fw-medium">{{ $item->brand }}</div>
                                        <div class="text-muted">{{ $item->model }}</div>
                                    </div>
                                </td>
                                <td>
                                    <code class="small">{{ $item->serial_number ?: '-' }}</code>
                                </td>
                                <td>
                                    <span class="badge {{ $item->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $item->condition_badge }}">
                                        @switch($item->condition)
                                            @case('good')
                                                <i class="fas fa-check-circle me-1"></i>Baik
                                                @break
                                            @case('need_repair')
                                                <i class="fas fa-wrench me-1"></i>Perlu Perbaikan
                                                @break
                                            @case('broken')
                                                <i class="fas fa-times-circle me-1"></i>Rusak
                                                @break
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $item->location ?: 'Tidak diset' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('inventory.show', $item) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('inventory.edit', $item) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Hapus"
                                                onclick="confirmDelete('{{ $item->id }}', '{{ $item->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada item ditemukan</h5>
                @if(request()->hasAny(['search', 'category', 'condition', 'status']))
                    <p class="text-muted">Coba ubah kriteria pencarian atau filter</p>
                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary">
                        Tampilkan Semua Item
                    </a>
                @else
                    <p class="text-muted">Mulai dengan menambahkan item inventaris pertama</p>
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Item Pertama
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Pagination -->
@if($items->count() > 0)
    <div class="modern-pagination">
        <div class="pagination-info">
            <div class="pagination-stats">
                Showing <strong>{{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }}</strong> 
                of <strong>{{ $items->total() }}</strong> items
                @if($items->hasPages())
                    <span class="text-muted">(Page {{ $items->currentPage() }} of {{ $items->lastPage() }})</span>
                @endif
            </div>
            <div class="pagination-controls">
                <div class="per-page-selector">
                    <span>Show:</span>
                    <select onchange="changePerPage(this.value)">
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Only show navigation if there are multiple pages -->
        @if($items->hasPages())
            <nav class="pagination-nav">
                {{-- First Page --}}
                @if($items->currentPage() > 3)
                    <a class="page-btn nav-btn" href="{{ $items->url(1) }}" title="First page">
                        <i class="fas fa-angles-left"></i>
                    </a>
                @endif
                
                {{-- Previous Page --}}
                @if($items->onFirstPage())
                    <span class="page-btn nav-btn disabled">
                        <i class="fas fa-angle-left"></i>
                    </span>
                @else
                    <a class="page-btn nav-btn" href="{{ $items->previousPageUrl() }}" title="Previous page">
                        <i class="fas fa-angle-left"></i>
                    </a>
                @endif
                
                {{-- Page Numbers --}}
                @php
                    $start = max($items->currentPage() - 2, 1);
                    $end = min($start + 4, $items->lastPage());
                    $start = max($end - 4, 1);
                @endphp
                
                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $items->currentPage())
                        <span class="page-btn active">{{ $i }}</span>
                    @else
                        <a class="page-btn" href="{{ $items->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor
                
                {{-- Show ellipsis and last page if needed --}}
                @if($end < $items->lastPage())
                    @if($end < $items->lastPage() - 1)
                        <span class="page-btn disabled">...</span>
                    @endif
                    <a class="page-btn" href="{{ $items->url($items->lastPage()) }}">{{ $items->lastPage() }}</a>
                @endif
                
                {{-- Next Page --}}
                @if($items->hasMorePages())
                    <a class="page-btn nav-btn" href="{{ $items->nextPageUrl() }}" title="Next page">
                        <i class="fas fa-angle-right"></i>
                    </a>
                @else
                    <span class="page-btn nav-btn disabled">
                        <i class="fas fa-angle-right"></i>
                    </span>
                @endif
                
                {{-- Last Page --}}
                @if($items->currentPage() < $items->lastPage() - 2)
                    <a class="page-btn nav-btn" href="{{ $items->url($items->lastPage()) }}" title="Last page">
                        <i class="fas fa-angles-right"></i>
                    </a>
                @endif
            </nav>
        @else
            <!-- Show message when all items fit on one page -->
            <div class="text-center text-muted">
                <small><i class="fas fa-info-circle me-1"></i>All items are displayed on this page</small>
            </div>
        @endif
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus item <strong id="itemName"></strong>?</p>
                <p class="text-muted small">Item yang dihapus dapat dipulihkan dari trash.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(itemId, itemName) {
    document.getElementById('itemName').textContent = itemName;
    document.getElementById('deleteForm').action = `/inventory/${itemId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Change items per page
function changePerPage(perPage) {
    const form = document.getElementById('filterForm');
    const perPageInput = form.querySelector('input[name="per_page"]');
    perPageInput.value = perPage;
    form.submit();
}

// Jump to specific page
function jumpToPage(page) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('page', page);
    window.location.href = currentUrl.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    // Clear search on Escape key
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
            }
        });
    }
    
    // Auto-submit on Enter in search field
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.form.submit();
            }
        });
    }
});

// Keep your existing functions and add this if needed
function changePerPage(perPage) {
    const form = document.getElementById('filterForm');
    const perPageInput = form.querySelector('input[name="per_page"]');
    perPageInput.value = perPage;
    form.submit();
}
</script>
@endpush