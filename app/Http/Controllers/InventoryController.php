<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Category;
use App\Models\ItemHistory;
use App\Http\Requests\StoreInventoryItemRequest;
use App\Http\Requests\UpdateInventoryItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('inventory_code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 15); // Default to 15 if not set

        // Validate per_page to prevent abuse
        $allowedPerPage = [15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $items = $query->latest()->paginate($perPage);
        $categories = Category::all();

        return view('inventory.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('inventory.create', compact('categories'));
    }

    public function store(StoreInventoryItemRequest $request)
    {
        $data = $request->validated();
        
        // Use database transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Handle image upload first
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                
                // Simple image storage without resizing
                $path = $image->storeAs('public/inventory', $filename);
                $data['image_path'] = 'inventory/' . $filename;
            }

            // SOLUTION 1: Generate temporary inventory code first
            // Get next available ID by checking the highest existing ID
            $nextId = InventoryItem::max('id') + 1;
            $tempInventoryCode = $this->generateInventoryCode($data['category_id'], $nextId);
            
            // Add inventory_code to data before creating
            $data['inventory_code'] = $tempInventoryCode;

            // Create the item with inventory code
            $item = InventoryItem::create($data);
            
            // If the actual ID differs from predicted ID, regenerate the code
            if ($item->id != $nextId) {
                $actualInventoryCode = $this->generateInventoryCode($data['category_id'], $item->id);
                $item->update(['inventory_code' => $actualInventoryCode]);
                
                // Use the actual code for logging
                $finalInventoryCode = $actualInventoryCode;
            } else {
                $finalInventoryCode = $tempInventoryCode;
            }

            // Log history
            ItemHistory::create([
                'item_id' => $item->id,
                'user_id' => auth()->id(),
                'action' => 'created',
                'notes' => 'Item created with inventory code: ' . $finalInventoryCode
            ]);

            DB::commit();

            return redirect()->route('inventory.index')
                ->with('success', 'Item berhasil ditambahkan dengan kode inventaris: ' . $finalInventoryCode);
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Clean up uploaded image if transaction fails
            if (isset($data['image_path'])) {
                Storage::disk('public')->delete($data['image_path']);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan item: ' . $e->getMessage());
        }
    }

    /**
     * Generate inventory code with format: mmddyy(category_id)(id)
     * Example: 250225010001 = Feb 25, 2025, category 01, item 0001
     */
    private function generateInventoryCode($categoryId, $itemId)
    {
        $currentDate = now();
        
        // Format: mmddyy
        $month = $currentDate->format('m');
        $day = $currentDate->format('d');
        $year = $currentDate->format('y');
        $datePrefix = $month . $day . $year;
        
        // Category ID (2 digits with leading zero)
        $categoryCode = str_pad($categoryId, 2, '0', STR_PAD_LEFT);
        
        // Item ID (4 digits with leading zeros)
        $itemCode = str_pad($itemId, 4, '0', STR_PAD_LEFT);
        
        $inventoryCode = $datePrefix . $categoryCode . $itemCode;
        
        // Ensure uniqueness (though it should be unique by design)
        $counter = 1;
        $originalCode = $inventoryCode;
        while (InventoryItem::where('inventory_code', $inventoryCode)->where('id', '!=', $itemId)->exists()) {
            // If somehow duplicate exists, append counter to item code
            $itemCode = str_pad($itemId + $counter, 4, '0', STR_PAD_LEFT);
            $inventoryCode = $datePrefix . $categoryCode . $itemCode;
            $counter++;
        }
        
        return $inventoryCode;
    }

    public function show(InventoryItem $inventory)
    {
        $inventory->load(['category', 'histories.user']);
        return view('inventory.show', compact('inventory'));
    }

    public function edit(InventoryItem $inventory)
    {
        $categories = Category::all();
        return view('inventory.edit', compact('inventory', 'categories'));
    }

    public function update(UpdateInventoryItemRequest $request, InventoryItem $inventory)
    {
        $data = $request->validated();
        $oldData = $inventory->toArray();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($inventory->image_path) {
                Storage::disk('public')->delete($inventory->image_path);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            
            // Simple image storage without resizing
            $path = $image->storeAs('public/inventory', $filename);
            $data['image_path'] = 'inventory/' . $filename;
        }

        // Handle remove image checkbox
        if ($request->has('remove_image') && $request->remove_image) {
            if ($inventory->image_path) {
                Storage::disk('public')->delete($inventory->image_path);
                $data['image_path'] = null;
            }
        }

        // If category is changed, regenerate inventory code
        if (isset($data['category_id']) && $data['category_id'] != $inventory->category_id) {
            $data['inventory_code'] = $this->generateInventoryCode($data['category_id'], $inventory->id);
            
            // Log inventory code change
            ItemHistory::create([
                'item_id' => $inventory->id,
                'user_id' => auth()->id(),
                'action' => 'inventory_code_changed',
                'field_changed' => 'inventory_code',
                'old_value' => $inventory->inventory_code,
                'new_value' => $data['inventory_code'],
                'notes' => 'Category changed, inventory code updated'
            ]);
        }

        $inventory->update($data);

        // Log significant changes
        $changedFields = array_diff_assoc($data, $oldData);
        foreach ($changedFields as $field => $newValue) {
            if (in_array($field, ['status', 'condition'])) {
                ItemHistory::create([
                    'item_id' => $inventory->id,
                    'user_id' => auth()->id(),
                    'action' => 'status_changed',
                    'field_changed' => $field,
                    'old_value' => $oldData[$field] ?? null,
                    'new_value' => $newValue,
                ]);
            }
        }

        // Log update notes if provided
        if ($request->filled('update_notes')) {
            ItemHistory::create([
                'item_id' => $inventory->id,
                'user_id' => auth()->id(),
                'action' => 'updated',
                'notes' => $request->update_notes
            ]);
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Item berhasil diupdate.');
    }

    public function destroy(InventoryItem $inventory)
    {
        // Log deletion
        ItemHistory::create([
            'item_id' => $inventory->id,
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'notes' => 'Item deleted with inventory code: ' . $inventory->inventory_code
        ]);

        // Delete image
        if ($inventory->image_path) {
            Storage::disk('public')->delete($inventory->image_path);
        }

        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Item berhasil dihapus.');
    }

    /**
     * Resize image using GD library (alternative to Intervention Image)
     */
    private function resizeImage($sourcePath, $destinationPath, $maxWidth = 800, $maxHeight = 600)
    {
        $imageInfo = getimagesize($sourcePath);
        $mime = $imageInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);

        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and GIF
        if ($mime == 'image/png' || $mime == 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resize
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($newImage, $destinationPath, 90);
                break;
            case 'image/png':
                imagepng($newImage, $destinationPath);
                break;
            case 'image/gif':
                imagegif($newImage, $destinationPath);
                break;
        }

        // Clean up
        imagedestroy($image);
        imagedestroy($newImage);

        return true;
    }
}