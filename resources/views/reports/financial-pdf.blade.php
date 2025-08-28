{{-- resources/views/reports/financial-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Finansial Inventaris</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 20px;
            margin: 0 0 5px 0;
            font-weight: bold;
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
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        
        .info-value {
            color: #007bff;
            font-weight: bold;
        }
        
        .section-title {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            margin: 20px 0 10px 0;
            font-size: 12px;
            font-weight: bold;
            border-radius: 3px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 3px;
        }
        
        .stat-label {
            font-size: 8px;
            color: #6c757d;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        table, th, td {
            border: 1px solid #dee2e6;
        }
        
        th {
            background-color: #f8f9fa;
            color: #495057;
            padding: 8px 5px;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        
        td {
            padding: 5px;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        
        .money {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #28a745;
        }
        
        .code {
            font-family: 'Courier New', monospace;
            background-color: #e9ecef;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 8px;
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        
        .badge-primary { background-color: #007bff; color: white; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        
        .progress-bar {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin: 2px 0;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #007bff;
            border-radius: 4px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 8px;
            border-top: 2px solid #dee2e6;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .summary-box {
            background-color: #e7f3ff;
            border: 2px solid #007bff;
            border-radius: 5px;
            padding: 12px;
            margin: 15px 0;
        }
        
        .highlight {
            background-color: #fff3cd;
            padding: 3px 6px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN FINANSIAL INVENTARIS</h1>
        <h2>Lab Computer Inventory System (LCIS)</h2>
    </div>

    <!-- Report Info -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Tanggal Laporan:</span>
                <span class="info-value">{{ $generated_at }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Jenis Laporan:</span>
                <span class="info-value">{{ $report_type }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Nilai Aset:</span>
                <span class="info-value">Rp {{ number_format($statistics['total_value'], 0, ',', '.') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Item:</span>
                <span class="info-value">{{ number_format($statistics['total_items']) }} item</span>
            </div>
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="summary-box">
        <h3 style="color: #007bff; margin-top: 0; font-size: 12px;">RINGKASAN EKSEKUTIF</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <div>
                <strong>Nilai Investasi:</strong><br>
                Total aset senilai <span class="highlight">Rp {{ number_format($statistics['total_value'], 0, ',', '.') }}</span>
                tersebar di {{ $statistics['items_with_price'] }} item.
            </div>
            <div>
                <strong>Rata-rata Nilai:</strong><br>
                Setiap item bernilai rata-rata <span class="highlight">Rp {{ number_format($statistics['avg_item_value'], 0, ',', '.') }}</span>
                dengan range Rp {{ number_format($statistics['least_expensive'], 0, ',', '.') }} - Rp {{ number_format($statistics['most_expensive'], 0, ',', '.') }}.
            </div>
        </div>
        @if($statistics['items_without_price'] > 0)
        <div style="margin-top: 10px; color: #856404; background-color: #fff3cd; padding: 5px; border-radius: 3px;">
            <strong>Perhatian:</strong> {{ $statistics['items_without_price'] }} item belum memiliki data harga pembelian.
        </div>
        @endif
    </div>

    <!-- Statistics Overview -->
    <div class="section-title">
        <i class="fas fa-chart-bar"></i> STATISTIK KEUANGAN
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">Rp {{ number_format($statistics['total_value'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Nilai Aset</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $statistics['items_with_price'] }}</div>
            <div class="stat-label">Item dengan Harga</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">Rp {{ number_format($statistics['avg_item_value'], 0, ',', '.') }}</div>
            <div class="stat-label">Rata-rata per Item</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $statistics['items_without_price'] }}</div>
            <div class="stat-label">Item Tanpa Harga</div>
        </div>
    </div>

    <!-- Category Breakdown -->
    @if($categoryValues->count() > 0)
    <div class="section-title">
        <i class="fas fa-layer-group"></i> BREAKDOWN NILAI PER KATEGORI
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Kategori</th>
                <th style="width: 10%">Jumlah Item</th>
                <th style="width: 20%">Total Nilai</th>
                <th style="width: 20%">Rata-rata per Item</th>
                <th style="width: 20%">Persentase dari Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryValues as $index => $category)
                @php
                    $percentage = $totalValue > 0 ? ($category->total_value / $totalValue) * 100 : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $category->category_name }}</strong></td>
                    <td class="text-center">{{ number_format($category->item_count) }}</td>
                    <td class="text-end money">Rp {{ number_format($category->total_value, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($category->avg_price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                        </div>
                        <strong>{{ number_format($percentage, 1) }}%</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #e9ecef; font-weight: bold;">
            <tr>
                <td colspan="2" class="text-center"><strong>TOTAL</strong></td>
                <td class="text-center">{{ number_format($categoryValues->sum('item_count')) }}</td>
                <td class="text-end money">Rp {{ number_format($categoryValues->sum('total_value'), 0, ',', '.') }}</td>
                <td colspan="2" class="text-center">-</td>
            </tr>
        </tfoot>
    </table>
    @endif

    <!-- Yearly Analysis -->
    @if($yearlyPurchases->count() > 0)
    <div class="section-title">
        <i class="fas fa-chart-line"></i> ANALISIS PEMBELIAN PER TAHUN
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 15%">Tahun</th>
                <th style="width: 15%">Jumlah Item</th>
                <th style="width: 25%">Total Pengeluaran</th>
                <th style="width: 25%">Rata-rata per Item</th>
                <th style="width: 20%">% dari Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($yearlyPurchases as $year)
                @php
                    $yearPercentage = $totalValue > 0 ? ($year->total_spent / $totalValue) * 100 : 0;
                @endphp
                <tr>
                    <td class="text-center"><strong>{{ $year->year }}</strong></td>
                    <td class="text-center">{{ number_format($year->item_count) }}</td>
                    <td class="text-end money">Rp {{ number_format($year->total_spent, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($year->avg_price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($yearPercentage, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot style="background-color: #e9ecef; font-weight: bold;">
            <tr>
                <td class="text-center"><strong>TOTAL</strong></td>
                <td class="text-center">{{ number_format($yearlyPurchases->sum('item_count')) }}</td>
                <td class="text-end money">Rp {{ number_format($yearlyPurchases->sum('total_spent'), 0, ',', '.') }}</td>
                <td colspan="2" class="text-center">-</td>
            </tr>
        </tfoot>
    </table>
    @endif

    <!-- High Value Items -->
    @if($highValueItems->count() > 0)
    <div class="section-title">
        <i class="fas fa-star"></i> 10 ITEM DENGAN NILAI TERTINGGI
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 8%">Ranking</th>
                <th style="width: 15%">Kode Inventaris</th>
                <th style="width: 20%">Nama Item</th>
                <th style="width: 12%">Kategori</th>
                <th style="width: 15%">Brand/Model</th>
                <th style="width: 15%">Harga Beli</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($highValueItems as $index => $item)
                <tr>
                    <td class="text-center">
                        @if($index < 3)
                            <span class="badge badge-warning">🏆 {{ $index + 1 }}</span>
                        @else
                            <span class="badge badge-primary">{{ $index + 1 }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="code">{{ $item->inventory_code }}</span>
                    </td>
                    <td>
                        <strong>{{ $item->name }}</strong>
                        @if($item->serial_number)
                            <br><small style="color: #6c757d;">SN: {{ $item->serial_number }}</small>
                        @endif
                    </td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>
                        {{ $item->brand }}
                        @if($item->model)
                            <br><small style="color: #6c757d;">{{ $item->model }}</small>
                        @endif
                    </td>
                    <td class="text-end money">
                        Rp {{ number_format($item->purchase_price, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @php
                            $statusClass = match($item->status) {
                                'available' => 'badge-success',
                                'in_use' => 'badge-info',
                                'maintenance' => 'badge-warning',
                                'disposed' => 'badge-danger',
                                default => 'badge-primary'
                            };
                            $statusLabel = match($item->status) {
                                'available' => 'Tersedia',
                                'in_use' => 'Digunakan',
                                'maintenance' => 'Maintenance',
                                'disposed' => 'Dibuang',
                                default => $item->status
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Summary Analysis -->
    <div class="section-title">
        <i class="fas fa-clipboard-list"></i> ANALISIS & REKOMENDASI
    </div>
    
    <div style="background-color: #f8f9fa; padding: 12px; border-radius: 5px; border-left: 4px solid #007bff;">
        <h4 style="color: #007bff; margin-top: 0; font-size: 11px;">Kesimpulan Finansial:</h4>
        <ul style="margin: 5px 0; font-size: 9px;">
            <li><strong>Total investasi:</strong> Rp {{ number_format($statistics['total_value'], 0, ',', '.') }} tersebar di {{ $statistics['items_with_price'] }} item</li>
            @if($categoryValues->count() > 0)
                <li><strong>Kategori terbesar:</strong> {{ $categoryValues->first()->category_name }} 
                    (Rp {{ number_format($categoryValues->first()->total_value, 0, ',', '.') }})</li>
            @endif
            <li><strong>Nilai rata-rata per item:</strong> Rp {{ number_format($statistics['avg_item_value'], 0, ',', '.') }}</li>
            @if($statistics['items_without_price'] > 0)
                <li style="color: #856404;"><strong>Perlu perhatian:</strong> {{ $statistics['items_without_price'] }} item belum memiliki data harga</li>
            @endif
        </ul>
        
        <h4 style="color: #007bff; margin: 10px 0 5px 0; font-size: 11px;">Rekomendasi:</h4>
        <ul style="margin: 5px 0; font-size: 9px;">
            @if($statistics['items_without_price'] > 0)
                <li>Lengkapi data harga untuk {{ $statistics['items_without_price'] }} item yang belum memiliki harga</li>
            @endif
            <li>Lakukan review berkala terhadap aset dengan nilai tinggi untuk memastikan kondisi optimal</li>
            <li>Pertimbangkan asuransi untuk item dengan nilai di atas Rp {{ number_format($statistics['avg_item_value'] * 3, 0, ',', '.') }}</li>
            <li>Buat jadwal replacement planning untuk item yang mendekati end-of-life</li>
        </ul>
    </div>

    @if($categoryValues->count() == 0)
    <div class="no-data">
        <p><strong>Tidak ada data finansial yang tersedia.</strong></p>
        <p>Pastikan item inventaris memiliki data harga pembelian untuk menghasilkan laporan finansial yang akurat.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Lab Computer Inventory System (LCIS) - Laporan Finansial</strong></p>
        <p>Dibuat pada: {{ $generated_at }} | Sistem Inventory Laboratorium Komputer</p>
        <p style="font-size: 7px; color: #999;">
            Data dalam laporan ini bersifat rahasia dan hanya untuk keperluan internal laboratorium.
        </p>
    </div>
</body>
</html>