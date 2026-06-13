<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
<script>
    if (localStorage.getItem('laundry_onboarding_done') === 'true') {
        window.location.href = '{{ route("login") }}';
    } else {
        window.location.href = '{{ route("onboarding") }}';
    }
</script>
</body>
</html>