<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?= htmlspecialchars($title) ?></title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: <?= json_encode($icon) ?>,
            title: <?= json_encode($title) ?>,
            text: <?= json_encode($text) ?>,
            confirmButtonColor: '#111'
        }).then(() => {
            window.location = <?= json_encode($redirect) ?>;
        });
    </script>
</body>
</html>
