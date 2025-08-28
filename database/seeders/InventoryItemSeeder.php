<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;
use App\Models\Category;
use App\Models\ItemHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        
        try {
            $categories = Category::all()->keyBy('name');
            
            $inventoryData = [
                // 1. Komputer & Laptop
                [
                    'name' => 'Laptop ASUS VivoBook 14 A1400EA',
                    'category_id' => $categories['Komputer & Laptop']->id,
                    'brand' => 'ASUS',
                    'model' => 'VivoBook 14 A1400EA',
                    'serial_number' => 'A1400EA001',
                    'purchase_date' => '2024-01-15',
                    'purchase_price' => 8500000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Admin - Lantai 1',
                    'specifications' => 'Intel Core i3-1115G4, 8GB DDR4 RAM, 512GB SSD, Intel UHD Graphics, 14" FHD, Windows 11 Home',
                    'notes' => 'Laptop untuk staff administrasi'
                ],
                [
                    'name' => 'Laptop Dell Inspiron 15 3515',
                    'category_id' => $categories['Komputer & Laptop']->id,
                    'brand' => 'Dell',
                    'model' => 'Inspiron 15 3515',
                    'serial_number' => 'DL3515001',
                    'purchase_date' => '2024-02-10',
                    'purchase_price' => 7200000,
                    'condition' => 'good',
                    'status' => 'available',
                    'location' => 'Gudang IT',
                    'specifications' => 'AMD Ryzen 3 3250U, 4GB DDR4 RAM, 256GB SSD, AMD Radeon Graphics, 15.6" HD, Windows 11 Home',
                    'notes' => 'Laptop cadangan untuk staff'
                ],
                [
                    'name' => 'PC Desktop HP Pavilion TP01',
                    'category_id' => $categories['Komputer & Laptop']->id,
                    'brand' => 'HP',
                    'model' => 'Pavilion TP01-2000',
                    'serial_number' => 'HPTP01001',
                    'purchase_date' => '2024-01-20',
                    'purchase_price' => 9500000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Accounting',
                    'specifications' => 'Intel Core i5-10400F, 8GB DDR4 RAM, 1TB HDD, NVIDIA GT730 2GB, DVD-RW, Windows 11 Home',
                    'notes' => 'PC untuk tim accounting'
                ],
                [
                    'name' => 'MacBook Air M2 13 inch',
                    'category_id' => $categories['Komputer & Laptop']->id,
                    'brand' => 'Apple',
                    'model' => 'MacBook Air M2',
                    'serial_number' => 'MBA2022001',
                    'purchase_date' => '2024-03-05',
                    'purchase_price' => 18500000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Creative',
                    'specifications' => 'Apple M2 Chip, 8GB Unified Memory, 256GB SSD, 13.6" Liquid Retina Display, macOS',
                    'notes' => 'Laptop untuk tim kreatif dan design'
                ],

                // 2. Printer & Scanner
                [
                    'name' => 'Printer Canon PIXMA G2010',
                    'category_id' => $categories['Printer & Scanner']->id,
                    'brand' => 'Canon',
                    'model' => 'PIXMA G2010',
                    'serial_number' => 'CNG2010001',
                    'purchase_date' => '2024-01-25',
                    'purchase_price' => 2100000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Admin',
                    'specifications' => 'Inkjet All-in-One, Print/Scan/Copy, 4800x1200 dpi, USB 2.0, A4 Size',
                    'notes' => 'Printer untuk kebutuhan sehari-hari'
                ],
                [
                    'name' => 'Printer HP LaserJet Pro MFP M428fdw',
                    'category_id' => $categories['Printer & Scanner']->id,
                    'brand' => 'HP',
                    'model' => 'LaserJet Pro MFP M428fdw',
                    'serial_number' => 'HPM428001',
                    'purchase_date' => '2024-02-15',
                    'purchase_price' => 6500000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Operasional',
                    'specifications' => 'Laser Monochrome MFP, Print/Scan/Copy/Fax, 38ppm, Duplex, WiFi, Ethernet',
                    'notes' => 'Printer multifungsi untuk volume tinggi'
                ],
                [
                    'name' => 'Scanner Epson Perfection V39',
                    'category_id' => $categories['Printer & Scanner']->id,
                    'brand' => 'Epson',
                    'model' => 'Perfection V39',
                    'serial_number' => 'EPV39001',
                    'purchase_date' => '2024-02-20',
                    'purchase_price' => 1250000,
                    'condition' => 'good',
                    'status' => 'available',
                    'location' => 'Gudang IT',
                    'specifications' => 'Flatbed Scanner, 4800x9600 dpi, USB 2.0, A4 Size',
                    'notes' => 'Scanner khusus untuk dokumen'
                ],

                // 3. Networking
                [
                    'name' => 'Router TP-Link Archer C6',
                    'category_id' => $categories['Networking']->id,
                    'brand' => 'TP-Link',
                    'model' => 'Archer C6',
                    'serial_number' => 'TPC6001',
                    'purchase_date' => '2024-01-30',
                    'purchase_price' => 650000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Server',
                    'specifications' => 'AC1200 Dual Band, 4x Gigabit LAN, 1x Gigabit WAN, 4 Antena, MU-MIMO',
                    'notes' => 'Router utama untuk jaringan kantor'
                ],
                [
                    'name' => 'Switch Netgear GS108',
                    'category_id' => $categories['Networking']->id,
                    'brand' => 'Netgear',
                    'model' => 'GS108',
                    'serial_number' => 'NGS108001',
                    'purchase_date' => '2024-02-05',
                    'purchase_price' => 450000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Server',
                    'specifications' => '8-Port Gigabit Ethernet Switch, Unmanaged, Metal Case, Fanless',
                    'notes' => 'Switch untuk ekspansi jaringan'
                ],
                [
                    'name' => 'Access Point Ubiquiti UniFi AP AC Lite',
                    'category_id' => $categories['Networking']->id,
                    'brand' => 'Ubiquiti',
                    'model' => 'UniFi AP AC Lite',
                    'serial_number' => 'UBACL001',
                    'purchase_date' => '2024-02-10',
                    'purchase_price' => 1200000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Lantai 2 - Area Meeting',
                    'specifications' => 'AC1200 Dual Band, PoE, 2.4GHz/5GHz, MIMO, Ceiling/Wall Mount',
                    'notes' => 'WiFi Access Point untuk lantai 2'
                ],

                // 4. Furniture Kantor
                [
                    'name' => 'Meja Kerja Kantor Minimalis',
                    'category_id' => $categories['Furniture Kantor']->id,
                    'brand' => 'IKEA',
                    'model' => 'LINNMON/ADILS',
                    'serial_number' => 'IK001',
                    'purchase_date' => '2024-01-10',
                    'purchase_price' => 850000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Open Office Area',
                    'specifications' => 'Top meja 120x60cm, kaki adjustable, warna putih, material partikel board',
                    'notes' => 'Meja kerja untuk staff'
                ],
                [
                    'name' => 'Kursi Kantor Ergonomis',
                    'category_id' => $categories['Furniture Kantor']->id,
                    'brand' => 'Funika',
                    'model' => 'Office Chair OF103',
                    'serial_number' => 'FN103001',
                    'purchase_date' => '2024-01-15',
                    'purchase_price' => 1650000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Manager',
                    'specifications' => 'Mesh back, leather seat, adjustable height, armrest, lumbar support',
                    'notes' => 'Kursi manager dengan fitur ergonomis'
                ],
                [
                    'name' => 'Lemari Arsip 4 Laci',
                    'category_id' => $categories['Furniture Kantor']->id,
                    'brand' => 'Olympic',
                    'model' => 'Filing Cabinet FC-4',
                    'serial_number' => 'OLYFC001',
                    'purchase_date' => '2024-01-20',
                    'purchase_price' => 2800000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Archive',
                    'specifications' => 'Baja, 4 laci, central lock, ukuran F4/A4, warna abu-abu',
                    'notes' => 'Penyimpanan dokumen penting'
                ],
                [
                    'name' => 'Sofa Ruang Tamu Kantor',
                    'category_id' => $categories['Furniture Kantor']->id,
                    'brand' => 'Informa',
                    'model' => 'Belluno 3 Seater',
                    'serial_number' => 'INF3S001',
                    'purchase_date' => '2024-02-25',
                    'purchase_price' => 4500000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Reception Area',
                    'specifications' => '3 seater, fabric upholstery, wooden frame, warna coklat muda',
                    'notes' => 'Sofa untuk area resepsionis'
                ],

                // 5. Audio Visual
                [
                    'name' => 'Projector Epson EB-X41',
                    'category_id' => $categories['Audio Visual']->id,
                    'brand' => 'Epson',
                    'model' => 'EB-X41',
                    'serial_number' => 'EPX41001',
                    'purchase_date' => '2024-02-01',
                    'purchase_price' => 6200000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Meeting Room A',
                    'specifications' => '3600 Lumens, XGA 1024x768, 3LCD, HDMI/VGA/USB, Remote Control',
                    'notes' => 'Proyektor untuk presentasi meeting'
                ],
                [
                    'name' => 'Monitor LED ASUS VA24EHE',
                    'category_id' => $categories['Audio Visual']->id,
                    'brand' => 'ASUS',
                    'model' => 'VA24EHE',
                    'serial_number' => 'ASV24001',
                    'purchase_date' => '2024-01-18',
                    'purchase_price' => 1850000,
                    'condition' => 'good',
                    'status' => 'available',
                    'location' => 'Gudang IT',
                    'specifications' => '24" Full HD 1920x1080, IPS Panel, HDMI/VGA, Eye Care, Frameless',
                    'notes' => 'Monitor tambahan untuk workstation'
                ],
                [
                    'name' => 'Speaker Logitech Z313',
                    'category_id' => $categories['Audio Visual']->id,
                    'brand' => 'Logitech',
                    'model' => 'Z313',
                    'serial_number' => 'LGZ313001',
                    'purchase_date' => '2024-02-08',
                    'purchase_price' => 450000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Meeting Room B',
                    'specifications' => '2.1 System, 25W RMS, Subwoofer, 3.5mm input, Volume control',
                    'notes' => 'Speaker untuk audio presentasi'
                ],
                [
                    'name' => 'Microphone Audio-Technica AT2020',
                    'category_id' => $categories['Audio Visual']->id,
                    'brand' => 'Audio-Technica',
                    'model' => 'AT2020',
                    'serial_number' => 'AT2020001',
                    'purchase_date' => '2024-03-01',
                    'purchase_price' => 2100000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Studio Recording',
                    'specifications' => 'Condenser Microphone, Cardioid, XLR output, 144dB SPL, 20-20kHz',
                    'notes' => 'Microphone untuk recording dan webinar'
                ],

                // 6. Kendaraan
                [
                    'name' => 'Toyota Avanza 1.3 G MT',
                    'category_id' => $categories['Kendaraan']->id,
                    'brand' => 'Toyota',
                    'model' => 'Avanza 1.3 G MT',
                    'serial_number' => 'B1234ABC',
                    'purchase_date' => '2023-06-15',
                    'purchase_price' => 235000000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Parkiran Kantor',
                    'specifications' => '1.3L 4A-FE Engine, Manual Transmission, 7 Seater, Metallic Silver',
                    'notes' => 'Kendaraan operasional untuk tim lapangan'
                ],
                [
                    'name' => 'Honda Scoopy 110cc',
                    'category_id' => $categories['Kendaraan']->id,
                    'brand' => 'Honda',
                    'model' => 'Scoopy',
                    'serial_number' => 'B5678DEF',
                    'purchase_date' => '2024-01-12',
                    'purchase_price' => 22500000,
                    'condition' => 'good',
                    'status' => 'available',
                    'location' => 'Parkiran Motor',
                    'specifications' => '110cc eSP Engine, CVT, Electric Starter, Stylish Blue',
                    'notes' => 'Motor untuk kurir dan keperluan urgent'
                ],

                // 7. Alat Tulis & ATK
                [
                    'name' => 'Mesin Laminating Secure Compact A3',
                    'category_id' => $categories['Alat Tulis & ATK']->id,
                    'brand' => 'Secure',
                    'model' => 'Compact A3',
                    'serial_number' => 'SCA3001',
                    'purchase_date' => '2024-02-18',
                    'purchase_price' => 850000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Admin',
                    'specifications' => 'A3 Size, Hot/Cold Lamination, 4 Roller System, ABS adjustment',
                    'notes' => 'Mesin laminating untuk dokumen penting'
                ],
                [
                    'name' => 'Binding Machine GBC CombBind C110',
                    'category_id' => $categories['Alat Tulis & ATK']->id,
                    'brand' => 'GBC',
                    'model' => 'CombBind C110',
                    'serial_number' => 'GBCC110001',
                    'purchase_date' => '2024-01-28',
                    'purchase_price' => 1250000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Ruang Produksi',
                    'specifications' => 'Manual Comb Binding, 12 Sheet Punch, 2" Comb capacity, A4 Size',
                    'notes' => 'Mesin jilid untuk laporan dan dokumen'
                ],

                // 8. Peralatan Keamanan
                [
                    'name' => 'CCTV Camera Hikvision DS-2CE16D0T',
                    'category_id' => $categories['Peralatan Keamanan']->id,
                    'brand' => 'Hikvision',
                    'model' => 'DS-2CE16D0T-IR',
                    'serial_number' => 'HK2CE001',
                    'purchase_date' => '2024-01-05',
                    'purchase_price' => 650000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Entrance - Lobby',
                    'specifications' => '2MP 1080p, IR Night Vision 20m, IP66 Weatherproof, BNC Output',
                    'notes' => 'CCTV untuk monitoring entrance'
                ],
                [
                    'name' => 'DVR Hikvision DS-7104HQHI-K1',
                    'category_id' => $categories['Peralatan Keamanan']->id,
                    'brand' => 'Hikvision',
                    'model' => 'DS-7104HQHI-K1',
                    'serial_number' => 'HK7104001',
                    'purchase_date' => '2024-01-05',
                    'purchase_price' => 1850000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Security Room',
                    'specifications' => '4CH Turbo HD DVR, H.265+, 1080p, HDMI/VGA output, Network storage',
                    'notes' => 'DVR untuk sistem CCTV'
                ],
                [
                    'name' => 'UPS APC Back-UPS BX1400U-MS',
                    'category_id' => $categories['Peralatan Keamanan']->id,
                    'brand' => 'APC',
                    'model' => 'Back-UPS BX1400U-MS',
                    'serial_number' => 'APCBX1400001',
                    'purchase_date' => '2024-02-12',
                    'purchase_price' => 2200000,
                    'condition' => 'good',
                    'status' => 'in_use',
                    'location' => 'Server Room',
                    'specifications' => '1400VA/700W, 6 Outlets, AVR, USB connectivity, LCD Display',
                    'notes' => 'UPS untuk server dan perangkat kritikal'
                ]
            ];

            // Create inventory items dengan inventory code yang benar
            foreach ($inventoryData as $index => $data) {
                // Predict next ID dan generate inventory code
                $nextId = InventoryItem::max('id') + 1;
                $tempInventoryCode = $this->generateInventoryCode($data['category_id'], $nextId, $data['purchase_date']);
                
                // Add inventory_code to data
                $data['inventory_code'] = $tempInventoryCode;
                
                // Create item dengan inventory code
                $item = InventoryItem::create($data);
                
                // Jika actual ID berbeda dari predicted ID, update inventory code
                if ($item->id != $nextId) {
                    $actualInventoryCode = $this->generateInventoryCode($data['category_id'], $item->id, $data['purchase_date']);
                    $item->update(['inventory_code' => $actualInventoryCode]);
                    $finalInventoryCode = $actualInventoryCode;
                } else {
                    $finalInventoryCode = $tempInventoryCode;
                }

                // Create history record
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => 1, // Assuming admin user with ID 1
                    'action' => 'created',
                    'notes' => 'Initial inventory item created via seeder with code: ' . $finalInventoryCode
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Generate inventory code dengan format: mmddyy(category_id)(id)
     */
    private function generateInventoryCode($categoryId, $itemId, $purchaseDate)
    {
        $date = Carbon::parse($purchaseDate);
        
        // Format: mmddyy
        $month = $date->format('m');
        $day = $date->format('d');
        $year = $date->format('y');
        $datePrefix = $month . $day . $year;
        
        // Category ID (2 digits)
        $categoryCode = str_pad($categoryId, 2, '0', STR_PAD_LEFT);
        
        // Item ID (4 digits)
        $itemCode = str_pad($itemId, 4, '0', STR_PAD_LEFT);
        
        return $datePrefix . $categoryCode . $itemCode;
    }
}