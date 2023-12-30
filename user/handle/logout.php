<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        setcookie('userid', '', time() - 3600, '/');
        setcookie('username', '', time() - 3600, '/');
        setcookie('fullname', '', time() - 3600, '/');
        setcookie('email', '', time() - 3600, '/');
        setcookie('password', '', time() - 3600, '/');
        setcookie('phone', '', time() - 3600, '/');
        setcookie('status', '', time() - 3600, '/');
        header("Location: /CuoiKiWeb/user/index.php");
        exit();
    ?>
</body>
</html>