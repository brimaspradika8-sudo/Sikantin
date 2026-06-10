@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pengajuan Penjual</h1>
            <p class="text-gray-600 mb-2">Isi formulir di bawah untuk mengajukan diri sebagai penjual di Sikantin</p>
            <p class="text-sm text-gray-500 mb-8">Untuk memeriksa status persetujuan penjual, pastikan Anda sudah login dengan akun yang mengajukan permintaan (contoh: anita@gmail.com).</p>

            @if($application)
                <div class="mb-8 p-4 rounded-lg @if($application->isApproved()) bg-green-100 border border-green-400 @elseif($application->isPending()) bg-blue-100 border border-blue-400 @else bg-red-100 border border-red-400 @endif">
                    <p class="font-semibold @if($application->isApproved()) text-green-800 @elseif($application->isPending()) text-blue-800 @else text-red-800 @endif">
                        @if($application->isApproved())
                            ✓ Pengajuan Anda telah DISETUJUI
                        @elseif($application->isPending())
                            ⏳ Pengajuan Anda sedang DIPROSES
                        @else
                            ✗ Pengajuan Anda telah DITOLAK
                        @endif
                    </p>
                    @if($application->isRejected() && $application->rejection_reason)
                        <p class="text-sm mt-2">Alasan: {{ $application->rejection_reason }}</p>
                    @endif
                </div>

                @if($application->isApproved())
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-300 rounded-lg">
                        <p class="font-semibold text-blue-900 mb-4">Akun Penjual Anda Telah Disetujui</p>
                        <p class="text-sm text-gray-700 mb-2">Email Akun: <span class="font-mono bg-gray-200 px-2 py-1">{{ $application->sellerUser->email }}</span></p>
                        <p class="text-sm text-gray-700 mb-4">Silakan login kembali menggunakan akun ini. Jika Anda sudah memiliki password, gunakan password yang sama dengan akun Anda.</p>
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Login →</a>
                    </div>
                @endif
            @endif

            @if(!$application || $application->isRejected())
                <form action="{{ route('user.seller-application.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="business_name" class="block text-sm font-medium text-gray-900 mb-2">
                            Nama Usaha <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="business_name" 
                            id="business_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('business_name') border-red-500 @enderror"
                            value="{{ old('business_name', $application->business_name ?? '') }}"
                            required
                        >
                        @error('business_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-900 mb-2">
                            Alamat Usaha <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="address" 
                            id="address"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('address') border-red-500 @enderror"
                            required
                        >{{ old('address', $application->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact" class="block text-sm font-medium text-gray-900 mb-2">
                            Nomor Kontak <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            name="contact" 
                            id="contact"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('contact') border-red-500 @enderror"
                            placeholder="08xxxxxxxxx"
                            value="{{ old('contact', $application->contact ?? '') }}"
                            required
                        >
                        @error('contact')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="product_type" class="block text-sm font-medium text-gray-900 mb-2">
                            Jenis Produk yang Dijual <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="product_type" 
                            id="product_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('product_type') border-red-500 @enderror"
                            placeholder="Contoh: Makanan, Minuman, Snack"
                            value="{{ old('product_type', $application->product_type ?? '') }}"
                            required
                        >
                        @error('product_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($application?->isPending())
                        <button 
                            type="button"
                            class="w-full bg-gray-400 text-white py-2 rounded-lg font-semibold cursor-not-allowed"
                            disabled
                        >
                            Pengajuan sedang diproses...
                        </button>
                    @else
                        <button 
                            type="submit"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded-lg transition duration-200"
                        >
                            {{ $application?->isRejected() ? 'Ajukan Ulang' : 'Ajukan Sebagai Penjual' }}
                        </button>
                    @endif
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
