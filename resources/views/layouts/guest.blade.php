<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Lab Inventory System') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    @yield('content')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di Lab Inventory System')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <i class="fas fa-boxes fa-2x mb-3"></i>
                <h3 class="mb-1">{{ $totalItems }}</h3>
                <p class="mb-0">Total Item</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card success">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x mb-3"></i>
                <h3 class="mb-1">{{ $availableItems }}</h3>
                <p class="mb-0">Tersedia</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card warning">
            <div class="card-body text-center">
                <i class="fas fa-tools fa-2x mb-3"></i>
                <h3 class="mb-1">{{ $maintenanceItems }}</h3>
                <p class="mb-0">Maintenance</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card danger">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h3 class="mb-1">{{ $brokenItems }}</h3>
                <p class="mb-0">Rusak</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Category Distribution -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Distribusi Kategori
                </h5>
            </div>
            <div class="card-body">
                @if($categoryStats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Jumlah Item</th>
                                    <th>Persentase</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $category)
                                    @php
                                        $percentage = $totalItems > 0 ? ($category->inventory_items_count / $totalItems) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <i class="fas fa-tag me-2"></i>
                                            {{ $category->name }}
                                        </td>
                                        <td>{{ $category->inventory_items_count }}</td>
                                        <td>{{ number_format($percentage, 1) }}%</td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $percentage }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data kategori</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($recentActivities->count() > 0)
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        @switch($activity->action)
                                            @case('created')
                                                <i class="fas fa-plus-circle text-success"></i>
                                                @break
                                            @case('updated')
                                                <i class="fas fa-edit text-warning"></i>
                                                @break
                                            @case('status_changed')
                                                <i class="fas fa-exchange-alt text-info"></i>
                                                @break
                                            @case('deleted')
                                                <i class="fas fa-trash text-danger"></i>
                                                @break
                                            @default
                                                <i class="fas fa-circle text-secondary"></i>
                                        @endswitch
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="mb-1 small">
                                            <strong>{{ $activity->item->name ?? 'Item' }}</strong>
                                            @switch($activity->action)
                                                @case('created')
                                                    telah ditambahkan
                                                    @break
                                                @case('updated')
                                                    telah diupdate
                                                    @break
                                                @case('status_changed')
                                                    status diubah
                                                    @break
                                                @case('deleted')
                                                    telah dihapus
                                                    @break
                                            @endswitch
                                        </p>
                                        <small class="text-muted">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada aktivitas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('inventory.create') }}" class="text-decoration-none">
                            <div class="p-3 border rounded-3 h-100 hover-lift">
                                <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                                <h6>Tambah Item</h6>
                                <small class="text-muted">Tambah item baru ke inventaris</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('inventory.index') }}" class="text-decoration-none">
                            <div class="p-3 border rounded-3 h-100 hover-lift">
                                <i class="fas fa-list fa-2x text-success mb-2"></i>
                                <h6>Lihat Semua</h6>
                                <small class="text-muted">Lihat semua item inventaris</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('categories.index') }}" class="text-decoration-none">
                            <div class="p-3 border rounded-3 h-100 hover-lift">
                                <i class="fas fa-tags fa-2x text-warning mb-2"></i>
                                <h6>Kelola Kategori</h6>
                                <small class="text-muted">Atur kategori item</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('reports.index') }}" class="text-decoration-none">
                            <div class="p-3 border rounded-3 h-100 hover-lift">
                                <i class="fas fa-chart-bar fa-2x text-info mb-2"></i>
                                <h6>Laporan</h6>
                                <small class="text-muted">Generate laporan inventaris</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-lift {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .timeline-item {
        position: relative;
    }
    
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 7px;
        top: 20px;
        width: 1px;
        height: calc(100% + 12px);
        background-color: #dee2e6;
    }
</style>
@endpush