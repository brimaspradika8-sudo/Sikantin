@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('admin.seller-applications.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-4 inline-block">← Kembali</a>

        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="mb-8 pb-6 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900">Detail Pengajuan Penjual</h1>
                <p class="text-gray-600 mt-2">Nama Pengguna: {{ $application->user->name }} ({{ $application->user->email }})</p>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Status -->
            <div class="mb-8 p-4 rounded-lg @if($application->isApproved()) bg-green-100 border border-green-400 @elseif($application->isPending()) bg-blue-100 border border-blue-400 @else bg-red-100 border border-red-400 @endif">
                <p class="font-semibold @if($application->isApproved()) text-green-800 @elseif($application->isPending()) text-blue-800 @else text-red-800 @endif">
                    Status: 
                    @if($application->isApproved())
                        DISETUJUI
                    @elseif($application->isPending())
                        PENDING
                    @else
                        DITOLAK
                    @endif
                </p>
                @if($application->isRejected() && $application->rejection_reason)
                    <p class="text-sm mt-2">Alasan: {{ $application->rejection_reason }}</p>
                @endif
            </div>

            <!-- Application Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="text-sm font-medium text-gray-600">Nama Usaha</label>
                    <p class="text-lg text-gray-900 mt-1">{{ $application->business_name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Jenis Produk</label>
                    <p class="text-lg text-gray-900 mt-1">{{ $application->product_type }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Nomor Kontak</label>
                    <p class="text-lg text-gray-900 mt-1">{{ $application->contact }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Tanggal Pengajuan</label>
                    <p class="text-lg text-gray-900 mt-1">{{ $application->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <!-- Address -->
            <div class="mb-8">
                <label class="text-sm font-medium text-gray-600">Alamat Usaha</label>
                <p class="text-lg text-gray-900 mt-1">{{ $application->address }}</p>
            </div>

            <!-- Seller Account Info -->
            @if($application->isApproved() && $application->sellerUser)
                <div class="mb-8 p-4 bg-blue-50 border border-blue-300 rounded-lg">
                    <h3 class="font-semibold text-blue-900 mb-4">Informasi Akun Penjual</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-700">Email Penjual:</p>
                            <p class="text-lg font-mono text-gray-900">{{ $application->sellerUser->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-700">ID Penjual:</p>
                            <p class="text-lg font-mono text-gray-900">{{ $application->sellerUser->id }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            @if($application->isPending())
                <div class="border-t border-gray-200 pt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Tindakan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Approve Button -->
                        <form action="{{ route('admin.seller-applications.approve', $application) }}" method="POST" onsubmit="return confirm('Setujui pengajuan ini?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition duration-200">
                                ✓ Setujui Pengajuan
                            </button>
                        </form>

                        <!-- Reject Button (opens modal or form) -->
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition duration-200">
                            ✗ Tolak Pengajuan
                        </button>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-8 max-w-md w-full">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tolak Pengajuan</h3>
                        <form action="{{ route('admin.seller-applications.reject', $application) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-900 mb-2">
                                    Alasan Penolakan
                                </label>
                                <textarea 
                                    name="rejection_reason" 
                                    id="rejection_reason"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                    required
                                ></textarea>
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition duration-200">
                                    Tolak
                                </button>
                                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold py-2 rounded-lg transition duration-200">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
