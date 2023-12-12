@extends('layouts.master')

@section('content')
    @push('css')
        <style>
            .td-row {
                padding-left: 20px;
            }
        </style>
    @endpush
    <section>

        <div class="mb-4">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent"
                role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block border-2 cursor-pointer px-6 py-2.5 font-semibold text-sm leading-none uppercase rounded-xl focus:outline-none focus:ring-0 transition duration-150 ease-in-out bg-white text-blue-0 hover:bg-white focus:bg-white active:bg-white"
                        id="detail-tab" data-tabs-target="#detailt" type="button" role="tab" aria-controls="detailt"
                        aria-selected="false">Detail</button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block border-2  cursor-pointer px-6 py-2.5 font-semibold text-sm leading-none uppercase rounded-xl focus:outline-none focus:ring-0 transition duration-150 ease-in-out bg-white text-blue-0 hover:bg-white focus:bg-white active:bg-white"
                        id="progress-tab" data-tabs-target="#progresst" type="button" role="tab"
                        aria-controls="progresst" aria-selected="false">Progress</button>
                </li>
            </ul>
        </div>
        <div id="myTabContent">
            <div class="hidden rounded-lg" id="detailt" role="tabpanel" aria-labelledby="detail-tab">
                <div class="px-5 text-gray-600 border-2 shadow-md rounded-xl md:px-12 py-7 border-blue-0">
                    <div>
                        <div class="text-blue-0"><i class="fas fa-circle-info fa-lg"></i><span
                                class="ml-4 text-lg font-semibold uppercase">Detail Changes</span>
                        </div>
                        <div>
                            <div class="mt-4 font-semibold"><span>{{ $changes->nomor_changes ?? '' }}</span>
                                <span>-</span>
                                <span class="rounded">{{ $changes?->status }}</span>
                            </div>
                            {{-- <div class="flex flex-col mt-4 ml-2 space-y-2 text-sm">
                            <div class="grid grid-cols-1 gap-2 xl:grid-cols-2">
                                <div><i class="fas fa-user"></i><span
                                        class="ml-2">{{ $changes->creator_name ?? '' }}</span>
                                </div>
                                <div class="{{ $changes->created_date == null ? 'hidden' : '' }}"><i
                                        class="fas fa-clock"></i><span class="ml-2"> Dibuat
                                        {{ str_replace('yang', '', $changes->created_date->diffForHumans()) ?? '' }}
                                    </span></div>
                            </div>
                            @if ($changes->last_updated_date && !in_array($changes->last_updated_date, ['', '00', null]))
                                <div><i class="fas fa-clock"></i><span class="ml-2">Diupdate
                                        {{ str_replace('yang', '', $changes->last_updated_date->diffForHumans()) ?? '' }}</span>
                                </div>str_replace('yang', '', $changes->last_updated_date->diffForHumans()) ?? ''
                            @endif
                        </div> --}}
                        </div>
                        @php
                            $changes_table = [
                                [
                                    'name' => 'Dibuat oleh',
                                    'value' => $changes->creator_name ?? '-',
                                ],
                                [
                                    'name' => 'Dibuat pada',
                                    'value' => $changes->created_date ? str_replace('yang', '', $changes->created_date->diffForHumans()) : '-',
                                ],
                                [
                                    'name' => 'Diupdate pada',
                                    'value' => $changes->last_updated_date ? str_replace('yang', '', $changes->last_updated_date->diffForHumans()) : '-',
                                ],
                            ];
                        @endphp
                        <div>
                            <div class="flex flex-col mt-4 ml-2 text-sm">
                                <table class="border-b">
                                    @foreach ($changes_table as $tables)
                                        <tr>
                                            <td class="pb-2" width="40%" style="word-wrap: break-word;">
                                                <div>{{ $tables['name'] }}</div>
                                            </td>
                                            <td class="pb-2">
                                                <div>{{ $tables['value'] }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        @php
                            $title_table = [
                                [
                                    'name' => 'Title',
                                    'value' => $changes->title ?? '-',
                                ],
                                [
                                    'name' => 'Referensi',
                                    'value' => $changes->reference ?? '-',
                                ],
                                [
                                    'name' => 'Prioritas',
                                    'value' => $changes->priority ?? '-',
                                ],
                                [
                                    'name' => 'T/W Aksi',
                                    'value' => $changes->datetime_action
                                        ? \Carbon\Carbon::parse($changes->event_datetime)
                                            ->locale('id')
                                            ->isoFormat('dddd, D MMMM YYYY, hh:mm')
                                        : '',
                                ],
                                [
                                    'name' => 'PIC Telkomsat',
                                    'value' => $changes->pic_telkomsat ?? '-',
                                ],
                                [
                                    'name' => 'PIC Non Telkomsat',
                                    'value' => $changes->pic_nontelkomsat ?? '-',
                                ],
                                [
                                    'name' => 'Tipe Changes',
                                    'value' => is_array($changes->type) ? implode(', ', $changes->type) : $changes->type,
                                ],
                                [
                                    'name' => 'Lokasi',
                                    'value' => $changes->location_name ?? '-',
                                ],
                                [
                                    'name' => 'Agenda',
                                    'value' => $changes->agenda ?? '-',
                                ],
                            ];
                        @endphp
                        <div>
                            <div class="flex flex-col mt-2 ml-2 text-sm">
                                <table class="border-b">
                                    @foreach ($title_table as $tables)
                                        <tr>
                                            <td class="pb-2" width="40%" style="word-wrap: break-word;">
                                                {{ $tables['name'] }}</td>
                                            <td class="pb-2">
                                                <div>{{ $tables['value'] }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        @php
                            $pic_table = [
                                [
                                    'name' => 'Nama Engineer',
                                    'value' => $changes->engineer->full_name ?? '-',
                                ],
                                [
                                    'name' => 'Persetujuan Manager',
                                    'value' => $changes->approval1_name ?? '-',
                                ],
                                [
                                    'name' => 'Persetujuan General Manager',
                                    'value' => $changes->approval2_name ?? '-',
                                ],
                            ];
                        @endphp
                        <div>
                            <div class="flex flex-col mt-2 ml-2 text-sm">
                                <table>
                                    @foreach ($pic_table as $tables)
                                        {{-- <tr>
                                        <td
                                            class="{{ $tables['name'] === 'Nama Engineer' ? 'align-top' : (!$loop->last ? 'pb-2' : '') }}">
                                            {{ $tables['name'] ?? '' }}</td>
                                        @if ($tables['name'] === 'Nama Engineer')
                                            <td class="td-row">
                                                <ul class="list-disc">
                                                    @forelse ($tables['value'] as $engineers)
                                                        <li>{{ $engineers->full_name ?? '' }}</li>
                                                    @empty
                                                    @endforelse
                                                </ul>
                                            </td>
                                        @else
                                            <td class="{{ !$loop->last ? 'pb-2' : '' }}">
                                                {{ $tables['value'] ?? '' }}</td>
                                        @endif
                                    </tr> --}}

                                        <tr>
                                            <td class="pb-2" width="40%" style="word-wrap: break-word;">
                                                {{ $tables['name'] }}</td>
                                            <td class="pb-2">
                                                <div>{{ $tables['value'] }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12 mb-4">
                        <div class="text-blue-0"><i class="fas fa-location-arrow fa-lg"></i><span
                                class="ml-4 text-lg font-semibold">Changes Content</span>
                        </div>
                        <div class="my-2 ml-2">
                            <span class="text-gray-500">{!! $changes->content !!}</span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="hidden bg-white rounded-lg" id="progresst" role="tabpanel" aria-labelledby="progress-tab">
                <div
                    class="grid grid-cols-3 px-5 bg-white border-2 shadow-md md:grid-cols-5 rounded-xl md:px-12 py-7 border-blue-0 md:gap-0 gap-y-8">
                    {{-- <div class="grid grid-cols-3"> --}}
                    <div class="col-span-3 border-r">
                        <div class="text-blue-0"><i class="fas fa-location-arrow fa-lg"></i><span
                                class="ml-4 text-lg font-semibold">TRACKING CHANGES</span></div>


                        <div class="pt-10 overflow-x-auto scrollbar">
                            {{-- @dd($time) --}}
                            <ol class="w-full text-gray-600 align-top sm:flex">

                                @php
                                    $i = 0;
                                    $timeArrayKeys = array_keys($time);
                                    $mappingDiffToTitle = [
                                        'diff_submit' => 'Submit',
                                        'diff_approval1' => 'Approval By Manager',
                                        'diff_approval2' => 'Approval By GM',
                                        'diff_eng_trouble' => 'Engineer Troubleshoot',
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
                                                    <time class="mx-2 text-sm whitespace-nowrap">{{ $value ?? '' }}</time>
                                                    <div class="hidden w-full h-1 bg-gray-200 sm:flex"></div>
                                                </div>
                                            </div>
                                            <div class="mt-3 sm:pr-8">
                                                <h3 class="">Created</h3>
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
                                                        class="mx-2 text-sm whitespace-nowrap">{{ $time[$timeArrayKeys[$i + 1]] ?? '' }}</time>
                                                    <div class="hidden w-full h-1 bg-gray-200 sm:flex"></div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="mt-3 sm:pr-8">
                                            <h3 class="whitespace-nowrap">{{ $mappingDiffToTitle[$key] ?? '' }}</h3>
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
                                            <h3 class="">Created</h3>
                                        </div>
                                    </li>
                                @endforelse

                            </ol>

                        </div>

                        <div class="mt-12">
                            <div class="text-blue-0"><i class="fas fa-timeline"></i><span
                                    class="ml-4 text-lg font-semibold">DETAIL
                                    PROGRESS</span></div>

                            <div class="mt-10">
                                <ol class="relative">
                                    @foreach ($changes->changeManageProgress ?? [] as $progress)
                                        <li class="pb-10 pl-6 border-l border-gray-200">
                                            <span
                                                class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                            </span>
                                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                                {{ $progress->progress_type }}</h3>
                                            <time
                                                class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                                {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                                                ● {{ $progress->user->full_name ?? '' }}
                                                ({{ $progress->user->department->name ?? '' }})
                                            </time>
                                            <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                                <table>
                                                    @if (in_array($progress->progress_type, ['Approval By GM', 'Approval By Manager']))
                                                        <tr>
                                                            <td>{{ $progress->progress_type == 'Approval By GM' ? 'General Manager:' : 'Manager:' }}
                                                            </td>
                                                            <td class="td-row">
                                                                {{ $progress->progress_type == 'Approval By GM' ? $changes->approval2->full_name ?? '' : $changes->approval1->full_name ?? '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Status Persetujuan:</td>
                                                            <td class="td-row">{!! $progress->approval
                                                                ? ($progress->approval->status_approval === 1
                                                                    ? '<div class="py-0.1 px-1 bg-green-0 text-white rounded-sm w-fit"><span>Disetujui</span></div>'
                                                                    : '<div class="py-0.1 px-1 bg-red-0 text-white rounded-sm w-fit"><span>Tidak disetujui</span></div>')
                                                                : '' !!}</td>
                                                        </tr>
                                                        @if ($progress->approval ? $progress->approval->status_approval === 2 : false)
                                                            <tr>
                                                                <td>Alasan:</td>
                                                                <td class="td-row">{!! $progress->approval->reason_reject !!}</td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                    @if (in_array($progress->progress_type, ['Edit']))
                                                        <tr>
                                                            <td>Edited:</td>
                                                            <td class="td-row">
                                                                {{ is_array($progress->edited) ? implode(', ', $progress->edited) : $progress->edited }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (in_array($progress->progress_type, ['Submit', 'Edit']) && $progress?->information)
                                                        <tr>
                                                            <td>Keterangan:</td>
                                                            <td class="td-row">
                                                                {{ $progress->information }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                            @if (!empty($progress->attachments->name))
                                                <a href="{{ asset('storage/' . $progress->attachments->name) }}"
                                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                        class="fa-solid fa-paperclip"></i><span
                                                        class="ml-2">Attachment</span>
                                                </a>
                                            @endif
                                        </li>

                                        {{-- @if ($progress->progress_type == 'Approval By Manager')
                                                <li class="pb-10 pl-6 border-l border-gray-200">
                                                    <span
                                                        class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                                    </span>
                                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">
                                                        {{ $progress->progress_type }}</h3>
                                                    <time
                                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                                        {{ $progress->inputed_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                                                        ● {{ $progress->user->full_name ?? '' }}
                                                        ({{ $progress->user->department->name ?? '' }})
                                                    </time>
                                                    <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                                        <table>
                                                            <tr>
                                                                <td>Manager:</td>
                                                                <td class="td-row">{{ $changes->approval1->full_name ?? '' }}</td>
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
                                            @endif --}}
                                    @endforeach
                                    <li class="pl-6">
                                        <span
                                            class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 bg-green-0 ring-8 ring-white">
                                        </span>
                                        <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-700">Created
                                        </h3>
                                        <time
                                            class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">●
                                            {{ $changes->created_date->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m:s') ?? '' }}
                                            ●
                                            {{ $changes->user->full_name ?? '' }}
                                            ({{ $changes->user->department->name ?? '' }})</time>
                                        <div class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                                            <table>
                                                <tr>
                                                    <td>Nomor Ticket:</td>
                                                    <td class="td-row">{{ $changes->nomor_changes ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Subject:</td>
                                                    <td class="td-row">{{ $changes->title ?? '' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        @if (!empty($changes->attachments))
                                            @foreach ($changes->attachments as $attachment_progress)
                                                <a href="{{ asset('storage/' . str_replace('public/', '', $attachment_progress->url)) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center px-2 py-1 text-sm font-normal text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:z-10 focus:outline-none focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"><i
                                                        class="fa-solid fa-paperclip"></i><span
                                                        class="ml-2">Attachment</span>
                                                </a>
                                            @endforeach
                                        @endif
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3 md:col-span-2">
                        <div class="w-full pl-4">
                            <div class="text-blue-0"><i class="fas fa-comment fa-lg"></i><span
                                    class="ml-4 text-lg font-semibold uppercase">Comment</span></div>
                            <form id="form-comment" action="{{ route('change-managements.comment.create', $changes) }}"
                                class="mt-10" method="post">
                                @method('put')
                                <div class="mb-3">
                                    <textarea id="comment" rows="4" name="comment"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Enter a comment ..." required></textarea>
                                    <label id="comment-error" class="text-xs text-blue-500 error" for="comment"></label>
                                </div>
                                <div class="flex justify-end mb-3">
                                    <button type="submit"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center transition duration-150 ease-in-out">
                                        Submit
                                    </button>
                                    {{-- <button type="submit"
                                            class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">
                                            Submit
                                        </button> --}}
                                </div>
                            </form>
                            <hr class="my-2 border-gray-200">
                            <div id="list-comment"
                                class="flex flex-col items-center justify-center w-full antialiased rounded-xl">
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>

    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                $(document).ready(function() {
                    init()
                    handler()
                })

                function init() {
                    initComment()
                }

                function initComment() {
                    $('#list-comment').empty()

                    apiCall({
                        url: '{{ route('change-managements.comment.all', $changes) }}',
                        success: function(response) {
                            if (response?.success) {
                                const comments = response.data.map(item => {
                                    return `
                                        <div class="flex-col w-full py-2 mb-1 bg-white sm:rounded-lg">
                                            <div class="flex flex-row">
                                                <img class="object-cover w-12 h-12 border-2 border-gray-300 rounded-full"
                                                     alt="Noob master's avatar"
                                                     src="{{ asset('assets/img/pp.png') }}">
                                                <div class="flex-col w-full mt-1">
                                                    <div
                                                        class="flex items-center flex-1 px-4 font-semibold leading-tight text-gray-600">${item.creator}
                                                        <span
                                                            class="ml-2 text-xs font-normal text-gray-500">${item.created_at}</span>
                                                    </div>
                                                    <div class="relative top-0 w-full text-red-0"><i
                                                    class="float-right -mt-4 cursor-pointer fas fa-trash" data-status="hide" onclick="deleteComment(${item.id})" data-id="${item.id}"></i></div>
                                                    <div class="flex-1 px-2 ml-2 text-sm text-gray-600">${item.comment}</div>
                                                </div>
                                            </div>
                                        </div>
                                        `
                                }).join('')

                                $('#list-comment').append(comments)
                            }
                        },
                        error: function(response) {
                            // Do nothing
                        }
                    })
                }

                function deleteComment(id) {
                    Swal.fire({
                        title: 'Apakah yakin ingin delete comment ini?',
                        // showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Iya',
                        // denyButtonText: `Tidak Disetujui`,
                        confirmButtonColor: '#00A19D',
                        // denyButtonColor: '#E05D5D',
                        cancelButtonColor: '#E05D5D',
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            let url = `{{ route('change-managements.delete-comment', ':id') }}`;
                            url = url.replace(':id', id);
                            apiCall({
                                url,
                                type: 'DELETE',
                                data: {
                                    id: id
                                },
                                success: function(response) {
                                    if (response?.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: response.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        })

                                        initComment()
                                    }
                                }
                            })
                        }
                    })
                }

                function handler() {
                    $('#form-comment').validate({
                        submitHandler: function(form, e) {
                            e.preventDefault()

                            callApiWithForm(form, {
                                success: function(response) {
                                    if (response?.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: response.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        })

                                        $(form).trigger('reset')
                                        initComment()
                                    }
                                },
                            })
                        }
                    })
                }
            </script>
        @endonce
    @endpush
@endsection
