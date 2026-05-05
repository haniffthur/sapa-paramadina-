@extends('layouts.app')

@section('content')
<!-- Wrapper Full Screen -->
<div class="fixed inset-0 z-[100] bg-slate-950 flex flex-col font-sans overflow-hidden">
    
    <!-- Camera Feed (Absolute Full Screen) -->
    <div id="reader" class="absolute inset-0 w-full h-full object-cover"></div>

    <!-- UI Overlay (Numpuk di atas kamera) -->
    <div class="absolute inset-0 z-10 flex flex-col pointer-events-none">
        
        <!-- Header Nav (Glassmorphism) -->
        <div class="px-5 py-8 flex items-center justify-between w-full pointer-events-auto">
            <a href="{{ route('dashboard') }}" class="w-12 h-12 bg-slate-900/40 backdrop-blur-xl rounded-full flex items-center justify-center text-white border border-white/20 active:scale-90 transition-transform shadow-lg">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div class="bg-slate-900/40 backdrop-blur-xl px-5 py-3 rounded-full border border-white/10 shadow-lg">
                <h2 class="text-white font-black tracking-[0.2em] text-[10px] uppercase flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-brand rounded-full animate-pulse"></span>
                    Scan Ruangan
                </h2>
            </div>
            <div class="w-12 h-12"></div> <!-- Spacer -->
        </div>

        <!-- Scanner Target Box (Tengah) -->
        <div class="flex-1 flex items-center justify-center relative">
            
            <!-- Trik Overlay Gelap: Pake shadow raksasa biar area luar kotak jadi gelap -->
            <div class="w-[280px] h-[280px] rounded-[2rem] relative shadow-[0_0_0_4000px_rgba(15,23,42,0.85)] border-2 border-white/10">
                
                <!-- Corner Accents (Siku-siku Premium) -->
                <div class="absolute -top-1 -left-1 w-12 h-12 border-t-4 border-l-4 border-brand rounded-tl-[2rem] shadow-[0_0_15px_rgba(30,58,138,0.5)]"></div>
                <div class="absolute -top-1 -right-1 w-12 h-12 border-t-4 border-r-4 border-brand rounded-tr-[2rem] shadow-[0_0_15px_rgba(30,58,138,0.5)]"></div>
                <div class="absolute -bottom-1 -left-1 w-12 h-12 border-b-4 border-l-4 border-brand rounded-bl-[2rem] shadow-[0_0_15px_rgba(30,58,138,0.5)]"></div>
                <div class="absolute -bottom-1 -right-1 w-12 h-12 border-b-4 border-r-4 border-brand rounded-br-[2rem] shadow-[0_0_15px_rgba(30,58,138,0.5)]"></div>
                
                <!-- Laser Animasi Keren -->
                <div class="absolute left-4 right-4 h-[3px] bg-gradient-to-r from-transparent via-brand to-transparent scan-laser drop-shadow-[0_0_8px_rgba(3b,130,246,1)] rounded-full"></div>
            </div>

        </div>

        <!-- Instruksi Card (Bawah) -->
        <div class="pb-12 px-6 pt-4 flex justify-center w-full">
            <div class="bg-white/10 backdrop-blur-2xl p-5 rounded-[2rem] border border-white/20 shadow-2xl w-full max-w-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-brand to-blue-600 rounded-[1.2rem] flex items-center justify-center shrink-0 shadow-lg shadow-brand/30">
                    <i class="fa-solid fa-qrcode text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white text-sm font-black mb-0.5 tracking-tight">Pindai QR Pintu</h3>
                    <p class="text-slate-300 text-[10px] font-medium leading-relaxed">
                        Arahkan kamera ke QR Code yang tertempel di pintu laboratorium atau ruangan.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Reset & Override UI Bawaan html5-qrcode biar bersih */
    #reader { border: none !important; background: #020617 !important; }
    #reader img { display: none !important; } /* Sembunyiin gambar default */
    #reader video { 
        object-fit: cover !important; 
        width: 100% !important; 
        height: 100% !important; 
    }
    
    /* Animasi Laser Naik Turun */
    .scan-laser {
        animation: scan 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }
    
    @keyframes scan {
        0% { top: 5%; opacity: 0; }
        15% { opacity: 1; }
        85% { opacity: 1; }
        100% { top: 95%; opacity: 0; }
    }
</style>

<!-- Library Scanner via CDN -->
<script src="https://unpkg.com/html5-qrcode"></script>

<!-- Script Logic Nyalain Kamera -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const html5QrCode = new Html5Qrcode("reader");
        
        // Konfigurasi ukuran disesuaikan dengan UI (aspect ratio HP)
        const config = { 
            fps: 15, 
            qrbox: { width: 280, height: 280 },
            aspectRatio: window.innerHeight / window.innerWidth
        };
        
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            html5QrCode.stop().then((ignore) => {
                
                // Tambahkan efek loading atau langsung redirect
                if(decodedText.startsWith('http')) {
                    window.location.href = decodedText;
                } else {
                    window.location.href = `/scan/room/${decodedText}`;
                }
                
            }).catch((err) => {
                console.error(err);
            });
        };

        // Mulai kamera belakang dengan proteksi error
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
        .catch((err) => {
            alert("Oops! Akses kamera ditolak atau perangkat tidak mendukung. Pastikan izin kamera aktif di browser Anda.");
            window.location.href = "{{ route('dashboard') }}";
        });
    });
</script>
@endsection