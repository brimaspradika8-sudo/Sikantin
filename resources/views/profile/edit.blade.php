<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('status') === 'seller-requested')
                <div class="rounded-3xl bg-yellow-50 dark:bg-yellow-900/50 border border-yellow-200 dark:border-yellow-700 p-6 text-yellow-800 dark:text-yellow-100 shadow-sm">
                    <p class="font-semibold">Permintaan Penjual Diterima</p>
                    <p class="mt-2">Permintaan Anda untuk menjadi penjual telah dikirim. Silakan tunggu konfirmasi dari admin.</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="rounded-3xl bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 p-6 text-red-800 dark:text-red-100 shadow-sm">
                    <p>{{ session('warning') }}</p>
                </div>
            @endif

            @if($user->role === 'user' || ($user->role === 'seller' && $user->status === 'rejected'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Ajukan Akun Penjual</h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-1">Jika Anda ingin menjual produk di SiKantin, ajukan permintaan untuk akun penjual.</p>
                        </div>
                        @if($user->role === 'seller' && $user->status === 'rejected')
                            <div class="rounded-2xl bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 p-4 text-red-700 dark:text-red-100">
                                Permintaan penjual sebelumnya ditolak. Silakan ajukan ulang dengan data yang lengkap.
                            </div>
                        @endif
                        <a href="{{ route('profile.seller-request') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white shadow hover:bg-brand-600 transition">
                            <i class="fa-solid fa-shop"></i> Ajukan Menjadi Penjual
                        </a>
                    </div>
                </div>
            @elseif($user->role === 'seller' && $user->status === 'pending')
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <div class="rounded-2xl bg-yellow-50 dark:bg-yellow-900/50 border border-yellow-200 dark:border-yellow-700 p-6 text-yellow-800 dark:text-yellow-100">
                            <h3 class="text-lg font-semibold">Permintaan Penjual Sedang Diproses</h3>
                            <p class="mt-2">Akun penjual Anda sedang menunggu konfirmasi dari admin. Kami akan mengirimkan notifikasi setelah status diperbarui.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
