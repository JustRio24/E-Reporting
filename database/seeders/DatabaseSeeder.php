<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FacilityCategory;
use App\Models\Location;
use App\Models\Facility;
use App\Models\DamageCategory;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Create Users ──────────────────────────────────────────
        $users = [
            [
                'name' => 'PTBA Admin',
                'email' => 'admin@reporting.com',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'phone' => '081122334455',
                'is_active' => true,
            ],
            [
                'name' => 'PTBA Inspector',
                'email' => 'inspector@reporting.com',
                'password' => Hash::make('password'),
                'role' => UserRole::INSPECTOR,
                'phone' => '081234567890',
                'is_active' => true,
            ],
            [
                'name' => 'PTBA Supervisor',
                'email' => 'supervisor@reporting.com',
                'password' => Hash::make('password'),
                'role' => UserRole::SUPERVISOR,
                'phone' => '082198765432',
                'is_active' => true,
            ],
            [
                'name' => 'PTBA Maintenance Team 1',
                'email' => 'maintenance@reporting.com',
                'password' => Hash::make('password'),
                'role' => UserRole::MAINTENANCE,
                'phone' => '083111222333',
                'is_active' => true,
            ],
            [
                'name' => 'PTBA Maintenance Team 2',
                'email' => 'maintenance2@reporting.com',
                'password' => Hash::make('password'),
                'role' => UserRole::MAINTENANCE,
                'phone' => '083111222334',
                'is_active' => true,
            ],
            [
                'name' => 'Inactive User',
                'email' => 'inactive@reporting.com',
                'password' => Hash::make('password'),
                'role' => UserRole::INSPECTOR,
                'phone' => '089999888777',
                'is_active' => false,
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // ─── Create Facility Categories ─────────────────────────────
        $categories = [
            ['name' => 'Dermaga', 'description' => 'Fasilitas bongkar muat kapal/tongkang batubara.'],
            ['name' => 'Conveyor System', 'description' => 'Sistem ban berjalan untuk transportasi batubara.'],
            ['name' => 'Gudang & Penimbunan', 'description' => 'Area penyimpanan batubara dan material pendukung.'],
            ['name' => 'Kelistrikan & Utilitas', 'description' => 'Sistem kelistrikan, air, bangunan operasional, dan utilitas penunjang.'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = FacilityCategory::create($cat);
        }

        // ─── Create Locations ───────────────────────────────────────
        $locations = [
            [
                'name' => 'Dermaga Kertapati 1',
                'description' => 'Area dermaga utama PT Bukit Asam Kertapati Port.',
                'latitude' => -2.992683,
                'longitude' => 104.731776,
            ],
            [
                'name' => 'Transfer Tower 1 (TT1)',
                'description' => 'Tower transfer jalur conveyor batubara.',
                'latitude' => -2.993412,
                'longitude' => 104.730894,
            ],
            [
                'name' => 'Stockpile Area A',
                'description' => 'Area penimbunan batubara utama bagian barat.',
                'latitude' => -2.994200,
                'longitude' => 104.729900,
            ],
            [
                'name' => 'Gardu Induk Kertapati',
                'description' => 'Pusat distribusi daya listrik pelabuhan.',
                'latitude' => -2.995010,
                'longitude' => 104.728510,
            ]
        ];

        $locationModels = [];
        foreach ($locations as $loc) {
            $locationModels[] = Location::create($loc);
        }

        // ─── Create Facilities ──────────────────────────────────────
        $facilities = [
            // Data Fasilitas Awal
            [
                'facility_code' => 'FAC-DMG-01',
                'facility_name' => 'Jetty Loading Arm 1',
                'facility_category_id' => $categoryModels[0]->id,
                'location_id' => $locationModels[0]->id,
                'description' => 'Lengan pemuat batubara ke tongkang pada Dermaga 1.',
                'latitude' => -2.992600,
                'longitude' => 104.731700,
            ],
            [
                'facility_code' => 'FAC-CVY-01A',
                'facility_name' => 'Conveyor Belt CVY-01A',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Conveyor utama pembawa batubara dari Stockpile ke TT1.',
                'latitude' => -2.993400,
                'longitude' => 104.730800,
            ],
            [
                'facility_code' => 'FAC-STK-01',
                'facility_name' => 'Coal Shed Storage A',
                'facility_category_id' => $categoryModels[2]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Gudang tertutup penimbunan batubara.',
                'latitude' => -2.994100,
                'longitude' => 104.729800,
            ],
            [
                'facility_code' => 'FAC-UTL-GI01',
                'facility_name' => 'Main Transformer 10MVA',
                'facility_category_id' => $categoryModels[3]->id,
                'location_id' => $locationModels[3]->id,
                'description' => 'Trafo utama distribusi kelistrikan pelabuhan.',
                'latitude' => -2.995000,
                'longitude' => 104.728500,
            ],

            // ─── Penambahan Data Fasilitas Baru ──────────────────────────
            [
                'facility_code' => 'FAC-CRU-01',
                'facility_name' => 'Crusher',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id, // Default assign to TT1 or update accordingly
                'description' => 'Fasilitas penghancur batubara.',
                'latitude' => -3.021640,
                'longitude' => 104.746714,
            ],
            [
                'facility_code' => 'FAC-SIZ-01',
                'facility_name' => 'Sizing-station',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Fasilitas pemilahan ukuran batubara.',
                'latitude' => -3.021022,
                'longitude' => 104.747357,
            ],
            [
                'facility_code' => 'FAC-STK-BC03',
                'facility_name' => 'Stacker BC03',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Mesin stacker untuk area BC03.',
                'latitude' => -3.018975,
                'longitude' => 104.748191,
            ],
            [
                'facility_code' => 'FAC-STK-CC03A',
                'facility_name' => 'Stacker CC03 (A)',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Mesin stacker untuk area CC03 (Titik A).',
                'latitude' => -3.018664,
                'longitude' => 104.749134,
            ],
            [
                'facility_code' => 'FAC-AF-56',
                'facility_name' => 'Apron Feeder 5-6',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Sistem apron feeder 5-6.',
                'latitude' => -3.021809,
                'longitude' => 104.747226,
            ],
            [
                'facility_code' => 'FAC-AF-1234',
                'facility_name' => 'Apron Feeder 1-2-3-4',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Sistem apron feeder 1-2-3-4.',
                'latitude' => -3.022084,
                'longitude' => 104.746757,
            ],
            [
                'facility_code' => 'FAC-SHP-ENIM',
                'facility_name' => 'Shiploader Enim',
                'facility_category_id' => $categoryModels[0]->id,
                'location_id' => $locationModels[0]->id,
                'description' => 'Shiploader Enim di area dermaga.',
                'latitude' => -3.018340,
                'longitude' => 104.747853,
            ],
            [
                'facility_code' => 'FAC-SHP-OGAN',
                'facility_name' => 'Shiploader Ogan',
                'facility_category_id' => $categoryModels[0]->id,
                'location_id' => $locationModels[0]->id,
                'description' => 'Shiploader Ogan di area dermaga.',
                'latitude' => -3.017754,
                'longitude' => 104.748201,
            ],
            [
                'facility_code' => 'FAC-PCC-01',
                'facility_name' => 'Pear Control Center',
                'facility_category_id' => $categoryModels[3]->id,
                'location_id' => $locationModels[3]->id,
                'description' => 'Pusat kontrol pear.',
                'latitude' => -3.020112,
                'longitude' => 104.747099,
            ],
            [
                'facility_code' => 'FAC-KPL-1A',
                'facility_name' => 'Kolam Pengendapan Lumpur (KPL) 1 (A)',
                'facility_category_id' => $categoryModels[2]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Kolam pengendapan lumpur titik A.',
                'latitude' => -3.020907,
                'longitude' => 104.746613,
            ],
            [
                'facility_code' => 'FAC-KPL-1B',
                'facility_name' => 'Kolam Pengendapan Lumpur (KPL) 1 (B)',
                'facility_category_id' => $categoryModels[2]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Kolam pengendapan lumpur titik B.',
                'latitude' => -3.019591,
                'longitude' => 104.747425,
            ],
            [
                'facility_code' => 'FAC-BC-03B',
                'facility_name' => 'Belt Conveyor 03B',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Jalur belt conveyor 03B.',
                'latitude' => -3.021273,
                'longitude' => 104.746753,
            ],
            [
                'facility_code' => 'FAC-BC-03',
                'facility_name' => 'Belt Conveyor 03',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Jalur belt conveyor 03.',
                'latitude' => -3.020378,
                'longitude' => 104.747402,
            ],
            [
                'facility_code' => 'FAC-BC-04',
                'facility_name' => 'Belt Conveyor 04',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Jalur belt conveyor 04.',
                'latitude' => -3.018204,
                'longitude' => 104.748266,
            ],
            [
                'facility_code' => 'FAC-BC-05',
                'facility_name' => 'Belt Conveyor 05',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Jalur belt conveyor 05.',
                'latitude' => -3.018689,
                'longitude' => 104.747727,
            ],
            [
                'facility_code' => 'FAC-CC-02',
                'facility_name' => 'CC02',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Conveyor CC02.',
                'latitude' => -3.021364,
                'longitude' => 104.747113,
            ],
            [
                'facility_code' => 'FAC-CC-03',
                'facility_name' => 'CC03',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Conveyor CC03.',
                'latitude' => -3.019562,
                'longitude' => 104.748473,
            ],
            [
                'facility_code' => 'FAC-CC-04',
                'facility_name' => 'CC04',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Conveyor CC04.',
                'latitude' => -3.018330,
                'longitude' => 104.748882,
            ],
            [
                'facility_code' => 'FAC-CC-05',
                'facility_name' => 'CC05',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[1]->id,
                'description' => 'Conveyor CC05.',
                'latitude' => -3.017449,
                'longitude' => 104.748479,
            ],
            [
                'facility_code' => 'FAC-STK-CC03B',
                'facility_name' => 'Stacker CC03 (B)',
                'facility_category_id' => $categoryModels[1]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Mesin stacker untuk area CC03 (Titik B).',
                'latitude' => -3.019286,
                'longitude' => 104.748701,
            ],
            [
                'facility_code' => 'FAC-OFC-OPS',
                'facility_name' => 'Kantor Operasional',
                'facility_category_id' => $categoryModels[3]->id,
                'location_id' => $locationModels[3]->id,
                'description' => 'Fasilitas bangunan Kantor Operasional.',
                'latitude' => -3.018275,
                'longitude' => 104.749498,
            ],
            [
                'facility_code' => 'FAC-OFC-MNT',
                'facility_name' => 'Kantor Perawatan & Workshop',
                'facility_category_id' => $categoryModels[3]->id,
                'location_id' => $locationModels[3]->id,
                'description' => 'Fasilitas perbaikan dan bengkel.',
                'latitude' => -3.018117,
                'longitude' => 104.749124,
            ],
            [
                'facility_code' => 'FAC-GDG-01',
                'facility_name' => 'Gudang',
                'facility_category_id' => $categoryModels[2]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Fasilitas gudang penyimpanan umum.',
                'latitude' => -3.018291,
                'longitude' => 104.749042,
            ],
            [
                'facility_code' => 'FAC-POS-01',
                'facility_name' => 'Pos Keamanan',
                'facility_category_id' => $categoryModels[3]->id,
                'location_id' => $locationModels[3]->id,
                'description' => 'Pos pengamanan dan penjagaan.',
                'latitude' => -3.017936024087559,
                'longitude' => 104.7492798477194,
            ],
            [
                'facility_code' => 'FAC-OFC-K3',
                'facility_name' => 'Kantor K3',
                'facility_category_id' => $categoryModels[3]->id,
                'location_id' => $locationModels[3]->id,
                'description' => 'Bangunan operasional kesehatan dan keselamatan kerja.',
                'latitude' => -3.017896,
                'longitude' => 104.749222,
            ],
            [
                'facility_code' => 'FAC-GDG-K3',
                'facility_name' => 'Gudang K3',
                'facility_category_id' => $categoryModels[2]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Gudang penyimpanan logistik K3.',
                'latitude' => -3.017655,
                'longitude' => 104.749112,
            ],
            [
                'facility_code' => 'FAC-TNK-SLR',
                'facility_name' => 'Tangki Solar',
                'facility_category_id' => $categoryModels[2]->id,
                'location_id' => $locationModels[2]->id,
                'description' => 'Tangki penampungan bahan bakar solar.',
                'latitude' => -3.017457,
                'longitude' => 104.749234,
            ],
            [
                'facility_code' => 'FAC-DLPH-OLD',
                'facility_name' => 'Dolphin Lama',
                'facility_category_id' => $categoryModels[0]->id,
                'location_id' => $locationModels[0]->id,
                'description' => 'Struktur mooring dolphin lama.',
                'latitude' => -3.019288,
                'longitude' => 104.747150,
            ],
            [
                'facility_code' => 'FAC-DLPH-NEW',
                'facility_name' => 'Dolphin Baru',
                'facility_category_id' => $categoryModels[0]->id,
                'location_id' => $locationModels[0]->id,
                'description' => 'Struktur mooring dolphin baru.',
                'latitude' => -3.019711,
                'longitude' => 104.746932,
            ]
        ];

        foreach ($facilities as $fac) {
            Facility::create($fac);
        }

        // ─── Create Damage Categories ───────────────────────────────
        $damageCategories = [
            ['name' => 'Struktural', 'description' => 'Kerusakan pada struktur fisik, pondasi, kerangka baja, dan dinding.'],
            ['name' => 'Mekanikal', 'description' => 'Kerusakan pada mesin, roda gigi, motor conveyor, ban conveyor, dan katrol.'],
            ['name' => 'Elektrikal', 'description' => 'Kerusakan pada kabel daya, panel kontrol, sensor, dan pencahayaan.'],
            ['name' => 'Sipil & Drainase', 'description' => 'Kerusakan pada jalan beton, saluran air, dan pagar pembatas.'],
        ];

        foreach ($damageCategories as $dmgCat) {
            DamageCategory::create($dmgCat);
        }

        // ─── Call Demo Data Seeder ──────────────────────────────
        $this->call(DemoDataSeeder::class);
    }
}