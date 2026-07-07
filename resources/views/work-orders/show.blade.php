@extends('layouts.app')

@section('page-title', 'Detail Surat Perintah Kerja (WO)')

@section('content')
<div class="space-y-6">
    <!-- Top Action bar -->
    <div class="card p-4 flex flex-wrap items-center justify-between gap-4 transition-all duration-300 hover:shadow-card-hover">
        <div class="flex items-center space-x-3">
            <a href="{{ route('work-orders.index') }}" class="text-xs text-slate-400 hover:text-primary font-semibold">&larr; Kembali ke Daftar WO</a>
            <span class="text-slate-355">|</span>
            <span class="text-xs font-mono font-bold text-slate-500">{{ $workOrder->wo_number }}</span>
        </div>

        <div class="flex items-center gap-2">
            <!-- Action to Start WO -->
            @if($workOrder->status->value === 'pending')
                @if(auth()->id() === $workOrder->assigned_to || auth()->user()->isAdmin())
                    <form action="{{ route('work-orders.start', $workOrder->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-primary-dark transition-colors">
                            Mulai Perbaikan
                        </button>
                    </form>
                @endif
            @endif

            <!-- Action to Complete WO -->
            @if($workOrder->status->value === 'in_progress')
                @if(auth()->id() === $workOrder->assigned_to || auth()->user()->isAdmin())
                    <form action="{{ route('work-orders.complete', $workOrder->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-success text-slate-800 text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-emerald-700 transition-colors">
                            Selesaikan Pekerjaan
                        </button>
                    </form>
                @endif
            @endif

            <!-- Option to Verify Report if status is waiting_verification -->
            @if($workOrder->damageReport->status->value === 'waiting_verification')
                @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                    <a href="{{ route('damage-reports.show', $workOrder->damage_report_id) }}" class="bg-success text-slate-800 text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-emerald-700 transition-colors">
                        Verifikasi Hasil Kerja &rarr;
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- Main Detail Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Details & Progress Logs (Left span 2) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- WO details -->
            <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
                <div class="px-6 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Informasi Surat Perintah</h3>
                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase text-slate-800 
                        @if($workOrder->status->value === 'pending') bg-slate-500
                        @elseif($workOrder->status->value === 'in_progress') bg-amber-500
                        @elseif($workOrder->status->value === 'completed') bg-emerald-600
                        @else bg-red-600 @endif">
                        Status: {{ $workOrder->status->value }}
                    </span>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Progress Bar Header -->
                    <div class="bg-surface-50 p-4 rounded border border-gray-200">
                        <div class="flex justify-between items-center mb-2 text-xs">
                            <span class="font-bold text-blue-200">Kemajuan Progres Perbaikan</span>
                            <span class="font-mono font-bold text-success text-sm">{{ $workOrder->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-4 overflow-hidden">
                            <div class="bg-success h-full transition-all duration-300 flex items-center justify-center text-[10px] font-bold text-white shadow-sm" style="width: {{ $workOrder->progress_percentage }}%">
                                @if($workOrder->progress_percentage > 5)
                                    {{ $workOrder->progress_percentage }}%
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-gray-100 pb-5 text-xs">
                        <div>
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">Petugas Lapangan</span>
                            <span class="text-xs font-semibold text-slate-850 block mt-0.5">{{ $workOrder->assignee->name }}</span>
                            <span class="text-[11px] font-mono text-slate-400">{{ $workOrder->assignee->phone ?? 'No telp -' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">Pemberi Perintah</span>
                            <span class="text-xs font-semibold text-slate-850 block mt-0.5">{{ $workOrder->assigner->name }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">Tanggal Penugasan</span>
                            <span class="text-xs font-mono text-slate-500 block mt-0.5">{{ $workOrder->assigned_date->format('d M Y') }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">Batas Waktu (Due Date)</span>
                            <span class="text-xs font-mono block mt-0.5 {{ $workOrder->isOverdue() && $workOrder->status->value !== 'completed' ? 'text-red-600 font-bold' : 'text-slate-500' }}">
                                {{ $workOrder->due_date->format('d M Y') }}
                            </span>
                        </div>
                    </div>

                    @if($workOrder->notes)
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Instruksi Penugasan</span>
                            <p class="text-xs text-slate-750 bg-surface-50 p-4 rounded border border-slate-150 leading-relaxed font-mono">{{ $workOrder->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Logging Entries -->
            <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
                <div class="px-6 py-4 border-b border-gray-200 bg-surface-50">
                    <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Log Progres Perbaikan</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @forelse($workOrder->progressEntries as $entry)
                                <li>
                                    <div class="relative pb-8 font-mono">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-secondary-container/20 text-secondary flex items-center justify-center ring-8 ring-white text-xs font-bold">
                                                    {{ $entry->progress_percentage }}%
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-xs text-slate-500 leading-normal">{{ $entry->description }}</p>
                                                    @if(!empty($entry->photo))
                                                        <div class="mt-3 flex flex-wrap gap-2">
                                                            @foreach($entry->photo as $photoPath)
                                                                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded overflow-hidden border border-gray-200 cursor-pointer" onclick="viewPhoto('{{ asset('storage/' . $photoPath) }}')">
                                                                    <img src="{{ asset('storage/' . $photoPath) }}" alt="Foto Progres" class="w-full h-full object-cover">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="text-right text-[11px] whitespace-nowrap text-slate-400">
                                                    <div>{{ $entry->created_at->format('d M Y H:i') }}</div>
                                                    <div class="font-bold text-slate-400 mt-1">oleh {{ $entry->creator->name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <div class="py-6 text-center text-slate-400 text-xs font-mono">
                                    Belum ada log perbaikan yang dilaporkan.
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add progress entry & metadata panels (Right span 1) -->
        <div class="space-y-6">
            <!-- Add Progress Form (Only for assignee during in_progress status) -->
            @if($workOrder->status->value === 'in_progress')
                @if(auth()->id() === $workOrder->assigned_to || auth()->user()->isAdmin())
                    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
                        <div class="px-5 py-4 border-b border-gray-200 bg-surface-50">
                            <h3 class="text-xs font-bold text-yellow-400 uppercase font-mono tracking-wider">Update Progres Harian</h3>
                        </div>
                        <form action="{{ route('repair-progress.store', $workOrder->id) }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                            @csrf
                            
                            <div>
                                <label for="progress_percentage" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Persentase Kemajuan (0 - 100%)</label>
                                <input type="number" name="progress_percentage" id="progress_percentage" min="{{ $workOrder->progress_percentage }}" max="100" value="{{ old('progress_percentage', $workOrder->progress_percentage) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono" required>
                                @error('progress_percentage')
                                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="desc" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Uraian Pekerjaan / Material</label>
                                <textarea name="description" id="desc" rows="4" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" placeholder="Jelaskan mengenai apa saja tindakan yang telah dilakukan, kendala di lapangan, atau material yang telah terpasang..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="photo" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Foto Bukti (Opsional, Max 5 Foto, @4MB)</label>
                                <input type="file" name="photo[]" id="photo" class="w-full text-xs" accept="image/*" multiple>
                                @error('photo')
                                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-secondary text-slate-800 text-xs font-bold tracking-wider uppercase py-2.5 rounded hover:bg-secondary-dark transition-colors shadow-xs">
                                Kirim Progres Kerja
                            </button>
                        </form>
                    </div>
                @endif
            @endif

            <!-- Original Damage Report Link -->
            <div class="card p-5 space-y-3 transition-all duration-300 hover:shadow-card-hover">
                <span class="block text-[11px] font-mono font-bold tracking-wider text-slate-400 uppercase">Kerusakan Terkait</span>
                <h4 class="text-xs font-bold text-slate-800 leading-snug">{{ $workOrder->damageReport->title }}</h4>
                <a href="{{ route('damage-reports.show', $workOrder->damage_report_id) }}" class="text-[11px] font-bold text-secondary hover:underline block pt-2 border-t border-gray-100">
                    Buka Laporan Kerusakan Asli &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewPhoto(src) {
        Swal.fire({
            imageUrl: src,
            showCloseButton: true,
            showConfirmButton: false,
            width: 'auto',
            maxHeight: '90vh',
            customClass: { popup: 'bg-transparent border-none' }
        });
    }
</script>
@endpush
