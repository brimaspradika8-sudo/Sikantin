<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <i class="fa-solid fa-cart-shopping text-yellow-500 mr-2"></i> Keranjang Belanja
                </h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Cek produk yang sudah dipilih, ubah jumlah, atau lanjut pembayaran.</p>
            </div>
    </x-slot>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm mb-6">
                    <i class="fa-solid fa-circle-check mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    @if($items->isEmpty())
                        <div class="text-center py-20 text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-shopping-basket text-5xl mb-6"></i>
                            <p class="text-xl font-semibold">Keranjang masih kosong</p>
                            <p class="mt-2">Silakan kembali ke katalog untuk memilih produk.</p>
                        </div>
                    @else
                        <div class="grid gap-6">
                            @php $total = 0; @endphp
                            @foreach($items as $item)
                                @php $subtotal = $item->quantity * $item->product->price; $total += $subtotal; @endphp
                                <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_200px] gap-6 items-center rounded-3xl border border-gray-200 dark:border-gray-700 p-6">
                                    <div class="flex items-start gap-4">
                                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-3xl overflow-hidden">
                                            @if($item->product->image)
                                                <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="h-full grid place-items-center text-brand-500"><i class="fa-solid fa-bowl-food text-2xl"></i></div>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                            
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <form action="{{ route('user.cart.update', $item) }}" method="POST" class="flex items-center gap-3 cart-quantity-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-20 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 cart-quantity-input" />
                                        </form>
                                        <form action="{{ route('user.cart.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Hapus dari keranjang</button>
                                        </form>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Subtotal</p>
                                        <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 rounded-3xl border border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Pembayaran</p>
                                    <p class="text-3xl font-black text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('user.checkout') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-brand-500 text-white font-semibold shadow hover:bg-brand-600 transition">
                                    Lanjut ke Pembayaran <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.cart-quantity-input').forEach(function (input) {
                let timeout;
                input.addEventListener('input', function () {
                    if (timeout) {
                        window.clearTimeout(timeout);
                    }
                    timeout = window.setTimeout(function () {
                        input.closest('form')?.submit();
                    }, 400);
                });
            });
        });
    </script>
</x-app-layout>
