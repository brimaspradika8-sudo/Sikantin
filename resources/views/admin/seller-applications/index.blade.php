@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pengajuan Penjual</h1>
            <p class="text-gray-600 mt-2">Kelola pengajuan penjual dari calon penjual</p>
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

        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Daftar Pengajuan</h2>
                    <div class="flex gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Pending: {{ $applications->filter(fn($a) => $a->isPending())->count() }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Approved: {{ $applications->filter(fn($a) => $a->isApproved())->count() }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Rejected: {{ $applications->filter(fn($a) => $a->isRejected())->count() }}
                        </span>
                    </div>
                </div>
            </div>

            @if($applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama Pengguna</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama Usaha</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jenis Produk</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($applications as $application)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $application->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $application->business_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $application->product_type }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($application->isPending())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Pending
                                            </span>
                                        @elseif($application->isApproved())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $application->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('admin.seller-applications.show', $application) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="p-6 text-center text-gray-600">
                    <p>Tidak ada pengajuan penjual</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
