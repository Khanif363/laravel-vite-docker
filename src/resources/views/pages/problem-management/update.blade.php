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
        <div class="bg-tosca-0 py-4 px-10 rounded-xl flex justify-center items-center uppercase shadow-md">
            <span class="text-white text-lg font-semibold text-center">Form Update Progress Ticket</span>
        </div>

        <div class="bg-tosca-0 py-3 px-8 mt-2 rounded-xl flex justify-start items-center capitalize shadow-md">
            <span class="text-white text-lg font-semibold">{{ $data['ticket']->nomor_ticket ?? '' }}</span>
        </div>

        <div class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md overflow-x-auto">
            {{-- <ol>
                <li>Durasi Ticket</li>
            </ol> --}}
            <table class="text-gray-600">
                <tr>
                    <td class="pr-24 md:pr-36 py-1">Durasi Ticket</td>
                    <td class="py-1">{{ !empty($data['ticket']->time_diff_now) ? $data['ticket']->time_diff_now . ' Jam' : '' }}
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
                                            <td>{{ $data['ticket']->departmentDispatch->name ?? ($data['ticket']->department->name ?? '') }}
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
                    <td class="py-1">{{ $data['ticket']->priority ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Layanan</td>
                    <td class="py-1">{{ $data['ticket']->service->name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Sumber Info</td>
                    <td class="py-1">{{ $data['ticket']->source_info_trouble ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Aktifitas/Masalah</td>
                    <td class="py-1">{{ $data['ticket']->problem ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1 align-top">Engineer</td>
                    <td class="py-1">
                        @foreach ($data['ticket']->ttpEngineerAssignment ?? [] as $ass)
                            @foreach ($ass->engineerUser as $eng)
                                <li>{{ $eng->full_name ?? '' }}</li>
                            @endforeach
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>

        <div class="bg-tosca-0 py-3 px-8 mt-2 rounded-xl flex justify-start items-center capitalize shadow-md">
            <span class="text-white text-lg font-semibold">Progress</span>
        </div>

        <form action="{{ route('problem-managements.update', $data['problem']->id) }}" method="POST"
            enctype="multipart/form-data" id="form">
            @csrf
            @method('PUT')
            <input type="number" name="inputer_id" value="{{ auth()->user()->id }}" hidden>
            <div
                class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 w-full overflow-x-auto">

                <table class="text-gray-600 whitespace-nowrap">
                    <colgroup>
                        <col span="1" class="md:w-1/5">
                    </colgroup>
                    <tr>
                        @php
                            $last_date = Carbon\Carbon::parse($data['ticket']->last_updated_date ?? '')
                                ->locale('id')
                                ->isoFormat('dddd, D MMMM YYYY');
                        @endphp
                        <td class="py-1 pr-10">Tanggal Update:</td>
                        <td class="py-1">{{ $last_date ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-10">Keterangan:</td>
                        <td class="py-1">{{ $data['ticket']->lastProgress->content ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-10">Aksi:</td>
                        <td class="py-1">{{ $data['ticket']->lastProgress->action ?? '' }}</td>
                    </tr>
                </table>
            </div>

            <div class="p-4 bg-white border-2 border-tosca-0 rounded-xl shadow-md mt-2 flex flex-col space-y-3 w-full">

                <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-x-16 gap-y-2">
                    <div
                        class="flex md:flex-row flex-col items-center justify-center max-w-xl md:space-x-8 md:space-y-0 space-y-2">
                        <div class="min-w-150 md:text-start text-center">
                            <label for="" class="text-gray-600 whitespace-nowrap">Status Ticket</label>
                        </div>
                        <div class="relative flex justify-end w-full">
                            <input name="" type="text" id=""
                                class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                              focus:outline-none focus:ring-1 focus:ring-tosca-0
                              disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none appearance-none bg-none text-gray-600"
                                value="{{ $data['ticket']->status ?? '' }}" disabled>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col items-center w-full md:space-x-8 md:space-y-0 space-y-2">
                        <div class="min-w-150 md:text-start text-center">
                            <label for="progress_type" class="text-gray-600 whitespace-nowrap">Update<sup><i
                                        class="fas fa-star-of-life h-1 w-1 text-red-0 scale-50"></i></sup></label>
                        </div>
                        <div class="flex flex-col w-full">
                            <div class="flex flex-row justify-center md:justify-start">
                                <label class="flex radio p-2 cursor-pointer">
                                    <input class="my-auto transform scale-75" type="radio" name="progress_type"
                                        value="Analysis" />
                                    <div class="title px-2 text-gray-600">Analysis</div>
                                </label>

                                <label class="flex radio p-2 cursor-pointer">
                                    <input class="my-auto transform scale-75" type="radio" name="progress_type"
                                        value="Result" />
                                    <div class="title px-2 text-gray-600">Result</div>
                                </label>
                            </div>
                            @error('progress_type')
                                <span class="inline-text text-sm text-pink-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div
                    class="flex md:flex-row flex-col items-center justify-center w-full md:space-x-8 md:space-y-0 space-y-2">
                    <div class="min-w-150 md:text-start text-center">
                        <label for="information" class="text-gray-600 whitespace-nowrap">Keterangan<sup><i
                                    class="fas fa-star-of-life h-1 w-1 text-red-0 scale-50"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <textarea name="information" id="information"
                            class="block w-full p-2 py-2 bg-white border-1.5 border-tosca-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-tosca-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                            placeholder="Keterangan" rows="3">{{ old('information') }}</textarea>
                        @error('information')
                            <span class="inline-text text-sm text-pink-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex md:flex-row flex-col w-full md:space-x-8 md:space-y-0 space-y-2">
                    <div class="min-w-150 md:text-start text-center">
                        <label for="image_proof" class="text-gray-600 text-left whitespace-nowrap">Attachment
                            File</label>
                    </div>
                    <div class="w-full flex flex-col">
                        <div class="flex md:justify-start justify-center">
                            <input name="image_proof[]" type="file" id="image_proof"
                                class="text-sm text-slate-500 file:rounded-2xl rounded-2xl
                file:text-sm file:font-semibold file:py-1
                file:bg-yellow-0 file:text-white
                hover:file:bg-yellow-2
                "
                                multiple />
                            @error('image_proof')
                                <span class="inline-flex text-sm text-pink-600">{{ $message }}</span>
                            @enderror
                            @if ($errors->has('image_proof.*'))
                                @foreach ($errors->get('image_proof.*') as $key => $error)
                                    <span class="inline-flex text-sm text-pink-600">{{ $errors->first($key) }}</span>
                                @endforeach
                            @endif
                        </div>
                        <div class="mt-2 md:text-left text-center">
                            <span class="text-red-0"><i class="fas fa-circle-exclamation"></i></span>
                            <span class="text-sm capitalize text-gray-500">
                                UKURAN FILE MAKS 1024 KB, EKSTENSI FILE YANG DIIJINKAN GIF, JPG,
                                PNG, PDF, JPEG, XLS, XLSX, DOC, DOCX
                            </span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="justify-between flex">
                <a href="{{ route('problem-managements.index') }}"
                    class="inline-block px-6 py-2.5 mt-5 bg-red-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-red-1 hover:shadow-lg focus:bg-red-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-2 active:shadow-lg transition duration-150 ease-in-out">Back</a>
                <button type="submit"
                    class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">Submit</button>
            </div>
        </form>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript"></script>
        @endonce
    @endpush
@endsection
