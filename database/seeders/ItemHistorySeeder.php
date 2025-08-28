<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemHistory;
use App\Models\InventoryItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ItemHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada users dan inventory items
        $users = User::all();
        $inventoryItems = InventoryItem::all();
        
        if ($users->isEmpty() || $inventoryItems->isEmpty()) {
            $this->command->warn('Users atau InventoryItems tidak ditemukan. Silakan jalankan seeder tersebut terlebih dahulu.');
            return;
        }

        DB::beginTransaction();
        
        try {
            // Array untuk menyimpan berbagai jenis aksi (sesuai dengan enum di schema)
            $actions = ['created', 'updated', 'status_changed', 'deleted'];
            $statusOptions = ['available', 'in_use', 'maintenance', 'disposed'];
            $conditionOptions = ['good', 'need_repair', 'broken'];
            $locations = [
                'Ruang Admin - Lantai 1',
                'Ruang IT - Lantai 2', 
                'Ruang Manager',
                'Meeting Room A',
                'Meeting Room B',
                'Gudang IT',
                'Reception Area',
                'Open Office Area',
                'Server Room',
                'Security Room',
                'Parkiran Kantor',
                'Ruang Archive'
            ];

            foreach ($inventoryItems as $item) {
                $currentDate = Carbon::parse($item->purchase_date);
                
                // 1. History pembuatan item
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => $users->random()->id,
                    'action' => 'created',
                    'notes' => 'Item inventaris baru ditambahkan ke sistem dengan kode: ' . $item->inventory_code,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);

                // 2. Generate random history events untuk setiap item
                $numEvents = rand(3, 8); // 3-8 events per item
                
                for ($i = 0; $i < $numEvents; $i++) {
                    $currentDate = $currentDate->copy()->addDays(rand(7, 45));
                    $action = $actions[array_rand($actions)];
                    $user = $users->random();
                    
                    $historyData = [
                        'item_id' => $item->id,
                        'user_id' => $user->id,
                        'action' => $action,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    // Customize berdasarkan jenis action
                    switch ($action) {
                        case 'status_changed':
                            $oldStatus = $statusOptions[array_rand($statusOptions)];
                            $newStatus = $statusOptions[array_rand($statusOptions)];
                            // Pastikan old dan new status berbeda
                            while ($oldStatus === $newStatus) {
                                $newStatus = $statusOptions[array_rand($statusOptions)];
                            }
                            $historyData['field_changed'] = 'status';
                            $historyData['old_value'] = $oldStatus;
                            $historyData['new_value'] = $newStatus;
                            $historyData['notes'] = "Status item diubah dari '{$oldStatus}' menjadi '{$newStatus}' oleh {$user->name}";
                            break;
                            
                        case 'updated':
                            // Berbagai jenis update field
                            $updateTypes = [
                                [
                                    'field' => 'condition',
                                    'old' => $conditionOptions[array_rand($conditionOptions)],
                                    'new' => $conditionOptions[array_rand($conditionOptions)],
                                    'note' => 'Kondisi item diperbarui setelah pemeriksaan'
                                ],
                                [
                                    'field' => 'location',
                                    'old' => $locations[array_rand($locations)],
                                    'new' => $locations[array_rand($locations)],
                                    'note' => 'Lokasi item dipindahkan'
                                ],
                                [
                                    'field' => 'purchase_price',
                                    'old' => number_format(rand(500000, 2000000)),
                                    'new' => number_format(rand(500000, 2000000)),
                                    'note' => 'Koreksi harga pembelian berdasarkan dokumen'
                                ],
                                [
                                    'field' => 'warranty_period',
                                    'old' => rand(12, 36) . ' bulan',
                                    'new' => rand(12, 36) . ' bulan',
                                    'note' => 'Update informasi warranty berdasarkan vendor'
                                ],
                                [
                                    'field' => 'specifications',
                                    'old' => 'Spesifikasi lama',
                                    'new' => 'Spesifikasi diperbarui',
                                    'note' => 'Update spesifikasi dan informasi teknis'
                                ]
                            ];
                            
                            $updateType = $updateTypes[array_rand($updateTypes)];
                            $historyData['field_changed'] = $updateType['field'];
                            $historyData['old_value'] = $updateType['old'];
                            $historyData['new_value'] = $updateType['new'];
                            $historyData['notes'] = $updateType['note'] . " dari '{$updateType['old']}' menjadi '{$updateType['new']}'";
                            break;
                            
                        case 'deleted':
                            // Hanya untuk beberapa item yang mungkin di-soft delete
                            if (rand(1, 10) <= 2) { // 20% chance
                                $historyData['notes'] = 'Item dihapus dari sistem karena rusak total/tidak dapat diperbaiki atau sudah tidak digunakan lagi';
                            } else {
                                // Skip delete action untuk kebanyakan item
                                continue 2;
                            }
                            break;
                            
                        default: // created
                            $historyData['notes'] = 'Item inventaris ditambahkan ke sistem';
                    }
                    
                    // Create each history record individually to avoid column mismatch
                    ItemHistory::create($historyData);
                }

                // 3. Tambahkan beberapa history spesifik berdasarkan jenis item
                $this->addSpecificHistoryByCategory($item, $users, $currentDate);
            }

            // 4. Tambahkan beberapa history global/sistem
            $this->addSystemHistories($users, $inventoryItems);

            DB::commit();
            
            $this->command->info('ItemHistorySeeder completed successfully!');
            $this->command->info('Total histories created: ' . ItemHistory::count());
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('Error in ItemHistorySeeder: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Add specific histories based on item category
     */
    private function addSpecificHistoryByCategory($item, $users, $baseDate)
    {
        $categoryName = $item->category->name ?? '';
        $user = $users->random();
        $date = $baseDate->copy()->addDays(rand(1, 30));

        // Create individual history records based on category
        switch ($categoryName) {
            case 'Komputer & Laptop':
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'action' => 'updated',
                    'field_changed' => 'software_version',
                    'old_value' => 'Windows 10',
                    'new_value' => 'Windows 11',
                    'notes' => 'Update sistem operasi dan software security patches. System restart required.',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                break;

            case 'Printer & Scanner':
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'action' => 'updated',
                    'field_changed' => 'maintenance_status',
                    'old_value' => 'cartridge_low',
                    'new_value' => 'cartridge_replaced',
                    'notes' => 'Penggantian cartridge tinta/toner. Print quality check completed.',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                break;

            case 'Kendaraan':
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'action' => 'updated',
                    'field_changed' => 'service_status',
                    'old_value' => 'due_service',
                    'new_value' => 'service_completed',
                    'notes' => 'Service rutin kendaraan: ganti oli, check rem, tune up engine.',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                break;

            case 'Networking':
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'action' => 'updated',
                    'field_changed' => 'configuration',
                    'old_value' => 'config_v1.0',
                    'new_value' => 'config_v1.1',
                    'notes' => 'Update konfigurasi network settings dan security protocols.',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                break;

            case 'Audio Visual':
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'action' => 'updated',
                    'field_changed' => 'calibration_status',
                    'old_value' => 'needs_calibration',
                    'new_value' => 'calibrated',
                    'notes' => 'Kalibrasi audio/video settings untuk optimal performance.',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                break;

            case 'Peralatan Keamanan':
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                    'action' => 'updated',
                    'field_changed' => 'security_check_status',
                    'old_value' => 'pending_check',
                    'new_value' => 'check_completed',
                    'notes' => 'Pemeriksaan sistem keamanan dan testing alarm/monitoring functions.',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                break;
        }
    }

    /**
     * Add system-wide histories
     */
    private function addSystemHistories($users, $inventoryItems)
    {
        $systemHistories = [
            [
                'item_id' => $inventoryItems->random()->id,
                'user_id' => $users->first()->id, // Admin user
                'action' => 'updated',
                'field_changed' => 'audit_status',
                'old_value' => 'pending_audit',
                'new_value' => 'audit_completed',
                'notes' => 'Audit inventaris bulanan - pengecekan kondisi dan lokasi semua item.',
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'item_id' => $inventoryItems->random()->id,
                'user_id' => $users->first()->id,
                'action' => 'updated',
                'field_changed' => 'policy_version',
                'old_value' => 'policy_v1.0',
                'new_value' => 'policy_v1.1',
                'notes' => 'Update policy penggunaan inventaris dan prosedur maintenance.',
                'created_at' => now()->subDays(60),
                'updated_at' => now()->subDays(60),
            ],
            [
                'item_id' => $inventoryItems->random()->id,
                'user_id' => $users->first()->id,
                'action' => 'updated',
                'field_changed' => 'reconciliation_status',
                'old_value' => 'pending',
                'new_value' => 'completed',
                'notes' => 'Rekonsiliasi data inventaris dengan sistem akuntansi.',
                'created_at' => now()->subDays(90),
                'updated_at' => now()->subDays(90),
            ]
        ];

        // Create each system history individually
        foreach ($systemHistories as $historyData) {
            ItemHistory::create($historyData);
        }
    }
}