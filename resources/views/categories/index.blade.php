{{-- resources/views/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')
@section('page-subtitle', 'Kelola kategori untuk inventaris barang')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Kategori</li>
@endsection

@section('page-actions')
<a href="{{ route('categories.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Tambah Kategori
</a>
@endsection


@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            @if($categories->count() > 0)
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Kategori</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->count() }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Kategori Aktif</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $categories->where('inventory_items_count', '>', 0)->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Kategori Kosong</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $categories->where('inventory_items_count', 0)->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Item</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $categories->sum('inventory_items_count') }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Categories Table Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tags me-2"></i>Daftar Kategori
                    </h6>
                    <span class="badge bg-primary">{{ $categories->count() }} Kategori</span>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 25%">Nama Kategori</th>
                                        <th style="width: 40%">Deskripsi</th>
                                        <th style="width: 15%" class="text-center">Jumlah Item</th>
                                        <th style="width: 15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $index => $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-tag text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 font-weight-bold">{{ $category->name }}</h6>
                                                        <small class="text-muted">ID: {{ $category->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($category->description)
                                                    <span class="text-muted">{{ Str::limit($category->description, 80) }}</span>
                                                @else
                                                    <em class="text-muted">Tidak ada deskripsi</em>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info fs-6">
                                                    {{ $category->inventory_items_count }} item
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('categories.edit', $category) }}" 
                                                       class="btn btn-outline-warning btn-sm" 
                                                       title="Edit Kategori">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($category->inventory_items_count == 0)
                                                        <form action="{{ route('categories.destroy', $category) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $category->name }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-outline-danger btn-sm" 
                                                                    title="Hapus Kategori">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-outline-secondary btn-sm" 
                                                                title="Tidak dapat menghapus kategori yang memiliki item"
                                                                disabled>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-tags fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Belum ada kategori</h5>
                            <p class="text-muted mb-4">Mulai dengan menambahkan kategori pertama untuk mengorganisir inventaris Anda.</p>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Kategori Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush