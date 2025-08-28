@extends('layouts.app')

@section('title', 'Detail Item - ' . $inventory->name)
@section('page-title', $inventory->name)
@section('page-subtitle', 'Kode: ' . $inventory->inventory_code)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('inventory.index') }}" class="text-decoration-none">Inventaris</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Detail Item</li>
@endsection

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('inventory.edit', $inventory) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Edit
    </a>
    <button type="button" 
            class="btn btn-danger"
            onclick="confirmDelete('{{ $inventory->id }}', '{{ $inventory->name }}')">
        <i class="fas fa-trash me-2"></i>Hapus
    </button>
    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>
@endsection

@push('styles')
<style>
    .detail-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .section-title {
        color: #495057;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .item-image {
        width: 100%;
        max-width: 400px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .info-item {
        margin-bottom: 15px;
        padding: 10px 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }
    
    .info-value {
        color: #6c757d;
    }
    
    .history-item {
        border-left: 3px solid #007bff;
        padding-left: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    
    .history-time {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .qr-code {
        max-width: 150px;
        border: 1px solid #dee2e6;
        padding: 10px;
        border-radius: 8px;
        background: white;
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Item Image -->
    <div class="col-lg-4 mb-4">
        <div class="detail-card">
            <h5 class="section-title">
                <i class="fas fa-image me-2 text-primary"></i>
                Foto Item
            </h5>
            
            <div class="text-center">
                @if($inventory->image_path)
                    <img src="{{ asset('storage/' . $inventory->image_path) }}" 
                         alt="{{ $inventory->name }}" 
                         class="item-image">
                @else
                    <div class="item-image d-flex align-items-center justify-content-center bg-light">
                        <div class="text-center text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p class="mb-0">Tidak ada foto</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- QR Code placeholder (for future enhancement) -->
            <div class="text-center mt-3">
                <div class="qr-code mx-auto d-flex align-items-center justify-content-center">
                    <div class="text-center text-muted">
                        <i class="fas fa-qrcode fa-2x mb-2"></i>
                        <p class="mb-0 small">QR Code</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Item Details -->
    <div class="col-lg-8 mb-4">
        <!-- Basic Information -->
        <div class="detail-card">
            <h5 class="section-title">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                Informasi Dasar
            </h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Kode Inventaris</div>
                        <div class="info-value">
                            <code class="fs-6">{{ $inventory->inventory_code }}</code>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Nama Item</div>
                        <div class="info-value">{{ $inventory->name }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Kategori</div>
                        <div class="info-value">
                            <span class="badge bg-light text-dark fs-6">
                                {{ $inventory->category->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Merk/Brand</div>
                        <div class="info-value">{{ $inventory->brand }}</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Model/Tipe</div>
                        <div class="info-value">{{ $inventory->model }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Serial Number</div>
                        <div class="info-value">
                            @if($inventory->serial_number)
                                <code>{{ $inventory->serial_number }}</code>
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Lokasi</div>
                        <div class="info-value">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $inventory->location ?: 'Tidak diset' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Ditambahkan</div>
                        <div class="info-value">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $inventory->created_at->format('d M Y, H:i') }}
                            <small class="text-muted">({{ $inventory->created_at->diffForHumans() }})</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Purchase Information -->
        <div class="detail-card">
            <h5 class="section-title">
                <i class="fas fa-receipt me-2 text-primary"></i>
                Informasi Pembelian
            </h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Tanggal Pembelian</div>
                        <div class="info-value">
                            @if($inventory->purchase_date)
                                <i class="fas fa-calendar me-1"></i>
                                {{ $inventory->purchase_date->format('d M Y') }}
                            @else
                                <span class="text-muted">Tidak diset</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Harga Beli</div>
                        <div class="info-value">
                            @if($inventory->purchase_price)
                                <i class="fas fa-money-bill-wave me-1"></i>
                                Rp {{ number_format($inventory->purchase_price, 0, ',', '.') }}
                            @else
                                <span class="text-muted">Tidak diset</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Status & Condition -->
    <div class="col-lg-6 mb-4">
        <div class="detail-card">
            <h5 class="section-title">
                <i class="fas fa-cogs me-2 text-primary"></i>
                Status & Kondisi
            </h5>
            
            <div class="row">
                <div class="col-6">
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge {{ $inventory->status_badge }} fs-6">
                                @switch($inventory->status)
                                    @case('available')
                                        <i class="fas fa-check-circle me-1"></i>Tersedia
                                        @break
                                    @case('in_use')
                                        <i class="fas fa-user me-1"></i>Sedang Digunakan
                                        @break
                                    @case('maintenance')
                                        <i class="fas fa-tools me-1"></i>Maintenance
                                        @break
                                    @case('disposed')
                                        <i class="fas fa-ban me-1"></i>Tidak Digunakan
                                        @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-6">
                    <div class="info-item">
                        <div class="info-label">Kondisi</div>
                        <div class="info-value">
                            <span class="badge {{ $inventory->condition_badge }} fs-6">
                                @switch($inventory->condition)
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Information -->
    <div class="col-lg-6 mb-4">
        <div class="detail-card">
            <h5 class="section-title">
                <i class="fas fa-clipboard-list me-2 text-primary"></i>
                Informasi Tambahan
            </h5>
            
            @if($inventory->specifications)
                <div class="info-item">
                    <div class="info-label">Spesifikasi Teknis</div>
                    <div class="info-value">
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($inventory->specifications)) !!}
                        </div>
                    </div>
                </div>
            @endif
            
            @if($inventory->notes)
                <div class="info-item">
                    <div class="info-label">Catatan</div>
                    <div class="info-value">
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($inventory->notes)) !!}
                        </div>
                    </div>
                </div>
            @endif
            
            @if(!$inventory->specifications && !$inventory->notes)
                <div class="text-center text-muted py-3">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p class="mb-0">Tidak ada informasi tambahan</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- History -->
<div class="row">
    <div class="col-12">
        <div class="detail-card">
            <h5 class="section-title">
                <i class="fas fa-history me-2 text-primary"></i>
                Riwayat Perubahan
            </h5>
            
            @forelse($inventory->histories->sortByDesc('created_at') as $history)
                <div class="history-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">
                                @switch($history->action)
                                    @case('created')
                                        <i class="fas fa-plus-circle text-success me-1"></i>
                                        Item Dibuat
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
                                        {{ ucfirst($history->action) }}
                                @endswitch
                            </h6>
                            
                            @if($history->action === 'status_changed')
                                <p class="mb-1">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $history->field_changed)) }}:</strong>
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $history->old_value)) }}</span>
                                    <i class="fas fa-arrow-right mx-2"></i>
                                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $history->new_value)) }}</span>
                                </p>
                            @endif
                            
                            @if($history->notes)
                                <p class="mb-1 text-muted">{{ $history->notes }}</p>
                            @endif
                            
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                {{ $history->user->name ?? 'System' }}
                            </small>
                        </div>
                        
                        <small class="history-time">
                            {{ $history->created_at->format('d M Y, H:i') }}
                            <br>
                            <span class="text-muted">{{ $history->created_at->diffForHumans() }}</span>
                        </small>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-history fa-2x mb-2"></i>
                    <p class="mb-0">Belum ada riwayat perubahan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

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
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait item ini.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Hapus Permanent
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

// Add click-to-copy functionality for codes
document.addEventListener('DOMContentLoaded', function() {
    const codes = document.querySelectorAll('code');
    codes.forEach(code => {
        code.style.cursor = 'pointer';
        code.title = 'Klik untuk copy';
        
        code.addEventListener('click', function() {
            navigator.clipboard.writeText(this.textContent).then(() => {
                // Show temporary feedback
                const originalText = this.textContent;
                this.textContent = 'Copied!';
                this.classList.add('text-success');
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('text-success');
                }, 1000);
            });
        });
    });
});
</script>
@endpush