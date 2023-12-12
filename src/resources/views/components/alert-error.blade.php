@push('scripts')
    @once
        <script type="text/javascript">
            let message = '{{ $message }}';
            Swal.fire({
                icon: 'error',
                title: `${message}`,
                confirmButtonColor: '#E05D5D',
            })
        </script>
    @endonce
@endpush
