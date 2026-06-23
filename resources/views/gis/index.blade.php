@extends('layouts.app')

@section('page-title', 'GIS Monitoring Kerusakan')

@section('content')
<div class="h-[calc(100vh-140px)] flex flex-col relative rounded-lg border border-gray-200 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-card-hover">
    <!-- Floating Filter Box -->
    <div class="absolute top-4 left-4 z-[1000] bg-white/95 backdrop-blur-md p-4 rounded-lg border border-gray-200 shadow-lg max-w-xs w-full space-y-3.5 transition-all duration-300 hover:shadow-card-hover">
        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-200 font-mono flex items-center">
            <span class="mr-2">🔍</span> Filter Peta GIS
        </h4>

        <div>
            <label for="filter-status" class="block text-[11px] font-mono font-bold uppercase text-slate-400 mb-1">Status Laporan</label>
            <select id="filter-status" class="w-full text-xs rounded border-slate-600/50 focus:border-primary focus:ring-primary/20 py-1.5 font-mono">
                <option value="">Semua Status</option>
                @foreach($statuses as $st)
                    <option value="{{ $st->value }}">{{ strtoupper(str_replace('_', ' ', $st->value)) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="filter-severity" class="block text-[11px] font-mono font-bold uppercase text-slate-400 mb-1">Tingkat Keparahan</label>
            <select id="filter-severity" class="w-full text-xs rounded border-slate-600/50 focus:border-primary focus:ring-primary/20 py-1.5 font-mono">
                <option value="">Semua Keparahan</option>
                @foreach($severities as $sev)
                    <option value="{{ $sev->value }}">{{ strtoupper($sev->value) }}</option>
                @endforeach
            </select>
        </div>

        <button onclick="loadMapData()" class="w-full bg-secondary text-slate-800 text-[11px] font-bold tracking-wider uppercase py-2 rounded hover:bg-secondary-dark transition-colors font-mono">
            Terapkan Filter
        </button>
    </div>

    <!-- Map Container -->
    <div id="gis-fullscreen-map" class="flex-1 w-full h-full z-0 bg-slate-100"></div>

    <!-- Floating Map Legend -->
    <div class="absolute bottom-4 right-4 z-[1000] bg-white/95 backdrop-blur-md px-4 py-3 rounded-lg border border-gray-200 shadow-md text-[11px] font-mono space-y-2 transition-all duration-300 hover:shadow-card-hover">
        <div class="font-bold text-slate-500 border-b border-slate-150 pb-1.5 mb-1.5">LEGENDA STATUS</div>
        <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-[#ba1a1a] mr-2 inline-block border border-white"></span> Aktif / Dilaporkan</div>
        <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-[#d97706] mr-2 inline-block border border-white"></span> Sedang Perbaikan</div>
        <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-[#059669] mr-2 inline-block border border-white"></span> Selesai</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let map;
    let markersLayer = L.layerGroup();

    document.addEventListener('DOMContentLoaded', function () {
        const ptbaBounds = [
            [-3.0330, 104.7340], // SouthWest
            [-3.0030, 104.7640]  // NorthEast
        ];

        // Initialize Map centered on Kertapati Port area and restrict bounds
        map = L.map('gis-fullscreen-map', {
            maxBounds: ptbaBounds,
            maxBoundsViscosity: 1.0,
            minZoom: 15
        }).setView([-3.0182, 104.7493], 16);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        markersLayer.addTo(map);

        // Load Initial Pins
        loadMapData();
    });

    function loadMapData() {
        const status = document.getElementById('filter-status').value;
        const severity = document.getElementById('filter-severity').value;

        // Clear existing markers
        markersLayer.clearLayers();

        // Build URL
        let url = `/gis-monitoring/data?`;
        if (status) url += `status=${status}&`;
        if (severity) url += `severity=${severity}&`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                data.forEach(function (point) {
                    if (point.lat && point.lng) {
                        let markerColor = '#ba1a1a'; // Active
                        if (point.status === 'in_progress' || point.status === 'assigned') {
                            markerColor = '#d97706'; // In Progress
                        } else if (point.status === 'completed' || point.status === 'waiting_verification') {
                            markerColor = '#059669'; // Completed
                        }

                        const customIcon = L.divIcon({
                            html: `<div style="background-color: ${markerColor}; width: 15px; height: 15px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.4)"></div>`,
                            className: 'custom-div-icon',
                            iconSize: [15, 15],
                            iconAnchor: [7, 7]
                        });

                        const popupContent = `
                            <div class="font-sans text-xs min-w-[200px] p-1.5">
                                <div class="font-bold text-slate-200">${point.facility || 'Master Facility'}</div>
                                <div class="text-slate-400 font-mono mt-0.5 text-xs">${point.report_number}</div>
                                <div class="font-semibold text-slate-500 mt-1.5 border-t border-gray-100 pt-1.5">${point.title}</div>
                                <div class="mt-2.5 flex items-center justify-between">
                                    <span class="text-[10px] uppercase font-mono px-1.5 py-0.5 rounded font-bold bg-slate-100 text-slate-500">${point.severity}</span>
                                    <a href="/damage-reports/${point.id}" class="text-[10px] font-bold text-secondary hover:underline">Detail &rarr;</a>
                                </div>
                            </div>
                        `;

                        const marker = L.marker([point.lat, point.lng], { icon: customIcon })
                            .bindPopup(popupContent);
                        
                        markersLayer.addLayer(marker);
                    }
                });
            })
            .catch(err => {
                console.error("Gagal memuat data GIS:", err);
            });
    }
</script>
@endpush
