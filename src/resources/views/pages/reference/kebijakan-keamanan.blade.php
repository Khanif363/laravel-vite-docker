@extends('layouts.blank')

@section('content')
    <section class="mt-20 mb-16 px-14">
        <div class="w-full">
            <div class="flex justify-center items-center text-gray-700">
                <i class="fas fa-lock fa-2xl"></i>
                <span class="ml-3 text-3xl font-semibold text-center underline">Kebijakan & Keamanan</span>
            </div>
            @php
                $kebijakan_keamanan = [
                    '1' => 'Menggunakan password dengan kriteria strong (minimal 8 karakter, huruf besar dan kecil serta karakter khusus).',
                    '2' => 'Menggunakan password yang berbeda dengan akun pribadi (social media dll).',
                    '3' => 'Wajib memperbarui password setiap 3 bulan sekali.',
                    '4' => 'Memastikan semua insiden sudah terassign pada teknisi/engineer apabila tidak tertangani service desk.',
                    '5' => 'Komitmen terhadap waktu penyelesaian insiden sesuai MTTR 2.5 jam.',
                    '6' => 'Apabila secara khusus penyelesaian insiden diminta dilanjutkan dilain hari oleh user, pastikan bahwa tiket sudah dilakukan pending dan terdapat bukti konfirmasi user.',
                    '7' => 'Apabila insiden selesai ditangani, pastikan bahwa terdapat konfirmasi user bahwa layanan sudah normal dan tiket dapat diclose.',
                ];
            @endphp
            <div class="flex flex-col space-y-2 mt-28">
                @foreach ($kebijakan_keamanan as $key => $val)
                    <div class="inline-flex">
                        <span class="text-gray-600 font-medium">{{ $key }}.</span>
                        <p class="text-gray-500 ml-2">{{ $val }}</p>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-center mt-20 items-center">
                <a href="{{ route('auth.login.show') }}"
                    class="inline-block px-6 py-2.5 mt-5 bg-red-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-red-1 hover:shadow-lg focus:bg-red-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-2 active:shadow-lg transition duration-150 ease-in-out"><i
                        class="fas fa-arrow-left"></i><span class="ml-2">Back</span></a>
            </div>
        </div>
    </section>
    <div class="fixed bottom-0 left-0 right-0 mb-6 w-full px-14">
        <div class="justify-between flex  text-blue-600">
            <span class="w-fit h-fit p-2 rounded-xl backdrop-blur-sm">Copyright 2022</span>
            <span class="w-fit h-fit p-2 rounded-xl backdrop-blur-sm">Telkom Satelit Indonesia</span>
        </div>
    </div>
@endsection
