@extends('layouts.master')

@section('content')
    @push('css')
        <style>
            .td-row {
                padding-left: 20px;
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

            textarea {
                resize: none;
            }
        </style>
    @endpush
    <section class="w-full h-full mt-4 mb-10 grid grid-cols-1 md:grid-cols-3 md:gap-x-4 gap-y-4">

        <div class="w-full rounded-xl col-span-2 hidden">
            <div class="bg-tosca-0 py-3 px-8 rounded-xl flex justify-start items-center capitalize shadow-md">
                <span class="text-white text-lg font-semibold">{{ $ticket->nomor_ticket }}</span>
            </div>

            <div class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md overflow-x-auto">
                {{-- <ol>
                <li>Durasi Ticket</li>
            </ol> --}}
                <table class="text-gray-600">
                    <tr>
                        <td class="pr-24 md:pr-36 py-1">Durasi Ticket</td>
                        <td class="py-1">
                            {{ $ticket->time_diff_now ? $ticket->time_diff_now . ' Jam' : '' }}
                        </td>
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
                        <td class="py-1">Prioritas</td>
                        <td class="py-1">{{ $ticket->priority ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Layanan</td>
                        <td class="py-1">{{ $ticket->service->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Sumber Info</td>
                        <td class="py-1">{{ $ticket->ticketInfo->source_info ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Aktifitas/Masalah</td>
                        <td class="py-1">{{ $ticket->problem ?? '' }}</td>
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

            @php
                $last_depart = $ticket->ttpDispatch[0]->departmentDispatch->id ?? ($ticket->department->id ?? null);
            @endphp

            <div class="bg-tosca-0 py-3 px-8 mt-2 rounded-xl flex justify-start items-center capitalize shadow-md">
                <span class="text-white text-lg font-semibold">Progress</span>
            </div>
            @foreach ($ticket->troubleTicketProgress ?? [] as $progress)
                @if ($progress->update_type == 'Closed')
                    <div
                        class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

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
                                    $date = Carbon\Carbon::parse($progress->inputed_date)
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{{ $progress->content ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center py-1 px-2 text-sm font-normal bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-blue-700 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
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
                        class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

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
                                    $date = Carbon\Carbon::parse($progress->inputed_date)
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{{ $progress->content ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center py-1 px-2 text-sm font-normal bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-blue-700 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
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
                        class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

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
                                    $date = Carbon\Carbon::parse($progress->inputed_date)
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
                                                class="inline-flex items-center py-1 px-2 text-sm font-normal bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-blue-700 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
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
                        class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

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
                                    $date = Carbon\Carbon::parse($progress->inputed_date)
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
                                                class="inline-flex items-center py-1 px-2 text-sm font-normal bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-blue-700 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span class="ml-2">Attachment</span>
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
                        class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

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
                                    $date = Carbon\Carbon::parse($progress->inputed_date)
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{{ $progress->content ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center py-1 px-2 text-sm font-normal bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-blue-700 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
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
                        class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

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
                                    $date = Carbon\Carbon::parse($progress->inputed_date)
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Engineer:</td>
                                <td class="py-1 td-row">
                                    <ul class="list-disc ml-4">
                                        @forelse ($progress->engineerAssignment->engineer ?? [] as $engineer)
                                            <li>{{ $engineer->user->full_name }}</li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{{ $progress->content ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center py-1 px-2 text-sm font-normal bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-blue-700 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
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
                        class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

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
                                    $date = Carbon\Carbon::parse($progress->inputed_date)
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
                                                class="inline-flex items-center py-1 px-2 text-sm font-normal bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-blue-700 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
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
                        class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

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
                                    $date = Carbon\Carbon::parse($progress->inputed_date)
                                        ->locale('id')
                                        ->isoFormat('dddd, D MMMM YYYY, H:m');
                                @endphp
                                <td class="py-1 pr-10">Tanggal Update:</td>
                                <td class="py-1">{{ $date ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">Keterangan:</td>
                                <td class="py-1">{{ $progress->content ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 pr-10">
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center py-1 px-2 text-sm font-normal bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-blue-700 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
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
        <div class="w-full rounded-xl col-span-2">
            <div class="h-full w-full border-2 border-tosca-0 pb-5 p-4 rounded-xl">
                {{-- @forelse ($resume_email as $resume_emails) --}}
                @php
                    $cont = [
                        '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                                    dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
                                    mollit anim id est laborum.</h2>',
                        'Lorem IPP.',
                    ];
                @endphp
                @foreach ($ticket->resumeEmail as $ress)
                    <table @if (!$loop->first) @class(['mt-8' => true]) @endif>
                        <tr>
                            <td class="align-top">
                                <div
                                    class="w-10 h-10 rounded-full border border-gray-600 flex justify-center items-center mr-2">
                                    <span
                                        class="fas fa-user object-cover text-gray-500 rounded-full flex justify-center items-center"></span>
                                </div>
                            </td>
                            {{-- @dd($cont) --}}

                            <td>
                                <div class="flex flex-col">
                                    <div class="flex items-center">
                                        <span class="text-gray-600 font-medium mr-2">{{ $ress->user->full_name }}</span>
                                        <span class="text-gray-400 mr-2">●</span>
                                        <span
                                            class="text-gray-400 text-sm">{{ str_replace('yang', '', $ress->created_date->diffForHumans()) ?? '' }}</span>
                                    </div>
                                    <div class="text-gray-600 {{ 'resume-content-' . $loop->index }}">
                                        {!! $ress->content !!}
                                    </div>
                                    <a class="inline-flex items-center py-1 px-2 w-fit text-sm font-medium bg-white rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-500 focus:z-10 focus:outline-none focus:text-gray-700 hover:text-gray-700 cursor-pointer mt-2 resume-reply"
                                        data-id="{{ $loop->index }}">
                                        <i class="fa-solid fa-share"></i>
                                        <span class="ml-2">Forward</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </table>
                @endforeach
                {{-- @empty

                @endforelse --}}

            </div>
        </div>
        <div class="h-full border-2 border-tosca-0 pb-5 px-8 rounded-xl text-white max-w-[448px]">
            <div><span class="text-lg uppercase font-bold">Detail Ticket</span></div>
            <form action="{{ route('tickets.send-resume-post', $ticket->id) }}" id="form-ajax"
                class="grid grid-cols-1 gap-y-2" method="POST">
                @csrf
                <div
                    class="flex md:flex-row flex-col items-center justify-start max-w-[280px] md:space-x-6 md:space-y-0 space-y-2">
                    <div class="md:text-start text-center">
                        <label for="email_receiver" class="min-w-150 text-gray-600 whitespace-nowrap">Penerima<sup><i
                                    class="fas fa-star-of-life h-1 w-1 text-red-0 scale-50"></i></sup></label>
                    </div>
                    <div class="flex flex-col min-w-[170px]">
                        <select name="email_receiver" type="text" class="w-full" id="email_receiver" required>
                            <option value="" disabled selected>- Pilih Penerima -</option>
                            {{-- <option value="manager">Manager</option> --}}
                            <option value="gm">GM</option>
                            @if (auth()->user()->role_id === 2 || $permission['role'] === 'Admin')
                                <option value="cto">CTO</option>
                            @endif
                        </select>
                        <label id="email_receiver-error" class="error text-sm text-red-500" for="email_receiver"></label>
                    </div>
                </div>
                <div class="flex flex-col justify-start w-full space-y-2">
                    <div class="text-start">
                        <label for="resume_content" class="text-gray-600 whitespace-nowrap">Resume<sup><i
                                    class="fas fa-star-of-life h-1 w-1 text-red-0 scale-50"></i></sup></label>
                    </div>
                    <div class="w-full flex flex-col">
                        <textarea name="resume_content" id="resume_content"
                            class="min-h-[200px] block w-full p-2 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                            placeholder="" required>{{ old('resume_content') }}</textarea>
                        <label id="resume_content-error" class="error text-sm text-red-500" for="resume_content"></label>
                    </div>
                </div>
                <button type="submit"
                    class="inline-block px-6 py-2 w-fit border-2 border-tosca-0 text-tosca-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-xl hover:bg-tosca-0 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                    <span>Kirim</span>
                </button>
            </form>
        </div>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                $(document).ready(function() {

                    $('.resume-reply').click(function() {
                        let id = $(this).attr("data-id");
                        console.log(id);
                        // let resume_content = $(`.resume-content-${id}`).html()
                        let resume_content = trimHtml($(`.resume-content-${id}`).html());
                        $('#resume_content').val(resume_content)
                        console.log(resume_content);
                    })

                    $('textarea').on('keyup keypress', function() {
                        $(this).height(0);
                        $(this).height(this.scrollHeight);
                    });

                    $("textarea").each(function(textarea) {
                        $(this).height(0);
                        $(this).height(this.scrollHeight);
                    });
                });

                function trimHtml(html) {
                    var htmlArray = html.split('\n'),
                        htmlToReturn = [];

                    // menghapus baris kosong pertama dari teks
                    if (htmlArray[0].trim() === '') {
                        htmlArray.shift();
                    }

                    return _.map(htmlArray, function(line) {
                        // per ogni linea trimmo
                        return jQuery.trim(line);
                    }).join('\n');
                }
            </script>
        @endonce
    @endpush
@endsection
