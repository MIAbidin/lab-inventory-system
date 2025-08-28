@extends('layouts.app')

@section('title', 'Edit Item - ' . $inventory->name)
@section('page-title', 'Edit Item Inventaris')
@section('page-subtitle', 'Perbarui informasi item: ' . $inventory->name)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('inventory.index') }}" class="text-decoration-none">Inventaris</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('inventory.show', $inventory) }}" class="text-decoration-none">Detail Item</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('page-actions')
<a href="{{ route('inventory.show', $inventory) }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail
</a>
@endsection

@push('styles')
<style>
    .form-section {
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
    
    .current-image {
        width: 200px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #dee2e6;
    }
    
    .image-preview {
        width: 200px;
        height: 150px;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        overflow: hidden;
        position: relative;
    }
    
    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .image-preview.dragover {
        border-color: #007bff;
        background: rgba(0, 123, 255, 0.1);
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
    
    .inventory-code-display {
        background: #e9ecef;
        padding: 10px 15px;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #495057;
    }
</style>
@endpush

@section('content')
<form action="{{ route('inventory.update', $inventory) }}" method="POST" enctype="multipart/form-data" id="inventoryForm">
    @csrf
    @method('PUT')
    
    <!-- Inventory Code Display -->
    <div class="form-section">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-barcode fa-2x text-primary"></i>
            </div>
            <div>
                <h6 class="mb-1">Kode Inventaris</h6>
                <div class="inventory-code-display">{{ $inventory->inventory_code }}</div>
                <small class="text-muted">Kode inventaris tidak dapat diubah</small>
            </div>
        </div>
    </div>
    
    <!-- Basic Information Section -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-info-circle me-2 text-primary"></i>
            Informasi Dasar
        </h5>
        
        <div class="row">
            <!-- Item Name -->
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label required-field">Nama Item</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $inventory->name) }}"
                       placeholder="Masukkan nama item"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Category -->
            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label required-field">Kategori</label>
                <select class="form-select @error('category_id') is-invalid @enderror" 
                        id="category_id" 
                        name="category_id" 
                        required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ old('category_id', $inventory->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Brand -->
            <div class="col-md-6 mb-3">
                <label for="brand" class="form-label required-field">Merk/Brand</label>
                <input type="text" 
                       class="form-control @error('brand') is-invalid @enderror" 
                       id="brand" 
                       name="brand" 
                       value="{{ old('brand', $inventory->brand) }}"
                       placeholder="Masukkan merk/brand"
                       required>
                @error('brand')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Model -->
            <div class="col-md-6 mb-3">
                <label for="model" class="form-label required-field">Model/Tipe</label>
                <input type="text" 
                       class="form-control @error('model') is-invalid @enderror" 
                       id="model" 
                       name="model" 
                       value="{{ old('model', $inventory->model) }}"
                       placeholder="Masukkan model/tipe"
                       required>
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Serial Number -->
            <div class="col-md-6 mb-3">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" 
                       class="form-control @error('serial_number') is-invalid @enderror" 
                       id="serial_number" 
                       name="serial_number" 
                       value="{{ old('serial_number', $inventory->serial_number) }}"
                       placeholder="Masukkan serial number (opsional)">
                @error('serial_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Location -->
            <div class="col-md-6 mb-3">
                <label for="location" class="form-label">Lokasi/Ruangan</label>
                <input type="text" 
                       class="form-control @error('location') is-invalid @enderror" 
                       id="location" 
                       name="location" 
                       value="{{ old('location', $inventory->location) }}"
                       placeholder="Contoh: Lab 1, Ruang Server">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Purchase Information Section -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-receipt me-2 text-primary"></i>
            Informasi Pembelian
        </h5>
        
        <div class="row">
            <!-- Purchase Date -->
            <div class="col-md-6 mb-3">
                <label for="purchase_date" class="form-label">Tanggal Pembelian</label>
                <input type="date" 
                       class="form-control @error('purchase_date') is-invalid @enderror" 
                       id="purchase_date" 
                       name="purchase_date" 
                       value="{{ old('purchase_date', $inventory->purchase_date?->format('Y-m-d')) }}">
                @error('purchase_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Purchase Price -->
            <div class="col-md-6 mb-3">
                <label for="purchase_price" class="form-label">Harga Beli</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" 
                           class="form-control @error('purchase_price') is-invalid @enderror" 
                           id="purchase_price" 
                           name="purchase_price" 
                           value="{{ old('purchase_price', $inventory->purchase_price) }}"
                           placeholder="0"
                           min="0"
                           step="0.01">
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status & Condition Section -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-cogs me-2 text-primary"></i>
            Status & Kondisi
        </h5>
        
        <div class="row">
            <!-- Current Status Display -->
            <div class="col-12 mb-3">
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Status Saat Ini:</strong>
                            <span class="badge {{ $inventory->status_badge }} ms-2">
                                {{ ucfirst(str_replace('_', ' ', $inventory->status)) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Kondisi Saat Ini:</strong>
                            <span class="badge {{ $inventory->condition_badge }} ms-2">
                                {{ ucfirst(str_replace('_', ' ', $inventory->condition)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Condition -->
            <div class="col-md-6 mb-3">
                <label for="condition" class="form-label required-field">Kondisi</label>
                <select class="form-select @error('condition') is-invalid @enderror" 
                        id="condition" 
                        name="condition" 
                        required>
                    <option value="">Pilih Kondisi</option>
                    <option value="good" {{ old('condition', $inventory->condition) == 'good' ? 'selected' : '' }}>
                        ✅ Baik
                    </option>
                    <option value="need_repair" {{ old('condition', $inventory->condition) == 'need_repair' ? 'selected' : '' }}>
                        🔧 Perlu Perbaikan
                    </option>
                    <option value="broken" {{ old('condition', $inventory->condition) == 'broken' ? 'selected' : '' }}>
                        ❌ Rusak
                    </option>
                </select>
                @error('condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Status -->
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label required-field">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" 
                        id="status" 
                        name="status" 
                        required>
                    <option value="">Pilih Status</option>
                    <option value="available" {{ old('status', $inventory->status) == 'available' ? 'selected' : '' }}>
                        ✅ Tersedia
                    </option>
                    <option value="in_use" {{ old('status', $inventory->status) == 'in_use' ? 'selected' : '' }}>
                        👤 Sedang Digunakan
                    </option>
                    <option value="maintenance" {{ old('status', $inventory->status) == 'maintenance' ? 'selected' : '' }}>
                        🔧 Maintenance
                    </option>
                    <option value="disposed" {{ old('status', $inventory->status) == 'disposed' ? 'selected' : '' }}>
                        🚫 Tidak Digunakan
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Additional Information Section -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-clipboard-list me-2 text-primary"></i>
            Informasi Tambahan
        </h5>
        
        <div class="row">
            <!-- Specifications -->
            <div class="col-md-6 mb-3">
                <label for="specifications" class="form-label">Spesifikasi Teknis</label>
                <textarea class="form-control @error('specifications') is-invalid @enderror" 
                          id="specifications" 
                          name="specifications" 
                          rows="4" 
                          placeholder="Masukkan spesifikasi teknis...">{{ old('specifications', $inventory->specifications) }}</textarea>
                @error('specifications')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Notes -->
            <div class="col-md-6 mb-3">
                <label for="notes" class="form-label">Catatan</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" 
                          name="notes" 
                          rows="4" 
                          placeholder="Catatan tambahan...">{{ old('notes', $inventory->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Image Upload Section -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-image me-2 text-primary"></i>
            Foto Item
        </h5>
        
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Foto Saat Ini</label>
                <div class="mb-2">
                    @if($inventory->image_path)
                        <img src="{{ asset('storage/' . $inventory->image_path) }}" 
                             alt="Current image" 
                             class="current-image">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                            <label class="form-check-label text-danger" for="remove_image">
                                Hapus foto saat ini
                            </label>
                        </div>
                    @else
                        <div class="current-image d-flex align-items-center justify-content-center bg-light">
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <div>Tidak ada foto</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-md-4">
                <label for="image" class="form-label">Upload Foto Baru</label>
                <input type="file" 
                       class="form-control @error('image') is-invalid @enderror" 
                       id="image" 
                       name="image" 
                       accept="image/jpeg,image/png,image/jpg">
                <div class="form-text">
                    Format: JPEG, PNG, JPG. Maksimal 2MB.
                </div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Preview Foto Baru</label>
                <div class="image-preview" id="imagePreview">
                    <div class="text-center text-muted">
                        <i class="fas fa-image fa-2x mb-2"></i>
                        <div>Preview foto baru akan muncul di sini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Update Log Section (for transparency) -->
    <div class="form-section">
        <h5 class="section-title">
            <i class="fas fa-history me-2 text-primary"></i>
            Informasi Update
        </h5>
        
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-light">
                    <strong>Dibuat:</strong> {{ $inventory->created_at->format('d M Y, H:i') }}<br>
                    <strong>Update Terakhir:</strong> {{ $inventory->updated_at->format('d M Y, H:i') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <textarea class="form-control" 
                              id="update_notes" 
                              name="update_notes" 
                              placeholder="Alasan update..."
                              style="height: 80px">{{ old('update_notes') }}</textarea>
                    <label for="update_notes">Catatan Update (opsional)</label>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Actions -->
    <div class="form-section">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Field yang wajib diisi ditandai dengan tanda * | 
                Perubahan status akan dicatat dalam riwayat
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('inventory.show', $inventory) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageCheckbox = document.getElementById('remove_image');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
            
            // Uncheck remove image if new image is selected
            if (removeImageCheckbox) {
                removeImageCheckbox.checked = false;
            }
        } else {
            resetImagePreview();
        }
    });
    
    function resetImagePreview() {
        imagePreview.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-image fa-2x mb-2"></i>
                <div>Preview foto baru akan muncul di sini</div>
            </div>
        `;
    }
    
    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imagePreview.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        imagePreview.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        imagePreview.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        imagePreview.classList.add('dragover');
    }
    
    function unhighlight() {
        imagePreview.classList.remove('dragover');
    }
    
    imagePreview.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            imageInput.files = files;
            const event = new Event('change', { bubbles: true });
            imageInput.dispatchEvent(event);
        }
    }
    
    // Form validation and submission
    const form = document.getElementById('inventoryForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.disabled = true;
    });
    
    // Track changes for confirmation
    let originalFormData = new FormData(form);
    
    window.addEventListener('beforeunload', function(e) {
        const currentFormData = new FormData(form);
        let hasChanges = false;
        
        // Simple change detection
        for (let [key, value] of currentFormData.entries()) {
            if (originalFormData.get(key) !== value) {
                hasChanges = true;
                break;
            }
        }
        
        if (hasChanges && !submitBtn.disabled) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Status/Condition change warnings
    const statusSelect = document.getElementById('status');
    const conditionSelect = document.getElementById('condition');
    const originalStatus = '{{ $inventory->status }}';
    const originalCondition = '{{ $inventory->condition }}';
    
    function showChangeWarning(field, oldValue, newValue) {
        if (oldValue !== newValue) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show mt-2';
            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Perhatian:</strong> ${field} akan diubah dari "${oldValue}" ke "${newValue}".
                Perubahan ini akan dicatat dalam riwayat.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Remove existing warning
            const existingWarning = document.querySelector('.change-warning');
            if (existingWarning) {
                existingWarning.remove();
            }
            
            alertDiv.classList.add('change-warning');
            statusSelect.parentNode.parentNode.appendChild(alertDiv);
        }
    }
    
    statusSelect.addEventListener('change', function() {
        if (this.value && this.value !== originalStatus) {
            showChangeWarning('Status', originalStatus.replace('_', ' '), this.value.replace('_', ' '));
        }
    });
    
    conditionSelect.addEventListener('change', function() {
        if (this.value && this.value !== originalCondition) {
            showChangeWarning('Kondisi', originalCondition.replace('_', ' '), this.value.replace('_', ' '));
        }
    });
});
</script>
@endpush