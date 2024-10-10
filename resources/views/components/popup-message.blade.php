@props(['type' => 'info', 'message'=>''])


@if ($type == 'info')
<script>
    Swal.fire({
        icon: "info",
        title: "{{ $message }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>

@elseif ($type == 'success')
<script>
    Swal.fire({
        icon: "success",
        title: "{{ $message }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>
@elseif ($type == 'warning')
<script>
    Swal.fire({
        icon: "warning",
        title: "{{ $message }}",
        showConfirmButton: true,
    });
</script>
@elseif ($type == 'error')
<script>
    Swal.fire({
        icon: "error",
        title: "{{ $message }}",
        showConfirmButton: true,
    });
</script>

@endif