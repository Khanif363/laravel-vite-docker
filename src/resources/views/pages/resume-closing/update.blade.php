@extends('layouts.master')

@section('content')
@push('css')
    <style>
        .select2-container .select2-selection--single,
            .select2-selection--multiple,
            .select2-search--dropdown .select2-search__field {
                border-color: #FFB344;
            }

            .select2-container .select2-selection--single:focus,
            .select2-search--dropdown .select2-search__field:focus {
                --tw-ring-color: #FFB344;
            }
    </style>
@endpush
    <section class="mx-auto md:mx-32 xl:mx-72">
        <div class="p-8 md:px-8 md:py-10 bg-[#F4F4F4] rounded-xl">
            <form action="{{ route('resumes.update', $data->id) }}" method="POST" id="form">
                @method('PUT')
                @csrf
                <div
                    class="px-4 pb-4 pt-10 bg-white border-4 border-yellow-0 rounded-xl shadow-md overflow-x-auto flex flex-col">
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="name" class="text-gray-600 w-full text-left">Resume</label>
                        <div class="w-full">
                            <input type="text" name="name" id="name"
                                class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
              focus:outline-none focus:ring-1 focus:ring-yellow-0
              disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
              invalid:border-pink-500 invalid:text-pink-600
              focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                value="{{ old('name') ?? $data->name }}">
                            @error('name')
                                <span class="inline-flex text-sm text-pink-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="name" class="text-gray-600 w-full text-left">Nama Department</label>
                        <div class="flex justify-end w-full flex-col">
                            <select name="department_id" type="text" class="w-full" id="department_id">
                                <option value="" disabled>-- Pilih Prioritas --</option>
                                @foreach ($data_view['departments_semicore'] as $departments)
                                    <option value="{{ $departments->id }}" @if ((old('department_id') ?? $data->department_id) == $departments->id) @endif>
                                        {{ $departments->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <span class="inline-text text-sm text-pink-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2 items-center mt-2">
                        <label for="status" class="text-gray-600 w-full text-left">Status?</label>
                        <div class="flex flex-col w-full">
                            <div class="flex flex-row md:justify-end justify-center">
                                <label class="flex radio p-2 cursor-pointer">
                                    <input name="status" class="my-auto transform scale-75" type="radio"
                                        value="1" @if ((old('status') ?? $data->status) == 1) checked @endif />
                                    <div class="title px-2">Aktive</div>
                                </label>

                                <label class="flex radio p-2 cursor-pointer">
                                    <input name="status" class="my-auto transform scale-75" type="radio"
                                        value="0" @if ((old('status') ?? $data->status) == 0) checked @endif />
                                    <div class="title px-2">Non Aktive</div>
                                </label>
                            </div>
                            @error('status')
                                <span class="inline-text text-sm text-pink-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="inline-flex justify-end items-center mt-8">
                        <button
                            class="px-3 py-2 border-2 border-green-0 text-green-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-xl hover:bg-green-0 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                            <span class="mr-1"><i class="fas fa-floppy-disk"></i></span>
                            <span>Save</span>
                        </button>
                        <a href="{{ route('resumes.index') }}"
                            class="px-3 py-2 border-2 border-red-0 ml-2 text-red-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-xl hover:bg-red-0 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                            <span class="mr-1"><i class="fas fa-xmark"></i></span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
