@extends('layouts.blank')

@section('content')
    <section class="mt-20 mb-16 px-14">
        <div class="w-full">
            <div class="flex justify-center items-center text-gray-700">
                <i class="fas fa-dna fa-2xl"></i>
                <span class="ml-3 text-3xl font-semibold text-center underline">Pusat Pengetahuan</span>
            </div>
            <div class="flex flex-col space-y-8 mt-28">
                <div>
                    <div class="inline-flex">
                        {{-- <span class="text-gray-600 font-medium">1.</span>
                        <p class="text-gray-500 ml-2">Pusat Pengetahuan</p> --}}
                    </div>
                </div>
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
