@extends('layouts.app')

@section('title', 'Laporan Inventaris')
@section('page-title', 'Laporan Inventaris')
@section('page-subtitle', 'Kelola dan ekspor laporan inventaris laboratorium')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Laporan Inventaris</li>
@endsection

@section('page-actions')
<button type="button" class="btn btn-outline-secondary" id="refreshStatsBtn">
    <i class="fas fa-sync-alt"></i> Refresh Data
</button>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Error/Success Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Item
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalItems">
                                        {{ number_format($statistics['total']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Tersedia
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="availableItems">
                                        {{ number_format($statistics['available']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Maintenance
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="maintenanceItems">
                                        {{ number_format($statistics['maintenance']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tools fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Rusak
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="brokenItems">
                                        {{ number_format($statistics['broken']) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="fas fa-filter"></i> Toggle Filter
                    </button>
                </div>
                
                <div class="collapse show" id="filterCollapse">
                    <div class="card-body" style="overflow: visible; position: relative;">
                        <form method="GET" action="{{ route('reports.index') }}" id="filterForm">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Pencarian</label>
                                    <input type="text" class="form-control" name="search" 
                                        value="{{ request('search') }}" 
                                        placeholder="Nama, kode, merk, model..."
                                        maxlength="255">
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select class="form-select" name="category">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Kondisi</label>
                                    <select class="form-select" name="condition">
                                        <option value="">Semua Kondisi</option>
                                        <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                                        <option value="need_repair" {{ request('condition') == 'need_repair' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                        <option value="broken" {{ request('condition') == 'broken' ? 'selected' : '' }}>Rusak</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>Sedang Digunakan</option>
                                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>Dibuang</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" name="location" 
                                        value="{{ request('location') }}" 
                                        placeholder="Nama lokasi"
                                        maxlength="255">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Tanggal Beli Dari</label>
                                    <input type="date" class="form-control" name="date_from" 
                                        value="{{ request('date_from') }}"
                                        max="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Tanggal Beli Sampai</label>
                                    <input type="date" class="form-control" name="date_to" 
                                        value="{{ request('date_to') }}"
                                        max="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <div class="w-100">
                                        <button type="submit" class="btn btn-primary w-100" id="filterBtn">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden field for per_page -->
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                            
                            <div class="row mt-2" style="margin-bottom: 60px;">
                                <div class="col-12 d-flex justify-content-between">
                                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo"></i> Reset Filter
                                    </a>
                                    
                                    <!-- Dropdown dengan positioning yang diperbaiki -->
                                    <div class="btn-group" style="position: relative; z-index: 10;">
                                        <button type="button" class="btn btn-success dropdown-toggle" 
                                                data-bs-toggle="dropdown" 
                                                data-bs-auto-close="true"
                                                aria-expanded="false">
                                            <i class="fas fa-download"></i> Export
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1050;">
                                            <li><a class="dropdown-item" href="#" onclick="exportPDF(event)">
                                                <i class="fas fa-file-pdf text-danger me-2"></i> Export PDF
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportExcel(event)">
                                                <i class="fas fa-file-excel text-success me-2"></i> Export Excel
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Data Inventaris ({{ $items->total() }} item)
                        </h6>
                        <small class="text-muted">
                            Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} item
                        </small>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Kode</th>
                                        <th width="15%">Nama Item</th>
                                        <th width="10%">Kategori</th>
                                        <th width="10%">Merk/Model</th>
                                        <th width="10%">Tanggal Beli</th>
                                        <th width="12%">Harga</th>
                                        <th width="8%">Kondisi</th>
                                        <th width="8%">Status</th>
                                        <th width="12%">Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $items->firstItem() + $index }}</td>
                                            <td><small class="text-primary font-monospace">{{ $item->inventory_code }}</small></td>
                                            <td>
                                                <strong>{{ $item->name }}</strong>
                                                @if($item->serial_number)
                                                    <br><small class="text-muted">SN: {{ $item->serial_number }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->category->name ?? '-' }}</td>
                                            <td>
                                                {{ $item->brand }}
                                                @if($item->model)
                                                    <br><small class="text-muted">{{ $item->model }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                @if($item->purchase_price)
                                                    Rp {{ number_format($item->purchase_price, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @switch($item->condition)
                                                        @case('good') bg-success @break
                                                        @case('need_repair') bg-warning @break
                                                        @case('broken') bg-danger @break
                                                        @default bg-secondary
                                                    @endswitch">
                                                    @switch($item->condition)
                                                        @case('good') Baik @break
                                                        @case('need_repair') Perlu Perbaikan @break
                                                        @case('broken') Rusak @break
                                                        @default {{ $item->condition }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @switch($item->status)
                                                        @case('available') bg-success @break
                                                        @case('in_use') bg-info @break
                                                        @case('maintenance') bg-warning @break
                                                        @case('disposed') bg-danger @break
                                                        @default bg-secondary
                                                    @endswitch">
                                                    @switch($item->status)
                                                        @case('available') Tersedia @break
                                                        @case('in_use') Digunakan @break
                                                        @case('maintenance') Maintenance @break
                                                        @case('disposed') Dibuang @break
                                                        @default {{ $item->status }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>{{ $item->location ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data yang ditemukan</h5>
                            <p class="text-muted">Coba ubah filter pencarian atau tambah item inventaris baru.</p>
                            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Item
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modern Pagination (Same as Inventory) -->
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
                                <select id="perPageSelect" onchange="changePerPage(this.value)">
                                    <option value="10" {{ request('per_page', 10) == '10' ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
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
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #007bff !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }
    .border-left-danger {
        border-left: 0.25rem solid #dc3545 !important;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .badge {
        font-size: 0.75rem;
    }
    .font-monospace {
        font-family: 'Courier New', monospace;
    }
    .card-body {
        overflow: visible !important;
        position: relative;
    }

    /* Memastikan dropdown memiliki z-index yang tepat */
    .btn-group .dropdown-menu {
        z-index: 1050 !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075), 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.15);
    }

    /* Memastikan dropdown tidak terpotong di container */
    .dropdown-menu-end {
        right: 0 !important;
        left: auto !important;
    }

    /* Animasi smooth untuk dropdown */
    .dropdown-menu {
        transition: opacity 0.15s linear, transform 0.15s ease-in-out;
    }

    .dropdown-menu.show {
        opacity: 1;
        transform: scale(1);
    }

    /* Style untuk dropdown items */
    .dropdown-item {
        padding: 0.5rem 1rem;
        transition: background-color 0.15s ease-in-out;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item i {
        width: 1.2em;
    }

    /* Modern Pagination Container (Same as Inventory) */
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

    /* Additional hover effects for better UX */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Global state
    let exportInProgress = false;
    let debounceTimeout = null;

    // Utility Functions
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function showLoading() {
        const loadingEl = document.getElementById('loadingOverlay');
        if (loadingEl) {
            loadingEl.classList.remove('d-none');
        }
    }

    function hideLoading() {
        const loadingEl = document.getElementById('loadingOverlay');
        if (loadingEl) {
            loadingEl.classList.add('d-none');
        }
    }

    function showPaginationLoading() {
        const loadingEl = document.getElementById('paginationLoading');
        if (loadingEl) {
            loadingEl.classList.remove('d-none');
        }
        showLoading();
    }

    function hidePaginationLoading() {
        const loadingEl = document.getElementById('paginationLoading');
        if (loadingEl) {
            loadingEl.classList.add('d-none');
        }
        hideLoading();
    }

    // Debounce function
    function debounce(func, wait) {
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(debounceTimeout);
                func(...args);
            };
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(later, wait);
        };
    }

    // PERBAIKAN UTAMA: Function changePerPage yang benar
    function changePerPage(perPage) {
        console.log('=== CHANGE PER PAGE DEBUG ===');
        console.log('Input perPage:', perPage, 'Type:', typeof perPage);
        
        const validValues = ['10', '25', '50', '100'];
        const perPageStr = String(perPage);
        
        if (!validValues.includes(perPageStr)) {
            console.error('Invalid per_page value:', perPage);
            alert('Nilai per page tidak valid: ' + perPage);
            return;
        }
        
        showPaginationLoading();
        
        try {
            // LANGKAH 1: Update hidden field di form
            const hiddenPerPageField = document.querySelector('input[name="per_page"]');
            if (hiddenPerPageField) {
                hiddenPerPageField.value = perPageStr;
                console.log('Updated hidden field to:', hiddenPerPageField.value);
            } else {
                console.error('Hidden per_page field not found!');
            }
            
            // LANGKAH 2: Update URL parameters
            const currentUrl = new URL(window.location.href);
            
            // Preserve semua parameter yang ada
            const preservedParams = {};
            for (const [key, value] of currentUrl.searchParams.entries()) {
                if (key !== 'per_page' && key !== 'page') {
                    preservedParams[key] = value;
                }
            }
            
            // Set parameter baru
            currentUrl.searchParams.set('per_page', perPageStr);
            currentUrl.searchParams.set('page', '1'); // Reset ke halaman 1
            
            console.log('Preserved params:', preservedParams);
            console.log('New URL:', currentUrl.toString());
            
            // Navigate ke URL baru
            window.location.href = currentUrl.toString();
            
        } catch (error) {
            console.error('Error in changePerPage:', error);
            hidePaginationLoading();
            alert('Terjadi kesalahan saat mengubah jumlah item per halaman: ' + error.message);
        }
    }

    // Export Functions
    function exportPDF(event) {
        event.preventDefault();
        
        if (exportInProgress) {
            alert('Export sedang diproses, harap tunggu...');
            return;
        }

        exportInProgress = true;
        showLoading();

        try {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const exportUrl = '{{ route("reports.export.pdf") }}?' + params.toString();
            
            const link = document.createElement('a');
            link.href = exportUrl;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            setTimeout(() => {
                exportInProgress = false;
                hideLoading();
            }, 3000);
        } catch (error) {
            console.error('PDF Export Error:', error);
            exportInProgress = false;
            hideLoading();
            alert('Terjadi kesalahan saat mengekspor PDF.');
        }
    }

    function exportExcel(event) {
        event.preventDefault();
        
        if (exportInProgress) {
            alert('Export sedang diproses, harap tunggu...');
            return;
        }

        exportInProgress = true;
        showLoading();

        try {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const exportUrl = '{{ route("reports.export.excel") }}?' + params.toString();
            
            window.location.href = exportUrl;

            setTimeout(() => {
                exportInProgress = false;
                hideLoading();
            }, 5000);
        } catch (error) {
            console.error('Excel Export Error:', error);
            exportInProgress = false;
            hideLoading();
            alert('Terjadi kesalahan saat mengekspor Excel.');
        }
    }

    // Form Validation Functions
    function validateForm() {
        const searchInput = document.querySelector('input[name="search"]');
        const locationInput = document.querySelector('input[name="location"]');
        
        if (searchInput && searchInput.value.length > 255) {
            alert('Pencarian terlalu panjang (maksimal 255 karakter)');
            return false;
        }
        
        if (locationInput && locationInput.value.length > 255) {
            alert('Lokasi terlalu panjang (maksimal 255 karakter)');
            return false;
        }
        
        return true;
    }

    function validateDateRange() {
        const dateFromInput = document.querySelector('input[name="date_from"]');
        const dateToInput = document.querySelector('input[name="date_to"]');
        
        if (!dateFromInput || !dateToInput) return true;
        
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;
        
        if (dateFrom && dateTo && dateFrom > dateTo) {
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
            return false;
        }
        
        return true;
    }

    // Auto-submit with debounce
    const debouncedSubmit = debounce(() => {
        if (validateForm() && validateDateRange()) {
            // PERBAIKAN: Preserve per_page value saat auto submit
            const currentPerPage = document.querySelector('select[onchange*="changePerPage"]')?.value || 
                                   document.querySelector('input[name="per_page"]')?.value || 
                                   new URLSearchParams(window.location.search).get('per_page') || '10';
            
            const hiddenPerPageField = document.querySelector('input[name="per_page"]');
            if (hiddenPerPageField) {
                hiddenPerPageField.value = currentPerPage;
            }
            
            console.log('Auto-submitting with per_page:', currentPerPage);
            document.getElementById('filterForm')?.submit();
        }
    }, 1000);

    function initializeFilters() {
        // Date inputs
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            input.addEventListener('change', function() {
                if (validateDateRange()) {
                    debouncedSubmit();
                } else {
                    this.value = '';
                }
            });
        });

        // Select inputs (kecuali per-page selector)
        document.querySelectorAll('select:not([onchange*="changePerPage"])').forEach(function(select) {
            select.addEventListener('change', debouncedSubmit);
        });

        // Search input
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    if (validateForm()) {
                        debouncedSubmit();
                    }
                } else {
                    debouncedSubmit();
                }
            });
        }

        // Location input
        const locationInput = document.querySelector('input[name="location"]');
        if (locationInput) {
            locationInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    if (validateForm()) {
                        debouncedSubmit();
                    }
                } else {
                    debouncedSubmit();
                }
            });
        }
    }
    
    function initializeRefreshStats() {
        const refreshBtn = document.getElementById('refreshStatsBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
                
                fetch('{{ route("reports.index") }}?refresh_stats=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.statistics) {
                        const stats = data.statistics;
                        document.getElementById('totalItems').textContent = formatNumber(stats.total);
                        document.getElementById('availableItems').textContent = formatNumber(stats.available);
                        document.getElementById('maintenanceItems').textContent = formatNumber(stats.maintenance);
                        document.getElementById('brokenItems').textContent = formatNumber(stats.broken);
                    }
                })
                .catch(error => {
                    console.error('Error refreshing stats:', error);
                    alert('Gagal memperbarui statistik. Silakan coba lagi.');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh Data';
                });
            });
        }
    }

    function initializeFormSubmission() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                // Debug form data sebelum submit
                console.log('=== FORM SUBMIT DEBUG ===');
                const formData = new FormData(this);
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value} (${typeof value})`);
                }
                
                if (!validateForm() || !validateDateRange()) {
                    e.preventDefault();
                    return false;
                }
                showLoading();
            });
        }
    }

    function initializeTooltips() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            document.querySelectorAll('.page-link[title]').forEach(function(element) {
                new bootstrap.Tooltip(element);
            });
        }
    }

    function scrollToContent() {
        if (window.location.search.includes('page=')) {
            setTimeout(() => {
                const contentEl = document.querySelector('.card.shadow');
                if (contentEl) {
                    contentEl.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }, 100);
        }
    }

    // PERBAIKAN: Debug initialization function
    function debugCurrentState() {
        console.log('=== INITIALIZATION DEBUG ===');
        console.log('Current URL:', window.location.href);
        
        const urlParams = new URLSearchParams(window.location.search);
        console.log('URL per_page param:', urlParams.get('per_page'));
        
        const hiddenField = document.querySelector('input[name="per_page"]');
        if (hiddenField) {
            console.log('Hidden field value:', hiddenField.value, 'Type:', typeof hiddenField.value);
        } else {
            console.error('Hidden per_page field not found!');
        }
        
        const selectElement = document.getElementById('perPageSelect') || 
                              document.querySelector('select[onchange*="changePerPage"]');
        if (selectElement) {
            console.log('Select element value:', selectElement.value);
            console.log('Select element options:', Array.from(selectElement.options).map(opt => ({
                value: opt.value,
                selected: opt.selected,
                text: opt.textContent
            })));
        } else {
            console.error('Per page select element not found!');
        }
        
        // Cek apakah ada form
        const form = document.getElementById('filterForm');
        console.log('Form found:', !!form);
        if (form) {
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);
        }
    }

    // PERBAIKAN: Sync per page values on load
    function syncPerPageValues() {
        const urlParams = new URLSearchParams(window.location.search);
        const urlPerPage = urlParams.get('per_page') || '10';
        
        // Update hidden field
        const hiddenField = document.querySelector('input[name="per_page"]');
        if (hiddenField && hiddenField.value !== urlPerPage) {
            console.log('Syncing hidden field from', hiddenField.value, 'to', urlPerPage);
            hiddenField.value = urlPerPage;
        }
        
        // Update select element
        const selectElement = document.getElementById('perPageSelect') || 
                              document.querySelector('select[onchange*="changePerPage"]');
        if (selectElement && selectElement.value !== urlPerPage) {
            console.log('Syncing select element from', selectElement.value, 'to', urlPerPage);
            selectElement.value = urlPerPage;
        }
    }

    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        try {
            console.log('DOM Content Loaded - Initializing...');
            
            // Debug current state
            debugCurrentState();
            
            // Sync values first
            syncPerPageValues();
            
            // Initialize other functions
            initializeFilters();
            initializeRefreshStats();
            initializeFormSubmission();
            initializeTooltips();
            scrollToContent();
            
            console.log('Initialization completed successfully');
            
        } catch (error) {
            console.error('Initialization error:', error);
            alert('Terjadi kesalahan saat inisialisasi halaman: ' + error.message);
        }
    });

    function initializeFilters() {
        // Date inputs
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            input.addEventListener('change', function() {
                if (validateDateRange()) {
                    debouncedSubmit();
                } else {
                    this.value = '';
                }
            });
        });

        // Select inputs (kecuali per-page selector)
        document.querySelectorAll('select:not([onchange*="changePerPage"])').forEach(function(select) {
            select.addEventListener('change', debouncedSubmit);
        });

        // Search input
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    if (validateForm()) {
                        debouncedSubmit();
                    }
                } else {
                    debouncedSubmit();
                }
            });
        }

        // Location input
        const locationInput = document.querySelector('input[name="location"]');
        if (locationInput) {
            locationInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    if (validateForm()) {
                        debouncedSubmit();
                    }
                } else {
                    debouncedSubmit();
                }
            });
        }
    }
    
    function initializeRefreshStats() {
        const refreshBtn = document.getElementById('refreshStatsBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
                
                fetch('{{ route("reports.index") }}?refresh_stats=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.statistics) {
                        const stats = data.statistics;
                        document.getElementById('totalItems').textContent = formatNumber(stats.total);
                        document.getElementById('availableItems').textContent = formatNumber(stats.available);
                        document.getElementById('maintenanceItems').textContent = formatNumber(stats.maintenance);
                        document.getElementById('brokenItems').textContent = formatNumber(stats.broken);
                    }
                })
                .catch(error => {
                    console.error('Error refreshing stats:', error);
                    alert('Gagal memperbarui statistik. Silakan coba lagi.');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh Data';
                });
            });
        }
    }

    function initializeFormSubmission() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                // Debug form data sebelum submit
                console.log('=== FORM SUBMIT DEBUG ===');
                const formData = new FormData(this);
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value} (${typeof value})`);
                }
                
                if (!validateForm() || !validateDateRange()) {
                    e.preventDefault();
                    return false;
                }
                showLoading();
            });
        }
    }

    function initializeTooltips() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            document.querySelectorAll('.page-link[title]').forEach(function(element) {
                new bootstrap.Tooltip(element);
            });
        }
    }

    function scrollToContent() {
        if (window.location.search.includes('page=')) {
            setTimeout(() => {
                const contentEl = document.querySelector('.card.shadow');
                if (contentEl) {
                    contentEl.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }, 100);
        }
    }

    // Handle page events
    window.addEventListener('load', function() {
        hideLoading();
        console.log('Page loaded completely');
    });
    
    window.addEventListener('popstate', showPaginationLoading);
    
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            hidePaginationLoading();
        }
    });

    // Global exports for external use
    window.reportUtils = {
        exportPDF,
        exportExcel,
        changePerPage,
        showLoading,
        hideLoading,
        showPaginationLoading,
        hidePaginationLoading,
        formatNumber,
        debugCurrentState,
        syncPerPageValues
    };

    // PERBAIKAN: Export global functions agar bisa dipanggil dari HTML
    window.exportPDF = exportPDF;
    window.exportExcel = exportExcel;
    window.changePerPage = changePerPage;
    
    console.log('Report scripts loaded successfully');
})();
</script>
@endpush