@extends('layouts.master')

@section('content')
    @push('css')
        <style>
            .td-row {
                padding-left: 20px;
            }
        </style>
    @endpush
    <section class="w-full h-full mt-4 mb-10 grid grid-cols-1 md:grid-cols-3 md:gap-x-4 gap-y-4">
        <div class="h-full bg-tosca-0 pt-24 pb-5 px-5 rounded-xl text-white max-w-[448px]">
            <div><span class="text-lg uppercase font-bold">Detail Problem Request</span></div>
            <div class="flex flex-col space-y-7 mt-9">
                <div>
                    <span class="font-semibold block">Problem Request ID</span>
                    <span class="font-semibold">{{ $problem->nomor_problem ?? '' }}</span>
                    <div class="flex flex-col space-y-2 text-sm mt-4 ml-2">
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-2">
                            <div class="{{ $problem->creator->full_name == null ? 'hidden' : '' }}"><i class="fas fa-user"></i><span
                                    class="ml-2">{{ $problem->creator->full_name ?? '' }}</span>
                            </div>
                            <div class="{{ $problem->created_at == null ? 'hidden' : '' }}"><i
                                    class="fas fa-clock"></i><span class="ml-2">
                                    Dibuat
                                    {{ str_replace('yang', '', \Carbon\Carbon::parse($problem->created_at)->diffForHumans()) ?? '' }}</span>
                            </div>
                        </div>
                        <div class="{{ ($problem->lastProgress->created_at ?? null) == null ? 'hidden' : '' }}"><i
                                class="fas fa-clock"></i><span class="ml-2">Diupdate
                                {{ str_replace('yang', '', \Carbon\Carbon::parse($problem->lastProgress->created_at ?? null)->diffForHumans()) ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="font-semibold block">Reference Ticket ID</span>
                    <span class="font-semibold block">{{ $problem->troubleTicket->nomor_ticket ?? '' }}</span>
                    <span
                        class="rounded bg-white text-tosca-0 py-0.5 px-2 drop-shadow-lg {{ empty($ticket->troubleTicket->status) ? 'hidden' : '' }}">{{ $ticket->troubleTicket->status ?? '' }}</span>
                </div>
            </div>
        </div>
        <div
            class="rounded-xl md:px-16 px-8 pt-7 pb-9 border-2 border-tosca-0 shadow-md col-span-2 scrollbar overflow-x-auto">
            <div class=""><i class="fas fa-ellipsis-vertical fa-lg text-tosca-0"></i><span
                    class="ml-4 text-lg font-semibold text-gray-600 uppercase">Subject</span></div>
            <div class="mt-8">

                <table class="text-gray-600 whitespace-nowrap">
                    <colgroup>
                        <col span="4" class="md:w-1/5">
                    </colgroup>
                    <tr>
                        <td class="py-1">Department:</td>
                        <td class="py-1">{{ $problem->troubleTicket->ttpDispatch[0]->departmentDispatch->name ?? ($problem->troubleTicket->department->name ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Layanan:</td>
                        <td class="py-1">{{ $problem->troubleTicket->service->name ?? '' }}</td>
                    </tr>
                </table>

            </div>

            <div class="mt-12">
                <div class=""><i class="fas fa-ellipsis-vertical fa-lg text-tosca-0"></i><span
                        class="ml-4 text-lg font-semibold text-gray-600 uppercase">Kejadian & Masalah</span></div>

                <div class="mt-8">
                    <table class="text-gray-600 whitespace-nowrap">
                        <colgroup>
                            <col span="4" class="md:w-1/5">
                        </colgroup>
                        @php
                            $date = Carbon\Carbon::parse($problem->created_at)
                                ->locale('id')
                                ->isoFormat('dddd, D MMMM YYYY');
                        @endphp
                        <tr>
                            <td class="py-1">Waktu:</td>
                            <td class="py-1">{{ $problem->date ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Sumber Info:</td>
                            <td class="py-1">{{ $problem->troubleTicket->ticketInfo->source_info ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Nama Pelapor:</td>
                            <td class="py-1">{{ $problem->troubleTicket->ticketInfo->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Nomor Pelapor:</td>
                            <td class="py-1">{{ $problem->troubleTicket->ticketInfo->number_phone ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Permasalahan:</td>
                            <td class="py-1">{{ $problem->troubleTicket->problem ?? '' }}</td>
                        </tr>
                        {{-- <tr>
                            <td class="py-1">Keterangan Request:</td>
                            <td class="py-1">{{ $problem->content ?? '' }}</td>
                        </tr> --}}
                    </table>
                </div>
            </div>
            <div class="mt-12">
                <div class=""><i class="fas fa-ellipsis-vertical fa-lg text-tosca-0"></i><span
                        class="ml-4 text-lg font-semibold text-gray-600 uppercase">Detail Request</span></div>

                <div class="mt-8">
                    <table class="text-gray-600 whitespace-nowrap">
                        <colgroup>
                            <col span="4" class="md:w-1/5">
                        </colgroup>
                        @php
                            $date = Carbon\Carbon::parse($problem->created_at)
                                ->locale('id')
                                ->isoFormat('dddd, D MMMM YYYY');
                        @endphp
                        <tr>
                            <td class="py-1">Keterangan Request:</td>
                            <td class="py-1">{{ $problem->content ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Last Progress:</td>
                            <td class="py-1">{{ $problem->lastProgress->information ?? '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-12">
                <div class=""><i class="fas fa-ellipsis-vertical fa-lg text-tosca-0"></i><span
                        class="ml-4 text-lg font-semibold text-gray-600 uppercase">Problem Result</span></div>

                <div class="mt-8">
                    <span class="w-full text-gray-600">{!! $problem->progressResult[0]->information ?? '' !!}</span>
                </div>
            </div>

            <div class="mt-12">
                <div class=""><i class="fas fa-ellipsis-vertical fa-lg text-tosca-0"></i><span
                        class="ml-4 text-lg font-semibold text-gray-600 uppercase">STATUS</span></div>

                @php
                    $status = [
                        'nama' => 'Manager',
                        'color' => $problem->is_verified == 1 || $problem->is_verified == 0 ? 'text-green-0' : 'text-red-0',
                        'icon' => $problem->is_verified == 1 ? 'fas fa-square-check' : ($problem->is_verified == 0 ? 'far fa-square' : ($problem->is_verified == 2 ? 'fas fa-square-xmark' : '')),
                    ];
                @endphp
                <div class="mt-8">
                    <span class="space-x-2 whitespace-nowrap block"><i
                            class="{{ $status['icon'] }} {{ $status['color'] }}"></i><span
                            class="text-gray-600">{{ $status['nama'] }}</span></span>

                </div>
            </div>

        </div>
    </section>
@endsection
