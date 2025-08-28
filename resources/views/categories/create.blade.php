{{-- resources/views/categories/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Item Kategori')
@section('page-subtitle', 'Tambahkan item baru ke Kategori laboratorium')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('categories.index') }}" class="text-decoration-none">Kategori</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Tambah Item</li>
@endsection

@section('page-actions')
<a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Kembali
</a>
@endsection

@section('content')
<div class="container-fluid px-4">

    <div class="row">
        <!-- Left Column - Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle me-2"></i>Formulir Kategori Baru
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        
                        <!-- Nama Kategori -->
                        <div class="mb-4">
                            <label for="name" class="form-label font-weight-bold text-gray-800">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Masukkan nama kategori..."
                                   maxlength="255"
                                   required>
                            <div class="form-text">Nama kategori harus unik dan mudah diidentifikasi</div>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="form-label font-weight-bold text-gray-800">
                                Deskripsi
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Berikan deskripsi singkat tentang kategori ini... (opsional)"
                                      maxlength="1000">{{ old('description') }}</textarea>
                            <div class="form-text">
                                <span id="char-count">0</span>/1000 karakter
                            </div>
                            @error('description')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Kategori
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" id="resetForm">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-danger">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Preview & Help -->
        <div class="col-lg-4">
            <!-- Preview Card -->
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-eye me-2"></i>Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-tag text-white fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 font-weight-bold" id="preview-name">Nama Kategori</h6>
                            <small class="text-muted" id="preview-description">Deskripsi akan muncul di sini</small>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-xs text-uppercase text-muted font-weight-bold">Status</div>
                            <div class="h6 text-success">
                                <i class="fas fa-circle fa-xs me-1"></i>Baru
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-xs text-uppercase text-muted font-weight-bold">Items</div>
                            <div class="h6 text-info">0 item</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card shadow border-left-info">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb me-2"></i>Tips Membuat Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <small class="text-muted">Gunakan nama yang jelas dan spesifik</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <small class="text-muted">Hindari kategori yang terlalu umum</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check text-success me-2 mt-1"></i>
                            <small class="text-muted">Deskripsi membantu identifikasi</small>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-info me-2 mt-1"></i>
                            <small class="text-muted">Kategori dapat diedit setelah dibuat</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card shadow border-left-warning">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-chart-line fa-2x text-warning mb-2"></i>
                        <h6 class="font-weight-bold text-warning">Statistik Kategori</h6>
                        <p class="text-muted small mb-0">
                            Setelah kategori dibuat, Anda dapat melihat jumlah item dan statistik lainnya di halaman daftar kategori.
                        </p>
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
    const resetBtn = document.getElementById('resetForm');

    // Real-time preview update
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Nama Kategori';
    });

    descriptionInput.addEventListener('input', function() {
        const value = this.value;
        previewDescription.textContent = value || 'Deskripsi akan muncul di sini';
        charCount.textContent = value.length;
        
        // Change color based on character count
        if (value.length > 800) {
            charCount.className = 'text-danger font-weight-bold';
        } else if (value.length > 600) {
            charCount.className = 'text-warning';
        } else {
            charCount.className = '';
        }
    });

    // Reset form and preview
    resetBtn.addEventListener('click', function() {
        setTimeout(function() {
            previewName.textContent = 'Nama Kategori';
            previewDescription.textContent = 'Deskripsi akan muncul di sini';
            charCount.textContent = '0';
            charCount.className = '';
        }, 10);
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        
        if (!name) {
            e.preventDefault();
            nameInput.focus();
            alert('Nama kategori harus diisi!');
            return false;
        }

        if (name.length < 2) {
            e.preventDefault();
            nameInput.focus();
            alert('Nama kategori minimal 2 karakter!');
            return false;
        }

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.disabled = true;
    });

    // Initialize character count on page load
    charCount.textContent = descriptionInput.value.length;
});
</script>
@endpush