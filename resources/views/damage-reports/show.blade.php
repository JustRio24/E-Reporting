@extends('layouts.app')

@section('page-title', 'Detail Laporan Kerusakan')

@section('content')
<div class="space-y-6">
    <!-- Action Top Bar -->
    <div class="card p-4 flex flex-wrap items-center justify-between gap-4 transition-all duration-300 hover:shadow-card-hover">
        <div class="flex items-center space-x-3">
            <a href="{{ route('damage-reports.index') }}" class="text-xs text-slate-400 hover:text-primary font-semibold">&larr; Kembali ke Daftar</a>
            <span class="text-slate-400">|</span>
            <span class="text-xs font-mono font-bold text-slate-500">{{ $report->report_number }}</span>
        </div>

        <div class="flex items-center gap-2">
            <!-- If Draft: Option to submit or edit -->
            @if($report->status->value === 'draft')
                @if(auth()->id() === $report->reporter_id || auth()->user()->isAdmin())
                    <a href="{{ route('damage-reports.edit', $report->id) }}" class="bg-slate-100 text-slate-500 text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded border hover:bg-slate-200 transition-colors">
                        Edit Draft
                    </a>
                    <form action="{{ route('damage-reports.submit', $report->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-primary-dark transition-colors">
                            Kirim Laporan
                        </button>
                    </form>
                @endif
            @endif

            <!-- If Reported: Only Supervisor can verify or reject -->
            @if($report->status->value === 'reported')
                @if(auth()->user()->isSupervisor())
                    <button onclick="openVerificationModal('verify')" class="bg-success text-slate-800 text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-emerald-700 transition-colors">
                        Verifikasi
                    </button>
                    <button onclick="openVerificationModal('reject')" class="bg-error text-slate-800 text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-red-700 transition-colors">
                        Tolak
                    </button>
                @endif
            @endif

            <!-- If Waiting Verification: Supervisor verifies final completion -->
            @if($report->status->value === 'waiting_verification')
                @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                    <button onclick="openVerificationModal('verify', '{{ route('damage-reports.verify-completion', $report->id) }}')" class="bg-success text-slate-800 text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-emerald-700 transition-colors">
                        Verifikasi Selesai
                    </button>
                    <button onclick="openVerificationModal('reject', '{{ route('damage-reports.verify-completion', $report->id) }}')" class="bg-error text-slate-800 text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-red-700 transition-colors">
                        Tolak Pekerjaan
                    </button>
                @endif
            @endif

            <!-- If Verified: Supervisor/Admin can issue Work Order -->
            @if($report->status->value === 'verified')
                @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                    <a href="{{ route('work-orders.create', ['damage_report_id' => $report->id]) }}" class="bg-secondary text-slate-800 text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-secondary-dark transition-colors">
                        Buat Perintah Kerja (WO)
                    </a>
                @endif
            @endif

            <!-- If Work Order exists: View WO -->
            @if($report->workOrder && !auth()->user()->isInspector())
                <a href="{{ route('work-orders.show', $report->workOrder->id) }}" class="bg-slate-905 text-slate-800 text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-slate-800 transition-colors">
                    Lihat Perintah Kerja (WO)
                </a>
            @endif
        </div>
    </div>

    <!-- Main Detail Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left col span 2) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Report Core Details -->
            <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
                <div class="px-6 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Rincian Laporan Kerusakan</h3>
                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase text-slate-800 
                        @if($report->status->value === 'draft') bg-slate-650
                        @elseif($report->status->value === 'reported') bg-blue-650
                        @elseif($report->status->value === 'verified') bg-indigo-500
                        @elseif($report->status->value === 'assigned') bg-amber-500
                        @elseif($report->status->value === 'in_progress') bg-orange-500
                        @elseif($report->status->value === 'waiting_verification') bg-yellow-500
                        @else bg-emerald-605 @endif">
                        Status: {{ str_replace('_', ' ', $report->status->value) }}
                    </span>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 leading-tight">{{ $report->title }}</h2>
                        <div class="mt-2.5 flex flex-wrap gap-4 text-xs font-mono text-slate-400">
                            <div>Pelapor: <span class="font-bold text-slate-500">{{ $report->reporter->name }}</span></div>
                            <div>Tanggal: <span class="font-bold text-slate-500">{{ $report->created_at->format('d M Y H:i') }}</span></div>
                        </div>
                    </div>

                    <div class="border-t border-b border-gray-100 py-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">Fasilitas</span>
                            <span class="text-xs font-semibold text-slate-800 block mt-0.5">{{ $report->facility->facility_name }}</span>
                            <span class="text-[10px] font-mono text-slate-400">{{ $report->facility->facility_code }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">Lokasi Area</span>
                            <span class="text-xs font-semibold text-slate-800 block mt-0.5">{{ $report->facility->location->name }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">Kategori Kerusakan</span>
                            <span class="text-xs font-semibold text-slate-800 block mt-0.5">{{ $report->damageCategory->name }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">Tingkat Keparahan</span>
                            <span class="inline-flex mt-1 px-2 py-0.5 rounded text-[10px] font-mono font-bold tracking-wider uppercase text-slate-800
                                @if($report->severity->value === 'low') bg-slate-500
                                @elseif($report->severity->value === 'medium') bg-amber-500
                                @elseif($report->severity->value === 'high') bg-orange-600
                                @else bg-red-600 @endif">
                                {{ $report->severity->value }}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <span class="block text-xs font-bold uppercase tracking-wider text-blue-200">Deskripsi Kondisi</span>
                        <p class="text-xs text-slate-500 leading-relaxed whitespace-pre-line bg-surface-50 p-4 rounded border border-slate-150">{{ $report->description }}</p>
                    </div>

                    <!-- Photo Gallery -->
                    <div class="space-y-2">
                        <span class="block text-xs font-bold uppercase tracking-wider text-blue-200">Dokumentasi Foto ({{ $report->photos->count() }})</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($report->photos as $photo)
                                <div class="relative group h-40 rounded overflow-hidden border border-gray-200 cursor-pointer" onclick="viewFullImage('{{ asset('storage/' . $photo->photo_path) }}')">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto Kerusakan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-150">
                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center text-slate-800 text-xs font-bold transition-opacity">
                                        Perbesar
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit History Timeline -->
            <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
                <div class="px-6 py-4 border-b border-gray-200 bg-surface-50">
                    <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Log Riwayat Status Laporan</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8 font-mono">
                            @foreach($report->statusHistories as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center ring-8 ring-white text-xs">
                                                    📌
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-xs text-slate-500">
                                                        Status diubah ke <span class="font-bold text-slate-905 uppercase text-xs px-1 py-0.5 rounded bg-slate-100 border border-gray-200">{{ str_replace('_', ' ', $log->new_status->value) }}</span>
                                                    </p>
                                                    @if($log->notes)
                                                        <p class="text-[11px] text-slate-450 mt-1 italic">Catatan: "{{ $log->notes }}"</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-[11px] whitespace-nowrap text-slate-400 flex flex-col items-end">
                                                    <span>{{ $log->created_at->format('d M Y H:i') }}</span>
                                                    <span class="font-bold text-slate-400 mt-0.5">oleh {{ $log->changedBy->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panels (Map / Geography / Links span 1) -->
        <div class="space-y-6">
            <!-- GIS Coordinates Map Widget -->
            @if($report->latitude && $report->longitude)
                <div class="card overflow-hidden flex flex-col transition-all duration-300 hover:shadow-card-hover">
                    <div class="px-5 py-4 border-b border-gray-200 bg-surface-50">
                        <h3 class="text-xs font-bold text-yellow-400 uppercase font-mono tracking-wider">Lokasi Koordinat GIS</h3>
                    </div>
                    <div class="h-64 relative z-0" id="detail-map"></div>
                    <div class="p-4 bg-surface-50 border-t border-gray-200 font-mono text-[11px] text-slate-400 flex flex-col space-y-1">
                        <div>Latitude: <span class="text-slate-800 font-bold">{{ $report->latitude }}</span></div>
                        <div>Longitude: <span class="text-slate-800 font-bold">{{ $report->longitude }}</span></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Verification/Rejection Modal -->
<div id="verification-modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeVerificationModal()"></div>
    
    <!-- Modal panel -->
    <div class="relative bg-white rounded-lg max-w-md w-full border border-gray-200 shadow-lg overflow-hidden z-10 transition-all duration-300 hover:shadow-card-hover">
        <div class="px-5 py-4 border-b border-gray-100 bg-surface-50">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider" id="modal-title">Verifikasi Laporan</h3>
        </div>
        <form action="{{ route('damage-reports.verify', $report->id) }}" method="POST" id="verification-form" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="action" id="modal-action-input">
            
            <div>
                <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Catatan / Keterangan</label>
                <textarea name="notes" id="notes" rows="4" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" placeholder="Tuliskan catatan persetujuan atau alasan penolakan jika dikembalikan..." required></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeVerificationModal()" class="bg-slate-150 text-slate-500 text-xs font-semibold px-4 py-2 rounded hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="bg-primary text-slate-800 text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-primary-dark shadow-sm" id="modal-submit-btn">
                    Proses
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if($report->latitude && $report->longitude)
    document.addEventListener('DOMContentLoaded', function () {
        const ptbaBounds = [
            [-3.0330, 104.7340], // SouthWest
            [-3.0030, 104.7640]  // NorthEast
        ];

        const detailMap = L.map('detail-map', {
            maxBounds: ptbaBounds,
            maxBoundsViscosity: 1.0,
            minZoom: 15
        }).setView([{{ $report->latitude }}, {{ $report->longitude }}], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(detailMap);

        const markerColor = '#ba1a1a'; // Safety Red
        const customIcon = L.divIcon({
            html: `<div style="background-color: ${markerColor}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3)"></div>`,
            className: 'custom-div-icon',
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });

        L.marker([{{ $report->latitude }}, {{ $report->longitude }}], { icon: customIcon })
            .bindPopup(`<div class="font-sans text-xs font-bold text-slate-800">{{ $report->facility->facility_name }}</div>`)
            .addTo(detailMap);
    });
    @endif

    function viewFullImage(src) {
        Swal.fire({
            imageUrl: src,
            imageAlt: 'Foto Kerusakan Fullscreen',
            showCloseButton: true,
            showConfirmButton: false,
            width: 'auto',
            maxHeight: '90vh',
            customClass: {
                popup: 'bg-transparent border-none shadow-none',
                closeButton: 'text-slate-800 hover:text-slate-400 focus:outline-none'
            }
        });
    }

    function openVerificationModal(action, url = null) {
        const modal = document.getElementById('verification-modal');
        const form = document.getElementById('verification-form');
        const actionInput = document.getElementById('modal-action-input');
        const title = document.getElementById('modal-title');
        const submitBtn = document.getElementById('modal-submit-btn');

        if (url) {
            form.action = url;
        } else {
            form.action = "{{ route('damage-reports.verify', $report->id) }}";
        }

        actionInput.value = action;
        
        if (action === 'verify') {
            title.textContent = 'Verifikasi & Setujui';
            submitBtn.textContent = 'Setujui';
            submitBtn.className = 'bg-success text-slate-800 text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-emerald-700 shadow-sm';
        } else {
            title.textContent = 'Tolak & Kembalikan';
            submitBtn.textContent = 'Tolak';
            submitBtn.className = 'bg-error text-slate-800 text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-red-700 shadow-sm';
        }

        modal.classList.remove('hidden');
    }

    function closeVerificationModal() {
        const modal = document.getElementById('verification-modal');
        modal.classList.add('hidden');
    }
</script>
@endpush
