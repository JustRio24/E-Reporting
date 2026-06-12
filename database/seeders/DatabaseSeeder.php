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
            ['name' => 'Kelistrikan & Utilitas', 'description' => 'Sistem kelistrikan, air, dan utilitas penunjang pelabuhan.'],
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
    }
}
