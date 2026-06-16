<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Scan QR</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Gunakan kamera untuk memindai QR pickup dari pesanan siswa.</p>
            </div>
            <a href="{{ route('seller.orders.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full border border-brand-500 text-brand-600 hover:bg-brand-50 transition">
                <i class="fa-solid fa-receipt"></i> Lihat Pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-8 xl:grid-cols-[280px_1fr]">
            @include('seller.partials.sidebar')
            <div class="space-y-6">
                @if(session('success'))
                    <div class="rounded-3xl bg-green-50 border border-green-200 text-green-700 p-4">{{ session('success') }}</div>
                @endif
                @if(session('warning'))
                    <div class="rounded-3xl bg-yellow-50 border border-yellow-200 text-yellow-700 p-4">{{ session('warning') }}</div>
                @endif
                @if(session('error'))
                    <div class="rounded-3xl bg-red-50 border border-red-200 text-red-700 p-4">{{ session('error') }}</div>
                @endif

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Scanner Kamera</h3>
                    <div id="qr-reader" class="rounded-3xl overflow-hidden bg-black" style="min-height:360px;"></div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Pilih kamera perangkat Anda dan arahkan ke QR pickup. Hasil scan akan diproses secara otomatis.</p>
                </div>

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Masukkan Token secara Manual</h3>
                    <form action="{{ route('seller.scan.process') }}" method="POST" id="scan-form" class="grid gap-4">
                        @csrf
                        <input type="hidden" id="scanned-token" name="token" value="" />
                        <label class="block">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Token QR</span>
                            <input type="text" name="token" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" placeholder="Masukkan token QR jika kamera tidak tersedia" />
                        </label>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">Proses Token</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
    <script>
        const html5QrCode = new Html5Qrcode("qr-reader");

        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('scanned-token').value = decodedText;
            document.getElementById('scan-form').submit();
            html5QrCode.stop().catch(() => {});
        }

        function onScanError(errorMessage) {
            // ignore scan errors for continuous scanning
        }

        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                html5QrCode.start(
                    cameras[0].id,
                    { fps: 10, qrbox: { width: 280, height: 280 } },
                    onScanSuccess,
                    onScanError
                ).catch(err => {
                    console.error(err);
                });
            }
        }).catch(err => {
            console.error(err);
        });
    </script>
</x-app-layout>
