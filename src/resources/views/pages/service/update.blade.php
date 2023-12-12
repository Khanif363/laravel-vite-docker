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
            <form action="{{ route('services.update', $data->id) }}" method="POST" id="form">
                @method('PUT')
                @csrf
                <div
                    class="flex flex-col px-4 pt-10 pb-4 overflow-x-auto bg-white border-4 shadow-md border-yellow-0 rounded-xl">
                    <div class="flex flex-col items-center space-y-2 md:flex-row">
                        <label for="name" class="w-full text-left text-gray-600">Nama Layanan</label>
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
                    <div class="flex flex-col items-center space-y-2 md:flex-row">
                        <label for="name" class="w-full text-left text-gray-600">Nama Department</label>
                        <div class="flex flex-col justify-end w-full">
                            <select name="department_id" type="text" class="w-full" id="department_id">
                                <option value="" disabled>-- Pilih Department --</option>
                                @foreach ($data_view['departments_semicore'] as $departments)
                                    <option value="{{ $departments->id }}" @if ((old('department_id') ?? $data->department_id) == $departments->id) @endif>
                                        {{ $departments->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col items-center mt-2 space-y-2 md:flex-row">
                        <label for="status" class="w-full text-left text-gray-600">Status?</label>
                        <div class="flex flex-col w-full">
                            <div class="flex flex-row justify-center md:justify-end">
                                <label class="flex p-2 cursor-pointer radio">
                                    <input name="status" class="my-auto transform scale-75" type="radio"
                                        value="1" @if ((old('status') ?? $data->status) == 1) checked @endif />
                                    <div class="px-2 title">Aktive</div>
                                </label>

                                <label class="flex p-2 cursor-pointer radio">
                                    <input name="status" class="my-auto transform scale-75" type="radio"
                                        value="0" @if ((old('status') ?? $data->status) == 0) checked @endif />
                                    <div class="px-2 title">Non Aktive</div>
                                </label>
                            </div>
                            @error('status')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    {{Log::info($data)}}
                    <div class="flex flex-col space-y-2 md:flex-row">
                        <label class="w-full mt-4 text-left text-gray-600">Kategori</label>
                        <div class="flex flex-col justify-end w-full">
                            @forelse ($data_view['categories'] as $category)
                                <div class="flex items-center">
                                    <input id="{{ strtolower($category) }}" name="category[]" type="checkbox"
                                        value="{{ $category }}" class="w-4 h-4 scale-75 rounded-md perm-edit"
                                        data-not-find="true"
                                        {{ ($data && in_array($category, $data->category)) ?? false ? 'checked' : '' }}>
                                    <label for="{{ strtolower($category) }}"
                                        class="ml-2 text-gray-600 dark:text-gray-300 whitespace-nowrap">{{ $category }}</label>
                                </div>

                            @empty
                            @endforelse
                            @error('category')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="inline-flex items-center justify-end mt-8">
                        <button type="submit"
                            class="px-3 py-2 text-sm font-medium leading-tight capitalize transition duration-150 ease-in-out border-2 shadow-md border-green-0 text-green-0 hover:shadow-lg rounded-xl hover:bg-green-0 hover:text-white focus:outline-none focus:ring-0">
                            <span class="mr-1"><i class="fas fa-floppy-disk"></i></span>
                            <span>Save</span>
                        </button>
                        <a href="{{ route('services.index') }}"
                            class="px-3 py-2 ml-2 text-sm font-medium leading-tight capitalize transition duration-150 ease-in-out border-2 shadow-md border-red-0 text-red-0 hover:shadow-lg rounded-xl hover:bg-red-0 hover:text-white focus:outline-none focus:ring-0">
                            <span class="mr-1"><i class="fas fa-xmark"></i></span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
