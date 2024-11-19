
            @if (session('success'))
            Swal.fire({
                position: 'top-center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2500
            });
            @endif

            @if (session('error'))
            Swal.fire({
                position: 'top-center',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 2500
            });
            @endif

            @if (session('warning'))
            Swal.fire({
                position: 'top-center',
                icon: 'warning',
                title: '{{ session('warning') }}',
                showConfirmButton: false,
                timer: 2500
            });
            @endif
