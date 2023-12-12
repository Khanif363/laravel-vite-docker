@push('scripts')
    @once
        <script type="text/javascript">
            let message = '{{ $message }}';
            Swal.fire({
                icon: 'success',
                title: `${message}`,
                confirmButtonColor: '#00A19D',
            })
        </script>
    @endonce
@endpush
