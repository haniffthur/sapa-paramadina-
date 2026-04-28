@extends('layouts.app')

@section('content')
<div class="fixed inset-0 bg-slate-900 z-[100] flex flex-col">
    
    <!-- Header Back Navigation -->
    <div class="px-5 py-6 flex items-center justify-between relative z-10">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white/10 rounded-2xl flex items-center justify-center text-white backdrop-blur-md border border-white/20 active:scale-90 transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-white font-black tracking-[0.2em] text-[11px] uppercase">Scan Pintu Lab</h2>
        <div class="w-10 h-10"></div> <!-- Spacer buat nengahin judul -->
    </div>

    <!-- Area Kamera Scanner -->
    <div class="flex-1 relative flex items-center justify-center overflow-hidden rounded-t-[2.5rem] bg-black shadow-[0_-10px_40px_rgba(0,0,0,0.5)]">
        
        <!-- Wadah Render Kamera -->
        <div id="reader" class="w-full h-full object-cover"></div>
        
        <!-- UI Overlay Target Box (Biar cakep kayak scanner beneran) -->
        <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-10">
            <div class="w-64 h-64 border border-white/20 rounded-3xl relative">
                <!-- Siku-siku putih di pojok (Corner Accents) -->
                <div class="absolute -top-1 -left-1 w-10 h-10 border-t-4 border-l-4 border-white rounded-tl-3xl"></div>
                <div class="absolute -top-1 -right-1 w-10 h-10 border-t-4 border-r-4 border-white rounded-tr-3xl"></div>
                <div class="absolute -bottom-1 -left-1 w-10 h-10 border-b-4 border-l-4 border-white rounded-bl-3xl"></div>
                <div class="absolute -bottom-1 -right-1 w-10 h-10 border-b-4 border-r-4 border-white rounded-br-3xl"></div>
                
                <!-- Efek Garis Scan Jalan -->
                <div class="w-full h-0.5 bg-brand absolute top-1/2 -translate-y-1/2 shadow-[0_0_15px_rgba(30,58,138,0.8)] animate-pulse"></div>
            </div>
        </div>

        <!-- Instruksi di bawah -->
        <div class="absolute bottom-12 left-0 right-0 text-center z-20 px-8">
            <div class="bg-slate-900/60 backdrop-blur-md py-3 px-6 rounded-2xl inline-flex items-center gap-3 border border-white/10 shadow-xl">
                <div class="w-8 h-8 bg-brand rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-qrcode text-white text-xs"></i>
                </div>
                <p class="text-white text-[10px] font-bold uppercase tracking-widest text-left leading-tight">
                    Arahkan kamera ke<br>QR Code Ruangan
                </p>
            </div>
        </div>

    </div>
</div>

<!-- Library Scanner via CDN -->
<script src="https://unpkg.com/html5-qrcode"></script>

<!-- Script Logic Nyalain Kamera -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const html5QrCode = new Html5Qrcode("reader");
        
        // Konfigurasi ukuran kotak scan
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        // Fungsi kalau QR Code berhasil kebaca
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            // Hentikan kamera biar gak scan berkali-kali
            html5QrCode.stop().then((ignore) => {
                
                // Cek isi QR Code:
                // Kalo isinya link lengkap (http...), langsung diarahkan
                if(decodedText.startsWith('http')) {
                    window.location.href = decodedText;
                } else {
                    // Kalo isinya cuma token ruangan (misal: "TOKEN-LAB-01"), lempar ke route scan.room
                    window.location.href = `/scan/room/${decodedText}`;
                }
                
            }).catch((err) => {
                console.log(err);
            });
        };

        // Mulai kamera belakang (environment)
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
        .catch((err) => {
            // Munculin alert kalau mahasiswa belum ngasih izin akses kamera di browsernya
            alert("Bro, izin kameranya belum diaktifin nih di browser lo. Izinkan dulu ya!");
            window.location.href = "{{ route('dashboard') }}";
        });
    });
</script>
@endsection