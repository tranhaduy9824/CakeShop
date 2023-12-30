<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/add-product.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head>
<body>
    <?php
        require_once '../classes/connectMySql.php';
        require_once '../classes/admins.php';
        require_once '../classes/users.php';
        require_once '../classes/bill.php';
        require_once '../classes/messages.php';
        require_once '../classes/sanphams.php';

        $sanphams = new Sanphams();

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["addsp"])) {
            $idsp=$_POST["idsp"];
            $namesp=$_POST["namesp"];
            $price=number_format($_POST["price"]) . ' VND';
            $ribbon=$_POST["ribbon"];
            $type=$_POST["type"];
            $dessp=$_POST["dessp"];
            $imagesp=file_get_contents($_FILES['imagesp']['tmp_name']);

            $sanphams->setSanpham($idsp, $imagesp, $namesp, $dessp, $price, $ribbon, $type);

            echo '<script>
            alert("Thêm sản phẩm thành công");
            window.location.href="add-product.php";
            </script>';
        }
    ?>

    <?php if (isset($_COOKIE["adminid"]) && $_COOKIE["role"] === "admin") {?>
    <!-- Main -->
    <div id="main">
        <!-- Header -->
        <?php include 'partials/header.php'?>

        <!-- Content -->
        <div id="content">
            <div class="row1">
                <p><b>Danh sách sản phẩm / Thêm sản phẩm</b></p>
            </div>
            <div class="row2">
                <h3>Tạo mới sản phẩm</h3>
                <form action="add-product.php" method="post" enctype="multipart/form-data">
                    <div class="row2-top">
                        <div>
                            <label for="">Mã sản phẩm</label>
                            <input type="number" name="idsp">
                        </div>
                        <div>
                            <label for="">Tên sản phẩm <span>(*)</span></label>
                            <input type="text" name="namesp" required>
                        </div>
                        <div>
                            <label for="">Giá bán (VND) <span>(*)</span></label>
                            <input type="text" name="price" required>
                        </div>
                        <div>
                            <label for="">Loại <span>(*)</span></label>
                            <select name="ribbon" id="" required>
                                <option value="" disabled selected style="display: none;">-- Chọn loại --</option>
                                <option value="hot">Hot</option>
                                <option value="new">New</option>
                                <option value="">Không</option>
                            </select>
                        </div>
                    </div>
                    <div class="row2-bottom">
                        <div>
                            <label for="">Danh mục <span>(*)</span></label>
                            <select name="type" id="" required>
                                <option value="" disabled selected style="display: none;">-- Chọn danh mục --</option>
                                <option value="bánh mì">Bánh mì</option>
                                <option value="bánh sinh nhật">Bánh sinh nhật</option>
                                <option value="bánh quy">Bánh quy</option>
                                <option value="bánh tráng miệng">Bánh tráng miệng</option>
                                <option value="cà rem">Cà rem</option>
                                <option value="bánh tươi">Bánh tươi</option>
                                <option value="sản phẩm đặc trưng">Sản phẩm đặc trưng</option>
                                <option value="phụ kiện sinh nhật">Phụ kiện sinh nhật</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Mô tả sản phẩm</label>
                            <textarea name="dessp" id="" cols="50" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="add-imagesp">
                        <label for="">Ảnh sản phẩm <span>(*)</span></label>
                        <input type="file" name="imagesp" required>
                    </div>
                    <div class="save-cancel">
                        <button type="submit" name="addsp">Lưu lại</button>
                        <a href="/CuoiKiWeb/admin/page/product-mng.php">Hủy bỏ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php 
        } else {
            header("Location: /CuoiKiWeb/admin/index.php");
            exit();
        }
    ?>

</body>
</html>