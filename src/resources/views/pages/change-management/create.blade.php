@extends('layouts.master')

@section('content')
    @push('css')
        <style>
            input[type="time"]::-webkit-calendar-picker-indicator {
                display: none;
            }

            /* .select2-container--default .select2-selection--multiple, .select2-container--default, .select2-container--below {
                                                                                                                                                        height: auto !important;
                                                                                                                                                        overflow:inherit
                                                                                                                                                    }

                                                                                                                                                    .select2-container--below {
                                                                                                                                                        height: auto !important;
                                                                                                                                                        overflow:inherit
                                                                                                                                                    } */

            textarea.select2-search__field {
                resize: none;
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

            .ck-editor__editable_inline {
                min-height: 150px;
            }
        </style>
    @endpush
    {{-- @error('pic') --}}
    <div class="alert alert-danger1">
        <div class="flex-col p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <div class="flex flex-row items-center">
                <i class="flex-shrink-0 inline mr-3 fas fa-circle-info"></i>
                <span class="font-medium error" id="error-pic"></span>
            </div>
        </div>
    </div>
    <div class="alert alert-danger2">
        <div class="flex-col p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <div class="flex flex-row items-center">
                <i class="flex-shrink-0 inline mr-3 fas fa-circle-info"></i>
                <span class="font-medium error" id="error-not_edit"></span>
            </div>
        </div>
    </div>
    {{-- @enderror --}}
    <section class="p-5 bg-[#F4F4F4] rounded-xl overflow-y-hidden">
        <div class="flex items-center justify-center px-10 py-4 uppercase shadow-md bg-blue-0 rounded-xl">
            <span class="text-lg font-semibold text-center text-white">Form Create Change Request</span>
        </div>
        {{-- <div class="flex items-center justify-center px-10 py-2 mt-2 uppercase shadow-md bg-blue-0 rounded-xl">
            <span class="text-lg font-semibold text-center text-white">Information</span>
        </div> --}}
        {{-- <form action="{{ route('change-managements.store') }}" method="POST" id="form-changes" enctype="multipart/form-data"> --}}
        <form id="form-changes" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="number" name="id" value="{{ $changes->id ?? 0 }}" hidden>
            <input type="text" name="step" value="2" class="hidden">
            <input type="text" name="creator_id" value="{{ auth()->id() }}" class="hidden">
            <div class="grid grid-cols-1 gap-2 mt-2 overflow-y-hidden sm:grid-cols-1 md:grid-cols-2">
                <div class="p-4 bg-white border-2 shadow-md border-blue-0 rounded-xl">
                    <div class="inline-block text-gray-600">
                        <span><i class="fas fa-files"></i></span>
                        <span>Title<sup><i class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></span>
                    </div>
                    <div class="flex items-center py-2 border-b border-blue-0">
                        <input name="title"
                            class="w-full px-2 py-1 mr-3 leading-tight text-gray-700 bg-transparent border-none appearance-none focus:outline-none"
                            type="text" placeholder="..." aria-label="Full name"
                            value="{{ old('title') ?? ($ticket->subject ?? ($changes->title ?? '')) }}">
                    </div>
                    {{-- @error('title') --}}
                    <span class="text-sm text-pink-600 inline-text error" id="error-title"></span>
                    {{-- @enderror --}}
                    <div class="flex flex-col">
                        @if ($ticket)
                            <div class="flex flex-col items-center space-y-2 ticket md:flex-row">
                                <div class="text-center min-w-200 md:text-start">
                                    <label class="text-gray-600 whitespace-nowrap">
                                        Reference<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="flex flex-col justify-end w-full">
                                    <input name="reference" type="text" id="reference"
                                        class="block w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                invalid:border-pink-500 invalid:text-pink-600
                                focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                        value="Ticket" readonly>
                                    @error('reference')
                                        <span class="text-sm text-pink-600 inline-text error" id="error-reference"></span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center space-y-2 md:flex-row">
                                <div class="text-center min-w-200 md:text-start">
                                    <label class="text-gray-600 whitespace-nowrap">Referensi Changes<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="w-full">
                                    <select name="reference" type="text" class="w-full">
                                        <option value="" disabled selected>-- Pilih Reference --</option>
                                        @foreach ($data_view['references'] as $reference)
                                            <option value="{{ $reference }}"
                                                @if (old('reference') == $reference ||
                                                        (($ticket ?? null) != null && $reference === 'Ticket') ||
                                                        ($changes?->reference && $changes->reference == $reference)) {{ 'selected' }} @endif>
                                                {{ $reference }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- @error('reference') --}}
                                    <span class="text-sm text-pink-600 inline-text error" id="error-reference"></span>
                                    {{-- @enderror --}}
                                </div>
                            </div>
                        @endif
                        <div class="flex flex-col items-center space-y-2 ticket md:flex-row">
                            <div class="text-center min-w-200 md:text-start">
                                <label class="text-gray-600 whitespace-nowrap">Ticket
                                    Reference<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col justify-end w-full">
                                <input name="ticket_reference" type="text" id="ticket_reference"
                                    class="block w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                invalid:border-pink-500 invalid:text-pink-600
                                focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                    value="{{ old('ticket_reference') ?? ($ticket->nomor_ticket ?? '') }}"
                                    {!! $ticket->nomor_ticket ?? '' ? 'readonly' : '' !!}>
                                {{-- @error('ticket_reference') --}}
                                <span class="text-sm text-pink-600 inline-text error" id="error-ticket_reference"></span>
                                {{-- @enderror --}}
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-2 md:flex-row">
                            <div class="text-center min-w-200 md:text-start">
                                <label class="text-gray-600 whitespace-nowrap">Prioritas<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="w-full">
                                <select name="priority" id="priority" type="text" class="w-full">
                                    <option value="" disabled selected>-- Pilih Prioritas --</option>
                                    @foreach ($data_view['priorities'] as $priority)
                                        <option value="{{ $priority }}"
                                            @if (old('priority') == $priority || ($changes?->priority && $changes->priority == $priority)) {{ 'selected' }} @endif>
                                            {{ $priority }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- @error('priority') --}}
                                <span class="text-sm text-pink-600 inline-text error" id="error-priority"></span>
                                {{-- @enderror --}}
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-2 md:flex-row">
                            <div class="text-center min-w-200 md:text-start">
                                <label class="text-gray-600 whitespace-nowrap">Date/Time Aksi<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            @php
                                $carbon_now = \Carbon\Carbon::now(); // mengambil tanggal hari ini
                                $today = $carbon_now->format('m/d/Y');
                                $date_action = $changes?->datetime_action ? \Carbon\Carbon::parse($changes->datetime_action)->format('m/d/Y') : null;
                                $time_action = $changes?->datetime_action ? \Carbon\Carbon::parse($changes->datetime_action)->format('H:i') : null;
                            @endphp
                            <div class="flex flex-row justify-end w-full space-x-2 justify-self-end">
                                <div class="flex flex-col w-full">
                                    <div class="relative flex justify-end w-full">
                                        <input name="date_action" datepicker datepicker-autohide type="text"
                                            id="date_action"
                                            class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                        focus:outline-none focus:ring-1 focus:ring-yellow-0
                                        disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                            value="{{ old('date_action') ?? ($date_action ?? $today) }}">
                                        <i
                                            class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                    </div>
                                    {{-- @error('date_action') --}}
                                    <span class="text-sm text-pink-600 inline-text error" id="error-date_action"></span>
                                    {{-- @enderror --}}
                                </div>
                                <div class="flex flex-col w-full">
                                    <div class="relative flex justify-end w-full">
                                        <input name="time_action" type="time" id="time_action"
                                            class="timepicker w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                        focus:outline-none focus:ring-1 focus:ring-yellow-0
                                        disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600 appearance-none bg-none"
                                            value="{{ old('time_action') ?? ($time_action ?? null) }}">
                                        <i
                                            class="fas fa-clock h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                    </div>
                                    {{-- @error('time_action') --}}
                                    <span class="text-sm text-pink-600 inline-text error" id="error-time_action"></span>
                                    {{-- @enderror --}}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-2 md:flex-row">
                            <div class="text-center min-w-200 md:text-start">
                                <label class="text-gray-600 whitespace-nowrap">PIC Telkomsat<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col w-full">
                                <textarea name="pic_telkomsat" id="pic_telkomsat"
                                    class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                            invalid:border-pink-500 invalid:text-pink-600
                            focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                    placeholder="" rows="3">{{ old('pic_telkomsat') ?? $changes?->pic_telkomsat }}</textarea>
                                {{-- @error('pic_telkomsat') --}}
                                <span class="text-sm text-pink-600 inline-text error" id="error-pic_telkomsat"></span>
                                {{-- @enderror --}}
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-2 md:flex-row">
                            <div class="text-center min-w-200 md:text-start">
                                <label class="text-gray-600 whitespace-nowrap">PIC Non
                                    Telkomsat<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col w-full">
                                <textarea name="pic_nontelkomsat"
                                    class="block w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                            invalid:border-pink-500 invalid:text-pink-600
                            focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                    placeholder="" id="pic_nontelkomsat" rows="3">{{ old('pic_nontelkomsat') ?? $changes?->pic_nontelkomsat }}</textarea>
                                {{-- @error('pic_nontelkomsat') --}}
                                <span class="text-sm text-pink-600 inline-text error" id="error-pic_nontelkomsat"></span>
                                {{-- @enderror --}}
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-2 md:flex-row">
                            <div class="text-center min-w-200 md:text-start">
                                <label class="text-gray-600 whitespace-nowrap">Tipe Changes<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="w-full">
                                <select name="type[]" id="type" type="text" class="w-full"
                                    multiple="multiple">
                                    <option disabled>-- Pilih Tipe --</option>

                                    @foreach ($data_view['cr_types'] as $cr_type)
                                        <option value="{{ $cr_type }}"
                                            @if (old('type') == $cr_type || ($changes?->type && $changes->type == $cr_type)) {{ 'selected' }} @endif>
                                            {{ $cr_type }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- @error('type') --}}
                                <span class="text-sm text-pink-600 inline-text error" id="error-type"></span>
                                {{-- @enderror --}}
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-2 md:flex-row">
                            <div class="text-center min-w-200 md:text-start">
                                <label class="text-gray-600 whitespace-nowrap">Location<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="w-full">
                                <select name="location_id" id="location_id" type="text" class="w-full">
                                    <option value="" disabled selected>-- Pilih Lokasi --</option>
                                    {{-- @foreach ($data_view['locations'] as $location)
                                        <option value="{{ $location->id }}"
                                            @if (old('location_id') == $location->id) {{ 'selected' }} @endif>
                                            {{ $location->name }}</option>
                                    @endforeach --}}

                                    @foreach ($data_view['locations'] as $location)
                                        <option value="{{ $location->id }}"
                                            @if (old('location_id') == $location->id || ($changes?->location->id && $changes->location->id == $location->id)) {{ 'selected' }} @endif>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- @error('location_id') --}}
                                <span class="text-sm text-pink-600 inline-text error" id="error-location_id"></span>
                                {{-- @enderror --}}
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-2 md:flex-row">
                            <div class="text-center min-w-200 md:text-start">
                                <label class="text-gray-600 whitespace-nowrap">Agenda<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col w-full">
                                <textarea name="agenda"
                                    class="block w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                invalid:border-pink-500 invalid:text-pink-600
                                focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                                    placeholder="" id="agenda" rows="3">{{ old('agenda') ?? $changes?->agenda }}</textarea>
                                {{-- @error('agenda') --}}
                                <span class="text-sm text-pink-600 inline-text error" id="error-agenda"></span>
                                {{-- @enderror --}}
                            </div>
                        </div>
                        {{-- @error('agenda') --}}
                        <span class="text-sm text-pink-600 inline-text error"></span>
                        {{-- @enderror --}}
                    </div>
                </div>
                <div class="flex flex-col h-full space-y-2 overflow-y-hidden">
                    <div
                        class="flex items-center justify-center px-10 py-2 uppercase shadow-md bg-blue-0 rounded-xl h-fit">
                        <span class="text-lg font-semibold text-center text-white">PIC</span>
                    </div>
                    <div
                        class="flex flex-col items-stretch h-full p-4 space-y-6 bg-white border-2 shadow-md border-blue-0 rounded-xl">

                        <div class="flex flex-col mt-2 space-y-2">
                            <div class="min-w-300 text-start">
                                <label class="text-gray-600 whitespace-nowrap">Nama Engineer<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col w-full mt-2">
                                <div class="flex flex-col">
                                    <select type="text" class="w-full" name="engineer_id" id="engineer_id">
                                        <option value="" disabled selected>-- Pilih Nama --</option>


                                        @foreach ($data_view['engineers'] as $user)
                                            <option value="{{ $user->id }}"
                                                @if (old('engineer_id') == $user->id || ($changes?->engineer->id && $changes->engineer->id == $user->id)) {{ 'selected' }} @endif>
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- @error('engineer_id') --}}
                                    <span class="text-sm text-pink-600 inline-text error" id="error-engineer_id"></span>
                                    {{-- @enderror --}}
                                    @if (!$changes?->id || $changes?->is_draft)
                                        <div class="mt-1 ml-2 whitespace-nowrap">
                                            <input name="engineer_email" type="checkbox" value="1"
                                                id="engineer_email"
                                                {{ old('engineer_email') ?? $changes?->email_to_level0 ? 'checked' : '' }}
                                                class="w-4 h-4 scale-75 rounded-md">
                                            <label class="ml-1 text-gray-600 dark:text-gray-300 whitespace-nowrap"
                                                for="engineer_email">Email</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col mt-2 space-y-2">
                            <div class="min-w-300 text-start">
                                <label class="text-gray-600 whitespace-nowrap">Persetujuan Oleh
                                    Manager<sup><i
                                            class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col w-full mt-2">
                                <div class="flex flex-col">
                                    <select type="text" class="w-full" name="approval_level1_id"
                                        id="approval_level1_id">
                                        <option value="" disabled selected>-- Pilih Nama --</option>
                                        {{-- @foreach ($data_view['managers'] as $user)
                                            <option value="{{ $user->id }}"
                                                @if (old('approval_level1_id') == $user->id) {{ 'selected' }} @endif>
                                                {{ $user->full_name }}</option>
                                        @endforeach --}}

                                        @foreach ($data_view['managers'] as $user)
                                            <option value="{{ $user->id }}"
                                                @if (old('approval_level1_id') == $user->id || ($changes?->approval1?->id && $changes->approval1->id == $user->id)) {{ 'selected' }} @endif>
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- @error('approval_level1_id') --}}
                                    <span class="text-sm text-pink-600 inline-text error"
                                        id="error-approval_level1_id"></span>
                                    {{-- @enderror --}}
                                    @if (!$changes?->id || $changes?->is_draft)
                                        <div class="mt-1 ml-2 whitespace-nowrap">
                                            <input name="manager_email" type="checkbox" value="1"
                                                id="manager_email"
                                                {{ old('manager_email') ?? $changes?->email_to_level1 ? 'checked' : '' }}
                                                class="w-4 h-4 scale-75 rounded-md">
                                            <label class="ml-1 text-gray-600 dark:text-gray-300 whitespace-nowrap"
                                                for="manager_email">Email</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-1 ml-2 whitespace-nowrap with-approve-gm">
                            <input name="with_approve_gm" type="checkbox" value="1"
                                class="w-4 h-4 scale-75 rounded-md" id="with_approve_gm">
                            <label class="ml-1 text-gray-600 dark:text-gray-300 whitespace-nowrap"
                                for="with_approve_gm">Perlu persetujuan GM</label>
                        </div>
                        <div class="flex flex-col mt-2 space-y-2 gm-approve">
                            <div class="min-w-300 text-start">
                                <label class="text-gray-600 whitespace-nowrap">Persetujuan Oleh
                                    GM<sup><i class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                            </div>
                            <div class="flex flex-col w-full mt-2">
                                <div class="flex flex-col">
                                    <select type="text" class="w-full" name="approval_level2_id"
                                        id="approval_level2_id">
                                        <option value="" disabled selected>-- Pilih User --</option>
                                        {{-- @foreach ($data_view['general_managers'] as $user)
                                            <option value="{{ $user->id }}"
                                                @if (old('approval_level2_id') == $user->id) {{ 'selected' }} @endif>
                                                {{ $user->full_name }}</option>
                                        @endforeach --}}

                                        @foreach ($data_view['general_managers'] as $user)
                                            <option value="{{ $user->id }}"
                                                @if (old('approval_level2_id') == $user->id || ($changes?->approval2?->id && $changes->approval2->id == $user->id)) {{ 'selected' }} @endif>
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- @error('approval_level2_id') --}}
                                    <span class="text-sm text-pink-600 inline-text error"
                                        id="error-approval_level2_id"></span>
                                    {{-- @enderror --}}
                                    @if (!$changes?->id || $changes?->is_draft)
                                        <div class="mt-1 ml-2 whitespace-nowrap">
                                            <input name="gm_email" type="checkbox" value="1" id="gm_email"
                                                {{ old('gm_email') ?? $changes?->email_to_level2 ? 'checked' : '' }}
                                                class="w-4 h-4 scale-75 rounded-md">
                                            <label class="ml-1 text-gray-600 dark:text-gray-300 whitespace-nowrap"
                                                for="gm_email">Email</label>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col p-4 mt-2 space-y-2 overflow-y-hidden bg-white border-2 shadow-md border-blue-0 rounded-xl">
                <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label class="text-gray-600 whitespace-nowrap">Content<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full mt-2">
                        <textarea name="content_ckeditor"
                            class="block ckeditor w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                    focus:outline-none focus:ring-1 focus:ring-yellow-0
                    disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                    invalid:border-pink-500 invalid:text-pink-600
                    focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                            placeholder="Content" id="content_ckeditor">{{ old('content_ckeditor') ?? $changes?->content }}</textarea>
                        {{-- @error('content') --}}
                        <span class="text-sm text-pink-600 inline-text error" id="error-content"></span>
                        {{-- @enderror --}}
                    </div>
                </div>

                <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label class="text-gray-600 whitespace-nowrap">Attachment File{!! !$changes?->id ? '<sup><i class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup>' : '' !!}</label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="flex justify-start">
                            <input name="image_proof[]" type="file" id="image_proof"
                                class="text-sm text-slate-500 file:rounded-2xl rounded-2xl file:text-sm file:font-semibold file:py-1 file:bg-yellow-0 file:text-white hover:file:bg-yellow-2"
                                multiple />
                            {{-- @error('image_proof') --}}
                            <span class="inline-flex my-auto text-sm text-center text-pink-600 error"
                                id="error-image_proof"></span>
                            {{-- @enderror --}}
                            @if ($errors->has('image_proof.*'))
                                @foreach ($errors->get('image_proof.*') as $key => $error)
                                    <span
                                        class="inline-flex my-auto text-sm text-center text-pink-600">{{ $errors->first($key) }}</span>
                                @endforeach
                            @endif
                        </div>
                        <div class="{{ count($changes?->attachments ?? []) > 0 ? "my-2" : '' }}">
                            @if (count($changes?->attachments ?? []) > 0)
                            <span class="text-sm text-gray-500">Attach exist</span>
                            @endif
                            <div class="grid w-full h-full grid-cols-8 gap-2">
                                @if ($changes?->id)
                                    @foreach ($changes?->attachments as $item)
                                        @php
                                            $filePath = str_replace('\\', '/', public_path('storage/' . str_replace('public/', '', $item->url)));
                                            $extension = pathinfo($item->name, PATHINFO_EXTENSION);
                                        @endphp
                                        @if (file_exists($filePath))
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $item->url)) }}" target="_blank" class="w-full h-full border rounded-md border-slate-500 prevent-link" id="{{"image".$item->id}}">
                                                <div class="relative remove-image" data-id="{{$item->id}}"><div class="absolute cursor-pointer top-2 right-2"><i class="fas fa-circle-xmark text-red-0"></i></div></div>
                                                @if (in_array($extension, ['gif', 'jpg', 'png', 'jpeg']))
                                                    <div class="flex items-center justify-center w-full h-full">
                                                        <img src="{{ asset("storage/".str_replace('public/', '', $item->url)) }}" src="Image Preview" width="100%" height="100%" />
                                                    </div>
                                                @else
                                                    <div style="min-width: 90px; min-height: 70px; width: 100%; height: 100%; " class="flex items-center justify-center w-full h-full"><span class="text-gray-700">{{strtoupper($extension)}}</span></div>
                                                @endif
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-red-0"><i class="fas fa-circle-exclamation"></i></span>
                            <span class="text-sm text-gray-500 capitalize warn-image">
                                UKURAN FILE MAKS 5000 KB, EKSTENSI FILE YANG DIIJINKAN GIF, JPG,
                                PNG, PDF, JPEG, XLS, XLSX, DOC, DOCX
                            </span>
                        </div>

                    </div>
                </div>
                <div class="p-2 mt-8">
                    <a href="mailto: comitcc@telkomsat.co.id" class="text-gray-600 border-b border-gray-500"><i
                            class="mr-2 fas fa-envelope text-red-0"></i>comitcc@telkomsat.co.id</a>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('change-managements.index') }}"
                    class="inline-block px-6 py-2.5 mt-5 bg-blue-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-red-1 hover:shadow-lg focus:bg-red-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-2 active:shadow-lg transition duration-150 ease-in-out">Back</a>
                <div class="space-x-2">
                    @if ($changes == null || $changes?->is_draft)
                        <button type="submit" name="action" value="save"
                            class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">
                            Save
                        </button>
                    @endif
                    <button type="submit" name="action" value="submit"
                        class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    @once
        <script type="text/javascript">
            // import("select2").then(m => m.default());
            $(document).ready(function() {
                asReload();
                let button_type = '';
                let images_removed = [];

                function asReload() {
                    referenceChanges();
                    $('.gm-approve').hide();
                    $('.with-approve-gm').hide();
                    $('#engineer_email').prop('checked', true);
                    $('#manager_email').prop('checked', true);
                }

                $('#reference').change(function() {
                    referenceChanges()
                });

                $('input[name="with_approve_gm"]').change(function() {
                    const checked = $(this).is(':checked');
                    $('.gm-approve').show();
                    if (!checked) {
                        $('#approval_level2_id').select2().val(null).trigger("change")
                        $('#gm_email').prop('checked', false);
                        $('.gm-approve').hide();
                    }
                });

                $('.prevent-link .fas.fa-circle-xmark').click(function(e) {
                    e.preventDefault();
                });

                if ('{{ $changes?->priority }}') setByPriority()

                function setByPriority() {
                    let val = $('#priority').val();
                    console.log(val);
                    $('.gm-approve').hide();
                    switch (val) {
                        case 'Low':
                            $('.with-approve-gm').hide();
                            $('.gm-approve').hide();
                            $('#with_approve_gm').prop('checked', false);
                            $('#approval_level2_id').select2().val(null).trigger("change")
                            $('#gm_email').prop('checked', false);
                            break;
                        case 'High':
                            $('.with-approve-gm').hide();
                            $('.gm-approve').show();
                            $('#gm_email').prop('checked', true);
                            break;
                        default:
                            $('.with-approve-gm').show();
                            $('.gm-approve').show();
                            $('#with_approve_gm').prop('checked', true);
                            $('#gm_email').prop('checked', true);
                    }
                }

                $('#priority').change(function() {
                    setByPriority()
                })

                function referenceChanges() {
                    let val = $('#reference').val();

                    switch (val) {
                        case 'Ticket':
                            $('.ticket').fadeIn(200);
                            break;
                        default:
                            $('#ticket_reference').val('');
                            $('.ticket').fadeOut(200);
                    }
                }

                $('button[type="submit"]').click(function() {
                    button_type = $(this).val();
                });

                $('.alert.alert-danger1').hide();
                $('.alert.alert-danger2').hide();
                const defaultType = '<?php echo json_encode($changes?->type); ?>' ?? null;
                // console.log(JSON.parse(defaultType));
                $('#type').val(JSON.parse(defaultType)).trigger('change');

                async function getProgressContent() {
                    const {
                        value: text
                    } = await Swal.fire({
                        title: 'Masukkan Keterangan',
                        input: 'textarea',
                        // inputLabel: 'Message',
                        inputPlaceholder: 'Type your content here...',
                        inputAttributes: {
                            'aria-label': 'Type your content here'
                        },
                        confirmButtonColor: '#00A19D',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Konten tidak boleh kosong!'
                            }
                        }
                    })
                    return text
                }

                $('#form-changes').submit(async function(event) {
                    event.preventDefault(); // Mencegah submission form default

                    // var formData = $(this).serialize(); // Mengserialize data form

                    const formData = new FormData(this); // Buat objek FormData baru

                    let selectVal = $('#type').select2('val');
                    formData.append('type', selectVal);

                    // Ambil semua file yang diunggah
                    const files = $('#image_proof')[0].files;
                    if (files.length) {
                        formData.append('image_proof', files);
                    } else {
                        formData.delete('image_proof');
                    }

                    let value_content = CKEDITOR.instances[`content_ckeditor`].getData();

                    formData.delete('content_ckeditor')
                    formData.append('content', value_content)

                    // const action = $(this).find('button[type="submit"]').val();
                    formData.append('button_type', button_type);

                    if ('{{ $last_is_not_approve }}') {
                        let progress_content = await getProgressContent();
                        if (!progress_content) return;
                        formData.append('progress_content', progress_content);
                    }


                    // Tambahkan data lainnya dari form ke objek FormData
                    var formArray = $(this).serializeArray();
                    var checkboxValues = {};

                    // Groupkan checkbox dengan nama yang sama
                    $.each(formArray, function(index, input) {
                        var inputName = input.name;
                        var inputValue = input.value;

                        if (input.type === 'checkbox') {
                            if (!checkboxValues.hasOwnProperty(inputName)) {
                                checkboxValues[inputName] = [];
                            }

                            if (input.checked) {
                                checkboxValues[inputName].push(inputValue);
                            }
                        } else {
                            formData.append(inputName, inputValue);
                        }
                    });

                    // Tambahkan nilai checkbox ke objek FormData
                    $.each(checkboxValues, function(checkboxName, checkboxValue) {
                        formData.append(checkboxName, checkboxValue);
                    });

                    $(`button[type="submit"]`).prop('disabled', true)
                    $(this).find(`button[type="submit"][value="${button_type}"]`).text('').append(
                        '<i class="fas fa-circle-notch fa-spin fa-lg"></i>');
                    // $(this).find(`button[type="submit"][value="${button_type}"]`).prop('disabled', true).text('Prosessing...');

                    $('[id^="error-"]').hide();
                    $('.warn-image').removeClass('text-pink-600');
                    const url = '{{ route('change-managements.store') }}'
                    $.ajax({
                        url: url, // Ganti dengan endpoint validasi backend Anda
                        method: 'post',
                        processData: false,
                        contentType: false,
                        cache: false,
                        enctype: 'multipart/form-data',
                        data: formData,
                        dataType: 'json',
                        // beforeSend: function() {
                        //     $(form).find('.error').hide()
                        //     $(form).find('button[type="submit"]').attr('disabled', true)
                        // },
                        success: function(response) {
                            if (response?.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response?.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    $(`button[type="submit"]`).prop('disabled', false)
                                    $(`button[type="submit"][value="${button_type}"]`)
                                        .text(button_type.toUpperCase());
                                    // $(`button[type="submit"][value="${button_type}"]`).prop('disabled', false).text(button_type.toUpperCase());
                                    window.location.href = response
                                        ?.data?.route
                                })

                                // $(form).trigger('reset')
                            }
                        },
                        error: function(xhr, status, error) {
                            $(`button[type="submit"]`).prop('disabled', false)
                            $(`button[type="submit"][value="${button_type}"]`).text(button_type
                                .toUpperCase());
                            try {
                                const error_message = xhr.responseText ? JSON.parse(xhr
                                    .responseText).data : null;
                                $.each(error_message, function(key, value) {
                                    if (key === 'pic') $('.alert.alert-danger1').show();
                                    if (key === 'not_edit') $('.alert.alert-danger2')
                                        .show();
                                    const element_error = $('#error-' + key);
                                    element_error.text(value).show();


                                    const regex = /^image_proof\.(\d+)$/;
                                    const match = key.match(regex);
                                    console.log(match);
                                    if (match) {
                                        $('.warn-image').addClass('text-pink-600');
                                    }
                                });
                            } catch (e) {
                                console.log('Error parsing JSON response:', e);
                            }
                        }
                    });
                });

                $('.remove-image').on('click', function() {
                    const id = $(this).data('id');
                    $(`#image${id}`).remove();
                    images_removed.push(id);
                    if($('input[name="images_removed"]').length == 0) {
                        $('#form-changes').append('<input name="images_removed" hidden />');
                        $('input[name="images_removed"]').val(images_removed.join(','));
                    } else {
                        $('input[name="images_removed"]').val(images_removed.join(','));
                    }
                });


            });
        </script>
    @endonce
@endpush
