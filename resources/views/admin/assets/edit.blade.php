@extends('layouts.admin')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    .page-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }

    .field-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #94a3b8;
        display: block;
        margin-bottom: 8px;
    }

    .field-input {
        width: 100%;
        padding: 13px 18px;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #1e293b;
        font-weight: 600;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        appearance: none;
    }
    .field-input::placeholder { color: #cbd5e1; font-weight: 500; }
    .field-input:focus {
        border-color: #3b82f6;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(59,130,246,0.08);
    }

    .section-card {
        background: #fff;
        border: 1.5px solid #e8edf5;
        border-radius: 24px;
        padding: 36px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(59,130,246,0.04);
    }

    .section-title {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 28px;
    }

    .section-title .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .btn-primary {
        width: 100%;
        background: #1d4ed8;
        color: #fff;
        padding: 16px 24px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 12px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(29,78,216,0.25);
    }
    .btn-primary:hover {
        background: #1e3a8a;
        box-shadow: 0 6px 20px rgba(29,78,216,0.3);
    }
    .btn-primary:active { transform: scale(0.98); }

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

    select.field-input { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px; }

    .image-preview-box {
        width: 80px;
        height: 80px;
        border-radius: 14px;
        overflow: hidden;
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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

    .file-input-wrap {
        position: relative;
        flex: 1;
    }
    .file-input-wrap input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    .file-input-visual {
        width: 100%;
        padding: 13px 18px;
        border-radius: 12px;
        border: 1.5px dashed #cbd5e1;
        background: #f8fafc;
        color: #94a3b8;
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: border-color 0.2s, background 0.2s;
        pointer-events: none;
    }
    .file-input-wrap:hover .file-input-visual {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #3b82f6;
    }
</style>

<div class="page-wrap max-w-5xl mx-auto px-6 py-10">

    <!-- PAGE HEADER -->
    <div class="mb-10 flex items-center gap-4">
        <a href="{{ route('admin.assets.index') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left" style="font-size:13px;"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight" style="letter-spacing:-0.02em;">Edit Inventaris</h1>
                <span class="asset-id-badge">#{{ $asset->id }}</span>
            </div>
            <p class="text-slate-400 text-sm font-medium">{{ $asset->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <!-- ALERT ERROR VALIDASI -->
        @if ($errors->any())
            <div style="background:#fef2f2; border:1.5px solid #fca5a5; border-radius:14px; padding:16px 20px; margin-bottom:24px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                    <i class="fa-solid fa-circle-exclamation" style="color:#ef4444; font-size:14px;"></i>
                    <span style="font-size:12px; font-weight:800; color:#b91c1c; text-transform:uppercase; letter-spacing:0.05em;">Gagal Menyimpan Data</span>
                </div>
                <ul style="color:#ef4444; font-size:12px; font-weight:600; padding-left:24px; list-style-type:disc; line-height:1.6;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KOLOM KIRI -->
            <div class="lg:col-span-2 space-y-6">
                <div class="section-card">
                    <div class="section-title" style="color:#3b82f6;">
                        <div class="dot" style="background:#3b82f6;"></div>
                        Detail Aset
                    </div>

                    <!-- IMAGE UPDATE ROW -->
                    <div style="display:flex; align-items:center; gap:16px; background:#f8fafc; border:1.5px solid #e8edf5; border-radius:16px; padding:16px 20px; margin-bottom:24px;">
                        <div class="image-preview-box">
                            @if($asset->image)
                                <img src="{{ asset('storage/' . $asset->image) }}" alt="Asset image">
                            @else
                                <i class="fa-solid fa-image" style="color:#cbd5e1; font-size:20px;"></i>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <label class="field-label" style="margin-bottom:6px;">Ganti Foto Aset</label>
                            <div class="file-input-wrap">
                                <input type="file" name="image" accept="image/*">
                                <div class="file-input-visual">
                                    <i class="fa-solid fa-arrow-up-from-bracket" style="font-size:12px;"></i>
                                    Pilih gambar baru
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="field-label">Nama Aset</label>
                            <input type="text" name="name" value="{{ old('name', $asset->name) }}" class="field-input" required>
                        </div>
                        <div>
                            <label class="field-label">Total Stok (Quantity)</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $asset->quantity) }}" class="field-input mono" required min="1">
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="field-label">Ruangan</label>
                                <select name="room_id" class="field-input">
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ $asset->room_id == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Kategori</label>
                                <select name="category_id" class="field-input">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $asset->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <div class="space-y-6">
                <div class="section-card">
                    <div class="section-title" style="color:#94a3b8;">
                        <div class="dot" style="background:#94a3b8;"></div>
                        Data Audit
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label class="field-label">Tahun Pengadaan</label>
                            <input type="number" name="tahun_beli" value="{{ $asset->tahun_beli }}" class="field-input mono">
                        </div>
                        <div>
                            <label class="field-label">Kondisi Fisik</label>
                            <select name="kondisi" class="field-input">
                                <option value="baru" {{ $asset->kondisi == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="baik" {{ $asset->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ $asset->kondisi == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label">Nilai Satuan (Rp)</label>
                            <input type="number" name="harga_beli" value="{{ (int)$asset->harga_beli }}" class="field-input mono">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk" style="font-size:13px;"></i>
                    Simpan Perubahan
                </button>

                <div style="background:#fff7ed; border:1.5px solid #fed7aa; border-radius:14px; padding:16px 18px;">
                    <p style="font-size:11px; color:#c2410c; font-weight:600; line-height:1.6;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i>
                        Perubahan yang disimpan tidak dapat dibatalkan. Pastikan data sudah benar.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection