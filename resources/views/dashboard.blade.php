@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan inventaris laboratorium komputer')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .activity-item {
        border-left: 3px solid #007bff;
        padding-left: 15px;
        margin-bottom: 15px;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .category-progress {
        height: 8px;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<!-- Overview Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Total Item</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $totalItems }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Item Tersedia</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $availableItems }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Dalam Maintenance</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $maintenanceItems }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Item Rusak</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $brokenItems }}</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Category Distribution Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>
                    Distribusi Item per Kategori
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Category Statistics -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tags me-2 text-primary"></i>
                    Statistik Kategori
                </h5>
            </div>
            <div class="card-body">
                @forelse($categoryStats as $category)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-medium">{{ $category->name }}</span>
                            <span class="badge bg-primary">{{ $category->inventory_items_count }}</span>
                        </div>
                        <div class="progress category-progress">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $totalItems > 0 ? ($category->inventory_items_count / $totalItems) * 100 : 0 }}%"
                                 aria-valuenow="{{ $category->inventory_items_count }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="{{ $totalItems }}">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-box-open fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada kategori</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-7 mb-4">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2 text-primary"></i>
                    Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    @switch($activity->action)
                                        @case('created')
                                            <i class="fas fa-plus-circle text-success me-1"></i>
                                            Item Ditambahkan
                                            @break
                                        @case('updated')
                                            <i class="fas fa-edit text-warning me-1"></i>
                                            Item Diperbarui
                                            @break
                                        @case('status_changed')
                                            <i class="fas fa-exchange-alt text-info me-1"></i>
                                            Status Diubah
                                            @break
                                        @case('deleted')
                                            <i class="fas fa-trash text-danger me-1"></i>
                                            Item Dihapus
                                            @break
                                        @default
                                            <i class="fas fa-info-circle text-secondary me-1"></i>
                                            Aktivitas
                                    @endswitch
                                </h6>
                                <p class="mb-1">
                                    <strong>{{ $activity->item->name ?? 'Item tidak ditemukan' }}</strong>
                                    @if($activity->action === 'status_changed')
                                        - Status: {{ ucfirst(str_replace('_', ' ', $activity->old_value)) }} 
                                        → {{ ucfirst(str_replace('_', ' ', $activity->new_value)) }}
                                    @elseif($activity->action === 'updated' && $activity->field_changed)
                                        - {{ ucfirst(str_replace('_', ' ', $activity->field_changed)) }} diperbarui
                                    @endif
                                </p>
                                @if($activity->notes)
                                    <small class="text-muted">{{ $activity->notes }}</small>
                                @endif
                            </div>
                            <small class="activity-time">
                                {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-history fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Quick Actions & Alerts -->
    <div class="col-lg-5 mb-4">
        <!-- Quick Actions -->
        <div class="card mb-3">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2 text-primary"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Item Baru
                    </a>
                    <a href="{{ route('inventory.index', ['status' => 'maintenance']) }}" class="btn btn-warning">
                        <i class="fas fa-tools me-2"></i>
                        Lihat Item Maintenance
                    </a>
                    <a href="{{ route('reports.export.pdf') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-file-pdf me-2"></i>
                        Export Laporan PDF
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Status Alerts -->
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bell me-2 text-primary"></i>
                    Peringatan
                </h5>
            </div>
            <div class="card-body">
                @if($brokenItems > 0)
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>{{ $brokenItems }}</strong> item dalam kondisi rusak memerlukan perhatian
                        </div>
                    </div>
                @endif
                
                @if($maintenanceItems > 0)
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-tools me-2"></i>
                        <div>
                            <strong>{{ $maintenanceItems }}</strong> item sedang dalam maintenance
                        </div>
                    </div>
                @endif
                
                @if($brokenItems == 0 && $maintenanceItems == 0)
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            Semua item dalam kondisi baik
                        </div>
                    </div>
                @endif
                
                <!-- Low Stock Alert (if needed in future) -->
                <div class="text-center text-muted mt-3">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Pembaruan terakhir: {{ now()->format('d M Y, H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Items Added -->
@if(isset($recentItems) && $recentItems->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2 text-primary"></i>
                        Item Terbaru Ditambahkan
                    </h5>
                    <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th>Brand</th>
                                <th>Status</th>
                                <th>Kondisi</th>
                                <th>Ditambahkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentItems as $item)
                                <tr>
                                    <td>
                                        <code>{{ $item->inventory_code }}</code>
                                    </td>
                                    <td>
                                        <a href="{{ route('inventory.show', $item) }}" class="text-decoration-none fw-medium">
                                            {{ $item->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $item->category->name }}
                                        </span>
                                    </td>
                                    <td>{{ $item->brand }}</td>
                                    <td>
                                        <span class="badge {{ $item->status_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->condition_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->condition)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $item->created_at->diffForHumans() }}
                                        </small>
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
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Distribution Chart
    const ctx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($categoryStats);
    
    const labels = categoryData.map(cat => cat.name);
    const data = categoryData.map(cat => cat.inventory_items_count);
    const backgroundColors = [
        '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', 
        '#fd7e14', '#20c997', '#e83e8c', '#6c757d', '#343a40'
    ];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColors.slice(0, labels.length),
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%',
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
    
    // Auto refresh dashboard every 5 minutes
    setInterval(function() {
        if (!document.hidden) {
            window.location.reload();
        }
    }, 300000); // 5 minutes
});
</script>
@endpush