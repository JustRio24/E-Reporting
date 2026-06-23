@extends('layouts.app')

@section('page-title', 'Dashboard Monitoring')

@section('content')
<div class="space-y-6">
    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1: Total Reports -->
        <div class="card flex items-center p-5 scroll-animate">
            <div class="p-3 rounded-lg bg-blue-50 text-primary mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Laporan</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['total'] }}</h3>
            </div>
        </div>

        <!-- Card 2: Active Reports -->
        <div class="card flex items-center p-5 scroll-animate delay-100">
            <div class="p-3 rounded-lg bg-amber-50 text-warning mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Laporan Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['active'] }}</h3>
            </div>
        </div>

        <!-- Card 3: Completed Reports -->
        <div class="card flex items-center p-5 scroll-animate delay-200">
            <div class="p-3 rounded-lg bg-emerald-50 text-success mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Selesai Ditangani</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['completed'] }}</h3>
            </div>
        </div>

        <!-- Card 4: Critical Reports -->
        <div class="card flex items-center p-5 scroll-animate delay-300">
            <div class="p-3 rounded-lg bg-red-50 text-error mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tingkat Kritis</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['critical'] }}</h3>
            </div>
        </div>
    </div>

    <!-- GIS Map & Category Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- GIS Map -->
        <div class="lg:col-span-2 card overflow-hidden flex flex-col scroll-animate">
            <div class="card-header flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Peta GIS Kerusakan Fasilitas</h3>
                <span class="badge badge-info font-mono text-[10px]">REAL-TIME</span>
            </div>
            <div class="flex-1 min-h-[400px] h-[400px] relative z-0" id="map"></div>
            <div class="px-5 py-3 bg-surface-50 border-t border-gray-100 flex flex-wrap gap-4 text-xs">
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-2 inline-block border border-white shadow-sm"></span> Aktif / Dilaporkan</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-2 inline-block border border-white shadow-sm"></span> Sedang Perbaikan</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-2 inline-block border border-white shadow-sm"></span> Selesai</div>
            </div>
        </div>

        <!-- Category Chart -->
        <div class="card overflow-hidden flex flex-col scroll-animate delay-200">
            <div class="card-header">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Kategori Kerusakan</h3>
            </div>
            <div class="flex-1 p-5 flex items-center justify-center min-h-[300px]">
                @if(count($categoryTotals) > 0)
                    <canvas id="categoryChart"></canvas>
                @else
                    <div class="flex flex-col items-center justify-center opacity-60">
                        <svg class="w-10 h-10 text-slate-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-xs text-slate-400">Belum ada data tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly & Facility Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Line Chart -->
        <div class="card overflow-hidden flex flex-col scroll-animate">
            <div class="card-header">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tren Kerusakan Bulanan</h3>
            </div>
            <div class="p-5 min-h-[250px] flex items-center justify-center">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Facility Bar Chart -->
        <div class="card overflow-hidden flex flex-col scroll-animate delay-200">
            <div class="card-header">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Kerusakan Per Fasilitas</h3>
            </div>
            <div class="p-5 min-h-[250px] flex items-center justify-center">
                @if(count($facilityTotals) > 0)
                    <canvas id="facilityChart"></canvas>
                @else
                    <div class="flex flex-col items-center justify-center opacity-60">
                        <svg class="w-10 h-10 text-slate-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-xs text-slate-400">Belum ada data tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ptbaBounds = [[-3.0330, 104.7340], [-3.0030, 104.7640]];

        const map = L.map('map', {
            maxBounds: ptbaBounds,
            maxBoundsViscosity: 1.0,
            minZoom: 15
        }).setView([-3.0182, 104.7493], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const mapData = @json($mapData);
        mapData.forEach(function (point) {
            if (point.lat && point.lng) {
                let markerColor = '#dc2626';
                if (point.status === 'in_progress' || point.status === 'assigned') {
                    markerColor = '#d97706';
                } else if (point.status === 'completed' || point.status === 'waiting_verification') {
                    markerColor = '#059669';
                }

                const customIcon = L.divIcon({
                    html: `<div style="background-color: ${markerColor}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.2)"></div>`,
                    className: 'custom-div-icon',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                const popupContent = `
                    <div class="font-sans text-xs min-w-[180px] p-1">
                        <div class="font-bold text-slate-800">${point.facility || 'Fasilitas'}</div>
                        <div class="text-slate-400 font-mono mt-0.5 text-[10px]">${point.report_number}</div>
                        <div class="font-semibold text-slate-600 mt-1 border-t border-gray-100 pt-1">${point.title}</div>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="badge badge-neutral text-[10px]">${point.severity}</span>
                            <a href="/damage-reports/${point.id}" class="text-[10px] font-bold text-primary hover:underline cursor-pointer">Detail &rarr;</a>
                        </div>
                    </div>
                `;

                L.marker([point.lat, point.lng], { icon: customIcon })
                    .bindPopup(popupContent)
                    .addTo(map);
            }
        });

        // Chart configurations for light theme
        Chart.defaults.color = '#64748b';
        Chart.defaults.borderColor = '#f1f5f9';

        @if(count($categoryTotals) > 0)
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryTotals),
                    backgroundColor: ['#1961a1', '#f26522', '#059669', '#d97706', '#dc2626', '#8b5cf6'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Inter', size: 12 }, boxWidth: 12, padding: 16 }
                    }
                }
            }
        });
        @endif

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Jumlah Kerusakan',
                    data: @json($monthlyTotals),
                    borderColor: '#1961a1',
                    backgroundColor: 'rgba(25, 97, 161, 0.08)',
                    tension: 0.4,
                    borderWidth: 2,
                    fill: true,
                    pointBackgroundColor: '#1961a1',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });

        @if(count($facilityTotals) > 0)
        new Chart(document.getElementById('facilityChart'), {
            type: 'bar',
            data: {
                labels: @json($facilityLabels),
                datasets: [{
                    label: 'Jumlah Kerusakan',
                    data: @json($facilityTotals),
                    backgroundColor: '#1961a1',
                    borderRadius: 6,
                    barThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
        @endif
    });
</script>
@endpush
