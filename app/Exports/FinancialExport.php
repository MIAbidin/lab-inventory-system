<?php

namespace App\Exports;

use App\Models\InventoryItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FinancialExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Ringkasan' => new FinancialSummarySheet(),
            'Per Kategori' => new FinancialCategorySheet(),
            'Item Nilai Tinggi' => new HighValueItemsSheet(),
        ];
    }
}

class FinancialSummarySheet implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return collect([
            (object) [
                'metric' => 'Item dengan Harga',
                'value' => InventoryItem::whereNotNull('purchase_price')->where('purchase_price', '>', 0)->count(),
                'unit' => 'Item'
            ],
            (object) [
                'metric' => 'Item tanpa Harga',
                'value' => InventoryItem::whereNull('purchase_price')->orWhere('purchase_price', 0)->count(),
                'unit' => 'Item'
            ],
            (object) [
                'metric' => 'Rata-rata Nilai Item',
                'value' => InventoryItem::whereNotNull('purchase_price')->where('purchase_price', '>', 0)->avg('purchase_price'),
                'unit' => 'Rupiah'
            ],
            (object) [
                'metric' => 'Item Termahal',
                'value' => InventoryItem::max('purchase_price'),
                'unit' => 'Rupiah'
            ],
            (object) [
                'metric' => 'Item Termurah',
                'value' => InventoryItem::where('purchase_price', '>', 0)->min('purchase_price'),
                'unit' => 'Rupiah'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Metrik',
            'Nilai',
            'Satuan'
        ];
    }

    public function map($item): array
    {
        $value = $item->value;
        if ($item->unit === 'Rupiah' && $value) {
            $value = 'Rp ' . number_format($value, 0, ',', '.');
        }
        
        return [
            $item->metric,
            $value ?? 0,
            $item->unit
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => '007bff']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'A:C' => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]
        ];
    }
}

class FinancialCategorySheet implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return InventoryItem::join('categories', 'inventory_items.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category_name, 
                        SUM(COALESCE(inventory_items.purchase_price, 0)) as total_value, 
                        COUNT(inventory_items.id) as item_count,
                        AVG(COALESCE(inventory_items.purchase_price, 0)) as avg_price')
            ->whereNotNull('inventory_items.purchase_price')
            ->where('inventory_items.purchase_price', '>', 0)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_value', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kategori',
            'Jumlah Item',
            'Total Nilai',
            'Rata-rata per Item',
            'Persentase'
        ];
    }

    public function map($item): array
    {
        static $counter = 0;
        static $totalValue = null;
        
        if ($totalValue === null) {
            $totalValue = InventoryItem::whereNotNull('purchase_price')->sum('purchase_price');
        }
        
        $counter++;
        $percentage = $totalValue > 0 ? ($item->total_value / $totalValue) * 100 : 0;

        return [
            $counter,
            $item->category_name,
            $item->item_count,
            'Rp ' . number_format($item->total_value, 0, ',', '.'),
            'Rp ' . number_format($item->avg_price, 0, ',', '.'),
            number_format($percentage, 1) . '%'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => '28a745']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'A:F' => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'C:C' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'D:E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]],
            'F:F' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]
        ];
    }
}

class HighValueItemsSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return InventoryItem::with('category')
            ->whereNotNull('purchase_price')
            ->where('purchase_price', '>', 0)
            ->orderBy('purchase_price', 'desc')
            ->take(20)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Ranking',
            'Kode Inventaris',
            'Nama Item',
            'Kategori',
            'Brand',
            'Model',
            'Serial Number',
            'Harga Beli',
            'Status',
            'Kondisi',
            'Lokasi'
        ];
    }

    public function map($item): array
    {
        static $counter = 0;
        $counter++;

        $statusLabels = [
            'available' => 'Tersedia',
            'in_use' => 'Sedang Digunakan',
            'maintenance' => 'Dalam Perbaikan',
            'disposed' => 'Dibuang'
        ];

        $conditionLabels = [
            'good' => 'Baik',
            'need_repair' => 'Perlu Perbaikan',
            'broken' => 'Rusak'
        ];

        return [
            $counter,
            $item->inventory_code,
            $item->name,
            $item->category->name ?? '-',
            $item->brand,
            $item->model ?? '-',
            $item->serial_number ?? '-',
            'Rp ' . number_format($item->purchase_price, 0, ',', '.'),
            $statusLabels[$item->status] ?? $item->status,
            $conditionLabels[$item->condition] ?? $item->condition,
            $item->location ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'ffc107']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'A:K' => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'H:H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]]
        ];
    }
}