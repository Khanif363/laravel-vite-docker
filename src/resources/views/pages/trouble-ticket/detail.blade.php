@extends('layouts.master')

@section('content')
    @push('css')
        <style>
            .td-row {
                padding-left: 20px;
            }
        </style>
    @endpush
    <section class="grid w-full h-full grid-cols-1 mt-4 mb-10 md:grid-cols-3 md:gap-x-4 gap-y-4">
        <div class="h-full bg-tosca-0 pt-24 pb-5 px-5 rounded-xl text-white max-w-[448px]">
            <div><span class="text-lg font-bold uppercase">Detail Ticket</span></div>
            <div class="flex flex-col space-y-7 mt-9">
                <div>
                    <div class="font-semibold"><span>{{ $ticket->nomor_ticket ?? '-' }}</span><span
                            class="rounded bg-grey-0 text-gray-800 ml-3 py-0.5 px-2">{{ $ticket->status ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col mt-4 ml-2 space-y-2 text-sm">
                        <div class="grid grid-cols-1 gap-2 xl:grid-cols-2">
                            <div><i class="fas fa-user"></i><span class="ml-2">{{ $ticket->user->full_name ?? '-' }}</span>
                            </div>
                            <div class="{{ $ticket->created_date == null ? 'hidden' : '' }}"><i
                                    class="fas fa-clock"></i><span class="ml-2"> Dibuat
                                    {{ str_replace('yang', '', $ticket->created_date->diffForHumans()) ?? '-' }}
                                </span></div>
                        </div>
                        @if ($ticket->last_updated_date && !in_array($ticket->last_updated_date, ['', '00', null]))
                            <div><i class="fas fa-clock"></i><span class="ml-2">Diupdate
                                    {{ str_replace('yang', '', $ticket->last_updated_date->diffForHumans()) ?? '-' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="font-semibold"><span>{!! $ticket->subject ?? '-' !!}</span></div>
                    <div class="pt-3"><span
                            class="rounded bg-white text-tosca-0 py-0.5 px-2 {{ $ticket->priority ? '' : 'hidden' }}">{{ $ticket->priority ?? '-' }}</span><span
                            class="rounded bg-white text-tosca-0 ml-3 py-0.5 px-2">{{ $ticket->type ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col mt-4 ml-2 text-sm">
                        <table>
                            <tr>
                                <td>Department</td>
                                <td>{{ $ticket->department->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="my-1">Kategori</td>
                                <td class="my-1">{{ $ticket->category ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Service</td>
                                <td>{{ $ticket->service->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>RFO</td>
                                <td>{{ (count($ticket?->ttpTechnicalClose) > 0 ? $ticket?->ttpTechnicalClose[0]?->technicalClose?->rfo : null) ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Tindakan</td>
                                <td>{{ $ticket->resume->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @php
                    $table = [
                        [
                            'name' => 'Waktu',
                            'value' => !empty($ticket->event_datetime)
                                ? \Carbon\Carbon::parse($ticket->event_datetime)
                                    ->locale('id')
                                    ->isoFormat('dddd, D MMMM YYYY')
                                : '-',
                        ],
                        [
                            'name' => 'Lokasi',
                            'value' => $ticket->eventLocation->name ?? '-',
                        ],
                        [
                            'name' => 'Sumber Info',
                            'value' => $ticket->ticketInfo->source_info ?? '-',
                        ],
                        [
                            'name' => 'Nama Pelapor',
                            'value' => $ticket->ticketInfo->name ?? '-',
                        ],
                        [
                            'name' => 'Nomor Pelapor',
                            'value' => $ticket->ticketInfo->number_phone ?? '-',
                        ],
                        [
                            'name' => 'Permasalahan',
                            'value' => $ticket->problem ?? '-',
                        ],
                    ];
                @endphp
                <div>
                    <div class="font-semibold"><span>KEJADIAN & MASALAH</span></div>
                    <div class="flex flex-col mt-4 ml-2 text-sm">
                        <table>
                            @foreach ($table as $tables)
                                @if ($tables['name'] !== 'Permasalahan')
                                    <tr>
                                        <td class="pb-2">{{ $tables['name'] ?? '-' }}</td>
                                        <td class="pb-2">{!! $tables['value'] ?? '-' !!}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                        <div class="flex flex-col space-y-2">
                            <div class="pb-2">{!! $table[5]['name'] ?? '-' !!}</div>
                            <div class="pb-2">{!! $table[5]['value'] ?? '-' !!}</div>
                            {{-- @dd($table) --}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="relative bottom-0 right-0"> --}}
            {{-- <a href=""
                    class="relative -bottom-20 right-0 cursor-pointer px-6 py-2.5 mx-2 font-medium text-sm leading-tight rounded-xl shadow-md hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-white text-tosca-0 focus:text-tosca-2">Back</a> --}}
            {{-- </div> --}}
        </div>
        <div class="col-span-2 px-8 border-2 shadow-md rounded-xl md:px-16 pt-7 border-tosca-0">
            <div class="text-tosca-0"><i class="fas fa-location-arrow fa-lg"></i><span
                    class="ml-4 text-lg font-semibold">TRACKING TICKET</span></div>
            <div class="pt-10 overflow-x-auto scrollbar">
                {{-- @dd($time) --}}
                <ol class="w-full text-gray-600 align-top sm:flex">

                    <ol class="w-full text-gray-600 align-top sm:flex">

                        @php
                            $i = 0;
                            $timeArrayKeys = array_keys($time);
                            $mappingDiffToTitle = [
                                'diff_diagnosa' => 'Diagnose',
                                'diff_eng_assign' => 'Engineer Assignment',
                                'diff_eng_trouble' => 'Engineer Troubleshoot',
                                'diff_tech_close' => 'Technical Close',
                                'diff_monitor' => 'Monitoring',
                                'diff_closed' => 'Closed',
                            ];
                        @endphp
                        @forelse($time as $key => $value)
                            @if ($i === 0)
                                <li class="relative mb-6 sm:mb-0">
                                    <div class="flex items-center">
                                        <div
                                            class="z-10 flex items-center justify-center w-8 h-8 rounded-full bg-green-0 ring-0 ring-white sm:ring-8 shrink-0">
                                        </div>
                                        <div class="w-full -mt-6 text-center">
                                            <time class="mx-2 text-sm whitespace-nowrap">{{ $value ?? '-' }}</time>
                                            <div class="hidden w-full h-1 bg-gray-200 sm:flex"></div>
                                        </div>
                                    </div>
                                    <div class="mt-3 sm:pr-8">
                                        <h3 class="">Open</h3>
                                    </div>
                                </li>
                            @endif

                            <li class="relative mb-6 sm:mb-0">
                                <div class="flex items-center">
                                    <div
                                        class="z-10 flex items-center justify-center w-8 h-8 rounded-full bg-green-0 ring-0 ring-white sm:ring-8 shrink-0">
                                    </div>

                                    @if ($i < count($time) - 1)
                                        <div class="w-full -mt-6 text-center">
                                            <time
                                                class="mx-2 text-sm whitespace-nowrap">{{ $time[$timeArrayKeys[$i + 1]] ?? '-' }}</time>
                                            <div class="hidden w-full h-1 bg-gray-200 sm:flex"></div>
                                        </div>
                                    @endif

                                </div>
                                <div class="mt-3 sm:pr-8">
                                    <h3 class="whitespace-nowrap">{{ $mappingDiffToTitle[$key] ?? '-' }}</h3>
                                </div>
                            </li>

                            @php $i++; @endphp
                        @empty

                            <li class="relative mb-6 sm:mb-0">
                                <div class="flex items-center">
                                    <div
                                        class="z-10 flex items-center justify-center w-8 h-8 rounded-full bg-green-0 ring-0 ring-white sm:ring-8 shrink-0">
                                    </div>
                                    <div class="w-full -mt-6 text-center">
                                        <time class="mx-2 text-sm whitespace-nowrap"></time>
                                        {{-- <div class="hidden w-full h-1 bg-gray-200 sm:flex"></div> --}}
                                    </div>
                                </div>
                                <div class="mt-3 sm:pr-8">
                                    <h3 class="">Open</h3>
                                </div>
                            </li>
                        @endforelse

                    </ol>

            </div>

            <div class="mt-12">
                <div class="text-tosca-0"><i class="fas fa-timeline"></i><span class="ml-4 text-lg font-semibold">DETAIL
                        PROGRESS</span></div>

                <div class="mt-10">
                    <ol class="relative">
                        @foreach ($ticket->troubleTicketProgress as $progress)
                            @if ($progress->update_type == 'Closed')
                                <li class="pb-10 pl-6 border-l border-gray-200">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                        {{ $progress->update_type ?? '-' }}
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Latest</span>
                                    </h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }}
                                        ● {{ $progress->user->full_name ?? '-' }}
                                        ({{ $progress->user->department->name ?? '-' }})
                                        @if (!empty($progress->editor))
                                            <br>
                                            Terakhir diedit oleh: {{ $progress->editor->full_name }}, pada
                                            {{ $progress->updated_date ? $progress->updated_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' : '' }}
                                        @endif
                                    </time>
                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                        @if ($permission['role'] === 'Admin' || isset($access_with_department['Edit Progress Ticket']))
                                            <div class="flex justify-end my-2"><i
                                                    class="cursor-pointer fas fa-pen-to-square editor-button"
                                                    data-type="update" data-id="{{ $progress->id }}"
                                                    data-status="hide"></i></div>
                                        @endif
                                        <div class="editor{{ $progress->id }}"></div>
                                        <table>
                                            <tr>
                                                <td>Skor Kepuasan:</td>
                                                <td class="td-row">{{ $ticket->rate ? $ticket->rate . ' %' : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Keterangan:</td>
                                                <td class="td-row data-content{{ $progress->id }}">{!! $progress->content ?? '-' !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    @if (!empty($progress->attachments))
                                        @foreach ($progress->attachments as $attachment_progress)
                                            <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                    class="fa-solid fa-paperclip"></i><span class="ml-2">Attachment</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </li>
                            @endif
                            @if ($progress->update_type == 'Monitoring')
                                <li class="pb-10 pl-6 border-l border-gray-200">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                        {{ $progress->update_type ?? '-' }}</h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }}
                                        ● {{ $progress->user->full_name ?? '-' }}
                                        ({{ $progress->user->department->name ?? '-' }})
                                        @if (!empty($progress->editor))
                                            <br>
                                            Terakhir diedit oleh: {{ $progress->editor->full_name }}, pada
                                            {{ $progress->updated_date ? $progress->updated_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' : '' }}
                                        @endif
                                    </time>
                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                        @if ($permission['role'] === 'Admin' || isset($access_with_department['Edit Progress Ticket']))
                                            <div class="flex justify-end my-2"><i
                                                    class="cursor-pointer fas fa-pen-to-square editor-button"
                                                    data-type="update" data-id="{{ $progress->id }}"
                                                    data-status="hide"></i></div>
                                        @endif
                                        <div class="editor{{ $progress->id }}"></div>
                                        <table>
                                            <tr>
                                                <td>Keterangan:</td>
                                                <td class="td-row data-content{{ $progress->id }}">{!! $progress->content ?? '-' !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
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
                                </li>
                            @endif
                            @if ($progress->update_type == 'Technical Close')
                                <li class="pb-10 pl-6 border-l border-gray-200">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                        {{ $progress->update_type ?? '-' }}</h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }}
                                        ● {{ $progress->user->full_name ?? '-' }}
                                        ({{ $progress->user->department->name ?? '-' }})
                                    </time>
                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                        <table>
                                            <tr>
                                                <td>Waktu Selesai:</td>
                                                <td class="td-row">
                                                    {{ $progress->technicalClose->close_datetime->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m') ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Visit Lokasi:</td>
                                                <td class="td-row">{{ $progress->is_visited == 1 ? 'Iya' : 'Tidak' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Evaluasi:</td>
                                                <td class="td-row">{{ $progress->technicalClose->evaluation ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>RFO:</td>
                                                <td class="td-row">{{ $progress->technicalClose->rfo ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    @if (!empty($progress->attachments->name))
                                        <a href="{{ asset('storage/' . $progress->attachments->name) }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                class="fa-solid fa-paperclip"></i><span class="ml-2">Attachment</span>
                                        </a>
                                    @endif
                                </li>
                            @endif
                            @if ($progress->update_type == 'Dispatch')
                                <li class="pb-10 pl-6 border-l border-gray-200">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                        {{ $progress->update_type }}</h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }}
                                        ● {{ $progress->user->full_name ?? '-' }}
                                        ({{ $progress->user->department->name ?? '-' }})
                                        @if (!empty($progress->editor))
                                            <br>
                                            Terakhir diedit oleh: {{ $progress->editor->full_name }}, pada
                                            {{ $progress->updated_date ? $progress->updated_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' : '' }}
                                        @endif
                                    </time>
                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                        @if ($permission['role'] === 'Admin' || isset($access_with_department['Edit Progress Ticket']))
                                            <div class="flex justify-end my-2"><i
                                                    class="cursor-pointer fas fa-pen-to-square editor-button"
                                                    data-type="update" data-id="{{ $progress->id }}"
                                                    data-status="hide"></i></div>
                                        @endif
                                        <div class="editor{{ $progress->id }}"></div>
                                        <table>
                                            <tr>
                                                <td>Department from:</td>
                                                <td class="td-row">
                                                    {{ $progress->dispatch->department_from->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Department to:</td>
                                                <td class="td-row">{{ $progress->dispatch->department_to->name ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Keterangan:</td>
                                                <td class="td-row data-content{{ $progress->id }}">{!! $progress->content ?? '-' !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
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
                                </li>
                            @endif
                            @if ($progress->update_type == 'Engineer Troubleshoot')
                                <li class="pb-10 pl-6 border-l border-gray-200">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                        {{ $progress->update_type ?? '-' }}</h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }}
                                        ● {{ $progress->user->full_name ?? '-' }}
                                        ({{ $progress->user->department->name ?? '-' }})
                                        @if (!empty($progress->editor))
                                            <br>
                                            Terakhir diedit oleh: {{ $progress->editor->full_name }}, pada
                                            {{ $progress->updated_date ? $progress->updated_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' : '' }}
                                        @endif
                                    </time>
                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                        @if ($permission['role'] === 'Admin' || isset($access_with_department['Edit Progress Ticket']))
                                            <div class="flex justify-end my-2"><i
                                                    class="cursor-pointer fas fa-pen-to-square editor-button"
                                                    data-type="update" data-id="{{ $progress->id }}"
                                                    data-status="hide"></i></div>
                                        @endif
                                        <div class="editor{{ $progress->id }}"></div>
                                        <table>
                                            <tr>
                                                <td>Keterangan:</td>
                                                <td class="td-row data-content{{ $progress->id }}">{!! $progress->content ?? '-' !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
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
                                </li>
                            @endif
                            @if ($progress->update_type == 'Engineer Assignment')
                                <li class="pb-10 pl-6 border-l border-gray-200">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                        {{ $progress->update_type ?? '-' }}</h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }}
                                        ● {{ $progress->user->full_name ?? '-' }}
                                        ({{ $progress->user->department->name ?? '-' }})
                                    </time>
                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                        <div class="editor{{ $progress->id }}"></div>
                                        <table>
                                            <tr>
                                                <td class="align-top">Engineer:</td>
                                                <td class="td-row">
                                                    <ul class="ml-4 list-disc">
                                                        @forelse ($progress->engineerAssignment->engineer as $engineer)
                                                            <li>{{ $engineer->user->full_name }}</li>
                                                        @empty
                                                        @endforelse
                                                    </ul>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
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
                                </li>
                            @endif
                            @if ($progress->update_type == 'Pending')
                                <li class="pb-10 pl-6 border-l border-gray-200">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                        {{ $progress->update_type ?? '-' }}</h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }}
                                        ● {{ $progress->user->full_name ?? '-' }}
                                        ({{ $progress->user->department->name ?? '-' }})
                                    </time>
                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                        <table>
                                            <tr>
                                                <td>Durasi Pending:</td>
                                                {{-- <td class="td-row">
                                                    {{ $progress->pending->duration->isoFormat('H:m') ?? '-' }}
                                                    jam</td> --}}
                                            </tr>
                                            <tr>
                                                <td>By:</td>
                                                <td class="td-row">{{ $progress->pending->pending_by ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Alasan:</td>
                                                <td class="td-row">{{ $progress->pending->reason ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
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
                                </li>
                            @endif
                            @if ($progress->update_type == 'Diagnose')
                                <li class="pb-10 pl-6 border-l border-gray-200">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                        {{ $progress->update_type ?? '-' }}</h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }}
                                        ● {{ $progress->user->full_name ?? '-' }}
                                        ({{ $progress->user->department->name ?? '-' }})
                                        @if (!empty($progress->editor))
                                            <br>
                                            Terakhir diedit oleh: {{ $progress->editor->full_name }}, pada
                                            {{ $progress->updated_date ? $progress->updated_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' : '' }}
                                        @endif
                                    </time>
                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                        @if ($permission['role'] === 'Admin' || isset($access_with_department['Edit Progress Ticket']))
                                            <div class="flex justify-end my-2"><i
                                                    class="cursor-pointer fas fa-pen-to-square editor-button"
                                                    data-type="update" data-id="{{ $progress->id }}"
                                                    data-status="hide"></i></div>
                                        @endif
                                        <div class="editor{{ $progress->id }}"></div>
                                        <table>
                                            <tr>
                                                <td>Department:</td>
                                                <td class="td-row">{{ $ticket->department->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Layanan:</td>
                                                <td class="td-row">{{ $ticket->service->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Data Backhaul/Data Link:</td>
                                                <td class="td-row">{{ $ticket->mantoolsDatacom->data_backhaul ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Keterangan:</td>
                                                <td class="td-row data-content{{ $progress->id }}">
                                                    {!! $progress->content ?? '-' !!}</td>
                                            </tr>
                                        </table>
                                    </div>
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
                                </li>
                            @endif
                        @endforeach
                        <li class="pb-10 pl-6">
                            <span
                                class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">Open
                            </h3>
                            <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                {{ $ticket->created_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '-' }} ●
                                {{ $ticket->user->full_name ?? '-' }} ({{ $ticket->user->department->name ?? '-' }})
                            </time>
                            <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                <table>
                                    <tr>
                                        <td>Nomor Ticket:</td>
                                        <td class="td-row">{{ $ticket->nomor_ticket ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Subject:</td>
                                        <td class="td-row data-content{{ $ticket->id }}">{!! $ticket->subject ?? '-' !!}
                                        </td>
                                    </tr>
                                    @if ($permission['role'] === 'Admin' || isset($access_with_department['Edit Progress Ticket']))
                                        <div class="flex justify-end my-2"><i
                                                class="cursor-pointer fas fa-pen-to-square editor-button"
                                                data-type="create" data-id="{{ $ticket->id }}"
                                                data-status="hide"></i></div>
                                    @endif
                                    <div class="editor{{ $ticket->id }}"></div>
                                </table>
                            </div>
                            @if (!empty($ticket->attachments))
                                @foreach ($ticket->attachments as $attachment_progress)
                                    <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                        target="_blank"
                                        class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                            class="fa-solid fa-paperclip"></i><span class="ml-2">Attachment</span>
                                    </a>
                                @endforeach
                            @endif
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                // let id = 0;
                // let num = 0
                $(document).ready(function() {
                    let currentType;

                    $('.editor-button').on('click', function() {
                        let id = $(this).data('id');
                        let status = $(this).data('status');
                        currentType = $(this).data('type');

                        if (status == "show") {
                            // Menambahkan textarea
                            $(`.editor${id}`).append(
                                `<textarea name="content" id="content${id}" class="ckeditor">${$(`.data-content${id}`, $(this).closest('.mb-4')).html().trim()}</textarea><button class="inline-block px-2 py-1 mt-2 mb-6 text-sm font-medium leading-tight text-white transition duration-150 ease-in-out bg-blue-600 rounded-md shadow-md editor-submit hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg" id="submit${id}" data-id="${id}">Save</button>`
                            );
                            CKEDITOR.replace(`content${id}`);
                            $(this).data('status', 'hide');
                        } else if (status == "hide") {
                            // Menghapus textarea
                            let editor = CKEDITOR.instances[`content${id}`];
                            if (editor) {
                                editor.destroy();
                            }
                            $(`.editor${id} textarea.ckeditor`).remove();
                            $(`#submit${id}`).remove();
                            $(this).data('status', 'show');
                        }
                    });

                    $(document).on('click', '.editor-submit', function() {
                        let id = $(this).data('id');
                        let url = '';
                        if (currentType == 'update'){
                            url = `{{ route('tickets.edit-progress', ':id') }}`;
                        }
                        if (currentType == 'create') {
                                url = `{{ route('tickets.edit-create', ':id') }}`;
                        }
                        url = url.replace(':id', id);
                        let value = CKEDITOR.instances[`content${id}`].getData();
                        let $this = $(this);


                        return Swal.fire({
                            title: 'Apakah anda sudah yakin?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#00A19D',
                            cancelButtonColor: '#E05D5D',
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Tidak'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                apiCall({
                                    url,
                                    type: 'PUT',
                                    data: {
                                        content: value,
                                    },
                                    success: function(response) {
                                        if (response?.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: response?.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                window.location.href = response.data
                                                    ?.route;
                                            });
                                        }
                                    },
                                    beforeSend: function() {
                                        // Menonaktifkan tombol "Save" saat pengiriman permintaan AJAX
                                        $this.attr('disabled', true);
                                    },
                                    complete: function() {
                                        // Mengaktifkan tombol "Save" setelah permintaan AJAX selesai
                                        $this.attr('disabled', false);
                                    },
                                    error: function() {
                                        // Menampilkan peringatan jika terjadi kesalahan pada permintaan AJAX
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Something went wrong!'
                                        });
                                    }
                                });
                            }
                        })

                    });

                    // Mengatur status awal textarea menjadi "show"
                    $('.editor-button').each(function() {
                        $(this).data('status', 'show');
                    });

                    CKEDITOR.on('instanceCreated', function(event) {
                        var editor = event.editor;
                        editor.on('contentDom', function() {
                            editor.document.on('click', function(event) {
                                event.stopPropagation();
                            });
                        });
                    });
                });
            </script>
        @endonce
    @endpush
@endsection
