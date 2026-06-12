@extends('layouts.app')

@section('page-title', 'Dashboard Monitoring')

@section('content')
<div class="space-y-6">
    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1: Total Reports -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 flex items-center p-5">
            <div class="p-3 rounded bg-secondary-container/20 text-secondary mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-mono font-bold uppercase tracking-wider text-slate-500">Total Laporan</p>
                <h3 class="text-2xl font-bold text-slate-900 font-sans mt-0.5">{{ $stats['total'] }}</h3>
            </div>
        </div>

        <!-- Card 2: Active Reports -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 flex items-center p-5">
            <div class="p-3 rounded bg-primary-fixed/30 text-primary mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-mono font-bold uppercase tracking-wider text-slate-500">Laporan Aktif</p>
                <h3 class="text-2xl font-bold text-slate-900 font-sans mt-0.5">{{ $stats['active'] }}</h3>
            </div>
        </div>

        <!-- Card 3: Completed Reports -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 flex items-center p-5">
            <div class="p-3 rounded bg-emerald-100 text-emerald-650 mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-mono font-bold uppercase tracking-wider text-slate-500">Selesai Ditangani</p>
                <h3 class="text-2xl font-bold text-slate-900 font-sans mt-0.5">{{ $stats['completed'] }}</h3>
            </div>
        </div>

        <!-- Card 4: Critical Reports -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 flex items-center p-5">
            <div class="p-3 rounded bg-red-100 text-red-650 mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-mono font-bold uppercase tracking-wider text-slate-500">Tingkat Kritis</p>
                <h3 class="text-2xl font-bold text-slate-900 font-sans mt-0.5">{{ $stats['critical'] }}</h3>
            </div>
        </div>
    </div>

    <!-- GIS Map Widget & Statistics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- GIS Map (Left/Center span 2) -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-55 py-3 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-800 uppercase font-mono tracking-wider">Peta GIS Kerusakan Fasilitas</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-4xs font-bold bg-slate-100 text-slate-500 border border-slate-200 font-mono">REAL-TIME</span>
            </div>
            <div class="flex-1 min-h-[400px] h-[400px] relative z-0" id="map"></div>
            <div class="px-5 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-wrap gap-4 text-xs font-mono">
                <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-[#ba1a1a] mr-2 inline-block border border-white"></span> Aktif / Dilaporkan</div>
                <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-[#d97706] mr-2 inline-block border border-white"></span> Sedang Perbaikan</div>
                <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-[#059669] mr-2 inline-block border border-white"></span> Selesai</div>
            </div>
        </div>

        <!-- Damage By Category Chart (Right span 1) -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-800 uppercase font-mono tracking-wider">Kategori Kerusakan</h3>
            </div>
            <div class="flex-1 p-5 flex items-center justify-center min-h-[300px]">
                @if(count($categoryTotals) > 0)
                    <canvas id="categoryChart"></canvas>
                @else
                    <p class="text-xs text-slate-400 font-mono">Belum ada data tersedia</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Stats & Facility Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Stats Line Chart -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-800 uppercase font-mono tracking-wider">Tren Kerusakan Bulanan</h3>
            </div>
            <div class="p-5 min-h-[250px] flex items-center justify-center">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Damage by Facility Bar Chart -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-800 uppercase font-mono tracking-wider">Kerusakan Per Fasilitas</h3>
            </div>
            <div class="p-5 min-h-[250px] flex items-center justify-center">
                @if(count($facilityTotals) > 0)
                    <canvas id="facilityChart"></canvas>
                @else
                    <p class="text-xs text-slate-400 font-mono">Belum ada data tersedia</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Map
        const map = L.map('map').setView([-2.9935, 104.7300], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Load Marker Pins
        const mapData = @json($mapData);
        mapData.forEach(function (point) {
            if (point.lat && point.lng) {
                // Color mapping: Red = Active, Yellow = In progress / Assigned, Green = Completed / Waiting verification
                let markerColor = '#ba1a1a'; // Active
                if (point.status === 'in_progress' || point.status === 'assigned') {
                    markerColor = '#d97706'; // In Progress
                } else if (point.status === 'completed' || point.status === 'waiting_verification') {
                    markerColor = '#059669'; // Completed
                }

                const customIcon = L.divIcon({
                    html: `<div style="background-color: ${markerColor}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3)"></div>`,
                    className: 'custom-div-icon',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                const popupContent = `
                    <div class="font-sans text-xs min-w-[180px] p-1">
                        <div class="font-bold text-slate-800">${point.facility || 'Master Facility'}</div>
                        <div class="text-slate-500 font-mono mt-0.5 text-2xs">${point.report_number}</div>
                        <div class="font-semibold text-slate-700 mt-1 border-t border-slate-100 pt-1">${point.title}</div>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-3xs uppercase font-mono px-1 py-0.5 rounded font-bold bg-slate-100 text-slate-600">${point.severity}</span>
                            <a href="/damage-reports/${point.id}" class="text-3xs font-bold text-secondary hover:underline">Detail &rarr;</a>
                        </div>
                    </div>
                `;

                L.marker([point.lat, point.lng], { icon: customIcon })
                    .bindPopup(popupContent)
                    .addTo(map);
            }
        });

        // ─── Chart JS Configurations ─────────────────────────────
        
        // Category Chart (Doughnut)
        @if(count($categoryTotals) > 0)
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryTotals),
                    backgroundColor: ['#a63b00', '#1961a1', '#545f72', '#d97706', '#059669', '#ba1a1a'],
                    borderWidth: 1,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'IBM Plex Sans', size: 11 },
                            boxWidth: 12
                        }
                    }
                }
            }
        });
        @endif

        // Monthly Stats Line Chart
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Jumlah Kerusakan',
                    data: @json($monthlyTotals),
                    borderColor: '#1961a1',
                    backgroundColor: 'rgba(25, 97, 161, 0.05)',
                    tension: 0.25,
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Facility Stats Bar Chart
        @if(count($facilityTotals) > 0)
        new Chart(document.getElementById('facilityChart'), {
            type: 'bar',
            data: {
                labels: @json($facilityLabels),
                datasets: [{
                    label: 'Jumlah Kerusakan',
                    data: @json($facilityTotals),
                    backgroundColor: '#a63b00',
                    borderRadius: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
        @endif
    });
</script>
@endpush
