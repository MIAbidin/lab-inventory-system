{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')
@section('page-subtitle', 'Kelola informasi akun dan keamanan Anda')

@section('content')
<div class="container-fluid px-4">

    {{-- Success Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- User Info Card --}}
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-user text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 font-weight-bold">{{ Auth::user()->name }}</h5>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-envelope me-1"></i>{{ Auth::user()->email }} |
                                        Bergabung: {{ Auth::user()->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            @if(Auth::user()->email_verified_at)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Email Terverifikasi
                                </span>
                            @else
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Email Belum Terverifikasi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Two Column Forms --}}
            <div class="row">
                {{-- Left Column - Profile Information --}}
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-user-edit me-2"></i>Informasi Profile
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                                @csrf
                                @method('PUT')
                                
                                {{-- Nama Lengkap --}}
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="name" class="col-form-label font-weight-bold text-gray-800">
                                            Nama <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', Auth::user()->name) }}" 
                                               placeholder="Masukkan nama lengkap..."
                                               maxlength="255"
                                               required>
                                        <div class="form-text">Nama lengkap untuk identifikasi</div>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                {{-- Email --}}
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="email" class="col-form-label font-weight-bold text-gray-800">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', Auth::user()->email) }}" 
                                               placeholder="user@example.com"
                                               required>
                                        <div class="form-text">Email untuk login dan notifikasi</div>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Account Info --}}
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label class="col-form-label font-weight-bold text-gray-800">
                                            Bergabung
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               value="{{ Auth::user()->created_at->format('d F Y, H:i') }}" 
                                               readonly>
                                        <div class="form-text">
                                            {{ \Carbon\Carbon::parse(Auth::user()->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-4">
                                        <label class="col-form-label font-weight-bold text-gray-800">
                                            Update Terakhir
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               value="{{ Auth::user()->updated_at->format('d F Y, H:i') }}" 
                                               readonly>
                                        <div class="form-text">
                                            {{ Auth::user()->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Changes Detection --}}
                                <div class="row mb-4" id="profile-changes-alert" style="display: none;">
                                    <div class="col-8 offset-4">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Perubahan Terdeteksi:</strong>
                                            <ul class="mb-0 mt-2" id="profile-changes-list"></ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="row">
                                    <div class="col-8 offset-4">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary" id="profileSaveBtn">
                                                <i class="fas fa-save me-2"></i>Update Profile
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="profileResetBtn">
                                                <i class="fas fa-undo me-2"></i>Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Password & Security --}}
                <div class="col-lg-6">
                    {{-- Change Password Card --}}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-warning">
                                <i class="fas fa-shield-alt me-2"></i>Keamanan Password
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
                                @csrf
                                @method('PUT')
                                
                                {{-- Current Password --}}
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="current_password" class="col-form-label font-weight-bold text-gray-800">
                                            Password Lama <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control @error('current_password') is-invalid @enderror" 
                                                   id="current_password" 
                                                   name="current_password" 
                                                   placeholder="Password saat ini"
                                                   required>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword('current_password')">
                                                <i class="fas fa-eye" id="current_password_icon"></i>
                                            </button>
                                            @error('current_password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- New Password --}}
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="password" class="col-form-label font-weight-bold text-gray-800">
                                            Password Baru <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Password baru"
                                                   required>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="password_icon"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Minimal 8 karakter</div>
                                        <div id="password-strength"></div>
                                    </div>
                                </div>
                                
                                {{-- Confirm Password --}}
                                <div class="row mb-4">
                                    <div class="col-4">
                                        <label for="password_confirmation" class="col-form-label font-weight-bold text-gray-800">
                                            Konfirmasi <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   placeholder="Ulangi password baru"
                                                   required>
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword('password_confirmation')">
                                                <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">Harus sama dengan password baru</div>
                                        <div id="password-match"></div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="row">
                                    <div class="col-8 offset-4">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-warning" id="passwordSaveBtn">
                                                <i class="fas fa-key me-2"></i>Ubah Password
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="passwordResetBtn">
                                                <i class="fas fa-times me-2"></i>Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Account Statistics Card --}}
                    <div class="card shadow border-left-info">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-info">
                                <i class="fas fa-chart-bar me-2"></i>Statistik Akun
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <div class="h4 mb-1 text-primary fw-bold">
                                            {{ \Carbon\Carbon::parse(Auth::user()->created_at)->diffInDays(\Carbon\Carbon::now()) }}
                                        </div>
                                        <small class="text-muted">Hari Aktif</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="h4 mb-1 fw-bold">
                                        @if(Auth::user()->remember_token)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-muted"></i>
                                        @endif
                                    </div>
                                    <small class="text-muted">Remember Login</small>
                                </div>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmLogout()">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Help Card --}}
            <div class="card shadow border-left-success mt-4">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">
                        <i class="fas fa-info-circle me-2"></i>Informasi Penting
                    </h6>
                    <ul class="mb-0 text-muted small">
                        <li>Pastikan email yang digunakan masih aktif untuk menerima notifikasi sistem</li>
                        <li>Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol</li>
                        <li>Update informasi profile secara berkala untuk menjaga keakuratan data</li>
                        <li>Hubungi administrator jika mengalami masalah dengan akun Anda</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile form elements
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const profileResetBtn = document.getElementById('profileResetBtn');
    const profileSaveBtn = document.getElementById('profileSaveBtn');
    const profileChangesAlert = document.getElementById('profile-changes-alert');
    const profileChangesList = document.getElementById('profile-changes-list');
    
    // Password form elements
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordResetBtn = document.getElementById('passwordResetBtn');
    const passwordSaveBtn = document.getElementById('passwordSaveBtn');
    
    // Original values
    const originalName = '{{ Auth::user()->name }}';
    const originalEmail = '{{ Auth::user()->email }}';
    
    let profileHasChanges = false;

    // Profile form change detection
    function checkProfileChanges() {
        const currentName = nameInput.value.trim();
        const currentEmail = emailInput.value.trim();
        
        const nameChanged = currentName !== originalName;
        const emailChanged = currentEmail !== originalEmail;
        
        profileHasChanges = nameChanged || emailChanged;
        
        if (profileHasChanges) {
            profileChangesAlert.style.display = 'block';
            profileChangesList.innerHTML = '';
            
            if (nameChanged) {
                profileChangesList.innerHTML += '<li>Nama: <strong>"' + originalName + '"</strong> → <strong>"' + currentName + '"</strong></li>';
            }
            
            if (emailChanged) {
                profileChangesList.innerHTML += '<li>Email: <strong>' + originalEmail + '</strong> → <strong>' + currentEmail + '</strong></li>';
            }
            
            profileSaveBtn.className = 'btn btn-warning';
        } else {
            profileChangesAlert.style.display = 'none';
            profileSaveBtn.className = 'btn btn-primary';
        }
    }

    // Profile event listeners
    if (nameInput) nameInput.addEventListener('input', checkProfileChanges);
    if (emailInput) emailInput.addEventListener('input', checkProfileChanges);

    // Profile reset button
    if (profileResetBtn) {
        profileResetBtn.addEventListener('click', function() {
            nameInput.value = originalName;
            emailInput.value = originalEmail;
            checkProfileChanges();
        });
    }

    // Password strength indicator
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let strengthLabel = '';
            let strengthClass = '';
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    strengthLabel = 'Sangat Lemah';
                    strengthClass = 'text-danger';
                    break;
                case 2:
                    strengthLabel = 'Lemah';
                    strengthClass = 'text-warning';
                    break;
                case 3:
                    strengthLabel = 'Sedang';
                    strengthClass = 'text-info';
                    break;
                case 4:
                    strengthLabel = 'Kuat';
                    strengthClass = 'text-success';
                    break;
                case 5:
                    strengthLabel = 'Sangat Kuat';
                    strengthClass = 'text-success fw-bold';
                    break;
            }
            
            const strengthElement = document.getElementById('password-strength');
            if (password.length > 0) {
                strengthElement.innerHTML = `<small class="${strengthClass}"><i class="fas fa-shield-alt me-1"></i>Kekuatan: ${strengthLabel}</small>`;
            } else {
                strengthElement.innerHTML = '';
            }
        });
    }

    // Password confirmation check
    if (passwordConfirmInput) {
        passwordConfirmInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmPassword = this.value;
            const matchElement = document.getElementById('password-match');
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchElement.innerHTML = '<small class="text-success"><i class="fas fa-check me-1"></i>Password cocok</small>';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    matchElement.innerHTML = '<small class="text-danger"><i class="fas fa-times me-1"></i>Password tidak cocok</small>';
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            } else {
                matchElement.innerHTML = '';
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }

    // Password reset button
    if (passwordResetBtn) {
        passwordResetBtn.addEventListener('click', function() {
            document.getElementById('current_password').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
            document.getElementById('password-strength').innerHTML = '';
            document.getElementById('password-match').innerHTML = '';
            
            // Remove validation classes
            document.querySelectorAll('#passwordForm .form-control').forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
            });
        });
    }

    // Initialize
    checkProfileChanges();
});

// Toggle password visibility
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const passwordIcon = document.getElementById(fieldId + '_icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

// Confirm logout
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        window.location.href = "{{ route('logout') }}";
    }
}

// Auto-hide success messages
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.remove('show');
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 5000);
    });
});

// Form submission handlers
document.getElementById('profileForm').addEventListener('submit', function(e) {
    if (!profileHasChanges) {
        e.preventDefault();
        alert('Tidak ada perubahan yang perlu disimpan.');
        return false;
    }
    
    profileSaveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    profileSaveBtn.disabled = true;
});

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password minimal 8 karakter!');
        return false;
    }
    
    passwordSaveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengubah...';
    passwordSaveBtn.disabled = true;
});
</script>

<style>
/* Custom Styles */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2) !important;
}

.card-header {
    background: linear-gradient(45deg, #f8f9fc, #ffffff);
    border-bottom: 1px solid #e3e6f0;
    border-radius: 15px 15px 0 0 !important;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #d1d3e2;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    transform: translateY(-1px);
}

.form-control[readonly] {
    background-color: #f8f9fc;
    border-color: #e3e6f0;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.input-group .btn {
    border-radius: 0 8px 8px 0;
    border: 1px solid #d1d3e2;
    border-left: none;
}

.input-group .form-control {
    border-radius: 8px 0 0 8px;
    border-right: none;
}

.input-group .form-control:focus + .btn {
    border-color: #4e73df;
}

.alert {
    border-radius: 10px;
    border: none;
}

.badge {
    border-radius: 20px;
    padding: 0.4rem 0.8rem;
}

.bg-primary {
    background: linear-gradient(45deg, #4e73df, #6f42c1) !important;
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

.fw-bold {
    font-weight: 700 !important;
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

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .row .col-4 {
        margin-bottom: 0.5rem;
    }
    
    .offset-4 {
        margin-left: 0 !important;
    }
    
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #e3e6f0 !important;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection