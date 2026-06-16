<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Kelola Produk</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Tambahkan produk baru ke toko Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-8 xl:grid-cols-[280px_1fr]">
            @include('seller.partials.sidebar')
            <div>
                @if ($errors->any())
                    <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('seller.products.form', ['buttonText' => 'Tambah Produk'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
