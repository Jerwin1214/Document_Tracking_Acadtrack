@props(['message', 'icon'])
<script>
    Swal.fire({
        icon: "{{$icon}}",
        title: "{{$message}}",
        showConfirmButton: false,
        timer: 1500
    });
</script>