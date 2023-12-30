<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/product-mng.css">
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

        $sanphams =new Sanphams();

        if (isset($_COOKIE["role"])) {
            $role = $_COOKIE["role"];
        }

        if (isset($_GET["search"]) && !empty($_GET["search"])) {
            $search = $_GET['search'];
        }

        if (isset($_COOKIE["role"]) && $_COOKIE["role"]=="admin") {
            if (isset($_GET['delete'])) {
                $idsp = $_GET['delete'];
    
                if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
                    $sanphams->deleteSanpham($idsp);
    
                    header("Location: product-mng.php");
                    exit();
                }
            }
    
            if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["editsp"])) {
                $idsp=$_POST["idsp"];
                $namesp=$_POST["namesp"];
                $price=$_POST["price"];
                $ribbon=$_POST["ribbon"];
                $type=$_POST["type"];
                $dessp=$_POST["dessp"];
    
                $sanphams->changeSanpham($idsp, $namesp, $dessp, $price, $ribbon, $type);
    
                header("Location: product-mng.php");
                exit();
            }
        }
    ?>

    <?php if (isset($_COOKIE["adminid"])) {?>
    <!-- Main -->
    <div id="main">
        <!-- Header -->
        <?php include 'partials/header.php';?>

        <?php 
            if (isset($_POST["countshow"])) {
                $limit = $_POST["countshow"];
            } else {
                $limit = 10;
            }
            if (isset($_GET["limit"])) {
                $limit = $_GET["limit"];
            }
            $page = isset($_GET["page"]) ? $_GET["page"] : 1;
            $start = ($page - 1) * $limit;
            
            $listSanpham = $sanphams->searchSanphams($start, $limit);
        ?>
        <!-- Content -->
        <div id="content">
            <div class="row1">
                <p><b>Danh sách sản phẩm</b></p>
                <div id="clock"></div>
            </div>
            <div class="row2">
                <div class="btn-element">
                    <div class="btn btn-add">
                        <i class="fas fa-plus"></i>
                        <a href="<?php if ($role === "admin") echo "/CuoiKiWeb/admin/page/add-product.php";?>">Tạo mới sản phẩm</a>
                    </div>
                    <div class="btn btn-delete-all">
                        <i class="fas fa-trash"></i>
                        <a href="">Xóa tất cả</a>
                    </div>
                </div>
                <div class="show-search">
                    <div class="show">
                        <form action="product-mng.php" method="post">
                            <label for="">
                            Hiện
                            <select name="countshow" onchange="this.form.submit()">
                                <option value="10" <?php if ($limit==10) echo "selected";?>>10</option>
                                <option value="25" <?php if ($limit==25) echo "selected";?>>25</option>
                                <option value="50" <?php if ($limit==50) echo "selected";?>>50</option>
                                <option value="100" <?php if ($limit==100) echo "selected";?>>100</option>
                            </select>
                            danh mục
                            </label>
                        </form>
                    </div>
                    <div class="search">
                        <form action="product-mng.php" method="get">
                            <label for="">
                                Tìm kiếm: 
                                <input type="text" name="search" placeholder="Tên sản phẩm" onchange="this.form.submit()" value="<?php if (!empty($_GET["search"])) echo $search; else echo "";?>">
                            </label>
                        </form>
                    </div>
                </div>
                <div class="show-info">
                    <table>
                        <tr>
                            <th>
                                <input type="checkbox"id="select-all" onclick="selectAllCheckboxes()">
                            </th>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Ảnh</th>
                            <th>Mô tả</th>
                            <th>Loại</th>
                            <th>Giá tiền</th>
                            <th>Danh mục</th>
                            <th>Chức năng</th>
                        </tr>
                        <?php 
                            foreach ($listSanpham as $row) {
                                $image=$row["imagesp"];
                                $imageinfo=getimagesizefromstring($image);
                                $mime=$imageinfo['mime'];
                                $imagesrc="data:" .$mime. ";base64," .base64_encode($image);
                                echo '<tr>
                                <td>
                                    <input type="checkbox" class="checkbox-item">
                                </td>
                                <td>' .$row["idsp"]. '</td>
                                <td>' .$row["namesp"]. '</td>
                                <td>    
                                    <img src="' .$imagesrc. '" alt="">
                                </td>
                                <td>' .$row["dessp"]. '</td>
                                <td>' .$row["ribbon"]. '</td>
                                <td>' .$row["price"]. '</td>
                                <td>' .$row["type"]. '</td>
                                <td><div>
                                    <a href="product-mng.php?delete=' .$row["idsp"]. '&confirm=true" onclick="return confirmDelete()"><i class="fas fa-trash"></i></a>
                                    <a class="btn-edit" href="product-mng.php?idsp=' .$row["idsp"]. '"><i class="fas fa-edit"></i></a>
                                </div></td>
                                </tr>';
                            }
                        ?>
                    </table>
                </div>
                <div class="change-page">
                    <?php 
                        $sql = "SELECT * FROM sanphams";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <p>Hiện <?php echo $start+1;?> đến <?php if ($start+$limit<count($result))echo $start+$limit; else echo count($result);?> của <?php echo count($result);?> danh mục</p>
                    <div class="btn-change">
                        <?php 
                            for ($i = 1; $i <= ceil(count($result)/$limit); $i++) {
                                echo '<div><a class="edit-link" href="product-mng.php?page=' . $i . '&limit=' .$limit. '">' .$i. '</a></div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
        if (isset($_GET["idsp"])) {
            $idsp=$_GET["idsp"];
            $listSanpham = $sanphams->getSanphamById($idsp);
        }
        if ($listSanpham) {
    ?>
    <?php if ($role === "admin") {?>
    <!-- Model edit product -->
    <div id="model-edit">
        <form action="product-mng.php" method="post" enctype="multipart/form-data">
            <div class="box-edit">
                <h5>Chỉnh sửa thông tin sản phẩm cơ bản</h5>
                <div class="row-edit">
                    <div>
                        <label for="">Mã sản phẩm</label>
                        <input type="hidden" name="idsp" value="<?php echo $listSanpham["idsp"];?>">
                        <input type="number" disabled name="idsp" value="<?php echo $listSanpham["idsp"];?>">
                    </div>
                    <div>
                        <label for="">Tên sản phẩm</label>
                        <input type="text" name="namesp" required value="<?php echo $listSanpham["namesp"]?>">
                    </div>
                    <div>
                        <label for="">Giá bán (VND)</label>
                        <input type="text" name="price" required value="<?php echo $listSanpham["price"]?>">
                    </div>
                    <div>
                        <label for="">Loại</label>
                        <select name="ribbon" id="" required>
                            <option value="hot" <?php if ($listSanpham["ribbon"]=="hot") echo 'selected';?>>Hot</option>
                            <option value="new" <?php if ($listSanpham["ribbon"]=="new") echo 'selected';?>>New</option>
                            <option value="" <?php if ($listSanpham["ribbon"]=="") echo 'selected';?>>Không</option>
                        </select>
                    </div>
                    <div>
                        <label for="">Danh mục</label>
                        <select name="type" id="" required>
                            <option value="bánh mì" <?php if ($listSanpham["type"]=="bánh mì") echo 'selected';?>>Bánh mì</option>
                            <option value="bánh sinh nhật" <?php if ($listSanpham["type"]=="bánh sinh nhật") echo 'selected';?>>Bánh sinh nhật</option>
                            <option value="bánh quy" <?php if ($listSanpham["type"]=="bánh quy") echo 'selected';?>>Bánh quy</option>
                            <option value="bánh tráng miệng" <?php if ($listSanpham["type"]=="bánh tráng miệng") echo 'selected';?>>Bánh tráng miệng</option>
                            <option value="cà rem" <?php if ($listSanpham["type"]=="cà rem") echo 'selected';?>>Cà rem</option>
                            <option value="bánh tươi" <?php if ($listSanpham["type"]=="bánh tươi") echo 'selected';?>>Bánh tươi</option>
                            <option value="sản phẩm đặc trưng" <?php if ($listSanpham["type"]=="sản phẩm đặc trưng") echo 'selected';?>>Sản phẩm đặc trưng</option>
                            <option value="phụ kiện sinh nhật" <?php if ($listSanpham["type"]=="phụ kiện sinh nhật") echo 'selected';?>>Phụ kiện sinh nhật</option>
                        </select>
                    </div>
                    <div>
                        <label for="">Mô tả sản phẩm</label>
                        <textarea name="dessp" id="" cols="40" rows="4" value="<?php if (isset($listSanpham["desp"])) echo $listSanpham["desp"]; else echo '';?>"></textarea>
                    </div>
                    <div class="add-imagesp">
                        <?php 
                            $imagesp=$listSanpham["imagesp"];
                            $imageinfo=getimagesizefromstring($imagesp);
                            $mime=$imageinfo['mime'];
                            $imagespsrc="data:" .$mime. ";base64," .base64_encode($imagesp);
                        ?>
                        <img src="<?php echo $imagespsrc;?>" alt="">
                        <br>
                        <label for="">Ảnh sản phẩm</label>
                        <input type="file" name="imagesp">
                    </div>
                </div>
                <div class="save-cancel">   
                    <button type="submit" name="editsp">Lưu lại</button>
                    <a href="/CuoiKiWeb/admin/page/product-mng.php">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
    <?php }} ?>

    <?php } else {
            header("Location: /CuoiKiWeb/admin/index.php");
            exit();
        }
    ?>
    
    <script>
        // Clock
        function updateClock() {
            var now = new Date();
            var dayOfWeek = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
            var day = now.getDate();
            var month = now.getMonth() + 1; // Tháng bắt đầu từ 0, nên cộng 1 để hiển thị đúng
            var year = now.getFullYear();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
        
            var timeString = dayOfWeek[now.getDay()] + ', ' +
                            day.toString().padStart(2, '0') + '/' +
                            month.toString().padStart(2, '0') + '/' +
                            year.toString() + ' - ' +
                            hours.toString().padStart(2, '0') + ' giờ ' +
                            minutes.toString().padStart(2, '0') + ' phút ' +
                            seconds.toString().padStart(2, '0') + ' giây';
        
            document.getElementById('clock').textContent = timeString;
        }
        setInterval(updateClock, 1000);

        function confirmDelete() {
            return confirm("Bạn có muốn xóa sản phẩm này không?");
        }

        var urlParams = new URLSearchParams(window.location.search);
        var idsp = urlParams.get('idsp');

        if (idsp) {
            var modelEdit = document.getElementById('model-edit');
            modelEdit.style.display = 'flex';
        }

        function selectAllCheckboxes() {
            var selectAllCheckbox = document.getElementById('select-all');
            var checkboxes = document.getElementsByClassName('checkbox-item');

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = selectAllCheckbox.checked;
            }
        }
    </script>
</body>
</html>