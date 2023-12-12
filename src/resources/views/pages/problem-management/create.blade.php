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
            <span class="text-lg font-semibold text-center text-white">Form Create Problem Request</span>
        </div>

        <form action="{{ route('problem-managements.store') }}" method="POST" enctype="multipart/form-data" id="form">
            @csrf
            <input name="creator_id" type="number" value="{{ auth()->user()->id }}" hidden>
            <div class="p-4 mt-2 space-y-2 bg-white border-2 shadow-md border-tosca-0 rounded-xl">
                <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="nomor_ticket" class="text-left text-gray-600 whitespace-nowrap">Trouble Ticket
                            ID</label>
                    </div>
                    <div class="flex flex-col w-full">
                        <input name="nomor_ticket" type="text" id="nomor_ticket"
                            class="max-w-sm p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                      focus:outline-none focus:ring-1 focus:ring-yellow-0
                      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                      invalid:border-pink-500 invalid:text-pink-600
                      focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                            value="{{ $ticket->nomor_ticket ?? '' }}">
                        @error('nomor_ticket')
                            <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="problem_type" class="text-left text-gray-600 whitespace-nowrap">Tipe Request<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="relative flex flex-col justify-center max-w-sm md:justify-end min-w-150">
                        <select name="problem_type" type="text" class="w-full flex !justify-end mr-0" id="problem_type">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Reactive" @if (old('problem_type') == 'Reactive') {{ 'selected' }} @endif>Reaktive
                            </option>
                            <option value="Proactive" @if (old('problem_type') == 'Proactive') {{ 'selected' }} @endif>Proaktive
                            </option>
                        </select>
                        @error('problem_type')
                            <span class="text-sm text-pink-600 inline-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="content" class="text-left text-gray-600 whitespace-nowrap">Keterangan<sup><i
                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                    </div>
                    <div class="w-full">
                        <textarea name="content" id="content"
                            class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:ring-1 focus:ring-yellow-0
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none text-gray-600"
                            rows="3">{{ old('content') }}</textarea>
                        @error('content')
                            <span class="inline-flex text-sm text-pink-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
                    <div class="text-center min-w-150 md:text-start">
                        <label for="image_proof" class="text-left text-gray-600 whitespace-nowrap">Attachment File</label>
                    </div>
                    <div class="flex flex-col w-full">
                        <div class="flex items-center justify-center md:justify-start">
                            {{-- <input type="file"
                            class="" /> --}}
                            <input name="image_proof[]" type="file" id="image_proof"
                                class="text-sm text-slate-500 file:rounded-2xl rounded-2xl file:text-sm file:font-semibold file:py-1 file:bg-yellow-0 file:text-white hover:file:bg-yellow-2 "
                                multiple />
                            @error('image_proof')
                                <span class="inline-flex my-auto text-sm text-center text-pink-600">{{ $message }}</span>
                            @enderror
                            @if ($errors->has('image_proof.*'))
                                @foreach ($errors->get('image_proof.*') as $key => $error)
                                    <span class="inline-flex my-auto text-sm text-center text-pink-600">{{ $errors->first($key) }}</span>
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
            <div class="flex justify-between">
                <a href="{{ route('problem-managements.index') }}"
                    class="inline-block px-6 py-2.5 mt-5 bg-red-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-red-1 hover:shadow-lg focus:bg-red-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-2 active:shadow-lg transition duration-150 ease-in-out">Back</a>
                <button type="submit"
                    class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">Submit</button>
            </div>
        </form>
    </section>
@endsection
@push('scripts')
    @once
        <script type="text/javascript">
            $(document).ready(function() {})
        </script>
    @endonce
@endpush
