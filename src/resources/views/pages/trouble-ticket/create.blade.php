@extends('layouts.master')

@section('content')
    @push('css')
        <style>
            input[type="time"]::-webkit-calendar-picker-indicator {
                display: none;
            }

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

    <section class="p-5 bg-[#F4F4F4] rounded-xl">
        <div class="flex items-center justify-center px-10 py-4 uppercase shadow-md bg-tosca-0 rounded-xl">
            <span class="text-lg font-semibold text-center text-white">Form Create Ticket</span>
        </div>

        <form id="form" action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div
                class="flex flex-col w-full px-32 py-6 mt-2 space-y-3 bg-white border-2 shadow-md border-tosca-0 rounded-xl">
                <ol class="justify-center w-full text-gray-600 align-top sm:flex">
                    <li class="relative mb-6 sm:mb-0">
                        <div class="flex items-center">
                            <div
                                class="flex z-10 justify-center items-center w-8 h-8 {{ $ticket == null ? 'bg-grey-0' : 'bg-green-0' }} rounded-full ring-0 ring-white sm:ring-8 shrink-0">
                            </div>
                            <div class="w-full text-center">
                                <div class="w-60"></div>
                                <div class="hidden w-full h-1 bg-gray-200 sm:flex"></div>
                            </div>
                        </div>
                        <div class="mt-3 sm:pr-8">
                            <h3 class="">Step 1</h3>
                        </div>
                    </li>
                    <li class="relative mb-6 sm:mb-0">
                        <div class="flex items-center">
                            <div
                                class="flex z-10 justify-center items-center w-8 h-8 {{ $ticket != null && ($ticket->step ?? null) == 2 ? 'bg-green-0' : 'bg-grey-0' }} bg-grey-0 rounded-full ring-0 ring-white sm:ring-8 shrink-0">
                            </div>
                        </div>
                        <div class="mt-3 sm:pr-8">
                            <h3 class="">Step 2</h3>
                        </div>
                    </li>
                </ol>

                <div class="flex flex-col space-y-2 {{ $ticket == null ? '' : 'hidden' }} step1">
                    <input name="creator_id" type="number" value="{{ auth()->user()->id }}" hidden>
                    <input type="number" name="step" value="1" hidden>
                    <div class="flex flex-col items-center w-full space-y-4 md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="category_step1" class="text-gray-600 whitespace-nowrap">Kategori<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="flex flex-col justify-end min-w-200">
                            <select name="category" type="text" class="w-full" id="category_step1">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach ($data_view['categories'] as $category)
                                    <option value="{{ $category }}"
                                        @if (old('category') == $category) {{ 'selected' }} @endif>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col items-center w-full space-y-4 md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="type_step1" class="text-gray-600 whitespace-nowrap">Jenis Ticket<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="flex flex-col justify-end min-w-200">
                            <select name="type" type="text" class="w-full" id="type_step1">
                                <option value="" disabled selected>-- Pilih Jenis --</option>
                                @foreach ($data_view['types'] as $types)
                                    @if (
                                        $permission['role'] === 'Admin' ||
                                            (in_array('Create Changes', $submenu_middleware) && in_array('Create Ticket', $submenu_middleware)))
                                        <option value="{{ $types }}"
                                            @if (old('type') == $types) {{ 'selected' }} @endif>
                                            {{ $types }}
                                        </option>
                                    @elseif (in_array('Create Ticket', $submenu_middleware) && $types !== 'Changes')
                                        <option value="{{ $types }}"
                                            @if (old('type') == $types) {{ 'selected' }} @endif>
                                            {{ $types }}
                                        </option>
                                    @elseif (in_array('Create Changes', $submenu_middleware) && $types === 'Changes')
                                        <option value="{{ $types }}"
                                            @if (old('type') == $types) {{ 'selected' }} @endif>
                                            {{ $types }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('type')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="reporter_name_step1" class="text-gray-600 whitespace-nowrap">Nama Pelapor<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="flex flex-col justify-end min-w-200">
                            <select name="reporter_name" type="text"
                                class="w-full flex !justify-end mr-0 select-with-word" id="reporter_name_step1">
                                <option value="" disabled selected>-- Pilih Nama --</option>
                            </select>
                            @error('reporter_name')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">

                        <div class="text-center min-w-150 md:text-start">
                            <label for="subject_step1" class="text-left text-gray-600 whitespace-nowrap">Subject<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="flex flex-col w-full">
                            <input name="subject" type="text" id="subject_step1"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                        focus:outline-none focus:ring-1 focus:ring-yellow-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                      invalid:border-pink-500 invalid:text-pink-600
                      focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                value="{{ old('subject') }}">
                            @error('subject')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="{{ $ticket == null ? 'hidden' : '' }} step2">
                <input type="number" name="id" value="{{ $ticket->id ?? 0 }}" hidden>
                <input name="creator_id" type="number" value="{{ auth()->user()->id }}" hidden>
                <input type="number" name="step" value="2" hidden>
                <div class="grid grid-cols-1 gap-2 mt-2 md:grid-cols-2">
                    <div class="flex items-center w-full p-4 bg-white border-2 shadow-md border-tosca-0 rounded-xl">
                        <div
                            class="flex flex-col items-center justify-center w-full space-y-4 md:flex-row md:space-x-8 md:space-y-0">
                            <div class="text-center min-w-150 md:text-start">
                                <label for="category" class="text-gray-600 whitespace-nowrap">Kategori<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col justify-end min-w-200">
                                <select name="category" type="text" class="w-full" id="category">
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    @foreach ($data_view['categories'] as $category)
                                        <option value="{{ $category }}"
                                            @if (old('category') == $category || ($ticket->category ?? '') == $category) {{ 'selected' }} @endif>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center w-full p-4 bg-white border-2 shadow-md border-tosca-0 rounded-xl">
                        <div
                            class="flex flex-col items-center justify-center w-full space-y-4 md:flex-row md:space-x-8 md:space-y-0">
                            <div class="text-center min-w-150 md:text-start">
                                <label for="type" class="text-gray-600 whitespace-nowrap">Jenis Ticket<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col justify-end min-w-200">
                                <select name="type" type="text" class="w-full" id="type">
                                    <option value="" disabled>-- Pilih Jenis --</option>
                                    @foreach ($data_view['types'] as $types)
                                        @if ($types !== 'Changes')
                                            <option value="{{ $types }}"
                                                @if (old('type') == $types || ($ticket->type ?? '') == $types) {{ 'selected' }} @endif>
                                                {{ $types }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('type')
                                    <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center w-full p-4 bg-white border-2 shadow-md border-tosca-0 rounded-xl"
                        id="parent-problem-type">
                        <div
                            class="flex flex-col items-center justify-center w-full space-y-4 md:flex-row md:space-x-8 md:space-y-0">
                            <div class="text-center min-w-150 md:text-start">
                                <label for="problem_type" class="text-gray-600 whitespace-nowrap">Tipe Gangguan<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col justify-end min-w-200">
                                <select name="problem_type" type="text" class="w-full" id="problem_type">
                                    <option value="" disabled>-- Pilih Ganguuan --</option>
                                    @foreach ($data_view['problem_types'] as $problem_types)
                                        <option value="{{ $problem_types }}"
                                            @if (old('problem_type') == $problem_types) {{ 'selected' }} @endif>
                                            {{ $problem_types }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('problem_type')
                                    <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center w-full p-4 bg-white border-2 shadow-md border-tosca-0 rounded-xl"
                        id="parent-service">
                        <div
                            class="flex flex-col items-center justify-center w-full space-y-4 md:flex-row md:space-x-8 md:space-y-0">
                            <div class="text-center min-w-150 md:text-start">
                                <label for="service_id" class="text-gray-600 whitespace-nowrap">Tipe Layanan<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col justify-end min-w-200">
                                <div class="relative flex justify-end w-full">
                                    <select name="service_id" type="text" class="w-full" id="service_id">
                                        <option value="" disabled selected>-- Pilih Service --</option>
                                        {{-- @foreach ($data_view['services'] as $services)
                                            <option value="{{ $services->id }}"
                                                @if (old('service_id') == $services->id) {{ 'selected' }} @endif>
                                                {{ $services->name }}
                                            </option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                @error('service_id')
                                    <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center w-full p-4 bg-white border-2 shadow-md border-tosca-0 rounded-xl">
                        <div
                            class="flex flex-col items-center justify-center w-full space-y-4 md:flex-row md:space-x-8 md:space-y-0">
                            <div class="text-center min-w-150 md:text-start">
                                <label for="priority" class="text-gray-600 whitespace-nowrap">Prioritas<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col justify-end min-w-200">
                                <select name="priority" type="text" class="w-full" id="priority">
                                </select>
                                @error('priority')
                                    <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $createdAt = \Carbon\Carbon::parse($ticket->created_date);
                    $tglCreate = $createdAt->format('m/d/Y');
                    $timeCreate = $createdAt->format('H:i');
                @endphp
                <div class="p-4 mt-2 bg-white border-2 shadow-md border-tosca-0 rounded-xl">
                    <div class="grid grid-cols-1 md:gap-x-16 lg:gap-x-20 gap-y-2 sm:grid-cols-1 md:grid-cols-2">
                        <div class="space-y-2">
                            <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                                <div class="text-center min-w-200 md:text-start">
                                    <label for="event_date" class="text-gray-600 whitespace-nowrap">WKT Pelaporan
                                        User<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="flex flex-col">
                                    <div class="flex flex-row justify-end w-full space-x-2 justify-self-end">
                                        <div class="flex flex-col w-full">
                                            <div class="relative flex justify-end w-full">
                                                <input name="event_date" datepicker datepicker-autohide type="text"
                                                    id="event_date"
                                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                                    value="{{ old('event_date') ?? $tglCreate }}">
                                                <i
                                                    class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                            </div>
                                            @error('event_date')
                                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="flex flex-col w-full">
                                            <div class="relative flex justify-end w-full">
                                                <input name="event_time" type="time" id="event_time"
                                                    class="timepicker shrink w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                        focus:outline-none focus:ring-1 focus:ring-yellow-0
                                        disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600 appearance-none bg-none"
                                                    value="{{ old('event_time') ?? $timeCreate }}">
                                                <i
                                                    class="fas fa-clock h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                            </div>
                                            @error('event_time')
                                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    @error('invalid_event_datetime')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                                <div class="text-center min-w-200 md:text-start">
                                    <label for="event_location_id" class="text-gray-600 whitespace-nowrap">Lokasi
                                        Kejadian<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="relative flex flex-col justify-center w-full md:justify-end">
                                    <select name="event_location_id" type="text" class="w-full flex !justify-end mr-0"
                                        id="event_location_id">
                                        <option value="" disabled selected>-- Pilih Lokasi --</option>
                                        @foreach ($data_view['locations'] as $locations)
                                            <option value="{{ $locations->id }}"
                                                @if (old('event_location_id') == $locations->id) {{ 'selected' }} @endif>
                                                {{ $locations->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('event_location_id')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0 form-create"
                                id="parent-name">
                                <div class="text-center min-w-200 md:text-start">
                                    <label for="reporter_name" class="text-gray-600 whitespace-nowrap">Nama Pelapor<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="relative flex flex-col justify-center w-full md:justify-end">
                                    <select name="reporter_name" type="text"
                                        class="w-full flex !justify-end mr-0 select-with-word" id="reporter_name">
                                        <option value="" disabled selected>-- Pilih Nama --</option>
                                    </select>
                                    @error('reporter_name')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                                <div class="text-center min-w-200 md:text-start">
                                    <label for="reporter_department" class="text-gray-600 whitespace-nowrap">Department
                                        Pelapor<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="relative flex flex-col justify-center w-full md:justify-end">
                                    <select name="reporter_department" type="text"
                                        class="w-full flex !justify-end mr-0" id="reporter_department">
                                        <option value="" disabled selected>-- Pilih Department --</option>
                                        @foreach ($data_view['departments'] as $departments)
                                            <option value="{{ $departments->id }}"
                                                @if (old('reporter_department') == $departments->id) {{ 'selected' }} @endif>
                                                {{ $departments->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('reporter_department')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="space-y-2">
                            <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                                <div class="text-center min-w-200 md:text-start">
                                    <label for="reporter_nik" class="text-left text-gray-600 whitespace-nowrap">NIK/NKK
                                        Pelapor<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="flex flex-col w-full">
                                    <div class="relative flex justify-end w-full">
                                        <input name="reporter_nik" type="text" id="reporter_nik"
                                            class="block w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                invalid:border-pink-500 invalid:text-pink-600
                                focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                            value="{{ old('reporter_nik') }}">
                                        <i
                                            class="fas fa-phone h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                    </div>
                                    @error('reporter_nik')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0"
                                id="parent-phone">
                                <div class="text-center min-w-200 md:text-start">
                                    <label for="reporter_phone" class="text-left text-gray-600 whitespace-nowrap">No.
                                        Telepon Pelapor<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>

                                <div class="flex flex-col w-full">
                                    <div class="relative flex justify-end w-full">
                                        <input name="reporter_phone" type="text" id=""
                                            class="block w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                invalid:border-pink-500 invalid:text-pink-600
                                focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                            value="{{ old('reporter_phone') }}">
                                        <i
                                            class="fas fa-phone h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                    </div>
                                    @error('reporter_phone')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0"
                                id="parent-email">

                                <div class="text-center min-w-200 md:text-start">
                                    <label for="reporter_email" class="text-left text-gray-600 whitespace-nowrap">Email
                                        Pelapor<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="flex-col w-full flex-">
                                    <div class="relative flex justify-end w-full">
                                        <input name="reporter_email" type="text" id="reporter_email"
                                            class="block w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                invalid:border-pink-500 invalid:text-pink-600
                                focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                            value="{{ old('reporter_email') }}">
                                        <i
                                            class="fas fa-envelope h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                    </div>
                                    @error('reporter_email')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                            <div class="text-center min-w-200 md:text-start">
                                <label for="source_info_trouble" class="text-gray-600 whitespace-nowrap">Sumber
                                    Info<sup><i class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex justify-end w-full space-x-2">
                                <div class="flex flex-col w-full">
                                    <select name="source_info_trouble" type="text" class="w-full"
                                        id="source_info_trouble">
                                        <option value="" disabled selected>-- Pilih Source --</option>
                                        @foreach ($data_view['sources_info_troubles'] as $sources_info_troubles)
                                            <option value="{{ $sources_info_troubles }}"
                                                @if (old('source_info_trouble') == $sources_info_troubles) {{ 'selected' }} @endif>
                                                {{ $sources_info_troubles }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('source_info_trouble')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex flex-col w-full">
                                    <select name="detail_info" type="text" class="w-full" id="detail_info">
                                        <option value="" disabled selected>-- Pilih Source Detail --</option>
                                        @foreach ($data_view['details_info'] as $details_info)
                                            <option value="{{ $details_info }}"
                                                @if (old('detail_info') == $details_info) {{ 'selected' }} @endif>
                                                {{ $details_info }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('detail_info')
                                        <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 mt-2 space-y-2 bg-white border-2 shadow-md border-tosca-0 rounded-xl">
                    <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">

                        <div class="text-center min-w-150 md:text-start">
                            <label for="subject" class="text-left text-gray-600 whitespace-nowrap">Subject<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="flex flex-col w-full">
                            <input name="subject" type="text" id="subject"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-yellow-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                      invalid:border-pink-500 invalid:text-pink-600
                      focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                placeholder="Subject" value="{{ old('subject') ?? ($ticket->subject ?? '') }}">
                            @error('subject')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="problem" class="text-left text-gray-600 whitespace-nowrap">Keterangan<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="w-full">
                            <textarea name="problem" id="problem"
                                class="ckeditor w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                placeholder="Keterangan" rows="3">{{ old('problem') }}</textarea>
                            @error('problem')
                                <span class="inline-flex text-sm text-pink-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="image_proof" class="text-left text-gray-600 whitespace-nowrap">Attachment
                                File<sup><i class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="flex flex-col w-full">
                            <div class="flex items-center justify-center md:justify-start">
                                {{-- <input type="file"
                            class="" /> --}}
                                <input name="image_proof[]" type="file" id="image_proof"
                                    class="text-sm text-slate-500 file:rounded-2xl rounded-2xl file:text-sm file:font-semibold file:py-1 file:bg-yellow-0 file:text-white hover:file:bg-yellow-2 "
                                    multiple />
                                @error('image_proof')
                                    <span
                                        class="inline-flex my-auto text-sm text-center text-pink-600">{{ $message }}</span>
                                @enderror
                                @if ($errors->has('image_proof.*'))
                                    @foreach ($errors->get('image_proof.*') as $key => $error)
                                        <span
                                            class="inline-flex my-auto text-sm text-center text-pink-600">{{ $errors->first($key) }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mt-2">
                                <span class="text-red-0"><i class="fas fa-circle-exclamation"></i></span>
                                <span class="text-sm text-gray-500 capitalize">
                                    UKURAN FILE MAKS 1024 KB, EKSTENSI FILE YANG DIIJINKAN GIF, JPG,
                                    PNG, PDF, JPEG, XLS, XLSX, DOC, DOCX
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('tickets.index') }}"
                    class="inline-block px-6 py-2.5 mt-5 bg-red-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-red-1 hover:shadow-lg focus:bg-red-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-2 active:shadow-lg transition duration-150 ease-in-out">Back</a>
                <button type="submit"
                    class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">
                    Submit
                </button>
            </div>
        </form>

    </section>
@endsection
@push('scripts')
    @once
        <script type="text/javascript">
            $(document).ready(function() {

                asReload();

                function asReload() {
                    typeTicket();
                    reporterNik();
                    getUser3Easy();
                }
                // $('#event_time').clockTimePicker( seperator: '.' );

                function typeTicket() {
                    let val = $('#type').val();
                    if (val) {
                        sessionStorage.setItem('type', val);
                    } else if (sessionStorage.getItem('type')) {
                        $('#type').val(sessionStorage.getItem('type')).trigger('change');
                    }
                    let type = sessionStorage.getItem('type');
                    switch (type) {
                        case 'Fulfillment':
                        case 'Service Request':
                            $('#parent-problem-type').hide();
                            $('#problem_type').attr('disabled', true);
                            $('#parent-service').show();
                            $('#service_id').attr('disabled', false)
                            break;
                        default:
                            $('#parent-problem-type').show();
                            $('#problem_type').attr('disabled', false);
                            $('#parent-service').hide();
                            $('#service_id').attr('disabled', true)
                    }
                }

                function infoTrouble() {
                    let val = $('#source_info_trouble').val();
                    if (val == 'Telp' || val == 'WhatsApp Personal' || val == 'WhatsApp Group' || val ==
                        'Memo' || val == 'Work Order') {
                        // $('#parent-email').hide();
                        $('#parent-phone').show();
                        $('#parent-name').show();
                    } else if (val == 'Email') {
                        $('#parent-phone').hide();
                        $('#parent-name').hide();
                        // $('#parent-email').show();
                    } else {
                        $('#parent-phone').hide();
                        // $('#parent-email').hide();
                        $('#parent-name').show();
                    }
                }

                $('#source_info_trouble').change(function() {
                    infoTrouble()
                })

                $('#type').change(function() {
                    typeTicket();
                })

                infoTrouble()

                function problemType() {
                    let val = $('[name="problem_type"]').val();
                    let selectPriority = `<option value="" disabled>-- Pilih Problem --</option>
                    <option value="Low" @if (old('priority') == 'Low' || ($ticket->priority ?? null) == 'Low') {{ 'selected' }} @endif>Low
                            </option>
                            <option value="Medium" @if (old('priority') == 'Medium' || ($ticket->priority ?? null) == 'Medium') {{ 'selected' }} @endif>Medium
                            </option>
                            <option value="High" @if (old('priority') == 'High' || ($ticket->priority ?? null) == 'High') {{ 'selected' }} @endif>High
                            </option>
                            `;
                    if (val != 'Non Gamas') {
                        selectPriority = `<option value="High" @if (old('priority') == 'High' || ($ticket->priority ?? null) == 'High') {{ 'selected' }} @endif>High
                            </option>`;
                    }

                    $('[name="priority"]').children().remove();
                    $('[name="priority"]').append(selectPriority);
                }

                $('[name="problem_type"]').change(function() {
                    problemType()
                });

                if ("{{ $ticket }}") {
                    $('.step1').remove();
                } else {
                    $('.step2').remove();
                }

                problemType()

                function category(val) {
                    const services = <?php echo json_encode($data_view['services']); ?>;
                    $('#service_id').empty();
                    $('#service_id').append(`<option value="" disabled selected>-- Pilih Layanan --</option>`);
                    services.forEach(function(item) {
                        if (item.category && item.category.includes(val)) {
                            $('#service_id').append(
                                `<option value="${item.id}">${item.name}</option>`);
                        }
                    });
                }

                if (<?php echo json_encode($ticket?->category); ?> ?? null)
                    category('{{ $ticket?->category }}')

                function getUser3Easy() {
                    apiCall({
                        url: `{{ route('user3easy.get-user-all') }}`,
                        success: function(response) {
                            response.data.forEach(function(item) {
                                let selected = ("{{ $ticket->ticketInfo->name ?? '' }}" ==
                                    item
                                    .nama) ? 'selected' : '';
                                $('[name=reporter_name]').append(
                                    `<option value="${item.nama}" ${selected} data-id="${item.id}">${item.nama}</option>`
                                );
                                if (selected) {
                                    $('[name=reporter_name]').val(item.nama).trigger('change');
                                }
                            });
                        }
                    });
                }

                $("#reporter_name").on("change", function() {
                    reporterNik();
                });

                $("#category").on("change", function() {
                    category($(this).val());
                });

                function reporterNik() {
                    let selectedOption = $("#reporter_name").find("option:selected");
                    let dataId = selectedOption.data("id");
                    if (dataId) {
                        let url = `{{ route('user3easy.detail', ':id') }}`;
                        url = url.replace(':id', dataId);
                        apiCall({
                            url: url,
                            success: function(response) {
                                $('#reporter_nik').val(response.data.nik);
                            }
                        });
                    }
                }
            });
        </script>
    @endonce
@endpush
