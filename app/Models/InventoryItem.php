<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'inventory_code',
        'name',
        'category_id',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'condition',
        'status',
        'location',
        'specifications',
        'image_path',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function histories()
    {
        return $this->hasMany(ItemHistory::class, 'item_id');
    }

    // Generate unique inventory code with format: mmddyy(category_id)(id)
    // Example: 250225010001 = Feb 25, 2025, category 01, item 0001
    public static function generateInventoryCode($categoryId)
    {
        // Create temporary item to get the next ID
        $tempItem = new self();
        $tempItem->fill([
            'name' => 'temp',
            'category_id' => $categoryId,
            'condition' => 'good',
            'status' => 'available'
        ]);
        $tempItem->save();
        
        $nextId = $tempItem->id;
        $currentDate = now();
        
        // Format: mmddyy
        $datePrefix = $currentDate->format('mdY');
        $datePrefix = substr($datePrefix, 0, 2) . substr($datePrefix, 2, 2) . substr($datePrefix, 4, 2);
        
        // Category ID (2 digits with leading zero)
        $categoryCode = str_pad($categoryId, 2, '0', STR_PAD_LEFT);
        
        // Item ID (4 digits with leading zeros)
        $itemCode = str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        $inventoryCode = $datePrefix . $categoryCode . $itemCode;
        
        // Update the temporary item with the correct inventory code
        $tempItem->update(['inventory_code' => $inventoryCode]);
        
        // Delete the temporary item since we only needed the ID
        $tempItem->delete();
        
        return $inventoryCode;
    }

    // Alternative method that doesn't create temporary records
    public static function generateInventoryCodeV2($categoryId)
    {
        $currentDate = now();
        
        // Format: mmddyy
        $month = $currentDate->format('m');
        $day = $currentDate->format('d');
        $year = $currentDate->format('y');
        $datePrefix = $month . $day . $year;
        
        // Category ID (2 digits with leading zero)
        $categoryCode = str_pad($categoryId, 2, '0', STR_PAD_LEFT);
        
        // Get the next ID by finding the highest ID and adding 1
        $lastId = self::max('id') ?? 0;
        $nextId = $lastId + 1;
        
        // Item ID (4 digits with leading zeros)
        $itemCode = str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        $inventoryCode = $datePrefix . $categoryCode . $itemCode;
        
        // Check if this code already exists (rare edge case)
        while (self::where('inventory_code', $inventoryCode)->exists()) {
            $nextId++;
            $itemCode = str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $inventoryCode = $datePrefix . $categoryCode . $itemCode;
        }
        
        return $inventoryCode;
    }

    // Get condition badge class
    public function getConditionBadgeAttribute()
    {
        return match($this->condition) {
            'good' => 'bg-success',
            'need_repair' => 'bg-warning',
            'broken' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    // Get status badge class
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'available' => 'bg-success',
            'in_use' => 'bg-info',
            'maintenance' => 'bg-warning',
            'disposed' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    // Helper method to decode inventory code information
    public function getInventoryCodeInfoAttribute()
    {
        $code = $this->inventory_code;
        if (strlen($code) !== 12) {
            return null;
        }

        $month = substr($code, 0, 2);
        $day = substr($code, 2, 2);
        $year = '20' . substr($code, 4, 2);
        $categoryId = intval(substr($code, 6, 2));
        $itemNumber = intval(substr($code, 8, 4));

        return [
            'date' => $month . '/' . $day . '/' . $year,
            'category_id' => $categoryId,
            'item_number' => $itemNumber
        ];
    }
}
