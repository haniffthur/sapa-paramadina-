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

    .badge-section {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    select.field-input { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px; }

    .file-input-wrap {
        position: relative;
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

    .divider-label {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 24px 0;
    }
    .divider-label::before, .divider-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e8edf5;
    }
    .divider-label span {
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.15em;
        color: #cbd5e1;
        text-transform: uppercase;
        white-space: nowrap;
    }
</style>

<div class="page-wrap max-w-5xl mx-auto px-6 py-10">

    <!-- PAGE HEADER -->
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-3">
            <span class="badge-section" style="background:#eff6ff; color:#3b82f6;">
                <i class="fa-solid fa-circle-plus" style="font-size:9px;"></i>
                Pendaftaran Baru
            </span>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight" style="letter-spacing:-0.02em;">Tambah Inventaris Aset</h1>
        <p class="text-slate-400 mt-1 text-sm font-medium">Lengkapi seluruh informasi di bawah untuk mendaftarkan aset baru ke sistem.</p>
    </div>

    <form action="{{ route('admin.assets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KOLOM KIRI (2/3) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- INFORMASI TEKNIS -->
                <div class="section-card">
                    <div class="section-title" style="color:#3b82f6;">
                        <div class="dot" style="background:#3b82f6;"></div>
                        Informasi Teknis
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="field-label">Nama Perangkat / Aset</label>
                            <input type="text" name="name" class="field-input" placeholder="Contoh: MacBook Pro M2 — Lab Komputer A" required>
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="field-label">Lokasi Penempatan</label>
                                <select name="room_id" class="field-input" required>
                                    <option value="" disabled selected>Pilih Ruangan</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Kategori Aset</label>
                                <select name="category_id" class="field-input" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="field-label">Jumlah Unit</label>
                                <input type="number" name="quantity" min="1" value="1" class="field-input" required>
                            </div>
                            <div>
                                <label class="field-label">Foto Aset</label>
                                <div class="file-input-wrap">
                                    <input type="file" name="image" accept="image/*">
                                    <div class="file-input-visual">
                                        <i class="fa-solid fa-arrow-up-from-bracket" style="font-size:13px;"></i>
                                        Unggah gambar
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (1/3) -->
            <div class="space-y-6">

                <!-- DATA AUDIT -->
                <div class="section-card">
                    <div class="section-title" style="color:#94a3b8;">
                        <div class="dot" style="background:#94a3b8;"></div>
                        Data Audit
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="field-label">Tahun Pengadaan</label>
                            <input type="number" name="tahun_beli" value="{{ date('Y') }}" class="field-input mono">
                        </div>
                        <div>
                            <label class="field-label">Kondisi Fisik</label>
                            <select name="kondisi" class="field-input">
                                <option value="baru">Baru</option>
                                <option value="baik" selected>Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label">Nilai Satuan (Rp)</label>
                            <input type="number" name="harga_beli" class="field-input mono" placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- SUBMIT -->
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check" style="font-size:13px;"></i>
                    Daftarkan Aset
                </button>

                <!-- NOTE -->
                <div style="background:#f0f9ff; border:1.5px solid #bae6fd; border-radius:14px; padding:16px 18px;">
                    <p style="font-size:11px; color:#0369a1; font-weight:600; line-height:1.6;">
                        <i class="fa-solid fa-circle-info" style="margin-right:6px;"></i>
                        Pastikan semua data sudah benar sebelum menyimpan. Data yang telah disimpan dapat diubah melalui menu Edit.
                    </p>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection