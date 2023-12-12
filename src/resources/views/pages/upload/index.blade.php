@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

<form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="flex flex-col items-center w-full space-y-2 md:flex-row md:space-x-8 md:space-y-0">
        <div class="text-center min-w-150 md:text-start">
            <label for="image_proof_rev" class="text-left text-gray-600 whitespace-nowrap">Attachment
                File<sup><i class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
        </div>
        <div class="flex flex-col w-full">
            <div class="flex items-center justify-center md:justify-start">
                <input name="image_proof_rev[]" type="file" id="image_proof_rev"
                    class="text-sm text-slate-500 file:rounded-2xl rounded-2xl file:text-sm file:font-semibold file:py-1 file:bg-yellow-0 file:text-white hover:file:bg-yellow-2 "
                    multiple />
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
    <button type="submit"
        class="inline-block px-6 py-2.5 mt-5 bg-green-0 text-white font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out">
        Submit
    </button>
</form>
