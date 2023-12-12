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
        <div class="flex items-center justify-center px-10 py-4 uppercase shadow-md bg-red-0 rounded-xl">
            <span class="text-lg font-semibold text-center text-white">Form Update Progress Ticket</span>
        </div>

        <div class="flex items-center justify-start px-8 py-3 mt-2 capitalize shadow-md bg-red-0 rounded-xl">
            <span class="text-lg font-semibold text-white">{{ $changes->nomor_changes }}</span>
        </div>

        <div class="p-4 overflow-x-auto bg-white border-2 shadow-md border-red-0 rounded-xl">
            {{-- <ol>
                <li>Durasi Ticket</li>
            </ol> --}}
            <table class="text-gray-600">
                <tr>
                    <td class="py-1">Title</td>
                    <td class="py-1">{{ $changes->title ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Prioritas</td>
                    <td class="py-1">{{ $changes->priority ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1">T/W Eksekusi</td>
                    <td class="py-1">
                        {{ $changes->datetime_action? Carbon\Carbon::parse($changes->datetime_action)->locale('id')->isoFormat('dddd, D MMMM YYYY, H:m'): '' }}
                    </td>
                </tr>
                <tr>
                    <td class="py-1">Referensi Changes</td>
                    <td class="py-1">{{ $changes->reference ?? '' }}</td>
                </tr>
                @if ($changes->reference === 'Ticket')
                    <tr>
                        <td class="py-1">No. Ticket Reference</td>
                        <td class="py-1">{{ $ticket->nomor_ticket ?? '' }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="py-1">PIC Telkomsat</td>
                    <td class="py-1">{{ $changes->pic_telkomsat ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1">PIC Non Telkomsat</td>
                    <td class="py-1">{{ $changes->pic_nontelkomsat ?? '' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Tipe Changes</td>
                    <td class="py-1">
                        {{ is_array($changes->type) ? implode(',', $changes->type) : $changes->type ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="py-1 align-top">Mengetahui</td>
                    <td class="py-1">
                        <table class="">
                            <tr>
                                <td>
                                    <ul class="list-disc">
                                        <li>
                                            <ul class="ml-4 list-decimal">
                                                @forelse ($changes->engineers as $engineers)
                                                    <li>{{ $engineers->full_name ?? '' }}</li>
                                                @empty
                                                @endforelse
                                            </ul>
                                        </li>
                                        <li class="py-1">{{ $changes->approval1_name ?? '' }}</li>
                                        <li class="py-1">{{ $changes->approval2_name ?? '' }}</li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <form action="{{ route('change-managements.update', $changes->id) }}" method="POST" enctype="multipart/form-data"
            id="form">
            @method('PUT')
            @csrf
            {{-- <input type="number" name="inputer_id" value="{{ auth()->user()->id }}" hidden> --}}
            <div class="flex flex-col w-full p-4 mt-2 space-y-3 bg-white border-2 shadow-md border-red-0 rounded-xl">
                <div
                    class="flex flex-col items-center justify-center max-w-xl space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="progress_type" class="text-gray-600 whitespace-nowrap">Jenis Update<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="relative flex flex-col justify-end w-full">
                        <select name="progress_type" type="text" class="w-full" id="progress_type">
                            <option value="" disabled selected>-- Pilih Progress --</option>
                            @foreach ($data_view['progress_types'] as $progress_types)
                                @if (in_array($changes->lastProgress->progress_type, ['Approval By GM']) && in_array($progress_types, ['Engineer Troubleshoot']))
                                    <option value="{{ $progress_types }}"
                                        @if (old('progress_type') === $progress_types) {{ 'selected' }} @endif>{{ $progress_types }}
                                    </option>
                                @endif
                                @if (!in_array($changes->lastProgress->progress_type, ['Approval By Manager', 'Approval By GM']) && $changes->lastProgress->progress_type === 'Engineer Troubleshoot' && in_array($progress_types, ['Engineer Troubleshoot', 'Closed']))
                                    <option value="{{ $progress_types }}"
                                        @if (old('progress_type') === $progress_types) {{ 'selected' }} @endif>{{ $progress_types }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div
                    class="flex flex-col items-center justify-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="content" class="text-gray-600 whitespace-nowrap">Keterangan<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="flex flex-col w-full">
                        <textarea name="content" id="content"
                            class="block w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-yellow-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                            placeholder="Keterangan" rows="3">{{ old('content') }}</textarea>
                    </div>
                </div>

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
                <a href="{{ route('change-managements.index') }}"
                    class="inline-block px-6 py-2.5 mt-5 bg-red-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-red-1 hover:shadow-lg focus:bg-red-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-2 active:shadow-lg transition duration-150 ease-in-out">Back</a>
                <button type="submit"
                    class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">Submit</button>
            </div>
        </form>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                $(document).ready(function() {});
            </script>
        @endonce
    @endpush
@endsection
