<div>
    <div class="w-full md:w-[50%] mx-auto">

        <flux:card>
            <flux:heading size="lg" class="mt-4 italic">
                <p>Aplikasi Absensi Berbasis PWA dapat digunakan pada Android dan Apple dengan syarat memiliki google chrome</p>
            </flux:heading>
            <flux:callout color="blue" class="mt-6 mb-6">
                <div class="flex items-center gap-4">
                    <flux:avatar size="lg" src="{{ url('/disk-s3/pwa/192/'.$instansi->logo, []) }}" />
                    <div>
                        <flux:heading size="lg">Taylor Otwell</flux:heading>
                        <flux:text>Creator of Laravel</flux:text>
                    </div>
                </div>
            </flux:callout>
            <div class="">
                <div id="installApp" style="display: none">
                    <div class="flex">
                        <flux:button variant="primary" icon="arrow-down-tray"  class="w-full ml-auto">Download Aplikasi</flux:button>
                    </div>
                </div>
                <div id="installAppDone" style="display: none">
                    <div class="flex">
                        <flux:button variant="filled" icon="arrow-down-tray" class="w-full ml-auto disabled">Aplikasi Telah Didownload</flux:button>
                    </div>
                </div>

            </div>
        </flux:card>

        

           
            
    </div>
</div>

@push("jsku")
    <script>
    let deferredPrompt;

    // Fungsi pembantu untuk memunculkan status "Telah Didownload"
    function showAppInstalledStatus() {
        const btnInstall = document.getElementById('installApp');
        const btnDone = document.getElementById('installAppDone');
        
        if (btnInstall) btnInstall.style.display = 'none';
        if (btnDone) btnDone.style.display = 'block';
    }

    // [BARU] Cek saat halaman pertama kali dimuat (Apakah dibuka via Aplikasi PWA / Standalone?)
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
    const isIOSStandalone = window.navigator.standalone === true; // Khusus untuk iOS Safari

    if (isStandalone || isIOSStandalone) {
        // Jika dibuka dari dalam aplikasi yang sudah terinstall, jalankan ini
        showAppInstalledStatus();
    }

    // 1. Registrasi Service Worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js');
    }

    // 2. Tangkap event install (Hanya terpicu di BROWSER jika BELUM terinstall)
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        const btn = document.getElementById('installApp');
        if (btn) btn.style.display = 'block';
    });

    // 3. Logika saat tombol ditekan
    const installBtnElement = document.getElementById('installApp');
    if (installBtnElement) {
        installBtnElement.addEventListener('click', async () => {
            if (!deferredPrompt) return;

            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response: ${outcome}`);

            deferredPrompt = null;
            
            // Jika user setuju menginstall, langsung ubah tampilannya
            if (outcome === 'accepted') {
                showAppInstalledStatus();
            }
        });
    }

    // 4. Deteksi jika proses instalasi baru saja selesai (Real-time klik install)
    window.addEventListener('appinstalled', () => {
        console.log('PWA sukses terpasang!');
        showAppInstalledStatus();
    });
</script>
@endpush
