<!-- resources/views/reports/partials/table.blade.php -->
@if($items->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Kode</th>
                    <th width="15%">Nama Item</th>
                    <th width="10%">Kategori</th>
                    <th width="10%">Merk/Model</th>
                    <th width="10%">Tanggal Beli</th>
                    <th width="12%">Harga</th>
                    <th width="8%">Kondisi</th>
                    <th width="8%">Status</th>
                    <th width="12%">Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $items->firstItem() + $index }}</td>
                        <td><small class="text-primary font-monospace">{{ $item->inventory_code }}</small></td>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if($item->serial_number)
                                <br><small class="text-muted">SN: {{ $item->serial_number }}</small>
                            @endif
                        </td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>
                            {{ $item->brand }}
                            @if($item->model)
                                <br><small class="text-muted">{{ $item->model }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            @if($item->purchase_price)
                                Rp {{ number_format($item->purchase_price, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                @switch($item->condition)
                                    @case('good') bg-success @break
                                    @case('need_repair') bg-warning @break
                                    @case('broken') bg-danger @break
                                    @default bg-secondary
                                @endswitch">
                                @switch($item->condition)
                                    @case('good') Baik @break
                                    @case('need_repair') Perlu Perbaikan @break
                                    @case('broken') Rusak @break
                                    @default {{ $item->condition }}
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <span class="badge 
                                @switch($item->status)
                                    @case('available') bg-success @break
                                    @case('in_use') bg-info @break
                                    @case('maintenance') bg-warning @break
                                    @case('disposed') bg-danger @break
                                    @default bg-secondary
                                @endswitch">
                                @switch($item->status)
                                    @case('available') Tersedia @break
                                    @case('in_use') Digunakan @break
                                    @case('maintenance') Maintenance @break
                                    @case('disposed') Dibuang @break
                                    @default {{ $item->status }}
                                @endswitch
                            </span>
                        </td>
                        <td>{{ $item->location ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Tidak ada data yang ditemukan</h5>
        <p class="text-muted">Coba ubah filter pencarian atau tambah item inventaris baru.</p>
        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Item
        </a>
    </div>
@endif