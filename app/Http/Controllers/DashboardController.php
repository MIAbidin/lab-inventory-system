<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Category;
use App\Models\ItemHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic statistics
        $totalItems = InventoryItem::count();
        $availableItems = InventoryItem::where('status', 'available')->count();
        $maintenanceItems = InventoryItem::where('status', 'maintenance')->count();
        $brokenItems = InventoryItem::where('condition', 'broken')->count();

        // Category statistics for chart
        $categoryStats = Category::withCount('inventoryItems')
            ->having('inventory_items_count', '>', 0)
            ->get();

        // Recent activities (last 5)
        $recentActivities = ItemHistory::with(['item', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Recent items added (last 5)
        $recentItems = InventoryItem::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Additional statistics for alerts
        $inUseItems = InventoryItem::where('status', 'in_use')->count();
        $disposedItems = InventoryItem::where('status', 'disposed')->count();
        $needRepairItems = InventoryItem::where('condition', 'need_repair')->count();

        return view('dashboard', compact(
            'totalItems',
            'availableItems', 
            'maintenanceItems',
            'brokenItems',
            'inUseItems',
            'disposedItems',
            'needRepairItems',
            'categoryStats',
            'recentActivities',
            'recentItems'
        ));
    }

    /**
     * Get dashboard statistics for AJAX requests
     */
    public function getStats()
    {
        $stats = [
            'total_items' => InventoryItem::count(),
            'available_items' => InventoryItem::where('status', 'available')->count(),
            'maintenance_items' => InventoryItem::where('status', 'maintenance')->count(),
            'broken_items' => InventoryItem::where('condition', 'broken')->count(),
            'in_use_items' => InventoryItem::where('status', 'in_use')->count(),
            'need_repair_items' => InventoryItem::where('condition', 'need_repair')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get category distribution data for charts
     */
    public function getCategoryStats()
    {
        $categoryStats = Category::withCount('inventoryItems')
            ->having('inventory_items_count', '>', 0)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->inventory_items_count
                ];
            });

        return response()->json($categoryStats);
    }
}