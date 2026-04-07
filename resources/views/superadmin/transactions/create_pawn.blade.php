@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tambah Transaksi Gadai</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Masukkan data transaksi gadai baru</p>
            </div>
            <a href="{{ route('superadmin.transactions.pawn') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Kembali</a>
        </div>
        
        <form method="POST" action="{{ route('superadmin.transactions.pawn.store') }}" enctype="multipart/form-data"
            class="space-y-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @csrf
            @include('superadmin.transactions._form_pawn')
            <div class="pt-4">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection
