@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Slide Mobile" />

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white">Form Edit Slide Mobile</h3>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Perbarui konten slide mobile untuk API.</p>
        </div>

        @include('superadmin.settings.mobile._form', [
            'action' => route('superadmin.settings.mobile.update', $slide),
            'method' => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ])
    </div>
@endsection
