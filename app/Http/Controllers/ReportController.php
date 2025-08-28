<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Category;
use App\Exports\InventoryExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::with('category');

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('inventory_code', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('serial_number', 'like', '%' . $search . '%');
            });
        }

        // Handle per_page parameter - PERBAIKAN UTAMA
        $perPage = $request->get('per_page', 10);
        
        // Debug: Log nilai yang diterima
        \Log::info('Per page received: ' . var_export($perPage, true) . ' (type: ' . gettype($perPage) . ')');
        
        // Konversi ke integer dan validasi
        $perPage = (int) $perPage;
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        
        \Log::info('Per page after processing: ' . $perPage);

        $items = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Append all query parameters to pagination links
        $items->appends($request->all());
        
        $categories = Category::all();

        // Statistics for summary
        $totalItems = InventoryItem::count();
        $availableItems = InventoryItem::where('status', 'available')->count();
        $inMaintenanceItems = InventoryItem::where('status', 'maintenance')->count();
        $brokenItems = InventoryItem::where('condition', 'broken')->count();

        $statistics = [
            'total' => $totalItems,
            'available' => $availableItems,
            'maintenance' => $inMaintenanceItems,
            'broken' => $brokenItems
        ];

        // Handle AJAX requests for stats refresh
        if ($request->ajax() && $request->has('refresh_stats')) {
            return response()->json(['statistics' => $statistics]);
        }

        return view('reports.index', compact('items', 'categories', 'statistics'));
    }

    public function exportPdf(Request $request)
    {
        $query = InventoryItem::with('category');

        // Apply same filters as index
        $this->applyFilters($query, $request);

        $items = $query->orderBy('created_at', 'desc')->get();
        
        // Get filter summary for PDF header
        $filterSummary = $this->getFilterSummary($request);

        $data = [
            'items' => $items,
            'filters' => $filterSummary,
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'total_items' => $items->count(),
            'total_value' => $items->sum('purchase_price')
        ];

        $pdf = PDF::loadView('reports.pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-inventaris-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new InventoryExport($request->all()), 
            'laporan-inventaris-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function conditionReport()
    {
        $conditionStats = InventoryItem::selectRaw('condition, COUNT(*) as count')
            ->groupBy('condition')
            ->get();

        $statusStats = InventoryItem::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $categoryStats = InventoryItem::join('categories', 'inventory_items.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, COUNT(*) as count, 
                        SUM(CASE WHEN condition = "broken" THEN 1 ELSE 0 END) as broken_count,
                        SUM(CASE WHEN condition = "need_repair" THEN 1 ELSE 0 END) as repair_count')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Items that need attention
        $needMaintenanceItems = InventoryItem::with('category')
            ->where('condition', 'need_repair')
            ->orWhere('status', 'maintenance')
            ->orderBy('purchase_date', 'asc')
            ->get();

        $brokenItems = InventoryItem::with('category')
            ->where('condition', 'broken')
            ->orderBy('purchase_date', 'asc')
            ->get();

        return view('reports.condition', compact(
            'conditionStats', 
            'statusStats', 
            'categoryStats', 
            'needMaintenanceItems', 
            'brokenItems'
        ));
    }

    private function applyFilters($query, $request)
    {
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('inventory_code', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('serial_number', 'like', '%' . $search . '%');
            });
        }
    }

    private function getFilterSummary($request)
    {
        $filters = [];

        if ($request->filled('category')) {
            $category = Category::find($request->category);
            $filters[] = 'Kategori: ' . ($category ? $category->name : 'Tidak diketahui');
        }

        if ($request->filled('condition')) {
            $conditionLabels = [
                'good' => 'Baik',
                'need_repair' => 'Perlu Perbaikan',
                'broken' => 'Rusak'
            ];
            $filters[] = 'Kondisi: ' . ($conditionLabels[$request->condition] ?? $request->condition);
        }

        if ($request->filled('status')) {
            $statusLabels = [
                'available' => 'Tersedia',
                'in_use' => 'Sedang Digunakan',
                'maintenance' => 'Dalam Perbaikan',
                'disposed' => 'Dibuang'
            ];
            $filters[] = 'Status: ' . ($statusLabels[$request->status] ?? $request->status);
        }

        if ($request->filled('location')) {
            $filters[] = 'Lokasi: ' . $request->location;
        }

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $dateRange = 'Periode Pembelian: ';
            if ($request->filled('date_from')) {
                $dateRange .= date('d/m/Y', strtotime($request->date_from));
            }
            if ($request->filled('date_to')) {
                $dateRange .= ' - ' . date('d/m/Y', strtotime($request->date_to));
            }
            $filters[] = $dateRange;
        }

        if ($request->filled('search')) {
            $filters[] = 'Pencarian: ' . $request->search;
        }

        return $filters;
    }

    // Financial report methods (unchanged)
    public function financialReport(Request $request)
    {
        try {
            // Total nilai inventaris
            $totalValue = InventoryItem::whereNotNull('purchase_price')->sum('purchase_price');
            
            // Breakdown per kategori
            $categoryValues = InventoryItem::join('categories', 'inventory_items.category_id', '=', 'categories.id')
                ->selectRaw('categories.name as category_name, 
                            SUM(COALESCE(inventory_items.purchase_price, 0)) as total_value, 
                            COUNT(inventory_items.id) as item_count,
                            AVG(COALESCE(inventory_items.purchase_price, 0)) as avg_price')
                ->whereNotNull('inventory_items.purchase_price')
                ->where('inventory_items.purchase_price', '>', 0)
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('total_value', 'desc')
                ->get();
            
            // Analisis per tahun pembelian
            $yearlyPurchases = InventoryItem::selectRaw('YEAR(purchase_date) as year, 
                                                    COUNT(*) as item_count, 
                                                    SUM(COALESCE(purchase_price, 0)) as total_spent,
                                                    AVG(COALESCE(purchase_price, 0)) as avg_price')
                ->whereNotNull('purchase_date')
                ->whereNotNull('purchase_price')
                ->where('purchase_price', '>', 0)
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get();
            
            // Analisis per bulan (12 bulan terakhir)
            $monthlyData = InventoryItem::selectRaw('DATE_FORMAT(purchase_date, "%Y-%m") as month,
                                                COUNT(*) as item_count,
                                                SUM(COALESCE(purchase_price, 0)) as total_spent')
                ->whereNotNull('purchase_date')
                ->whereNotNull('purchase_price')
                ->where('purchase_price', '>', 0)
                ->where('purchase_date', '>=', now()->subYear())
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();
            
            // Item dengan nilai tertinggi
            $highValueItems = InventoryItem::with('category')
                ->whereNotNull('purchase_price')
                ->where('purchase_price', '>', 0)
                ->orderBy('purchase_price', 'desc')
                ->take(10)
                ->get();
            
            // Item tanpa harga (untuk tracking)
            $itemsWithoutPrice = InventoryItem::whereNull('purchase_price')
                ->orWhere('purchase_price', 0)
                ->count();
            
            // Statistik umum
            $statistics = [
                'total_value' => $totalValue,
                'total_items' => InventoryItem::count(),
                'items_with_price' => InventoryItem::whereNotNull('purchase_price')->where('purchase_price', '>', 0)->count(),
                'items_without_price' => $itemsWithoutPrice,
                'avg_item_value' => InventoryItem::whereNotNull('purchase_price')->where('purchase_price', '>', 0)->avg('purchase_price'),
                'most_expensive' => InventoryItem::max('purchase_price'),
                'least_expensive' => InventoryItem::where('purchase_price', '>', 0)->min('purchase_price')
            ];
            
            return view('reports.financial', compact(
                'totalValue',
                'categoryValues', 
                'yearlyPurchases',
                'monthlyData',
                'highValueItems',
                'statistics'
            ));
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Financial Report Error: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan finansial: ' . $e->getMessage());
        }
    }

    public function exportFinancialPdf(Request $request)
    {
        try {
            // Total nilai inventaris
            $totalValue = InventoryItem::whereNotNull('purchase_price')->sum('purchase_price');
            
            // Breakdown per kategori
            $categoryValues = InventoryItem::join('categories', 'inventory_items.category_id', '=', 'categories.id')
                ->selectRaw('categories.name as category_name, 
                            SUM(COALESCE(inventory_items.purchase_price, 0)) as total_value, 
                            COUNT(inventory_items.id) as item_count,
                            AVG(COALESCE(inventory_items.purchase_price, 0)) as avg_price')
                ->whereNotNull('inventory_items.purchase_price')
                ->where('inventory_items.purchase_price', '>', 0)
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('total_value', 'desc')
                ->get();
            
            // Analisis per tahun pembelian
            $yearlyPurchases = InventoryItem::selectRaw('YEAR(purchase_date) as year, 
                                                    COUNT(*) as item_count, 
                                                    SUM(COALESCE(purchase_price, 0)) as total_spent,
                                                    AVG(COALESCE(purchase_price, 0)) as avg_price')
                ->whereNotNull('purchase_date')
                ->whereNotNull('purchase_price')
                ->where('purchase_price', '>', 0)
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get();
            
            // Item dengan nilai tertinggi
            $highValueItems = InventoryItem::with('category')
                ->whereNotNull('purchase_price')
                ->where('purchase_price', '>', 0)
                ->orderBy('purchase_price', 'desc')
                ->take(10)
                ->get();
            
            // Item tanpa harga
            $itemsWithoutPrice = InventoryItem::whereNull('purchase_price')
                ->orWhere('purchase_price', 0)
                ->count();
            
            // Statistik umum
            $statistics = [
                'total_value' => $totalValue,
                'total_items' => InventoryItem::count(),
                'items_with_price' => InventoryItem::whereNotNull('purchase_price')->where('purchase_price', '>', 0)->count(),
                'items_without_price' => $itemsWithoutPrice,
                'avg_item_value' => InventoryItem::whereNotNull('purchase_price')->where('purchase_price', '>', 0)->avg('purchase_price'),
                'most_expensive' => InventoryItem::max('purchase_price'),
                'least_expensive' => InventoryItem::where('purchase_price', '>', 0)->min('purchase_price')
            ];

            $data = [
                'totalValue' => $totalValue,
                'categoryValues' => $categoryValues,
                'yearlyPurchases' => $yearlyPurchases,
                'highValueItems' => $highValueItems,
                'statistics' => $statistics,
                'generated_at' => now()->format('d/m/Y H:i:s'),
                'report_type' => 'Laporan Finansial'
            ];

            $pdf = PDF::loadView('reports.financial-pdf', $data);
            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->download('laporan-finansial-' . date('Y-m-d') . '.pdf');
            
        } catch (\Exception $e) {
            \Log::error('Financial PDF Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    public function exportFinancialExcel(Request $request)
    {
        try {
            return Excel::download(
                new FinancialExport(), 
                'laporan-finansial-' . date('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            \Log::error('Financial Excel Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat Excel: ' . $e->getMessage());
        }
    }
}