<?php

namespace App\Exports;

use App\Models\InventoryItem;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InventoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents, WithTitle
{
    protected $filters;
    protected $statistics;
    protected $filterSummary;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->calculateStatistics();
        $this->generateFilterSummary();
    }

    public function collection()
    {
        $query = InventoryItem::with('category');
        $this->applyFilters($query);
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function title(): string
    {
        return 'Laporan Inventaris';
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Inventaris',
            'Nama Item',
            'Kategori',
            'Merk',
            'Model',
            'Serial Number',
            'Tanggal Pembelian',
            'Harga Pembelian (Rp)',
            'Kondisi',
            'Status',
            'Lokasi',
            'Spesifikasi',
            'Keterangan',
            'Tanggal Dibuat',
            'Terakhir Diupdate'
        ];
    }

    public function map($item): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $item->inventory_code ?? '-',
            $item->name ?? '-',
            $item->category->name ?? '-',
            $item->brand ?? '-',
            $item->model ?? '-',
            $item->serial_number ?? '-',
            $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-',
            $item->purchase_price ?? 0,
            $this->getConditionLabel($item->condition),
            $this->getStatusLabel($item->status),
            $item->location ?? '-',
            $item->specifications ?? '-',
            $item->notes ?? '-',
            $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-',
            $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->collection()->count() + 1;
        $lastColumn = 'P'; // Column P for the last column (16th column)

        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => '2E86AB']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            
            // All data styling
            "A1:{$lastColumn}{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],

            // Number column (No) - center alignment
            'A:A' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['bold' => true],
            ],

            // Inventory code column - center alignment
            'B:B' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name' => 'Courier New',
                    'size' => 10,
                ],
            ],

            // Price column - right alignment and currency format
            'I:I' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0',
                ],
                'font' => ['bold' => true],
            ],

            // Date columns - center alignment
            'H:H' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],

            'O:P' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['size' => 9],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->collection()->count() + 1;
                
                // Set row height for header
                $sheet->getRowDimension('1')->setRowHeight(25);
                
                // Set column widths
                $columnWidths = [
                    'A' => 8,   // No
                    'B' => 15,  // Kode
                    'C' => 25,  // Nama
                    'D' => 15,  // Kategori
                    'E' => 15,  // Merk
                    'F' => 15,  // Model
                    'G' => 18,  // Serial
                    'H' => 12,  // Tanggal Beli
                    'I' => 18,  // Harga
                    'J' => 12,  // Kondisi
                    'K' => 12,  // Status
                    'L' => 20,  // Lokasi
                    'M' => 30,  // Spesifikasi
                    'N' => 25,  // Keterangan
                    'O' => 15,  // Created
                    'P' => 15,  // Updated
                ];

                foreach ($columnWidths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Add alternating row colors
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle("A{$row}:P{$row}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('F8F9FA');
                    }
                }

                // Add conditional formatting for conditions
                $this->addConditionalFormattingForConditions($sheet, $lastRow);
                $this->addConditionalFormattingForStatus($sheet, $lastRow);
                
                // Add summary information at the top
                $this->addSummaryInfo($sheet);
                
                // Freeze header row
                $sheet->freezePane('A2');
            },
        ];
    }

    private function addConditionalFormattingForConditions($sheet, $lastRow)
    {
        for ($row = 2; $row <= $lastRow; $row++) {
            $conditionCell = $sheet->getCell("J{$row}");
            $conditionValue = $conditionCell->getValue();
            
            switch ($conditionValue) {
                case 'Baik':
                    $conditionCell->getStyle()->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('D4EDDA');
                    $conditionCell->getStyle()->getFont()->getColor()->setARGB('155724');
                    break;
                case 'Perlu Perbaikan':
                    $conditionCell->getStyle()->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFF3CD');
                    $conditionCell->getStyle()->getFont()->getColor()->setARGB('856404');
                    break;
                case 'Rusak':
                    $conditionCell->getStyle()->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('F8D7DA');
                    $conditionCell->getStyle()->getFont()->getColor()->setARGB('721C24');
                    break;
            }
        }
    }

    private function addConditionalFormattingForStatus($sheet, $lastRow)
    {
        for ($row = 2; $row <= $lastRow; $row++) {
            $statusCell = $sheet->getCell("K{$row}");
            $statusValue = $statusCell->getValue();
            
            switch ($statusValue) {
                case 'Tersedia':
                    $statusCell->getStyle()->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('D4EDDA');
                    $statusCell->getStyle()->getFont()->getColor()->setARGB('155724');
                    break;
                case 'Sedang Digunakan':
                    $statusCell->getStyle()->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('D1ECF1');
                    $statusCell->getStyle()->getFont()->getColor()->setARGB('0C5460');
                    break;
                case 'Dalam Perbaikan':
                    $statusCell->getStyle()->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFF3CD');
                    $statusCell->getStyle()->getFont()->getColor()->setARGB('856404');
                    break;
                case 'Dibuang':
                    $statusCell->getStyle()->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('F8D7DA');
                    $statusCell->getStyle()->getFont()->getColor()->setARGB('721C24');
                    break;
            }
        }
    }

    private function addSummaryInfo($sheet)
    {
        // Insert rows at the top for summary
        $sheet->insertNewRowBefore(1, 8);
        
        // Title
        $sheet->setCellValue('A1', 'LAPORAN INVENTARIS LABORATORIUM KOMPUTER');
        $sheet->mergeCells('A1:P1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => '2E86AB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension('1')->setRowHeight(30);

        // Generation info
        $sheet->setCellValue('A3', 'Tanggal Dibuat:');
        $sheet->setCellValue('B3', now()->format('d/m/Y H:i:s'));
        $sheet->setCellValue('A4', 'Total Item:');
        $sheet->setCellValue('B4', number_format($this->statistics['total']));
        $sheet->setCellValue('A5', 'Total Nilai:');
        $sheet->setCellValue('B5', 'Rp ' . number_format($this->statistics['total_value'], 0, ',', '.'));

        // Filters applied
        if (!empty($this->filterSummary)) {
            $sheet->setCellValue('D3', 'Filter yang Diterapkan:');
            $row = 3;
            foreach ($this->filterSummary as $filter) {
                $sheet->setCellValue('E' . $row, $filter);
                $row++;
            }
        }

        // Statistics summary
        $sheet->setCellValue('H3', 'Statistik:');
        $sheet->setCellValue('I3', 'Tersedia: ' . number_format($this->statistics['available']));
        $sheet->setCellValue('I4', 'Maintenance: ' . number_format($this->statistics['maintenance']));
        $sheet->setCellValue('I5', 'Rusak: ' . number_format($this->statistics['broken']));

        // Style the summary section
        $sheet->getStyle('A3:I6')->applyFromArray([
            'font' => ['size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'F8F9FA'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'DEE2E6'],
                ],
            ],
        ]);

        // Bold labels
        $sheet->getStyle('A3:A5')->getFont()->setBold(true);
        $sheet->getStyle('D3')->getFont()->setBold(true);
        $sheet->getStyle('H3')->getFont()->setBold(true);
    }

    private function applyFilters($query)
    {
        if (!empty($this->filters['category'])) {
            $query->where('category_id', $this->filters['category']);
        }

        if (!empty($this->filters['condition'])) {
            $query->where('condition', $this->filters['condition']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['location'])) {
            $query->where('location', 'like', '%' . $this->filters['location'] . '%');
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('purchase_date', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('purchase_date', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('inventory_code', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('serial_number', 'like', '%' . $search . '%');
            });
        }
    }

    private function calculateStatistics()
    {
        $query = InventoryItem::query();
        $this->applyFilters($query);
        
        $items = $query->get();
        
        $this->statistics = [
            'total' => $items->count(),
            'available' => $items->where('status', 'available')->count(),
            'maintenance' => $items->where('status', 'maintenance')->count(),
            'broken' => $items->where('condition', 'broken')->count(),
            'total_value' => $items->sum('purchase_price') ?? 0,
        ];
    }

    private function generateFilterSummary()
    {
        $filters = [];

        if (!empty($this->filters['category'])) {
            $category = Category::find($this->filters['category']);
            $filters[] = 'Kategori: ' . ($category ? $category->name : 'Tidak diketahui');
        }

        if (!empty($this->filters['condition'])) {
            $conditionLabels = [
                'good' => 'Baik',
                'need_repair' => 'Perlu Perbaikan',
                'broken' => 'Rusak'
            ];
            $filters[] = 'Kondisi: ' . ($conditionLabels[$this->filters['condition']] ?? $this->filters['condition']);
        }

        if (!empty($this->filters['status'])) {
            $statusLabels = [
                'available' => 'Tersedia',
                'in_use' => 'Sedang Digunakan',
                'maintenance' => 'Dalam Perbaikan',
                'disposed' => 'Dibuang'
            ];
            $filters[] = 'Status: ' . ($statusLabels[$this->filters['status']] ?? $this->filters['status']);
        }

        if (!empty($this->filters['location'])) {
            $filters[] = 'Lokasi: ' . $this->filters['location'];
        }

        if (!empty($this->filters['date_from']) || !empty($this->filters['date_to'])) {
            $dateRange = 'Periode Pembelian: ';
            if (!empty($this->filters['date_from'])) {
                $dateRange .= date('d/m/Y', strtotime($this->filters['date_from']));
            }
            if (!empty($this->filters['date_to'])) {
                $dateRange .= ' - ' . date('d/m/Y', strtotime($this->filters['date_to']));
            }
            $filters[] = $dateRange;
        }

        if (!empty($this->filters['search'])) {
            $filters[] = 'Pencarian: ' . $this->filters['search'];
        }

        $this->filterSummary = $filters;
    }

    private function getConditionLabel($condition)
    {
        return match($condition) {
            'good' => 'Baik',
            'need_repair' => 'Perlu Perbaikan',
            'broken' => 'Rusak',
            default => $condition ?? '-'
        };
    }

    private function getStatusLabel($status)
    {
        return match($status) {
            'available' => 'Tersedia',
            'in_use' => 'Sedang Digunakan',
            'maintenance' => 'Dalam Perbaikan',
            'disposed' => 'Dibuang',
            default => $status ?? '-'
        };
    }
}