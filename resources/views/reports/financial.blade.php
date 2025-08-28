{{-- resources/views/reports/financial.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Finansial')

@section('content')
<div class="container-fluid">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Laporan Finansial</h2>
                    <p class="text-muted mb-0">Analisis nilai dan investasi inventaris laboratorium</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('reports.financial.export.pdf') }}">
                                <i class="fas fa-file-pdf text-danger"></i> Export PDF
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('reports.financial.export.excel') }}">
                                <i class="fas fa-file-excel text-success"></i> Export Excel
                            </a></li>
                        </ul>
                    </div>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Laporan
                    </a>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Nilai Aset
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp {{ number_format($statistics['total_value'] ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                        Rata-rata Nilai Item
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp {{ number_format($statistics['avg_item_value'] ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Item Termahal
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Rp {{ number_format($statistics['most_expensive'] ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-crown fa-2x text-gray-300"></i>
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
                                        Item Tanpa Harga
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($statistics['items_without_price'] ?? 0) }}
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

            <div class="row">
                <!-- Category Value Breakdown -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-chart-pie"></i> Nilai Aset per Kategori
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($categoryValues->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Kategori</th>
                                                <th class="text-center">Jumlah Item</th>
                                                <th class="text-end">Total Nilai</th>
                                                <th class="text-end">Rata-rata</th>
                                                <th class="text-center">Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($categoryValues as $category)
                                                @php
                                                    $percentage = $totalValue > 0 ? ($category->total_value / $totalValue) * 100 : 0;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <strong>{{ $category->category_name }}</strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-info">{{ $category->item_count }}</span>
                                                    </td>
                                                    <td class="text-end font-monospace">
                                                        <strong>Rp {{ number_format($category->total_value, 0, ',', '.') }}</strong>
                                                    </td>
                                                    <td class="text-end">
                                                        Rp {{ number_format($category->avg_price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                                <div class="progress-bar" role="progressbar" 
                                                                     style="width: {{ $percentage }}%">
                                                                </div>
                                                            </div>
                                                            <small>{{ number_format($percentage, 1) }}%</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum Ada Data Harga</h5>
                                    <p class="text-muted">Tambahkan harga pembelian pada item inventaris untuk melihat analisis finansial.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-info-circle"></i> Informasi Cepat
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-2">
                                        <div class="h4 mb-1 text-primary">{{ $statistics['items_with_price'] ?? 0 }}</div>
                                        <small class="text-muted">Item dengan Harga</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-2">
                                        <div class="h4 mb-1 text-warning">{{ $statistics['items_without_price'] ?? 0 }}</div>
                                        <small class="text-muted">Item tanpa Harga</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="border rounded p-2">
                                        <div class="h6 mb-1 text-success">
                                            Rp {{ number_format($statistics['least_expensive'] ?? 0, 0, ',', '.') }} - 
                                            Rp {{ number_format($statistics['most_expensive'] ?? 0, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted">Range Harga Item</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yearly Purchase Analysis -->
            @if($yearlyPurchases->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-chart-line"></i> Analisis Pembelian per Tahun
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tahun</th>
                                            <th class="text-center">Jumlah Item</th>
                                            <th class="text-end">Total Pengeluaran</th>
                                            <th class="text-end">Rata-rata per Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($yearlyPurchases as $year)
                                            <tr>
                                                <td><strong>{{ $year->year }}</strong></td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary">{{ $year->item_count }}</span>
                                                </td>
                                                <td class="text-end font-monospace">
                                                    <strong>Rp {{ number_format($year->total_spent, 0, ',', '.') }}</strong>
                                                </td>
                                                <td class="text-end">
                                                    Rp {{ number_format($year->avg_price, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-center">{{ $yearlyPurchases->sum('item_count') }}</th>
                                            <th class="text-end">Rp {{ number_format($yearlyPurchases->sum('total_spent'), 0, ',', '.') }}</th>
                                            <th class="text-end">-</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- High Value Items -->
            @if($highValueItems->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-star"></i> 10 Item Dengan Nilai Tertinggi
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ranking</th>
                                            <th>Kode</th>
                                            <th>Nama Item</th>
                                            <th>Kategori</th>
                                            <th>Brand/Model</th>
                                            <th class="text-end">Harga Beli</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($highValueItems as $index => $item)
                                            <tr>
                                                <td>
                                                    @if($index < 3)
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-trophy"></i> {{ $index + 1 }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-primary font-monospace">{{ $item->inventory_code }}</small>
                                                </td>
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
                                                <td class="text-end font-monospace">
                                                    <strong>Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</strong>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Monthly Trends (if available) -->
            @if(isset($monthlyData) && $monthlyData->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-chart-area"></i> Tren Pembelian 12 Bulan Terakhir
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th class="text-center">Jumlah Item</th>
                                            <th class="text-end">Total Pengeluaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyData as $month)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}</td>
                                                <td class="text-center">{{ $month->item_count }}</td>
                                                <td class="text-end">Rp {{ number_format($month->total_spent, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
    .border-left-info {
        border-left: 0.25rem solid #17a2b8 !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }
    .font-monospace {
        font-family: 'Courier New', monospace;
    }
    .progress {
        height: 8px;
    }
</style>
@endpush