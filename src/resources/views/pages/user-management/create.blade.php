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
    <section class="mx-20 md:mx-32 xl:mx-72">
        <div class="p-8 md:px-8 md:py-10 bg-[#F4F4F4] rounded-xl">
            <form id="form-ajax" action="{{ route('users.store') }}" method="POST">
                <div
                    class="px-4 pb-4 pt-10 bg-white border-4 border-yellow-0 rounded-xl shadow-md overflow-x-auto flex flex-col">
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="username" class="text-gray-600 w-full text-left">Username</label>
                        <div class="w-full">
                            <input type="text" name="username" id="username"
                                class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
              focus:outline-none focus:ring-1 focus:ring-yellow-0
              disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                required>
                            <label id="username-error" class="error text-sm text-red-500" for="username"></label>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="password_create" class="text-gray-600 w-full text-left">Password</label>
                        <div class="w-full">
                            <input type="password" name="password" id="password_create"
                                class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
              focus:outline-none focus:ring-1 focus:ring-yellow-0
              disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                required>
                            <label id="password-error" class="error text-sm text-red-500" for="password_create"></label>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="full_name" class="text-gray-600 w-full text-left">Nama Lengkap</label>
                        <div class="w-full">
                            <input type="text" name="full_name" id="full_name"
                                class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
              focus:outline-none focus:ring-1 focus:ring-yellow-0
              disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                required>
                            <label id="full_name-error" class="error text-sm text-red-500" for="full_name"></label>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="email" class="text-gray-600 w-full text-left">Email</label>
                        <div class="w-full">
                            <input type="email" name="email" id="email"
                                class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
              focus:outline-none focus:ring-1 focus:ring-yellow-0
              disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                required>
                            <label id="email-error" class="error text-sm text-red-500" for="email"></label>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="nik" class="text-gray-600 w-full text-left">NIK</label>
                        <div class="w-full">
                            <input type="text" name="nik" id="nik"
                                class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
              focus:outline-none focus:ring-1 focus:ring-yellow-0
              disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                required>
                            <label id="nik-error" class="error text-sm text-red-500" for="nik"></label>
                        </div>
                    </div>

                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="department_id" class="text-gray-600 w-full text-left">Department</label>
                        <div class="flex justify-end w-full flex-col">
                            <select name="department_id" type="text" class="w-full" id="department_id" required>
                                <option disabled selected>-- Pilih Department --</option>
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <label id="department_id-error" class="error text-sm text-red-500" for="department_id"></label>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="role_id" class="text-gray-600 w-full text-left">Role</label>
                        <div class="flex justify-end w-full flex-col">
                            <select name="role_id" type="text" class="w-full" id="role_id" required>
                                <option selected disabled>-- Pilih Role --</option>
                                @forelse ($roles as $item)
                                    <option value="{{ $item->id ?? '' }}">{{ $item->role_name ?? '' }}</option>
                                @empty
                                @endforelse
                            </select>
                            <label id="role_id-error" class="error text-sm text-red-500" for="role_id"></label>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2 items-center">
                        <label for="is_enable" class="text-gray-600 w-full text-left">Enable</label>
                        <div class="flex justify-end w-full flex-col">
                            <div class="flex items-center">
                                <input id="is_enable" name="is_enable" type="checkbox" value="1"
                                    class="w-4 h-4 rounded-md scale-75">
                            </div>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col space-y-2">
                        <label class="text-gray-600 w-full text-left mt-4">Permissions</label>
                        <div class="flex justify-end w-full flex-col">
                            <div class="flex items-center mb-2">
                                <input id="all-perm-button" name="all-perm-button" type="checkbox"
                                    class="w-4 h-4 rounded-md scale-75">
                                <label for="all-perm-button"
                                    class="ml-2 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">Select Semua
                                    Permission</label>
                            </div>
                            @forelse ($accessRights as $i => $item)
                                <div class="flex items-center">
                                    <input id="permission-{{ $item->id ?? '' }}" name="permissions[]" type="checkbox"
                                        value="{{ $item->id ?? '' }}" class="w-4 h-4 rounded-md scale-75 perm-edit"
                                        data-not-find="true">
                                    <label for="permission-{{ $item->id ?? '' }}"
                                        class="ml-2 text-gray-600 dark:text-gray-300 whitespace-nowrap">{{ $item->access_name ?? '' }}&nbsp;{{ $item->department->name ?? '' }}</label>
                                </div>

                            @empty
                            @endforelse
                        </div>
                    </div>
                    <div class="inline-flex justify-end items-center mt-8">
                        <button type="submit"
                            class="px-3 py-2 border-2 border-green-0 text-green-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-xl hover:bg-green-0 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                            <span class="mr-1"><i class="fas fa-floppy-disk"></i></span>
                            <span>Save</span>
                        </button>
                        <a href="{{ route('users.index') }}"
                            class="px-3 py-2 border-2 border-red-0 ml-2 text-red-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-xl hover:bg-red-0 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                            <span class="mr-1"><i class="fas fa-xmark"></i></span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                $(document).ready(function() {

                    $('#all-perm-button').change(function() {
                        console.log('tess');
                        $('.perm-edit').prop('checked', this.checked);
                    })
                });
            </script>
        @endonce
    @endpush
@endsection
