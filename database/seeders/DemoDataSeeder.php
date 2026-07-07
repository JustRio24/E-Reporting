<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Facility;
use App\Models\DamageCategory;
use App\Models\DamageReport;
use App\Models\DamagePhoto;
use App\Models\WorkOrder;
use App\Models\RepairProgress;
use App\Models\StatusHistory;
use App\Enums\DamageSeverity;
use App\Enums\DamageStatus;
use App\Enums\WorkOrderStatus;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inspectors = User::where('role', UserRole::INSPECTOR)->get();
        $supervisors = User::where('role', UserRole::SUPERVISOR)->get();
        $maintenanceTeams = User::where('role', UserRole::MAINTENANCE)->get();
        
        $facilities = Facility::all();
        $categories = DamageCategory::all();
        
        if ($facilities->isEmpty() || $categories->isEmpty() || $inspectors->isEmpty()) {
            $this->command->info('Please run DatabaseSeeder first to setup initial master data.');
            return;
        }

        // Variative dummy reports setup
        $dummyReports = [
            // 1. Report: New / Draft
            [
                'title' => 'Keretakan pada pondasi Conveyor A',
                'description' => 'Ditemukan keretakan sepanjang 2 meter pada pondasi utama conveyor.',
                'severity' => DamageSeverity::HIGH,
                'status' => DamageStatus::REPORTED,
                'days_ago' => 1,
            ],
            // 2. Report: Verified, waiting assignment
            [
                'title' => 'Kabel terkelupas di Panel Utama',
                'description' => 'Kabel power terkelupas berisiko korsleting.',
                'severity' => DamageSeverity::CRITICAL,
                'status' => DamageStatus::VERIFIED,
                'days_ago' => 2,
            ],
            // 3. Report: Assigned
            [
                'title' => 'Lampu sorot mati di area Jetty',
                'description' => 'Beberapa lampu sorot utama mati, mengganggu operasional malam hari.',
                'severity' => DamageSeverity::MEDIUM,
                'status' => DamageStatus::ASSIGNED,
                'days_ago' => 3,
                'work_order' => [
                    'status' => WorkOrderStatus::PENDING,
                    'notes' => 'Segera ganti bohlam dan cek instalasi kabel.',
                ]
            ],
            // 4. Report: In Progress (with progress entries)
            [
                'title' => 'Roll conveyor aus dan macet',
                'description' => 'Beberapa idler roll pada belt conveyor macet menyebabkan gesekan tinggi.',
                'severity' => DamageSeverity::HIGH,
                'status' => DamageStatus::IN_PROGRESS,
                'days_ago' => 5,
                'work_order' => [
                    'status' => WorkOrderStatus::IN_PROGRESS,
                    'notes' => 'Ganti idler roll yang macet dan lumasi bearing.',
                    'progress' => [
                        ['percentage' => 30, 'desc' => 'Pembongkaran cover dan identifikasi roll macet.'],
                        ['percentage' => 60, 'desc' => 'Penggantian 5 unit roll baru.'],
                    ]
                ]
            ],
            // 5. Report: Completed
            [
                'title' => 'Pagar pengaman lepas',
                'description' => 'Pagar pengaman di area walkway menuju crusher lepas akibat tertabrak alat berat.',
                'severity' => DamageSeverity::LOW,
                'status' => DamageStatus::COMPLETED,
                'days_ago' => 10,
                'work_order' => [
                    'status' => WorkOrderStatus::COMPLETED,
                    'notes' => 'Lakukan pengelasan ulang pada pagar pengaman.',
                    'progress' => [
                        ['percentage' => 50, 'desc' => 'Persiapan alat las dan perapian pagar.'],
                        ['percentage' => 100, 'desc' => 'Pengelasan selesai, pagar sudah kuat kembali.'],
                    ]
                ]
            ],
            // 6. Report: Another In Progress
            [
                'title' => 'Kebocoran atap Gudang Storage A',
                'description' => 'Atap bocor cukup parah mengenai area penempatan batubara bersih.',
                'severity' => DamageSeverity::MEDIUM,
                'status' => DamageStatus::IN_PROGRESS,
                'days_ago' => 4,
                'work_order' => [
                    'status' => WorkOrderStatus::IN_PROGRESS,
                    'notes' => 'Tambal atap yang bocor.',
                    'progress' => [
                        ['percentage' => 40, 'desc' => 'Pembersihan area atap yang bocor.'],
                    ]
                ]
            ]
        ];

        foreach ($dummyReports as $index => $data) {
            $facility = $facilities->random();
            $category = $categories->random();
            $inspector = $inspectors->random();
            $reportedAt = Carbon::now()->subDays($data['days_ago']);

            // Create Report
            $report = DamageReport::create([
                'report_number' => 'RPT-' . date('Ym', strtotime($reportedAt)) . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'facility_id' => $facility->id,
                'reporter_id' => $inspector->id,
                'damage_category_id' => $category->id,
                'severity' => $data['severity'],
                'title' => $data['title'],
                'description' => $data['description'],
                'latitude' => $facility->latitude + (rand(-100, 100) / 1000000), // slight offset
                'longitude' => $facility->longitude + (rand(-100, 100) / 1000000),
                'status' => $data['status'],
                'reported_at' => $reportedAt,
            ]);

            // Create Status History for Report Creation
            StatusHistory::create([
                'damage_report_id' => $report->id,
                'old_status' => DamageStatus::DRAFT,
                'new_status' => DamageStatus::REPORTED,
                'changed_by' => $inspector->id,
                'remarks' => 'Laporan awal dibuat.',
                'created_at' => $reportedAt,
                'updated_at' => $reportedAt,
            ]);

            // Add verified status history if applicable
            if (in_array($data['status'], [DamageStatus::VERIFIED, DamageStatus::ASSIGNED, DamageStatus::IN_PROGRESS, DamageStatus::COMPLETED])) {
                $supervisor = $supervisors->random();
                $verifiedAt = $reportedAt->copy()->addHours(2);
                StatusHistory::create([
                    'damage_report_id' => $report->id,
                    'old_status' => DamageStatus::REPORTED,
                    'new_status' => DamageStatus::VERIFIED,
                    'changed_by' => $supervisor->id,
                    'remarks' => 'Laporan valid dan telah diverifikasi.',
                    'created_at' => $verifiedAt,
                    'updated_at' => $verifiedAt,
                ]);
            }

            // Create Work Order if provided
            if (isset($data['work_order'])) {
                $supervisor = $supervisors->random();
                $maintenance = $maintenanceTeams->random();
                $assignedAt = $reportedAt->copy()->addHours(5);
                
                $workOrder = WorkOrder::create([
                    'damage_report_id' => $report->id,
                    'assigned_to' => $maintenance->id,
                    'assigned_by' => $supervisor->id,
                    'assigned_date' => $assignedAt,
                    'due_date' => $assignedAt->copy()->addDays(3),
                    'status' => $data['work_order']['status'],
                    'notes' => $data['work_order']['notes'],
                    'created_at' => $assignedAt,
                    'updated_at' => $assignedAt,
                ]);

                StatusHistory::create([
                    'damage_report_id' => $report->id,
                    'old_status' => DamageStatus::VERIFIED,
                    'new_status' => DamageStatus::ASSIGNED,
                    'changed_by' => $supervisor->id,
                    'remarks' => 'Work order ditugaskan ke tim maintenance.',
                    'created_at' => $assignedAt,
                    'updated_at' => $assignedAt,
                ]);
                
                // Add in progress status history if applicable
                if (in_array($data['status'], [DamageStatus::IN_PROGRESS, DamageStatus::COMPLETED])) {
                    $inProgressAt = $assignedAt->copy()->addHours(1);
                    StatusHistory::create([
                        'damage_report_id' => $report->id,
                        'old_status' => DamageStatus::ASSIGNED,
                        'new_status' => DamageStatus::IN_PROGRESS,
                        'changed_by' => $maintenance->id,
                        'remarks' => 'Pengerjaan dimulai.',
                        'created_at' => $inProgressAt,
                        'updated_at' => $inProgressAt,
                    ]);
                }

                // Add progress entries if provided
                if (isset($data['work_order']['progress'])) {
                    $progressTime = $assignedAt->copy()->addHours(2);
                    foreach ($data['work_order']['progress'] as $pIndex => $prog) {
                        RepairProgress::create([
                            'work_order_id' => $workOrder->id,
                            'progress_percentage' => $prog['percentage'],
                            'description' => $prog['desc'],
                            'created_by' => $maintenance->id,
                            'created_at' => $progressTime,
                            'updated_at' => $progressTime,
                        ]);
                        $progressTime = $progressTime->addHours(3);
                    }
                }
                
                // Add completed status history if applicable
                if ($data['status'] === DamageStatus::COMPLETED) {
                    $completedAt = $progressTime ?? $assignedAt->copy()->addDays(1);
                    StatusHistory::create([
                        'damage_report_id' => $report->id,
                        'old_status' => DamageStatus::IN_PROGRESS,
                        'new_status' => DamageStatus::COMPLETED,
                        'changed_by' => $supervisor->id,
                        'remarks' => 'Pekerjaan selesai dan disetujui.',
                        'created_at' => $completedAt,
                        'updated_at' => $completedAt,
                    ]);
                }
            }
        }
    }
}
