<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Pembayaran</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Invoice {{ $firstOrder->payment?->invoice_number }} untuk {{ $firstOrder->order_number }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('user.orders.show', $firstOrder) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg border border-brand-500 text-brand-600 hover:bg-brand-50 transition">
                    <i class="fa-solid fa-box"></i> Detail Pesanan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Ringkasan Pesanan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Dipesan {{ $firstOrder->created_at->timezone(config('app.timezone'))->format('d M Y H:i') }}</p>
                            </div>
                            <span class="inline-flex w-fit items-center rounded-lg px-3 py-2 text-sm font-semibold {{ $firstOrder->statusClass() }}">{{ $firstOrder->statusLabel() }}</span>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 mb-6">
                            <div class="rounded-lg bg-gray-50 dark:bg-gray-900 p-4">
                                <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Pembeli</p>
                                <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $firstOrder->user->name }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 dark:bg-gray-900 p-4">
                                <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Penjual</p>
                                <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $firstOrder->seller->name ?? $firstOrder->vendor?->name ?? 'Penjual' }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 dark:bg-gray-900 p-4">
                                <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Estimasi Selesai</p>
                                <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $firstOrder->estimated_ready_at?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 dark:bg-gray-900 p-4">
                                <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Waktu Pengambilan</p>
                                <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $firstOrder->pickup_window_at?->timezone(config('app.timezone'))->format('d M Y H:i') ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($items as $item)
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-4 min-w-0">
                                            <div class="h-16 w-16 rounded-lg bg-gray-100 dark:bg-gray-900 overflow-hidden shrink-0">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full grid place-items-center text-gray-400"><i class="fa-solid fa-bowl-food"></i></div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <p class="font-bold text-gray-900 dark:text-white whitespace-nowrap">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($firstOrder->customer_note)
                            <div class="mt-6 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Catatan untuk penjual</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $firstOrder->customer_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 h-fit">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Instruksi Pembayaran</h3>

                    @if($firstOrder->payment_method === 'midtrans')
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Gunakan Midtrans Snap untuk QRIS, GoPay, ShopeePay, DANA/OVO jika tersedia, virtual account, atau kartu.</p>
                        <button type="button" id="pay-button" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-lg bg-brand-500 text-white font-semibold shadow hover:bg-brand-600 transition">
                            <i class="fa-solid fa-credit-card"></i> Bayar Online
                        </button>
                    @elseif($firstOrder->payment_method === 'manual_transfer')
                        <div class="rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-4 space-y-3 text-sm">
                            <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Bank</span><span class="font-semibold text-gray-900 dark:text-white">{{ $firstOrder->payment->bank_name }}</span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">No. Rekening</span><span class="font-semibold text-gray-900 dark:text-white">{{ $firstOrder->payment->account_number }}</span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Pemilik</span><span class="font-semibold text-gray-900 dark:text-white">{{ $firstOrder->payment->account_holder }}</span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 dark:text-gray-400">Nominal</span><span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                        </div>

                        <form action="{{ route('user.payment.proof', $firstOrder) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-3">
                            @csrf
                            <label for="payment_proof" class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Upload Bukti Transfer</label>
                            <input id="payment_proof" name="payment_proof" type="file" accept="image/png,image/jpeg,application/pdf" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-brand-500 text-white hover:bg-brand-600 transition">
                                <i class="fa-solid fa-upload"></i> Kirim Bukti
                            </button>
                        </form>

                        @if($firstOrder->payment->payment_proof)
                            <p class="mt-4 text-sm text-green-600">Bukti transfer sudah diunggah dan menunggu verifikasi.</p>
                        @endif
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-300">Pembayaran dilakukan saat pengambilan pesanan atau langsung di kantin. Simpan invoice ini untuk ditunjukkan ke penjual.</p>
                    @endif

                    <div class="mt-6 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                        <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Subtotal</span><span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($items->sum(fn($item) => $item->quantity * $item->price), 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Diskon</span><span class="font-semibold text-green-600">- Rp {{ number_format($firstOrder->discount_amount, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Pajak</span><span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($firstOrder->tax_amount, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Biaya layanan</span><span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($firstOrder->service_fee, 0, ',', '.') }}</span></div>
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                            <span class="font-semibold text-gray-900 dark:text-white">Total</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400 space-y-2">
                        <p>CSRF, validasi file, validasi nominal, dan webhook signature Midtrans aktif pada sisi server.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($firstOrder->payment_method === 'midtrans')
        <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $clientKey }}"></script>
        <script>
            const snapToken = @json($snapToken);

            document.getElementById('pay-button')?.addEventListener('click', function () {
                if (typeof snap === 'undefined' || !snapToken) {
                    alert('Token pembayaran belum tersedia. Silakan refresh halaman.');
                    return;
                }

                snap.pay(snapToken, {
                    onSuccess: function () {
                        fetch('{{ route("user.payment.clear") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                        window.location.href = "{{ route('user.orders.show', $firstOrder) }}?payment=success";
                    },
                    onPending: function () {
                        window.location.href = "{{ route('user.orders.show', $firstOrder) }}?payment=pending";
                    },
                    onError: function () {
                        window.location.href = "{{ route('user.orders.show', $firstOrder) }}?payment=error";
                    },
                    onClose: function () {
                        alert('Anda menutup pop-up pembayaran tanpa menyelesaikan pembayaran.');
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
