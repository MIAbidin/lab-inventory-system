@extends('layouts.app')

@section('title', 'Laporan Kondisi Item')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Laporan Kondisi Item</h2>
                    <p class="text-muted mb-0">Analisis kondisi dan status inventaris laboratorium</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Laporan
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <!-- Condition Statistics -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow h-100">
                        <div class="card-header bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-chart-pie"></i> Statistik Kondisi
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($conditionStats as $stat)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="badge 
                                            @switch($stat->condition)
                                                @case('good') bg-success @break
                                                @case('need_repair') bg-warning @break  
                                                @case('broken') bg-danger @break
                                                @default bg-secondary
                                            @endswitch me-2">
                                            @switch($stat->condition)
                                                @case('good') Baik @break
                                                @case('need_repair') Perlu Perbaikan @break
                                                @case('broken') Rusak @break
                                                @default {{ $stat->condition }}
                                            @endswitch
                                        </span>
                                    </div>
                                    <strong>{{ $stat->count }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Status Statistics -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-tasks"></i> Statistik Status
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($statusStats as $stat)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="badge 
                                            @switch($stat->status)
                                                @case('available') bg-success @break
                                                @case('in_use') bg-info @break
                                                @case('maintenance') bg-warning @break
                                                @case('disposed') bg-danger @break
                                                @default bg-secondary
                                            @endswitch me-2">
                                            @switch($stat->status)
                                                @case('available') Tersedia @break
                                                @case('in_use') Digunakan @break
                                                @case('maintenance') Maintenance @break
                                                @case('disposed') Dibuang @break
                                                @default {{ $stat->status }}
                                            @endswitch
                                        </span>
                                    </div>
                                    <strong>{{ $stat->count }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Category Breakdown -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow h-100">
                        <div class="card-header bg-success text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-layer-group"></i> Breakdown per Kategori
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($categoryStats as $stat)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong>{{ $stat->category }}</strong>
                                        <span class="badge bg-primary">{{ $stat->count }} total</span>
                                    </div>
                                    <div class="small text-muted">
                                        @if($stat->broken_count > 0)
                                            <span class="badge bg-danger me-1">{{ $stat->broken_count }} rusak</span>
                                        @endif
                                        @if($stat->repair_count > 0)
                                            <span class="badge bg-warning me-1">{{ $stat->repair_count }} perlu perbaikan</span>
                                        @endif
                                        @if($stat->broken_count == 0 && $stat->repair_count == 0)
                                            <span class="badge bg-success">Semua baik</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Need Attention -->
            <div class="row">
                <!-- Items Need Maintenance -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Item Perlu Perhatian ({{ $needMaintenanceItems->count() }})
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($needMaintenanceItems->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama Item</th>
                                                <th>Kategori</th>
                                                <th>Status</th>
                                                <th>Umur</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($needMaintenanceItems as $item)
                                                <tr>
                                                    <td>
                                                        <small class="text-primary">{{ $item->inventory_code }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $item->name }}</strong>
                                                        <br><small class="text-muted">{{ $item->brand }} {{ $item->model }}</small>
                                                    </td>
                                                    <td>{{ $item->category->name ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($item->condition == 'need_repair') bg-warning 
                                                            @else bg-info @endif">
                                                            @if($item->condition == 'need_repair')
                                                                Perlu Perbaikan
                                                            @else
                                                                Maintenance
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($item->purchase_date)
                                                            {{ $item->purchase_date->diffForHumans() }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <p class="text-muted mb-0">Tidak ada item yang perlu perhatian</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Broken Items -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-danger text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-times-circle"></i> 
                                Item Rusak ({{ $brokenItems->count() }})
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($brokenItems->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama Item</th>
                                                <th>Kategori</th>
                                                <th>Lokasi</th>
                                                <th>Umur</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($brokenItems as $item)
                                                <tr>
                                                    <td>
                                                        <small class="text-primary">{{ $item->inventory_code }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $item->name }}</strong>
                                                        <br><small class="text-muted">{{ $item->brand }} {{ $item->model }}</small>
                                                    </td>
                                                    <td>{{ $item->category->name ?? '-' }}</td>
                                                    <td>{{ $item->location ?? '-' }}</td>
                                                    <td>
                                                        @if($item->purchase_date)
                                                            {{ $item->purchase_date->diffForHumans() }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3 p-3 bg-light rounded">
                                    <h6 class="text-danger mb-2">
                                        <i class="fas fa-lightbulb"></i> Rekomendasi Tindakan:
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li>Evaluasi apakah item masih bisa diperbaiki atau perlu diganti</li>
                                        <li>Pertimbangkan untuk memindahkan item ke status "disposed" jika tidak dapat diperbaiki</li>
                                        <li>Dokumentasikan penyebab kerusakan untuk mencegah masalah serupa</li>
                                        <li>Rencanakan penggantian untuk item yang kritis</li>
                                    </ul>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <p class="text-muted mb-0">Tidak ada item yang rusak</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Report -->
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-clipboard-list"></i> Ringkasan Laporan Kondisi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Status Umum Inventaris:</h6>
                            <ul class="list-unstyled">
                                @php
                                    $totalItems = $conditionStats->sum('count');
                                    $goodItems = $conditionStats->where('condition', 'good')->first()->count ?? 0;
                                    $healthPercentage = $totalItems > 0 ? round(($goodItems / $totalItems) * 100, 1) : 0;
                                @endphp
                                <li><i class="fas fa-check text-success"></i> 
                                    Kesehatan inventaris: <strong>{{ $healthPercentage }}%</strong></li>
                                <li><i class="fas fa-tools text-warning"></i> 
                                    Item perlu perhatian: <strong>{{ $needMaintenanceItems->count() }} item</strong></li>
                                <li><i class="fas fa-exclamation-triangle text-danger"></i> 
                                    Item rusak: <strong>{{ $brokenItems->count() }} item</strong></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Rekomendasi Prioritas:</h6>
                            <div class="alert alert-light">
                                @if($brokenItems->count() > 0)
                                    <div class="text-danger mb-2">
                                        <i class="fas fa-exclamation-circle"></i> 
                                        <strong>Tinggi:</strong> {{ $brokenItems->count() }} item rusak perlu evaluasi
                                    </div>
                                @endif
                                @if($needMaintenanceItems->count() > 0)
                                    <div class="text-warning mb-2">
                                        <i class="fas fa-tools"></i> 
                                        <strong>Sedang:</strong> {{ $needMaintenanceItems->count() }} item perlu maintenance
                                    </div>
                                @endif
                                @if($brokenItems->count() == 0 && $needMaintenanceItems->count() == 0)
                                    <div class="text-success">
                                        <i class="fas fa-check-circle"></i> 
                                        <strong>Baik:</strong> Semua item dalam kondisi optimal
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>Laporan dibuat pada {{ now()->format('d F Y, H:i') }} WIB</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        font-size: 0.9rem;
    }
    
    .table-sm th,
    .table-sm td {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
    
    .alert-light {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
</style>
@endpush