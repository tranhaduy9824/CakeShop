<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        setcookie('adminid', '', time() - 3600, '/');
        setcookie('adminname', '', time() - 3600, '/');
        setcookie('adminfullname', '', time() - 3600, '/');
        header("Location: /ĐACS2_NEW/admin/index.php");
        exit();
    ?>
</body>
</html>