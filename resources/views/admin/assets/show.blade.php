@extends('layouts.admin')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    .page-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }

    .section-card {
        background: #fff;
        border: 1.5px solid #e8edf5;
        border-radius: 24px;
        padding: 36px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(59,130,246,0.04);
    }

    .btn-back {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.2s;
        text-decoration: none;
        flex-shrink: 0;
    }
    .btn-back:hover { background: #eff6ff; border-color: #93c5fd; color: #2563eb; }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        border: 1.5px solid #bfdbfe;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-edit:hover {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .stat-row:last-child { border-bottom: none; padding-bottom: 0; }
    .stat-row:first-child { padding-top: 0; }

    .stat-key {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #94a3b8;
    }

    .stat-val {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }

    .kondisi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .kondisi-baru    { background:#eff6ff; color:#2563eb; }
    .kondisi-baik    { background:#f0fdf4; color:#16a34a; }
    .kondisi-rusak   { background:#fff7ed; color:#ea580c; }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .status-chip .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .asset-id-badge {
        font-family: 'DM Mono', monospace;
        font-size: 11px;
        font-weight: 500;
        color: #64748b;
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 3px 10px;
    }

    .value-number {
        font-family: 'DM Mono', monospace;
        font-size: 26px;
        font-weight: 500;
        color: #1e293b;
        letter-spacing: -0.02em;
    }

    .value-sub {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #94a3b8;
        margin-top: 2px;
    }

    .info-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 24px 0;
    }

    .verified-banner {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        border-radius: 20px;
        padding: 24px 28px;
        color: #fff;
        box-shadow: 0 6px 20px rgba(29,78,216,0.25);
    }
</style>

<div class="page-wrap max-w-5xl mx-auto px-6 py-10">

    <!-- PAGE HEADER -->
    <div class="mb-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.assets.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left" style="font-size:13px;"></i>
            </a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-2xl font-extrabold text-slate-800" style="letter-spacing:-0.02em;">Detail Aset</h1>
                    <span class="asset-id-badge">#{{ $asset->id }}</span>
                </div>
                <p class="text-slate-400 text-sm font-medium">Informasi lengkap inventaris</p>
            </div>
        </div>
        <a href="{{ route('admin.assets.edit', $asset->id) }}" class="btn-edit">
            <i class="fa-solid fa-pen" style="font-size:11px;"></i>
            Edit Aset
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- KOLOM KIRI: MAIN INFO -->
        <div class="lg:col-span-2 space-y-6">
            <div class="section-card">

                <!-- IMAGE + TITLE ROW -->
                <div style="display:flex; gap:28px; align-items:flex-start;">
                    <div style="width:140px; height:140px; border-radius:20px; overflow:hidden; background:#f1f5f9; border:1.5px solid #e8edf5; flex-shrink:0;">
                        @if($asset->image)
                            <img src="{{ asset('storage/' . $asset->image) }}" alt="{{ $asset->name }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <div style="width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px;">
                                <i class="fa-solid fa-box-open" style="font-size:28px; color:#cbd5e1;"></i>
                                <span style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#cbd5e1;">No Image</span>
                            </div>
                        @endif
                    </div>

                    <div style="flex:1; padding-top:4px;">
                        <div style="margin-bottom:10px;">
                            <span class="kondisi-badge
                                @if($asset->kondisi === 'baru') kondisi-baru
                                @elseif($asset->kondisi === 'baik') kondisi-baik
                                @else kondisi-rusak
                                @endif">
                                @if($asset->kondisi === 'baru') <i class="fa-solid fa-star" style="font-size:8px;"></i>
                                @elseif($asset->kondisi === 'baik') <i class="fa-solid fa-circle-check" style="font-size:8px;"></i>
                                @else <i class="fa-solid fa-triangle-exclamation" style="font-size:8px;"></i>
                                @endif
                                {{ str_replace('_', ' ', $asset->kondisi) }}
                            </span>
                        </div>

                        <h2 style="font-size:22px; font-weight:800; color:#0f172a; letter-spacing:-0.02em; line-height:1.25; margin-bottom:8px;">{{ $asset->name }}</h2>

                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                            <i class="fa-solid fa-location-dot" style="font-size:11px; color:#94a3b8;"></i>
                            <span style="font-size:13px; font-weight:600; color:#64748b;">{{ $asset->room->name }}</span>
                            <span style="color:#e2e8f0;">·</span>
                            <span style="font-size:13px; font-weight:600; color:#3b82f6;">{{ $asset->category->name }}</span>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px;">
                            <div class="status-chip" style="background:#f0fdf4; color:#16a34a;">
                                <div class="dot" style="background:#16a34a;"></div>
                                {{ $asset->status }}
                            </div>
                            <span style="font-size:11px; color:#94a3b8; font-weight:500;">
                                Diperbarui {{ $asset->updated_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="info-divider"></div>

                <!-- STAT GRID -->
                <div class="grid grid-cols-3 gap-6" style="text-align:center;">
                    <div style="padding:20px; background:#f8fafc; border-radius:16px; border:1.5px solid #e8edf5;">
                        <div class="value-number">{{ $asset->quantity }}</div>
                        <div class="value-sub">Unit Tersedia</div>
                    </div>
                    <div style="padding:20px; background:#f8fafc; border-radius:16px; border:1.5px solid #e8edf5;">
                        <div class="value-number mono">{{ $asset->tahun_beli ?? '—' }}</div>
                        <div class="value-sub">Tahun Pengadaan</div>
                    </div>
                    
                </div>

            </div>
        </div>

        <!-- KOLOM KANAN: AUDIT SUMMARY -->
        <div class="space-y-6">

            <!-- AUDIT CARD -->
            <div class="section-card">
                <div style="font-size:10px; font-weight:800; letter-spacing:0.18em; text-transform:uppercase; color:#94a3b8; display:flex; align-items:center; gap:8px; margin-bottom:24px;">
                    <i class="fa-solid fa-chart-pie" style="color:#3b82f6;"></i>
                    Ringkasan Audit
                </div>

                <!-- NILAI PEROLEHAN -->
                <div style="margin-bottom:24px;">
                    <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; color:#94a3b8; margin-bottom:6px;">Nilai Perolehan</p>
                    <p class="mono" style="font-size:22px; font-weight:500; color:#1e293b; letter-spacing:-0.02em;">
                        Rp&nbsp;{{ number_format($asset->harga_beli, 0, ',', '.') }}
                    </p>
                </div>

                <!-- STAT ROWS -->
                <div>
                    <div class="stat-row">
                        <span class="stat-key">Kondisi</span>
                        <span class="kondisi-badge
                            @if($asset->kondisi === 'baru') kondisi-baru
                            @elseif($asset->kondisi === 'baik') kondisi-baik
                            @else kondisi-rusak
                            @endif" style="font-size:9px; padding:3px 10px;">
                            {{ str_replace('_', ' ', $asset->kondisi) }}
                        </span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-key">Tahun Beli</span>
                        <span class="stat-val mono">{{ $asset->tahun_beli ?? 'N/A' }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-key">Lokasi</span>
                        <span class="stat-val" style="font-size:12px;">{{ $asset->room->name }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-key">Total Unit</span>
                        <span class="stat-val" style="font-size:18px;">{{ $asset->quantity }} <span style="font-size:11px; color:#94a3b8; font-weight:500;">unit</span></span>
                    </div>
                </div>
            </div>

            <!-- VERIFIED BANNER -->
            <div class="verified-banner">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <span style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; opacity:0.7;">Terverifikasi</span>
                    <i class="fa-solid fa-shield-check" style="font-size:16px; opacity:0.8;"></i>
                </div>
                <p style="font-size:12px; font-weight:600; line-height:1.6; opacity:0.9;">
                    Seluruh data aset ini telah melalui proses audit dan verifikasi tahunan.
                </p>
            </div>

        </div>
    </div>

</div>
@endsection