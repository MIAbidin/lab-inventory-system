@extends('layouts.app')

@section('title', 'Tambah Item Baru')
@section('page-title', 'Tambah Item Inventaris')
@section('page-subtitle', 'Tambahkan item baru ke inventaris laboratorium')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('inventory.index') }}" class="text-decoration-none">Inventaris</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Tambah Item</li>
@endsection

@section('page-actions')
<a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Kembali
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
        border: 1px solid #e3e6f0;
    }
    
    .section-title {
        color: #495057;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-weight: 600;
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
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .image-preview:hover {
        border-color: #007bff;
        background: rgba(0, 123, 255, 0.05);
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
    
    .image-preview.has-image {
        border-style: solid;
        border-color: #28a745;
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
    
    .inventory-code-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        text-align: center;
        margin-bottom: 15px;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .condition-option {
        padding: 8px 12px;
        margin: 2px 0;
        border-radius: 5px;
    }
    
    .condition-good { background-color: #d4edda; color: #155724; }
    .condition-need-repair { background-color: #fff3cd; color: #856404; }
    .condition-broken { background-color: #f8d7da; color: #721c24; }
    
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .drag-drop-zone {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .drag-drop-zone:hover,
    .drag-drop-zone.dragover {
        border-color: #007bff;
        background: rgba(0, 123, 255, 0.1);
    }
    
    .file-info {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 5px;
    }
    
    .price-formatter {
        font-family: 'Courier New', monospace;
    }
</style>
@endpush

@section('content')
<form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" id="inventoryForm">
    @csrf
    
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
                       value="{{ old('name') }}"
                       placeholder="Masukkan nama item"
                       required
                       autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Contoh: Mikroskop Digital, Laptop Dell Inspiron</div>
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
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($categories->isEmpty())
                    <div class="form-text text-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Belum ada kategori. <a href="{{ route('categories.create') }}">Buat kategori baru</a>
                    </div>
                @endif
            </div>
            
            <!-- Brand -->
            <div class="col-md-6 mb-3">
                <label for="brand" class="form-label required-field">Merk/Brand</label>
                <input type="text" 
                       class="form-control @error('brand') is-invalid @enderror" 
                       id="brand" 
                       name="brand" 
                       value="{{ old('brand') }}"
                       placeholder="Masukkan merk/brand"
                       required
                       list="brandSuggestions">
                <datalist id="brandSuggestions">
                    <option value="Dell">
                    <option value="HP">
                    <option value="Lenovo">
                    <option value="Asus">
                    <option value="Canon">
                    <option value="Epson">
                    <option value="Samsung">
                    <option value="LG">
                </datalist>
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
                       value="{{ old('model') }}"
                       placeholder="Masukkan model/tipe"
                       required>
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Contoh: Inspiron 15 3000, LaserJet Pro M404n</div>
            </div>
            
            <!-- Serial Number -->
            <div class="col-md-6 mb-3">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" 
                       class="form-control @error('serial_number') is-invalid @enderror" 
                       id="serial_number" 
                       name="serial_number" 
                       value="{{ old('serial_number') }}"
                       placeholder="Masukkan serial number (opsional)">
                @error('serial_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Serial number untuk identifikasi unik perangkat</div>
            </div>
            
            <!-- Location -->
            <div class="col-md-6 mb-3">
                <label for="location" class="form-label">Lokasi/Ruangan</label>
                <input type="text" 
                       class="form-control @error('location') is-invalid @enderror" 
                       id="location" 
                       name="location" 
                       value="{{ old('location') }}"
                       placeholder="Masukkan lokasi"
                       list="locationSuggestions">
                <datalist id="locationSuggestions">
                    <option value="Lab Komputer 1">
                    <option value="Lab Komputer 2">
                    <option value="Lab Kimia">
                    <option value="Lab Fisika">
                    <option value="Lab Biologi">
                    <option value="Ruang Server">
                    <option value="Ruang Guru">
                    <option value="Perpustakaan">
                    <option value="Gudang">
                </datalist>
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Lokasi penyimpanan atau penempatan item</div>
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
                       value="{{ old('purchase_date') }}"
                       max="{{ date('Y-m-d') }}">
                @error('purchase_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Tanggal pembelian item (opsional)</div>
            </div>
            
            <!-- Purchase Price -->
            <div class="col-md-6 mb-3">
                <label for="purchase_price" class="form-label">Harga Beli</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" 
                           class="form-control price-formatter @error('purchase_price') is-invalid @enderror" 
                           id="purchase_price" 
                           name="purchase_price" 
                           value="{{ old('purchase_price') }}"
                           placeholder="0"
                           min="0"
                           step="1000">
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">Harga pembelian dalam Rupiah (opsional)</div>
                <div id="priceDisplay" class="small text-muted"></div>
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
            <!-- Condition -->
            <div class="col-md-6 mb-3">
                <label for="condition" class="form-label required-field">Kondisi</label>
                <select class="form-select @error('condition') is-invalid @enderror" 
                        id="condition" 
                        name="condition" 
                        required>
                    <option value="">Pilih Kondisi</option>
                    <option value="good" {{ old('condition', 'good') == 'good' ? 'selected' : '' }}>
                        ✅ Baik
                    </option>
                    <option value="need_repair" {{ old('condition') == 'need_repair' ? 'selected' : '' }}>
                        🔧 Perlu Perbaikan
                    </option>
                    <option value="broken" {{ old('condition') == 'broken' ? 'selected' : '' }}>
                        ❌ Rusak
                    </option>
                </select>
                @error('condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Kondisi fisik item saat ini</div>
            </div>
            
            <!-- Status -->
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label required-field">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" 
                        id="status" 
                        name="status" 
                        required>
                    <option value="">Pilih Status</option>
                    <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>
                        ✅ Tersedia
                    </option>
                    <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>
                        👤 Sedang Digunakan
                    </option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                        🔧 Maintenance
                    </option>
                    <option value="disposed" {{ old('status') == 'disposed' ? 'selected' : '' }}>
                        🚫 Tidak Digunakan
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Status ketersediaan item</div>
            </div>
            
            <!-- Status & Condition Info -->
            <div class="col-12">
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>
                        Penjelasan Status & Kondisi
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Kondisi:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Baik:</strong> Item berfungsi normal</li>
                                <li><strong>Perlu Perbaikan:</strong> Item butuh service</li>
                                <li><strong>Rusak:</strong> Item tidak dapat digunakan</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Tersedia:</strong> Dapat digunakan/dipinjam</li>
                                <li><strong>Sedang Digunakan:</strong> Sedang dipinjam</li>
                                <li><strong>Maintenance:</strong> Dalam perawatan</li>
                                <li><strong>Tidak Digunakan:</strong> Sudah tidak terpakai</li>
                            </ul>
                        </div>
                    </div>
                </div>
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
                          placeholder="Masukkan spesifikasi teknis...">{{ old('specifications') }}</textarea>
                @error('specifications')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    Contoh: RAM 8GB, Storage 256GB SSD, Processor Intel i5, dll
                </div>
            </div>
            
            <!-- Notes -->
            <div class="col-md-6 mb-3">
                <label for="notes" class="form-label">Catatan</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" 
                          name="notes" 
                          rows="4" 
                          placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    Catatan khusus, riwayat perbaikan, atau informasi penting lainnya
                </div>
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
            <div class="col-md-6 mb-3">
                <label for="image" class="form-label">Upload Foto</label>
                <input type="file" 
                       class="form-control @error('image') is-invalid @enderror" 
                       id="image" 
                       name="image" 
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       style="display: none;">
                
                <div class="drag-drop-zone" id="dropZone" onclick="document.getElementById('image').click()">
                    <div id="dropZoneContent">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <p class="mb-2">Klik untuk pilih foto atau drag & drop di sini</p>
                        <p class="text-muted small mb-0">Format: JPEG, PNG, JPG, WebP | Maksimal 2MB</p>
                    </div>
                </div>
                
                @error('image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                
                <div id="fileInfo" class="file-info" style="display: none;"></div>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Preview Foto</label>
                <div class="image-preview" id="imagePreview">
                    <div class="text-center text-muted">
                        <i class="fas fa-image fa-2x mb-2"></i>
                        <div>Preview foto akan muncul di sini</div>
                        <small class="text-muted">Foto akan digunakan untuk identifikasi item</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Actions -->
    <div class="form-section">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Field yang wajib diisi ditandai dengan tanda (<span class="text-danger">*</span>)
            </div>
            <div class="d-flex gap-2">
                <button type="reset" class="btn btn-outline-warning" id="resetBtn">
                    <i class="fas fa-undo me-2"></i>Reset Form
                </button>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save me-2"></i>Simpan Item
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
    const dropZone = document.getElementById('dropZone');
    const dropZoneContent = document.getElementById('dropZoneContent');
    const fileInfo = document.getElementById('fileInfo');
    
    // File input change handler
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        handleFile(file);
    });
    
    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropZone.classList.add('dragover');
    }
    
    function unhighlight() {
        dropZone.classList.remove('dragover');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            const file = files[0];
            // Set the file to input
            const fileList = new DataTransfer();
            fileList.items.add(file);
            imageInput.files = fileList.files;
            handleFile(file);
        }
    }
    
    function handleFile(file) {
        if (file) {
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPEG, PNG, JPG, atau WebP.');
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                imagePreview.classList.add('has-image');
            };
            reader.readAsDataURL(file);
            
            // Show file info
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            fileInfo.innerHTML = `
                <i class="fas fa-file-image me-1"></i>
                <strong>${file.name}</strong> (${fileSize} MB)
            `;
            fileInfo.style.display = 'block';
            
            // Update drop zone content
            dropZoneContent.innerHTML = `
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <p class="mb-1 text-success">Foto berhasil dipilih</p>
                <p class="text-muted small mb-0">Klik untuk ganti foto</p>
            `;
        } else {
            resetImagePreview();
        }
    }
    
    function resetImagePreview() {
        imagePreview.innerHTML = `
            <div class="text-center text-muted">
                <i class="fas fa-image fa-2x mb-2"></i>
                <div>Preview foto akan muncul di sini</div>
                <small class="text-muted">Foto akan digunakan untuk identifikasi item</small>
            </div>
        `;
        imagePreview.classList.remove('has-image');
        fileInfo.style.display = 'none';
        dropZoneContent.innerHTML = `
            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
            <p class="mb-2">Klik untuk pilih foto atau drag & drop di sini</p>
            <p class="text-muted small mb-0">Format: JPEG, PNG, JPG, WebP | Maksimal 2MB</p>
        `;
    }
    
    // Price formatter
    const priceInput = document.getElementById('purchase_price');
    const priceDisplay = document.getElementById('priceDisplay');
    
    priceInput.addEventListener('input', function() {
        const value = this.value;
        if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            priceDisplay.textContent = `Rp ${formatted}`;
        } else {
            priceDisplay.textContent = '';
        }
    });
    
    // Form validation and submission
    const form = document.getElementById('inventoryForm');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');
    
    form.addEventListener('submit', function(e) {
        // Basic validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.disabled = true;
        
        // Disable other buttons
        document.querySelectorAll('.btn').forEach(btn => {
            if (btn !== submitBtn) btn.disabled = true;
        });
    });
    
    // Reset form handler
    resetBtn.addEventListener('click', function() {
        if (confirm('Yakin ingin mengosongkan semua form?')) {
            form.reset();
            resetImagePreview();
            priceDisplay.textContent = '';
            
            // Clear validation classes
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            
            // Focus on first input
            document.getElementById('name').focus();
        }
    });
    
    // Auto-generate suggestions based on name input
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        
        // Auto-suggest category based on name
        const categorySelect = document.getElementById('category_id');
        if (value.includes('komputer') || value.includes('laptop') || value.includes('pc')) {
            Array.from(categorySelect.options).forEach(option => {
                if (option.text.toLowerCase().includes('komputer') || option.text.toLowerCase().includes('elektronik')) {
                    option.selected = true;
                }
            });
        }
    });
    
    // Prevent form submission on Enter key in text inputs
    document.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const form = e.target.form;
                const elements = Array.from(form.elements);
                const currentIndex = elements.indexOf(e.target);
                const nextElement = elements[currentIndex + 1];
                
                if (nextElement && nextElement.type !== 'submit') {
                    nextElement.focus();
                }
            }
        });
    });
    
    // Real-time validation feedback
    document.querySelectorAll('input[required], select[required]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Serial number validation
    const serialInput = document.getElementById('serial_number');
    serialInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
    });
    
    // Auto-complete location based on category
    const categorySelect = document.getElementById('category_id');
    const locationInput = document.getElementById('location');
    
    categorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const categoryName = selectedOption.text.toLowerCase();
        
        if (categoryName.includes('komputer')) {
            locationInput.value = locationInput.value || 'Lab Komputer 1';
        } else if (categoryName.includes('kimia')) {
            locationInput.value = locationInput.value || 'Lab Kimia';
        } else if (categoryName.includes('fisika')) {
            locationInput.value = locationInput.value || 'Lab Fisika';
        } else if (categoryName.includes('biologi')) {
            locationInput.value = locationInput.value || 'Lab Biologi';
        }
    });
    
    // Status and condition dependency
    const conditionSelect = document.getElementById('condition');
    const statusSelect = document.getElementById('status');
    
    conditionSelect.addEventListener('change', function() {
        if (this.value === 'broken') {
            statusSelect.value = 'maintenance';
            showAlert('info', 'Status otomatis diubah ke "Maintenance" karena kondisi rusak');
        } else if (this.value === 'need_repair') {
            if (statusSelect.value === 'available') {
                statusSelect.value = 'maintenance';
                showAlert('warning', 'Disarankan mengubah status ke "Maintenance" untuk item yang perlu perbaikan');
            }
        }
    });
    
    // Show temporary alerts
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-2`;
        alertDiv.innerHTML = `
            <i class="fas fa-info-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert after status section
        const statusSection = document.querySelector('.form-section:nth-of-type(4)');
        statusSection.insertAdjacentElement('afterend', alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv && alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Form progress indicator
    const requiredInputs = document.querySelectorAll('[required]');
    const progressBar = createProgressBar();
    
    function createProgressBar() {
        const progressDiv = document.createElement('div');
        progressDiv.className = 'progress mb-3';
        progressDiv.style.height = '5px';
        progressDiv.innerHTML = '<div class="progress-bar bg-success" style="width: 0%"></div>';
        
        const firstSection = document.querySelector('.form-section');
        firstSection.insertAdjacentElement('beforebegin', progressDiv);
        
        return progressDiv.querySelector('.progress-bar');
    }
    
    function updateProgress() {
        let filledCount = 0;
        requiredInputs.forEach(input => {
            if (input.value.trim()) filledCount++;
        });
        
        const percentage = (filledCount / requiredInputs.length) * 100;
        progressBar.style.width = percentage + '%';
        
        if (percentage === 100) {
            progressBar.classList.remove('bg-info');
            progressBar.classList.add('bg-success');
        } else {
            progressBar.classList.remove('bg-success');
            progressBar.classList.add('bg-info');
        }
    }
    
    // Update progress on input
    requiredInputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });
    
    // Initial progress update
    updateProgress();
    
    // Warn before leaving if form has data
    let formHasData = false;
    
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('input', () => {
            formHasData = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formHasData && !submitBtn.disabled) {
            e.preventDefault();
            e.returnValue = 'Form memiliki data yang belum disimpan. Yakin ingin meninggalkan halaman?';
        }
    });
    
    // Update inventory code preview
    function updateCodePreview() {
        const today = new Date();
        const dateStr = today.toISOString().slice(0, 10).replace(/-/g, '');
        document.getElementById('codePreview').textContent = `INV-${dateStr}-001 (contoh)`;
    }
    
    updateCodePreview();
});
</script>
@endpush