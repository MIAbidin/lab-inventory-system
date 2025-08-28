<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris Laboratorium</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 18px;
            margin: 0 0 5px 0;
        }
        
        .header h2 {
            color: #6c757d;
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
            display: inline-block;
        }
        
        /* Statistics Cards Styles */
        .statistics-section {
            margin-bottom: 25px;
        }
        
        .statistics-row {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 0 5px;
            vertical-align: top;
        }
        
        .stat-card-inner {
            border: 2px solid;
            border-radius: 8px;
            padding: 12px 8px;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card.total .stat-card-inner {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        
        .stat-card.available .stat-card-inner {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        
        .stat-card.maintenance .stat-card-inner {
            border-color: #ffc107;
            background-color: #fffef8;
        }
        
        .stat-card.broken .stat-card-inner {
            border-color: #dc3545;
            background-color: #fff8f8;
        }
        
        .stat-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .stat-card.total .stat-title {
            color: #007bff;
        }
        
        .stat-card.available .stat-title {
            color: #28a745;
        }
        
        .stat-card.maintenance .stat-title {
            color: #ffc107;
        }
        
        .stat-card.broken .stat-title {
            color: #dc3545;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin: 8px 0;
            line-height: 1;
        }
        
        .stat-card.total .stat-number {
            color: #007bff;
        }
        
        .stat-card.available .stat-number {
            color: #28a745;
        }
        
        .stat-card.maintenance .stat-number {
            color: #ffc107;
        }
        
        .stat-card.broken .stat-number {
            color: #dc3545;
        }
        
        .stat-icon {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .filters {
            margin-bottom: 20px;
        }
        
        .filters h3 {
            color: #495057;
            font-size: 12px;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .filter-item {
            display: inline-block;
            background-color: #e9ecef;
            padding: 3px 8px;
            margin: 2px;
            border-radius: 3px;
            font-size: 9px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table, th, td {
            border: 1px solid #dee2e6;
        }
        
        th {
            background-color: #007bff;
            color: white;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        
        td {
            padding: 4px;
            vertical-align: top;
            font-size: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .code {
            font-family: 'Courier New', monospace;
            background-color: #e9ecef;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7px;
        }
        
        .price {
            text-align: right;
            font-weight: bold;
        }
        
        .status {
            text-align: center;
        }
        
        .status-good {
            background-color: #d4edda;
            color: #155724;
            padding: 1px 4px;
            border-radius: 2px;
        }
        
        .status-warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 1px 4px;
            border-radius: 2px;
        }
        
        .status-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1px 4px;
            border-radius: 2px;
        }
        
        .status-info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 1px 4px;
            border-radius: 2px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 8px;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN INVENTARIS LABORATORIUM KOMPUTER</h1>
        <h2>Lab Computer Inventory System (LCIS)</h2>
    </div>

    <!-- Report Info -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Tanggal Dibuat:</span>
            <span>{{ $generated_at }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Item:</span>
            <span>{{ number_format($total_items) }} item</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Nilai:</span>
            <span>Rp {{ number_format($total_value, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="statistics-section">
        <div class="statistics-row">
            <div class="stat-card total">
                <div class="stat-card-inner">
                    <div class="stat-icon">📦</div>
                    <div class="stat-title">Total Item</div>
                    <div class="stat-number">{{ number_format($total_items) }}</div>
                </div>
            </div>
            <div class="stat-card available">
                <div class="stat-card-inner">
                    <div class="stat-icon">✓</div>
                    <div class="stat-title">Kondisi Baik</div>
                    <div class="stat-number">
                        @php
                            $goodCondition = $items->where('condition', 'good')->count();
                        @endphp
                        {{ number_format($goodCondition) }}
                    </div>
                </div>
            </div>
            <div class="stat-card maintenance">
                <div class="stat-card-inner">
                    <div class="stat-icon">🔧</div>
                    <div class="stat-title">Maintenance</div>
                    <div class="stat-number">
                        @php
                            $maintenanceItems = $items->where('condition', 'need_repair')->count();
                        @endphp
                        {{ number_format($maintenanceItems) }}
                    </div>
                </div>
            </div>
            <div class="stat-card broken">
                <div class="stat-card-inner">
                    <div class="stat-icon">⚠</div>
                    <div class="stat-title">Rusak</div>
                    <div class="stat-number">
                        @php
                            $brokenItems = $items->where('condition', 'broken')->count();
                        @endphp
                        {{ number_format($brokenItems) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Applied -->
    @if(count($filters) > 0)
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        @foreach($filters as $filter)
            <span class="filter-item">{{ $filter }}</span>
        @endforeach
    </div>
    @endif

    <!-- Data Table -->
    @if($items->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 12%">Kode Inventaris</th>
                    <th style="width: 15%">Nama Item</th>
                    <th style="width: 10%">Kategori</th>
                    <th style="width: 12%">Merk/Model</th>
                    <th style="width: 8%">Tgl Beli</th>
                    <th style="width: 10%">Harga</th>
                    <th style="width: 10%">Kondisi</th>
                    <th style="width: 8%">Status</th>
                    <th style="width: 10%">Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td><span class="code">{{ $item->inventory_code }}</span></td>
                    <td>
                        <strong>{{ $item->name }}</strong>
                        @if($item->serial_number)
                            <br><small>SN: {{ $item->serial_number }}</small>
                        @endif
                    </td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>
                        {{ $item->brand }}
                        @if($item->model)
                            <br>{{ $item->model }}
                        @endif
                    </td>
                    <td style="text-align: center">
                        {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}
                    </td>
                    <td class="price">
                        @if($item->purchase_price)
                            Rp {{ number_format($item->purchase_price, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="status">
                        @php
                            $conditionClass = match($item->condition) {
                                'good' => 'status-good',
                                'need_repair' => 'status-warning',
                                'broken' => 'status-danger',
                                default => ''
                            };
                            $conditionLabel = match($item->condition) {
                                'good' => 'Baik',
                                'need_repair' => 'Perlu Perbaikan',
                                'broken' => 'Rusak',
                                default => $item->condition
                            };
                        @endphp
                        <span class="{{ $conditionClass }}">{{ $conditionLabel }}</span>
                    </td>
                    <td class="status">
                        @php
                            $statusClass = match($item->status) {
                                'available' => 'status-good',
                                'in_use' => 'status-info',
                                'maintenance' => 'status-warning',
                                'disposed' => 'status-danger',
                                default => ''
                            };
                            $statusLabel = match($item->status) {
                                'available' => 'Tersedia',
                                'in_use' => 'Digunakan',
                                'maintenance' => 'Maintenance',
                                'disposed' => 'Dibuang',
                                default => $item->status
                            };
                        @endphp
                        <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>{{ $item->location ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data inventaris yang sesuai dengan filter yang diterapkan.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Lab Computer Inventory System (LCIS)</p>
        <p>Dicetak pada: {{ $generated_at }}</p>
        @if(count($filters) > 0)
            <p>Filter: {{ implode(' | ', $filters) }}</p>
        @endif
    </div>
</body>
</html>