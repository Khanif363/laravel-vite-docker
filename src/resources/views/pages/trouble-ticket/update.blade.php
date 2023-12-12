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
                border-color: #00A19D;
            }

            .select2-container .select2-selection--single:focus,
            .select2-search--dropdown .select2-search__field:focus {
                --tw-ring-color: #00A19D;
            }

            textarea.select2-search__field {
                resize: none;
            }

            input {
                color: rgb(55 65 81);
            }
        </style>
    @endpush
    <section class="p-5 bg-[#F4F4F4] rounded-xl">
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="flex-col p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    @foreach ($errors->all() as $error)
                        <div class="flex flex-row items-center">
                            <i class="flex-shrink-0 inline mr-3 fas fa-circle-info"></i>
                            <span class="font-medium">{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @php
            $carbon_now = \Carbon\Carbon::now(); // mengambil tanggal hari ini
            $today = $carbon_now->format('m/d/Y');
        @endphp
        <div class="flex items-center justify-center px-10 py-4 uppercase shadow-md bg-tosca-0 rounded-xl">
            <span class="text-lg font-semibold text-center text-white">Form Update Progress Ticket</span>
        </div>

        <div class="flex items-center justify-start px-8 py-3 mt-2 capitalize shadow-md bg-tosca-0 rounded-xl">
            <span class="text-lg font-semibold text-white">{{ $ticket->nomor_ticket }}</span>
        </div>

        <div class="p-4 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">
            {{-- <ol>
                <li>Durasi Ticket</li>
            </ol> --}}
            <table class="text-gray-600">
                <tr>
                    <td class="w-2/12 py-1">Prioritas</td>
                    <td class="py-1">{{ $ticket->priority ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Mengetahui</td>
                    <td class="py-1">
                        <table class="">
                            <tr>
                                <td class="pr-24 md:pr-36">Department</td>
                                <td>
                                    <table>
                                        <tr>
                                            <td>{{ $ticket->ttpDispatch[0]->departmentDispatch->name ?? ($ticket->department->name ?? '') }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="py-1">Aktifitas/Masalah</td>
                    <td class="py-1">{!! $ticket->problem ?? '' !!}</td>
                </tr>
                <tr>
                    <td class="py-1 align-top">Engineer</td>
                    <td class="py-1">
                        @foreach ($ticket->ttpEngineerAssignment as $ass)
                            @foreach ($ass->engineerUser as $eng)
                                <li>{{ $eng->full_name ?? '' }}</li>
                            @endforeach
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>

        <div class="flex items-center justify-start px-8 py-3 mt-2 capitalize shadow-md bg-tosca-0 rounded-xl">
            <span class="text-lg font-semibold text-white">Progress</span>
        </div>

        <div
            class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl last-progress">

            @if ($ticket->lastProgress)
                <table class="text-gray-600 whitespace-nowrap">
                    <colgroup>
                        <col span="1" class="md:w-1/5">
                    </colgroup>
                    <tr>
                        <td class="py-1 pr-10">Jenis Update:</td>
                        <td class="py-1">{{ $ticket->lastProgress->update_type ?? '' }}</td>
                    </tr>
                    <tr>
                        @php
                            $date = Carbon\Carbon::parse($ticket->lastProgress->inputed_date ?? '')
                                ->locale('id')
                                ->isoFormat('dddd, D MMMM YYYY, H:m');
                        @endphp
                        <td class="py-1 pr-10">Tanggal Update:</td>
                        <td class="py-1">{{ $date ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-10">Keterangan:</td>
                        <td class="py-1">{!! $ticket->lastProgress->content ?? '' !!}</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-10">
                            @if (!empty($ticket->lastProgress->attachments))
                                @foreach ($ticket->lastProgress->attachments as $attachment_progress)
                                    <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                        target="_blank"
                                        class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                            class="fa-solid fa-paperclip"></i><span class="ml-2">Attachment</span>
                                    </a>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                </table>
                <span class="font-semibold text-center text-blue-500 cursor-pointer read-more">Lihat Selengkapnya..</span>
            @else
                <span class="text-center text-gray-600 cursor-pointer">-- Belum ada progress --</span>
            @endif
        </div>

        <div class="hidden progress-info">
            @foreach ($ticket->troubleTicketProgress ?? [] as $progress)
                @if ($progress->update_type == 'Closed')
                    <div
                        class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">

                        <table class="text-gray-600 whitespace-nowrap">
                            <colgroup>
                                <col span="1" class="md:w-1/5">
                            </colgroup>
                            <tr>
                                <td class="py-1 pr-10">Jenis Update:</td>
                                <td class="py-1">{{ $progress->update_type ?? '' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $date = Carbon\Carbon::parse($progress->inputed_date ?? '')
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{!! $progress->content ?? '' !!}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
                @if ($progress->update_type == 'Monitoring')
                    <div
                        class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">

                        <table class="text-gray-600 whitespace-nowrap">
                            <colgroup>
                                <col span="1" class="md:w-1/5">
                            </colgroup>
                            <tr>
                                <td class="py-1 pr-10">Jenis Update:</td>
                                <td class="py-1">{{ $progress->update_type ?? '' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $date = Carbon\Carbon::parse($progress->inputed_date ?? '')
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{!! $progress->content ?? '' !!}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
                @if ($progress->update_type == 'Technical Close')
                    <div
                        class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">

                        <table class="text-gray-600 whitespace-nowrap">
                            <colgroup>
                                <col span="1" class="md:w-1/5">
                            </colgroup>
                            <tr>
                                <td class="py-1 pr-10">Jenis Update:</td>
                                <td class="py-1">{{ $progress->update_type ?? '' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $date = Carbon\Carbon::parse($progress->inputed_date ?? '')
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">RFO:</td>
                                <td class="py-1">{{ $progress->technicalClose->rfo ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
                @if ($progress->update_type == 'Dispatch')
                    <div
                        class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">

                        <table class="text-gray-600 whitespace-nowrap">
                            <colgroup>
                                <col span="1" class="md:w-1/5">
                            </colgroup>
                            <tr>
                                <td class="py-1 pr-10">Jenis Update:</td>
                                <td class="py-1">{{ $progress->update_type ?? '' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $date = Carbon\Carbon::parse($progress->inputed_date ?? '')
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Dari Department:</td>
                                <td class="py-1">{{ $progress->dispatch->department_from->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Ke Department:</td>
                                <td class="py-1">{{ $progress->dispatch->department_to->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span
                                                    class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
                @if ($progress->update_type == 'Engineer Troubleshoot')
                    <div
                        class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">

                        <table class="text-gray-600 whitespace-nowrap">
                            <colgroup>
                                <col span="1" class="md:w-1/5">
                            </colgroup>
                            <tr>
                                <td class="py-1 pr-10">Jenis Update:</td>
                                <td class="py-1">{{ $progress->update_type ?? '' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $date = Carbon\Carbon::parse($progress->inputed_date ?? '')
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{!! $progress->content ?? '' !!}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span
                                                    class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
                @if ($progress->update_type == 'Engineer Assignment')
                    <div
                        class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">

                        <table class="text-gray-600 whitespace-nowrap">
                            <colgroup>
                                <col span="1" class="md:w-1/5">
                            </colgroup>
                            <tr>
                                <td class="py-1 pr-10">Jenis Update:</td>
                                <td class="py-1">{{ $progress->update_type ?? '' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $date = Carbon\Carbon::parse($progress->inputed_date ?? '')
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Engineer:</td>
                                <td class="py-1 td-row">
                                    <ul class="ml-4 list-disc">
                                        @forelse ($progress->engineerAssignment->engineer ?? [] as $engineer)
                                            <li>{{ $engineer->user->full_name }}</li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{!! $progress->content ?? '' !!}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span
                                                    class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
                @if ($progress->update_type == 'Pending')
                    <div
                        class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">

                        <table class="text-gray-600 whitespace-nowrap">
                            <colgroup>
                                <col span="1" class="md:w-1/5">
                            </colgroup>
                            <tr>
                                <td class="py-1 pr-10">Jenis Update:</td>
                                <td class="py-1">{{ $progress->update_type ?? '' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $date = Carbon\Carbon::parse($progress->inputed_date ?? '')
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Durasi Pending:</td>
                                {{-- <td class="py-1">{{ $progress->pending->duration->isoFormat('H:m') ?? '' }} jam</td> --}}
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">By:</td>
                                <td class="py-1">{{ $progress->pending->pending_by ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Alasan:</td>
                                <td class="py-1">{{ $progress->pending->reason ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span
                                                    class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
                @if ($progress->update_type == 'Diagnose')
                    <div
                        class="flex flex-col w-full p-4 space-y-3 overflow-x-auto bg-white border-2 shadow-md border-tosca-0 rounded-xl">

                        <table class="text-gray-600 whitespace-nowrap">
                            <colgroup>
                                <col span="1" class="md:w-1/5">
                            </colgroup>
                            <tr>
                                <td class="py-1 pr-10">Jenis Update:</td>
                                <td class="py-1">{{ $progress->update_type ?? '' }}</td>
                            </tr>
                            <tr>
                                @php
                                    $date = Carbon\Carbon::parse($progress->inputed_date ?? '')
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{!! $progress->content ?? '' !!}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span
                                                    class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
            @endforeach
        </div>

        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data"
            id="form">
            @method('PUT')
            @csrf
            <input type="number" name="inputer_id" value="{{ auth()->user()->id }}" hidden>
            <input type="text" name="department_from_id" value="{{ $last_department ?? null }}" hidden>

            <div class="w-full p-4 mt-2 bg-white border-2 shadow-md update-border border-tosca-0 rounded-xl">

                <div class="block w-full">
                    <div class="grid w-full grid-cols-1 diagnose lg:grid-cols-2 gap-y-2 gap-x-2 lg:gap-x-16">
                        <div class="flex items-center w-full radio">
                            <div
                                class="flex flex-col items-center justify-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                                <div class="text-center min-w-150 md:text-start">
                                    <input class="my-auto transform scale-75" type="radio" name="depart_radio"
                                        id="core" value="core" />
                                    <label for="core"
                                        class="text-gray-600 cursor-pointer whitespace-nowrap">Department<sup><i
                                                class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                </div>
                                <div class="relative flex justify-end w-full">
                                    <select name="department_id" type="text" class="w-full" id="department_id"
                                        disabled>
                                        <option value="" disabled selected>-- Pilih Department --</option>
                                        @foreach ($data_view['departments_core'] as $departments)
                                            <option value="{{ $departments->id }}"
                                                @if (old('department_id') == $departments->id) {{ 'selected' }} @endif>
                                                {{ $departments->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="flex items-center w-full diagnose radio">
                            <div
                                class="flex flex-col items-center justify-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                                <div class="text-center min-w-150 md:text-start">
                                    <input class="my-auto transform scale-75" type="radio" name="depart_radio"
                                        id="serdesk" value="serdesk" />
                                    <label for="serdesk" class="text-gray-600 cursor-pointer whitespace-nowrap">Service
                                        Desk</label>
                                </div>
                                <div class="flex flex-col w-full">
                                    <div class="relative flex justify-end w-full">
                                        <input name="department_id" type="number" id="service_desk" readonly
                                            class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                          focus:outline-none focus:ring-1 focus:ring-tosca-0
                          disabled:bg-slate-50 disabled:border-slate-200 disabled:shadow-none appearance-none bg-none text-transparent">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="grid grid-cols-1 mt-2 lg:grid-cols-2 gap-y-2 lg:gap-x-16">
                    <div
                        class="flex flex-col items-center justify-center col-span-2 space-y-2 diagnose lg:col-span-1 md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="device_id" class="text-gray-600 whitespace-nowrap">Device</label>
                        </div>
                        <div class="relative flex justify-end w-full">
                            <select name="device_id" type="text" class="w-full" id="device_id">
                                <option value="" selected>-- Pilih Device --</option>
                                @foreach ($data_view['devices'] as $devices)
                                    <option value="{{ $devices->id }}"
                                        @if (old('device_id') == $devices->id) {{ 'selected' }} @endif>{{ $devices->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center justify-center col-span-2 space-y-2 diagnose lg:col-span-1 md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="service_id" class="text-gray-600 whitespace-nowrap">Service<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="relative flex justify-end w-full">
                            <select name="service_id" type="text" class="w-full" id="service_id">
                                <option value="" disabled selected>-- Pilih Service --</option>
                                @foreach ($data_view['services'] as $services)
                                    <option value="{{ $services->id }}"
                                        @if (old('service_id') == $services->id) {{ 'selected' }} @endif>
                                        {{ $services->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center justify-center col-span-2 space-y-2 backhaul md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="mantools_datacom_id" class="text-gray-600 whitespace-nowrap">Data Backhaul<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="relative flex justify-end w-full">
                            <select name="mantools_datacom_id" type="text" class="w-full" id="mantools_datacom_id">
                                <option value="" disabled selected>-- Pilih Backhaul --</option>
                                @foreach ($data_view['mantools_datacoms'] as $mantools_datacoms)
                                    <option value="{{ $mantools_datacoms['id'] }}"
                                        @if (old('backhaul_id') == $mantools_datacoms['id']) {{ 'selected' }} @endif>
                                        {{ $mantools_datacoms['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center justify-center max-w-xl space-y-2 engineer-assignment md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="" class="text-gray-600 whitespace-nowrap">Department<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="relative flex justify-end w-full">
                            <select name="" type="text" class="w-full" id="" disabled>
                                <option value="" disabled selected>-- Pilih Department --</option>
                                @foreach ($data_view['departments_core'] as $departments)
                                    <option value="{{ $departments->id }}"
                                        @if ($last_department == $departments->id) {{ 'selected' }} @endif>
                                        {{ $departments->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center justify-center max-w-xl space-y-2 engineer-assignment md:flex-row md:space-x-8 md:space-y-0">
                        <div class="text-center min-w-150 md:text-start">
                            <label for="engineer_assignment_id" class="text-gray-600 whitespace-nowrap">Engineer<sup><i
                                        class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                        </div>
                        <div class="relative flex justify-end w-full">
                            <select name="engineer_assignment_id[]" id="engineer_assignment_id" type="text"
                                class="w-full" multiple="multiple">
                                <option value="" disabled>-- Pilih Engineer --</option>
                                @foreach ($data_view['engineers'] as $engineers)
                                    <option value="{{ $engineers->id }}"
                                        @if (in_array($engineers->id, old('engineer_assignment_id', []))) data-selected="true" @endif>
                                        {{ $engineers->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col w-full p-4 mt-2 space-y-3 bg-white border-2 shadow-md border-tosca-0 rounded-xl">
                <div
                    class="flex flex-col items-center justify-center max-w-xl space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="update_type" class="text-gray-600 whitespace-nowrap">Jenis Update<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    @php
                        $diagnose = ['Dispatch', 'Engineer Assignment', 'Pending'];
                        $engineer_assignment = ['Dispatch', 'Engineer Assignment', 'Pending', 'Engineer Troubleshoot'];
                        $technical_close = ['Dispatch', 'Engineer Assignment', 'Pending', 'Engineer Troubleshoot', 'Technical Close', 'Monitoring'];
                        $pending = ['Dispatch', 'Engineer Assignment', 'Engineer Troubleshoot'];
                        $service_request = ['Dispatch', 'Engineer Assignment', 'Engineer Troubleshoot', 'Closed'];

                        $role_name = $permission['role'];
                    @endphp
                    {{-- @dd($data_view['update_types']) --}}
                    <div class="relative flex flex-col justify-end w-full">
                        <select name="update_type" type="text" class="w-full" id="update_type">
                            <option value="" disabled selected>-- Pilih Progress --</option>
                            @foreach ($data_view['update_types'] as $update_type)
                                @if (empty($ticket->lastProgress->update_type) && in_array($update_type, ['Diagnose', 'Pending']))
                                    <option value="{{ $update_type }}"
                                        @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                        {{ $update_type }}
                                    </option>
                                @endif
                                @if ($ticket->type == 'Service Request')
                                    {{-- @if (
                                        ($ticket->lastProgress->update_type ?? '') == 'Pending' &&
                                        $ticket->status != 'Pending' &&
                                        ($ticket->department_id ?? null) === null &&
                                            in_array($update_type, array_merge($pending, ['Pending', 'Diagnose', 'Closed'])))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (
                                        ($ticket->lastProgress->update_type ?? '') == 'Pending' &&
                                        $ticket->status == 'Pending' &&
                                        ($ticket->department_id ?? null) === null &&
                                            in_array($update_type, array_merge($pending, ['Diagnose', 'Technical Close', 'Closed'])))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (
                                        ($ticket->lastProgress->update_type ?? '') == 'Pending' &&
                                        $ticket->status == 'Pending' &&
                                        ($ticket->department_id ?? null) !== null &&
                                            in_array($update_type, array_merge($pending, ['Technical Close', 'Closed'])))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (!in_array($ticket->lastProgress->update_type ?? '', ['Pending']) && $ticket->status != 'Pending' && in_array($update_type, $service_request))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif --}}


                                    {{-- Sementara --}}
                                    @if ((empty($ticket->lastProgress->update_type) && in_array($update_type, $service_request)) || (!empty($ticket->lastProgress->update_type) && array_merge($service_request, ['Pending'])))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                @else
                                    {{-- @if ($ticket->lastProgress->update_type ?? '') == 'Pending' && in_array($update_type, ['Diagnose', 'Pending']))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif --}}
                                    @if (
                                        ($ticket->lastProgress->update_type ?? '') == 'Monitoring' &&
                                            in_array($update_type, ['Closed']) &&
                                            (isset($access_with_department['Close Ticket']) || $role_name == 'Admin'))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (
                                        (($ticket->lastProgress->update_type ?? '') == 'Diagnose' ||
                                            ($ticket->lastProgress->update_type ?? '') == 'Dispatch') &&
                                            // $ticket->need_engineer == 1 &&
                                            in_array($update_type, $diagnose))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (($ticket->lastProgress->update_type ?? '') == 'Engineer Assignment' && in_array($update_type, $engineer_assignment))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (
                                        ($ticket->lastProgress->update_type ?? '') == 'Pending' &&
                                            ($ticket->status == 'Pending' && ($ticket->department_id ?? null) !== null && in_array($update_type, array_merge($pending, ['Technical Close']))))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (
                                        ($ticket->lastProgress->update_type ?? '') == 'Pending' &&
                                            (($ticket->department_id ?? null) === null && in_array($update_type, ['Diagnose'])))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (
                                        ($ticket->lastProgress->update_type ?? '') == 'Pending' &&
                                            ($ticket->status != 'Pending' && in_array($update_type, array_merge($pending, ['Technical Close', 'Pending']))))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (
                                        (($ticket->lastProgress->update_type ?? '') == 'Technical Close' ||
                                            ($ticket->lastProgress->update_type ?? '') == 'Monitoring') &&
                                            in_array($update_type, $technical_close))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                    @if (
                                        ($ticket->lastProgress->update_type ?? '') == 'Engineer Troubleshoot' &&
                                            in_array($update_type, array_merge($engineer_assignment, ['Technical Close'])))
                                        <option value="{{ $update_type }}"
                                            @if (old('update_type') == $update_type) {{ 'selected' }} @endif>
                                            {{ $update_type }}
                                        </option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center max-w-xl space-y-2 dispatch md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="department_to_id" class="text-gray-600 whitespace-nowrap">Department<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="relative flex justify-end w-full">
                        <select name="department_to_id" type="text" class="w-full" id="department_to_id">
                            <option value="" disabled selected>-- Pilih Department --</option>
                            @foreach ($data_view['departments_semicore'] as $departments)
                                @if ($departments->id != $last_department)
                                    <option value="{{ $departments->id }}"
                                        @if (old('department_to_id') == $departments->id) {{ 'selected' }} @endif>
                                        {{ $departments->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div
                    class="flex-col items-center justify-center hidden w-full space-y-2 md:flex-row md:justify-start md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="inputed_date" class="text-gray-600 whitespace-nowrap">Waktu Update</label>
                    </div>
                    <div class="grid w-full grid-cols-1 gap-2 md:grid-cols-2 md:max-w-415">
                        <div class="flex flex-col w-full">
                            <div class="relative flex justify-end w-full">
                                <input name="inputed_date" datepicker datepicker-autohide type="text"
                                    id="inputed_date"
                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                    value="{{ old('inputed_date') }}">
                                <i
                                    class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-tosca-0"></i>
                            </div>
                            @error('inputed_date')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col w-full">
                            <div class="relative flex justify-end w-full">
                                <input name="inputed_time" type="time" id="inputed_time"
                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600 appearance-none bg-none"
                                    value="{{ old('inputed_time') }}">
                                <i class="fas fa-clock h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-tosca-0"></i>
                            </div>
                            @error('inputed_time')
                                <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center w-full space-y-2 engineer-assignment md:flex-row md:justify-start md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="close_estimation_date" class="text-gray-600 whitespace-nowrap">Estimasi Selesai<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="grid w-full grid-cols-1 gap-2 md:grid-cols-2 md:max-w-415">
                        <div class="flex flex-col w-full">
                            <div class="relative flex justify-end w-full">
                                <input name="close_estimation_date" datepicker datepicker-autohide type="text"
                                    id="close_estimation_date"
                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                    value="{{ old('close_estimation_date') ?? $today }}">
                                <i
                                    class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-tosca-0"></i>
                            </div>
                        </div>
                        <div class="flex flex-col w-full">
                            <div class="relative flex justify-end w-full">
                                <input name="close_estimation_time" type="time" id="close_estimation_time"
                                    class="timepicker w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600 appearance-none bg-none"
                                    value="{{ old('close_estimation_time') }}">
                                <i class="fas fa-clock h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-tosca-0"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div
                    class="flex flex-col items-center justify-center w-full space-y-2 diagnose md:flex-row md:justify-start md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="need_engineer" class="w-full text-left text-gray-600">Butuh Engineer<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="flex flex-row items-center justify-center md:justify-start md:space-x-10">
                            <label class="flex cursor-pointer radio">
                                <input name="need_engineer" class="my-auto transform scale-75" type="radio"
                                    value="1" @if (old('need_engineer') == 1) checked @endif />
                                <div class="px-2 text-gray-600 title">Iya</div>
                            </label>
                            <label class="flex cursor-pointer radio">
                                <input name="need_engineer" class="my-auto transform scale-75" type="radio"
                                    value="0" @if (old('need_engineer') == 0) checked @endif />
                                <div class="px-2 text-gray-600 title">Tidak</div>
                            </label>
                        </div>
                        @error('need_engineer')
                            <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div> --}}

                <div
                    class="flex flex-col items-center justify-center max-w-xl space-y-2 pending md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="pending_by" class="text-gray-600 whitespace-nowrap">Pending By<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="relative flex justify-end w-full">
                        <select name="pending_by" type="text" class="w-full" id="pending_by">
                            <option value="" disabled selected>-- Pilih Jenis Pending --</option>
                            @foreach ($data_view['pendings'] as $pending)
                                <option value="{{ $pending }}"
                                    @if (old('pending_by') == $pending) {{ 'selected' }} @endif>{{ $pending }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center max-w-xl space-y-2 technical-close md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="resume_id" class="text-gray-600 whitespace-nowrap">Tindakan<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="relative flex justify-end w-full">
                        <select name="resume_id" id="resume_id" type="text" class="w-full">
                            <option value="" disabled selected>-- Pilih Tindakan --</option>
                            @foreach ($data_view['resumes'] as $resumes)
                                <option value="{{ $resumes->id }}"
                                    @if (old('resume_id') == $resumes->id) {{ 'selected' }} @endif>{{ $resumes->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- <div
                    class="flex flex-col items-center justify-center max-w-xl space-y-2 pending md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="duration" class="text-gray-600 whitespace-nowrap">Durasi Pending<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="relative flex justify-end w-full">
                            <input name="duration" type="time" id="duration"
                                class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600 appearance-none bg-none"
                                value="{{ old('duration') }}">
                            <i class="fas fa-clock h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-tosca-0"></i>
                        </div>
                    </div>
                </div> --}}
                <div
                    class="flex flex-col items-center justify-center w-full space-y-2 pending md:flex-row md:justify-start md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="duration_date" class="text-gray-600 whitespace-nowrap">Durasi Pending<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="grid w-full grid-cols-1 gap-2 md:grid-cols-2 md:max-w-415">
                        <div class="flex flex-col w-full">
                            <div class="relative flex justify-end w-full">
                                <input name="duration_date" datepicker datepicker-autohide type="text"
                                    id="duration_date"
                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                                    value="{{ old('duration_date') ?? $today }}">
                                <i
                                    class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-tosca-0"></i>
                            </div>
                        </div>
                        <div class="flex flex-col w-full">
                            <div class="relative flex justify-end w-full">
                                <input name="duration_time" type="time" id="duration_time"
                                    class="timepicker w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600 appearance-none bg-none"
                                    value="{{ old('duration_time') }}">
                                <i class="fas fa-clock h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-tosca-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div
                    class="flex flex-col items-center justify-center max-w-xl space-y-2 closed md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="rate" class="text-gray-600 whitespace-nowrap">Rating Customer<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="relative flex justify-end w-full">
                            <select name="rate" id="rate" type="text" class="w-full">
                                <option value="" disabled selected>-- Pilih Rate --</option>
                                <option value="20">Sangat tidak puas</option>
                                <option value="40">Tidak puas</option>
                                <option value="60">Cukup puas</option>
                                <option value="80">Puas</option>
                                <option value="100">Sangat Puas</option>
                            </select>
                        </div>
                    </div>
                </div> --}}
                <div
                    class="flex flex-col items-center justify-center w-full space-y-2 closed md:flex-row md:justify-start md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="customer_confirm" class="w-full text-left text-gray-600">Status Konfirmasi<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="flex flex-row items-center justify-center md:justify-start md:space-x-10">
                            <label class="flex cursor-pointer radio">
                                <input name="customer_confirm" class="my-auto transform scale-75" type="radio"
                                    value="1" @if (old('customer_confirm') == 1) checked @endif />
                                <div class="px-2 text-gray-600 title">Sudah dikonfirmasi</div>
                            </label>
                            <label class="flex cursor-pointer radio">
                                <input name="customer_confirm" class="my-auto transform scale-75" type="radio"
                                    value="0" @if (old('customer_confirm') == 0) checked @endif />
                                <div class="px-2 text-gray-600 title">Belum dikonfirmasi</div>
                            </label>
                        </div>
                        @error('customer_confirm')
                            <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center max-w-xl space-y-2 technical-close md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="evaluation" class="text-gray-600 whitespace-nowrap">Evaluasi<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="relative flex justify-end w-full">
                            <select name="evaluation" id="evaluation" type="text" class="w-full">
                                <option value="" disabled selected>-- Pilih Evaluasi --</option>
                                <option value="Perlu RCA" @if (old('evaluation') == 'Perlu RCA') selected @endif>Perlu RCA
                                </option>
                                <option value="Tidak Perlu" @if (old('evaluation') == 'Tidak Perlu') selected @endif>Tidak Perlu
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center w-full space-y-2 technical-close md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="rfo" class="text-gray-600 whitespace-nowrap">RFO<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <textarea name="rfo" id="rfo"
                            class="block w-full p-2 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                            placeholder="Keterangan" rows="3">{{ old('rfo') }}</textarea>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center w-full space-y-2 keterangan md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="content" class="text-gray-600 whitespace-nowrap">Keterangan<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0 keterangan-icon"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <textarea name="content" id="content"
                            class="ckeditor block w-full p-2 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                            placeholder="Keterangan" rows="3">{{ old('content') }}</textarea>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center w-full space-y-2 pending md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="reason" class="text-gray-600 whitespace-nowrap">Alasan<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <textarea name="reason" id="reason"
                            class="block w-full p-2 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                            placeholder="Alasan" rows="3">{{ old('reason') }}</textarea>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center w-full space-y-2 solusi md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="solution" class="text-gray-600 whitespace-nowrap">Solusi<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <textarea name="solution" id="solution"
                            class="block w-full p-2 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                            placeholder="" rows="3">{{ old('solution') }}</textarea>
                    </div>
                </div>

                <div
                    class="flex flex-col items-center justify-start w-full space-y-2 technical-close md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="is_visited" class="text-gray-600 whitespace-nowrap">Visit Lokasi?<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="flex flex-row justify-center md:justify-start">
                            <label class="flex p-2 cursor-pointer radio">
                                <input class="my-auto transform scale-75" type="radio" name="is_visited"
                                    value="1" @if (old('is_visited') == 1) checked @endif />
                                <div class="px-2 text-gray-600 title">Iya</div>
                            </label>

                            <label class="flex p-2 cursor-pointer radio">
                                <input class="my-auto transform scale-75" type="radio" name="is_visited"
                                    value="0" @if (old('is_visited') == 0) checked @endif />
                                <div class="px-2 text-gray-600 title">Tidak</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col w-full space-y-2 email-report md:flex-row md:space-x-8 md:space-y-0">
                    <div class="min-w-150">
                    </div>
                    <div class="flex items-center ml-2 whitespace-nowrap">
                        <input name="status_report_email" type="checkbox" value="1"
                            class="w-4 h-4 scale-75 rounded-md" id="status_report_email">
                        <label for="status_report_email"
                            class="ml-1 text-gray-600 dark:text-gray-300 whitespace-nowrap">Email
                            (Pelapor)</label>
                    </div>
                </div>

                @if (!in_array($ticket->type, ['Incident TTR', 'Incident Non TTR']))
                    <div class="flex flex-col w-full space-y-2 email-report-gm md:flex-row md:space-x-8 md:space-y-0">
                        <div class="min-w-150">
                        </div>
                        <div class="flex items-center ml-2 whitespace-nowrap">
                            <input name="status_report_email_gm" type="checkbox" value="1" class="w-4 h-4 scale-75 rounded-md"
                                id="status_report_email_gm">
                            <label for="status_report_email_gm"
                                class="ml-1 text-gray-600 dark:text-gray-300 whitespace-nowrap">Email
                                (General Manager)</label>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col w-full space-y-2 attachment md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="image_proof" class="text-left text-gray-600 whitespace-nowrap">Attachment
                            File<sup class="attachment-icon"><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="flex items-center justify-center md:justify-start">
                            <input name="image_proof[]" type="file" id="image_proof"
                                class="text-sm text-slate-500 file:rounded-2xl rounded-2xl file:text-sm file:font-semibold file:py-1 file:bg-yellow-0 file:text-white hover:file:bg-yellow-2 "
                                multiple />
                        </div>
                        <div class="mt-2 text-center md:text-left">
                            <span class="text-red-0"><i class="fas fa-circle-exclamation"></i></span>
                            <span class="text-sm text-gray-500 capitalize">
                                UKURAN FILE MAKS 1024 KB, EKSTENSI FILE YANG DIIJINKAN GIF, JPG,
                                PNG, PDF, JPEG, XLS, XLSX, DOC, DOCX
                            </span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <a href="{{ route('tickets.index') }}"
                    class="inline-block px-6 py-2.5 mt-5 bg-red-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-red-1 hover:shadow-lg focus:bg-red-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-2 active:shadow-lg transition duration-150 ease-in-out">Back</a>
                <button type="submit"
                    class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">Submit</button>
            </div>
        </form>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                $(document).ready(function() {

                    asReload();

                    function asReload() {
                        $('.update-border').fadeOut(200);
                        updateType();
                        departCheck();
                        checkDepartId()
                        $('#engineer_assignment_id').find('option[data-selected=true]').prop('selected', true);
                        $('#engineer_assignment_id').select2();
                        if(!['Incident TTR', 'Incident Non TTR'].includes('{{ $ticket->type }}')) {
                            $('#status_report_email_gm').prop('checked', true);
                        }
                    }

                    function departCheck() {
                        $("input[name='depart_radio']").change(function() {
                            let depart = $("input[name='depart_radio']:checked").val();
                            if (depart == 'core') {
                                $('#service_desk').prop('disabled', true);
                                $('#department_id').prop('disabled', false);
                            } else {
                                $('#service_desk').prop('disabled', false);
                                $('#department_id').prop('disabled', true);
                            }
                        })
                    }

                    function checkDepartId() {
                        let current_department = $("select[name='department_id']").val();
                        console.log(current_department);

                        $('.backhaul').fadeOut(200);
                        if (current_department == 2) {
                            $('.backhaul').fadeIn(200);
                        }
                    }

                    function updateType() {
                        let val = $('#update_type').val();
                        if (val) {
                            sessionStorage.setItem('update_type', val);
                        } else if (sessionStorage.getItem('update_type')) {
                            $('#update_type').val(sessionStorage.getItem('update_type')).trigger('change');
                        }
                        let update_type = sessionStorage.getItem('update_type');
                        $('.update-border').fadeIn(200);
                        $('.keterangan').fadeIn(200);
                        $('.solusi').fadeOut(200);
                        $('.backhaul').fadeOut(200);
                        $('.email-report').fadeOut(200);
                        let current_type = '{{ $ticket->type }}';
                        switch (update_type) {
                            case 'Diagnose':
                                $('.pending').fadeOut(200);
                                $('.dispatch').fadeOut(200);
                                $('.engineer-assignment').fadeOut(200);
                                $('.technical-close').fadeOut(200);
                                $('.diagnose').fadeIn(200);
                                $('.closed').fadeOut(200);
                                $('.attachment-icon').fadeOut(200);
                                $('.keterangan').fadeIn(200);
                                $('.email-report').fadeIn(200);
                                break;
                            case 'Dispatch':
                                $('.pending').fadeOut(200);
                                $('.dispatch').fadeIn(200);
                                $('.engineer-assignment').fadeOut(200);
                                $('.technical-close').fadeOut(200);
                                $('.diagnose').fadeOut(200);
                                $('.update-border').fadeOut(200);
                                $('.closed').fadeOut(200);
                                $('.attachment-icon').fadeOut(200);
                                $('.keterangan').fadeIn(200);
                                break;
                            case 'Engineer Assignment':
                                $('.pending').fadeOut(200);
                                $('.dispatch').fadeOut(200);
                                $('.engineer-assignment').fadeIn(200);
                                $('.technical-close').fadeOut(200);
                                $('.diagnose').fadeOut(200);
                                $('.closed').fadeOut(200);
                                // $('.attachment-icon').fadeOut(200);
                                $('.attachment').fadeOut(200);
                                $('.keterangan').fadeOut(200);
                                break;
                            case 'Technical Close':
                                $('.pending').fadeOut(200);
                                $('.dispatch').fadeOut(200);
                                $('.engineer-assignment').fadeOut(200);
                                $('.technical-close').fadeIn(200);
                                $('.diagnose').fadeOut(200);
                                $('.update-border').fadeOut(200);
                                $('.closed').fadeOut(200);
                                $('.attachment-icon').fadeIn(200);
                                $('.solusi').fadeIn(200);
                                $('.keterangan').fadeOut(200);
                                if (current_type === 'Incident TTR') {
                                    $('.solusi').fadeIn(200);
                                }
                                break;
                            case 'Pending':
                                $('.pending').fadeIn(200);
                                $('.dispatch').fadeOut(200);
                                $('.engineer-assignment').fadeOut(200);
                                $('.technical-close').fadeOut(200);
                                $('.diagnose').fadeOut(200);
                                $('.update-border').fadeOut(200);
                                $('.closed').fadeOut(200);
                                $('.attachment-icon').fadeIn(200);
                                $('.keterangan').fadeIn(200);
                                break;
                            case 'Closed':
                                $('.pending').fadeOut(200);
                                $('.dispatch').fadeOut(200);
                                $('.engineer-assignment').fadeOut(200);
                                $('.technical-close').fadeOut(200);
                                $('.diagnose').fadeOut(200);
                                $('.update-border').fadeOut(200);
                                $('.closed').fadeIn(200);
                                $('.attachment-icon').fadeIn(200);
                                $('.keterangan').fadeIn(200);
                                break;
                            case 'Monitoring':
                                $('.pending').fadeOut(200);
                                $('.dispatch').fadeOut(200);
                                $('.engineer-assignment').fadeOut(200);
                                $('.technical-close').fadeOut(200);
                                $('.diagnose').fadeOut(200);
                                $('.update-border').fadeOut(200);
                                $('.closed').fadeOut(200);
                                $('.attachment-icon').fadeOut(200);
                                $('.keterangan-icon').fadeOut(200);
                                $('.email-report').fadeIn(200);
                                break;
                            default:
                                $('.pending').fadeOut(200);
                                $('.dispatch').fadeOut(200);
                                $('.engineer-assignment').fadeOut(200);
                                $('.technical-close').fadeOut(200);
                                $('.diagnose').fadeOut(200);
                                $('.update-border').fadeOut(200);
                                $('.closed').fadeOut(200);
                                $('.attachment-icon').fadeOut(200);
                                $('.keterangan').fadeIn(200);
                                $('.email-report').fadeIn(200);
                        }
                    }

                    $('#update_type').change(function() {
                        updateType();
                    });

                    $('#department_id').change(function() {
                        checkDepartId()
                        console.log("TESSSS");
                    });

                    $('.read-more').click(function() {
                        $(this).parent().fadeOut(200, function() {
                            $('.progress-info').fadeIn(200);
                        });
                    })

                    $('div.progress-info > div:last-child').append(
                        `<span class="font-semibold text-center text-blue-500 cursor-pointer read-less">Lihat Sedikit..</span>`
                    )

                    $('.read-less').click(function() {
                        $('.progress-info').fadeOut(200, function() {
                            $('.last-progress').fadeIn(200);
                        });
                    })

                    $('#core').click(function() {
                        $('input[name="department_id"]').val(null);
                    });

                    $('#serdesk').click(function() {
                        $('input[name="department_id"]').val(4);
                    });

                });
            </script>
        @endonce
    @endpush
@endsection
