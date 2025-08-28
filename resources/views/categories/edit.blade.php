{{-- resources/views/categories/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Kategori - ' . $category->name)
@section('page-title', 'Edit Item Kategori')
@section('page-subtitle', 'Perbarui informasi item: ' . $category->name)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('categories.index') }}" class="text-decoration-none">Kategori</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('page-actions')
<a href="{{ route('categories.index', $category) }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Kembali
</a>
@endsection

@section('content')
<div class="container-fluid px-4">
    {{-- Category Info Card --}}
    <div class="card shadow mb-4 border-left-info">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-tag text-white fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 font-weight-bold">{{ $category->name }}</h5>
                            <p class="mb-0 text-muted small">
                                Dibuat: {{ $category->created_at->format('d M Y H:i') }} | 
                                Terakhir diupdate: {{ $category->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-info fs-6">
                        {{ $category->inventoryItems()->count() }} item terkait
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Column Layout --}}
    <div class="row">
        {{-- Left Column - Edit Form --}}
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Formulir Edit Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category) }}" method="POST" id="editCategoryForm">
                        @csrf
                        @method('PUT')
                        
                        {{-- Nama Kategori --}}
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="name" class="col-form-label font-weight-bold text-gray-800">
                                    Nama Kategori <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $category->name) }}" 
                                       placeholder="Masukkan nama kategori..."
                                       maxlength="255"
                                       required>
                                <div class="form-text">
                                    Nama kategori harus unik dan mudah diidentifikasi
                                    @if($category->inventoryItems()->count() > 0)
                                        <br><small class="text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Perhatian: Kategori ini memiliki {{ $category->inventoryItems()->count() }} item terkait
                                        </small>
                                    @endif
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="description" class="col-form-label font-weight-bold text-gray-800">
                                    Deskripsi
                                </label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Berikan deskripsi singkat tentang kategori ini... (opsional)"
                                          maxlength="1000">{{ old('description', $category->description) }}</textarea>
                                <div class="form-text">
                                    <span id="char-count">{{ strlen($category->description ?? '') }}</span>/1000 karakter
                                </div>
                                @error('description')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Changes Detection --}}
                        <div class="row mb-4" id="changes-alert" style="display: none;">
                            <div class="col-md-9 offset-md-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Perubahan Terdeteksi:</strong>
                                    <ul class="mb-0 mt-2" id="changes-list"></ul>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row">
                            <div class="col-md-9 offset-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="resetBtn">
                                        <i class="fas fa-undo me-2"></i>Batalkan Perubahan
                                    </button>
                                    <a href="{{ route('categories.index') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column - Preview & Info --}}
        <div class="col-lg-4">
            {{-- Live Preview Card --}}
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-eye me-2"></i>Live Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-tag text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-weight-bold" id="preview-name">{{ $category->name }}</h6>
                            <small class="text-muted" id="preview-description">
                                {{ $category->description ?: 'Tidak ada deskripsi' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Related Items Status --}}
            @if($category->inventoryItems()->count() > 0)
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-boxes me-2"></i>Item Terkait
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="h3 mb-1 text-warning fw-bold">{{ $category->inventoryItems()->count() }}</div>
                            <small class="text-muted">Item Inventaris</small>
                        </div>
                        <p class="text-muted small mb-3">Perubahan nama kategori akan mempengaruhi semua item tersebut.</p>
                        <div class="d-grid">
                            <a href="{{ route('inventory.index', ['category' => $category->id]) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-eye me-1"></i>Lihat Item Terkait
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow mb-4 border-left-success">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                        <h6 class="font-weight-bold text-success mb-1">Kategori Kosong</h6>
                        <p class="text-muted small mb-0">Belum ada item inventaris yang menggunakan kategori ini.</p>
                    </div>
                </div>
            @endif

            {{-- Quick Stats Card --}}
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-line me-2"></i>Statistik Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h5 mb-0 text-info fw-bold">
                                    {{ \Carbon\Carbon::parse($category->created_at)->diffInDays(\Carbon\Carbon::now()) }}
                                </div>
                                <small class="text-muted">Hari Dibuat</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0 text-primary fw-bold">
                                {{ \Carbon\Carbon::parse($category->updated_at)->diffInDays(\Carbon\Carbon::now()) }}
                            </div>
                            <small class="text-muted">Hari Update</small>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Terakhir diubah {{ $category->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>

            {{-- Help & Guidelines Card --}}
            <div class="card shadow border-left-success">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">
                        <i class="fas fa-lightbulb me-2"></i>Panduan Edit
                    </h6>
                    <ul class="mb-0 text-muted small">
                        <li>Nama kategori harus unik dalam sistem</li>
                        <li>Gunakan nama yang mudah dipahami</li>
                        <li>Deskripsi membantu identifikasi kategori</li>
                        <li>Perubahan langsung mempengaruhi item terkait</li>
                    </ul>
                    
                    <hr class="my-3">
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-list me-1"></i>Semua Kategori
                        </a>
                        @if($category->inventoryItems()->count() == 0)
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="fas fa-trash me-1"></i>Hapus Kategori
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const previewName = document.getElementById('preview-name');
    const previewDescription = document.getElementById('preview-description');
    const charCount = document.getElementById('char-count');
    const resetBtn = document.getElementById('resetBtn');
    const saveBtn = document.getElementById('saveBtn');
    const changesAlert = document.getElementById('changes-alert');
    const changesList = document.getElementById('changes-list');
    
    // Original values
    const originalName = '{{ $category->name }}';
    const originalDescription = '{{ $category->description ?? '' }}';
    
    let hasChanges = false;

    // Real-time preview update
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Nama Kategori';
        checkForChanges();
    });

    descriptionInput.addEventListener('input', function() {
        const value = this.value;
        previewDescription.textContent = value || 'Tidak ada deskripsi';
        charCount.textContent = value.length;
        
        // Change color based on character count
        if (value.length > 800) {
            charCount.className = 'text-danger font-weight-bold';
        } else if (value.length > 600) {
            charCount.className = 'text-warning';
        } else {
            charCount.className = '';
        }
        
        checkForChanges();
    });

    // Check for changes
    function checkForChanges() {
        const currentName = nameInput.value.trim();
        const currentDescription = descriptionInput.value.trim();
        
        const nameChanged = currentName !== originalName;
        const descriptionChanged = currentDescription !== originalDescription;
        
        hasChanges = nameChanged || descriptionChanged;
        
        if (hasChanges) {
            changesAlert.style.display = 'block';
            changesList.innerHTML = '';
            
            if (nameChanged) {
                changesList.innerHTML += '<li>Nama kategori: <strong>"' + originalName + '"</strong> → <strong>"' + currentName + '"</strong></li>';
            }
            
            if (descriptionChanged) {
                const oldDesc = originalDescription || '(kosong)';
                const newDesc = currentDescription || '(kosong)';
                changesList.innerHTML += '<li>Deskripsi: <strong>' + oldDesc + '</strong> → <strong>' + newDesc + '</strong></li>';
            }
            
            saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Perubahan';
            saveBtn.className = 'btn btn-warning';
        } else {
            changesAlert.style.display = 'none';
            saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Perubahan';
            saveBtn.className = 'btn btn-primary';
        }
    }

    // Reset to original values
    resetBtn.addEventListener('click', function() {
        nameInput.value = originalName;
        descriptionInput.value = originalDescription;
        previewName.textContent = originalName;
        previewDescription.textContent = originalDescription || 'Tidak ada deskripsi';
        charCount.textContent = originalDescription.length;
        charCount.className = '';
        checkForChanges();
    });

    // Form validation
    nameInput.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value && value.length < 2) {
            this.classList.add('is-invalid');
            showValidationMessage(this, 'Nama kategori minimal 2 karakter');
        } else {
            this.classList.remove('is-invalid');
            hideValidationMessage(this);
        }
    });

    // Real-time character count update
    descriptionInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        // Visual feedback for character count
        if (length > 900) {
            charCount.className = 'text-danger fw-bold';
        } else if (length > 700) {
            charCount.className = 'text-warning';
        } else {
            charCount.className = 'text-muted';
        }
    });

    // Form submission
    document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        
        if (!name) {
            e.preventDefault();
            nameInput.focus();
            showValidationMessage(nameInput, 'Nama kategori harus diisi!');
            return false;
        }

        if (name.length < 2) {
            e.preventDefault();
            nameInput.focus();
            showValidationMessage(nameInput, 'Nama kategori minimal 2 karakter!');
            return false;
        }

        if (!hasChanges) {
            e.preventDefault();
            alert('Tidak ada perubahan yang perlu disimpan.');
            return false;
        }

        // Confirm if category has items
        const itemCount = {{ $category->inventoryItems()->count() }};
        if (itemCount > 0 && nameInput.value.trim() !== originalName) {
            const confirmed = confirm(
                'Kategori ini memiliki ' + itemCount + ' item terkait. ' +
                'Mengubah nama kategori akan mempengaruhi semua item tersebut. ' +
                'Apakah Anda yakin ingin melanjutkan?'
            );
            
            if (!confirmed) {
                e.preventDefault();
                return false;
            }
        }

        // Show loading state
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        saveBtn.disabled = true;
        resetBtn.disabled = true;
    });

    // Helper functions for validation messages
    function showValidationMessage(input, message) {
        hideValidationMessage(input);
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback custom-validation';
        feedback.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + message;
        input.parentNode.appendChild(feedback);
        input.classList.add('is-invalid');
    }

    function hideValidationMessage(input) {
        const feedback = input.parentNode.querySelector('.invalid-feedback.custom-validation');
        if (feedback) {
            feedback.remove();
        }
        input.classList.remove('is-invalid');
    }

    // Warning on page leave if there are unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            const message = 'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman?';
            e.preventDefault();
            e.returnValue = message;
            return message;
        }
    });

    // Auto-save draft functionality (optional)
    let autoSaveTimeout;
    function autoSaveDraft() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            if (hasChanges) {
                // You can implement auto-save to session or local storage here
                console.log('Auto-saving draft...');
            }
        }, 2000);
    }

    nameInput.addEventListener('input', autoSaveDraft);
    descriptionInput.addEventListener('input', autoSaveDraft);

    // Initialize
    checkForChanges();
});
</script>
@endpush

<style>
/* Enhanced form styling */
.form-control {
    border-radius: 8px;
    border: 1px solid #d1d3e2;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    transform: translateY(-1px);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.card {
    border: none;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2) !important;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-end {
    border-right: 1px solid #e3e6f0 !important;
}

/* Animation for form validation */
.is-invalid {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-3px); }
    75% { transform: translateX(3px); }
}

/* Mobile responsiveness */
@media (max-width: 992px) {
    .col-lg-4 {
        margin-top: 1rem;
    }
}

@media (max-width: 768px) {
    .col-md-3,
    .col-md-9 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .offset-md-3 {
        margin-left: 0 !important;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 > * {
        margin-bottom: 0.5rem;
    }
    
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #e3e6f0 !important;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
    }
}

/* Loading state */
.btn.loading {
    position: relative;
    color: transparent !important;
}

.btn.loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>