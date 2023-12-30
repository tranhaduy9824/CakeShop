<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/setting.css">
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

        $admins = new Admins();

        if ($_COOKIE["adminid"]) {
            $adminid=$_COOKIE["adminid"];
            $role=$_COOKIE["role"];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['avt'])) {
            $avt=file_get_contents($_FILES['avt']['tmp_name']);

            $admins->changeAvt($adminid, $avt);

            setcookie('avt', $avt, time() + 86400, '/');

            header("Location: setting.php");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["changeinfo"])) {
            $fullname = $_POST["fullname"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];

            $admins->changeInfo($fullname, $email, $phone, $adminid);

            header("Location: setting.php");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["changepass"])) {
            $password = $_POST["password"];
            $confirmPassword = $_POST["confirmPassword"];

            if ($password === $confirmPassword) {
                $admins->changePassWord($password, $adminid);
                                
                echo '<script>alert("Đổi mật khẩu thành công");window.location.href="/CuoiKiWeb/admin/page/setting.php";</script>';
            }
        }

        if (isset($_COOKIE["role"]) && $_COOKIE["role"] === "admin") {
            if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["editStaff"])) {
                $adminid = $_POST["adminid"];
                $fullname = $_POST["fullname"];
                $adminname = $_POST["adminname"];
                $password = $_POST["password"];
                $email = $_POST["email"];
                $phone = $_POST["phone"];
    
                $admins->changeInfoStaff($adminid, $fullname, $adminname, $password, $email, $phone);
    
                header("Location: setting.php");
                exit();
            }
    
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addStaff"])) {
                $fullname = $_POST["fullname"];
                $adminname = $_POST["adminname"];
                $password = $_POST["password"];
                $email = $_POST["email"];
                $phone = $_POST["phone"];
                $role = "staff";
    
                $admins->setStaff($fullname, $adminname, $password, $email, $phone, $role);
    
                echo '<script>alert("Thêm nhân viên thành công");window.location.href="/CuoiKiWeb/admin/page/setting.php";</script>';
            }
    
            if (isset($_GET['delete'])) {
                $adminid = $_GET['delete'];
    
                if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
                    $admins->deleteStaff($adminid);
    
                    echo '<script>alert("Xóa nhân viên thành công");window.location.href="/CuoiKiWeb/admin/page/setting.php";</script>';
                }
            }
        }
    ?>  

    <!-- Main -->
    <div id="main">
        <!-- Header -->
        <?php include 'partials/header.php';?>

        <!-- Content -->
        <div id="content">
            <?php 
                $listAdmin = $admins->getAdminById($adminid);
            ?>
            <div class="row1">
                <h1>Thông tin cá nhân</h1>
                <div class="box-avt-info">
                    <div class="box-avt">
                        <?php 
                            if (empty($listAdmin["avt"])) {
                                echo '<img src="/CuoiKiWeb/admin/img/avtmacdinh.jpg" alt="">';
                            }
                            else {
                                $avt = $listAdmin["avt"];
                                $infoavt = getimagesizefromstring($avt);
                                if (!empty($infoavt['mime'])) {
                                    $mime = $infoavt['mime'];
                                } else $mime="";
                                $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                echo '<img src="' .$avtsrc. '" alt="">';
                            }
                        ?>
                    </div>
                    <div class="box-info">
                        <table>
                            <tr>
                                <th>Họ và tên:</th>
                                <td><?php echo $listAdmin["fullname"];?></td>
                            </tr>
                            <tr>
                                <th>Tên đăng nhập:</th>
                                <td><?php echo $listAdmin["adminname"];?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $listAdmin["email"];?></td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td><?php echo $listAdmin["phone"];?></td>
                            </tr>
                            <tr>
                                <th>Vai trò:</th>
                                <td><?php echo $listAdmin["role"];?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box-change">  
                    <div class="btn-change change-avt">
                        <p>Thay đổi ảnh đại diện</p>
                        <form action="setting.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="avt" id="avt" onchange="this.form.submit()" required hidden>
                            <label name="update-avt" for="avt"><i class="fas fa-images"></i> Chọn tệp</label>
                        </form>   
                    </div>  
                    <div class="btn-change change-info"><i class="fas fa-edit"></i> Chỉnh sửa thông tin</div>
                    <div class="btn-change change-pass"><i class="fas fa-lock"></i> Đổi mật khẩu</div>
                </div>
            </div>

            <?php 
                $listAdmin = $admins->getStaffs();
            ?>
            <?php if ($role === "admin") {?>
            <div class="row2">
                <h2>Thông tin nhân viên</h2>
                <div class="btn-element">
                    <div class="btn btn-add add-staff">
                        <i class="fas fa-plus"></i> Thêm nhân viên
                    </div>
                    <div class="btn btn-delete-all">
                        <i class="fas fa-trash"></i>
                        <a href="">Xóa tất cả</a>
                    </div>
                </div>
                <div class="show-info">
                    <table>
                        <tr>
                            <th>
                                <input type="checkbox"id="select-all" onclick="selectAllCheckboxes()">
                            </th>
                            <th>ID</th>
                            <th>Họ và tên</th>
                            <th>Ảnh</th>
                            <th>Tên đăng nhập</th>
                            <th>Mật khẩu</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Chức năng</th>
                        </tr>
                        <?php 
                            foreach ($listAdmin as $row) {
                                echo '<tr>
                                <td>
                                    <input type="checkbox" class="checkbox-item">
                                </td>
                                <td>' .$row["adminid"]. '</td>
                                <td>' .$row["fullname"]. '</td>';
                                if (empty($listAdmin["avt"])) {
                                    echo '<td><img src="/CuoiKiWeb/admin/img/avtmacdinh.jpg" alt=""></td>';
                                }
                                else {
                                    $avt = $listAdmin["avt"];
                                    $infoavt = getimagesizefromstring($avt);
                                    if (!empty($infoavt['mime'])) {
                                        $mime = $infoavt['mime'];
                                    } else $mime="";
                                    $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                    echo '<td><img src="' .$avtsrc. '" alt=""></td>';
                                }
                                echo '<td>' .$row["adminname"]. '</td>
                                <td>' .$row["password"]. '</td>
                                <td>' .$row["email"]. '</td>
                                <td>' .$row["phone"]. '</td>
                                <td>' .$row["role"]. '</td>
                                <td><div>
                                    <a href="setting.php?delete=' .$row["adminid"]. '&confirm=true" onclick="return confirmDelete()"><i class="fas fa-trash"></i></a>
                                    <a class="btn-edit-staff" href="setting.php?adminid=' .$row["adminid"]. '"><i class="fas fa-edit"></i></a>
                                </div></td>
                                </tr>';
                            }
                        ?>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <?php 
        $listAdmin = $admins->getAdminById($adminid);
        if ($listAdmin) {
    ?>
    <!-- Model change info -->
    <div id="model-change-info">
        <form action="setting.php" method="post" enctype="multipart/form-data">
            <div class="box-change">
                <h5>Chỉnh sửa thông tin cá nhân</h5>
                <div class="row-change">
                    <div>
                        <label for="">ID</label>
                        <input type="hidden" name="adminid" value="<?php echo $listAdmin["adminid"];?>">
                        <input type="number" disabled name="adminid" value="<?php echo $listAdmin["adminid"];?>">
                    </div>
                    <div>
                        <label for="">Họ và tên</label>
                        <input type="text" name="fullname" required value="<?php echo $listAdmin["fullname"]?>">
                    </div>
                    <div>
                        <label for="">Email</label>
                        <input type="email" name="email" required value="<?php echo $listAdmin["email"]?>">
                    </div>
                    <div>
                        <label for="">Số điện thoại</label>
                        <input type="phone" name="phone" required value="<?php echo $listAdmin["phone"]?>">
                    </div>
                </div>
                <div class="save-cancel">   
                    <button type="submit" name="changeinfo">Lưu lại</button>
                    <a href="/CuoiKiWeb/admin/page/setting.php">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Model change pass -->
    <div id="model-change-pass">
        <form action="setting.php" method="post" enctype="multipart/form-data">
            <div class="box-change">
                <h5>Đổi mật khẩu</h5>
                <div class="row-change">
                    <div>
                        <label for="">Mật khẩu mới</label>
                        <input type="password" name="password" required>
                    </div>
                    <div>
                        <label for="">Nhập lại mật khẩu mới</label>
                        <input type="password" name="confirmPassword" required>
                    </div>
                </div>
                <div class="save-cancel">   
                    <button type="submit" name="changepass">Lưu lại</button>
                    <a href="/CuoiKiWeb/admin/page/setting.php">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
    <?php } ?>

    <?php 
        if (isset($_GET["adminid"])) {
            $adminid=$_GET["adminid"];
        }
        $listAdmin = $admins->getAdminById($adminid);
        if ($listAdmin) {
    ?>
    <?php if ($role === "admin") {?>
    <!-- Model edit staff -->
    <div id="model-change-staff">
        <form action="setting.php" method="post" enctype="multipart/form-data">
            <div class="box-change">
                <h5>Chỉnh sửa thông tin nhân viên</h5>
                <div class="row-edit">
                    <div>
                        <label for="">Mã nhân viên</label>
                        <input type="hidden" name="adminid" value="<?php echo $listAdmin["adminid"];?>">
                        <input type="number" disabled name="adminid" value="<?php echo $listAdmin["adminid"];?>">
                    </div>
                    <div>
                        <label for="">Họ và tên</label>
                        <input type="text" name="fullname" required value="<?php echo $listAdmin["fullname"]?>">
                    </div>
                    <div>
                        <label for="">Tên đăng nhập</label>
                        <input type="text" name="adminname" required value="<?php echo $listAdmin["adminname"]?>">
                    </div>
                    <div>
                        <label for="">Mật khẩu</label>
                        <input type="text" name="password" required value="<?php echo $listAdmin["password"]?>">
                    </div>
                    <div>
                        <label for="">Email</label>
                        <input type="email" name="email" required value="<?php echo $listAdmin["email"]?>">
                    </div>
                    <div>
                        <label for="">Số điện thoại</label>
                        <input type="text" name="phone" required value="<?php echo $listAdmin["phone"]?>">
                    </div>
                    <div>
                        <label for="role">Vai trò</label>
                        <select name="role" id="role" required>
                            <option disabled value="admin" <?php if ($listAdmin["role"]=="admin") echo 'selected';?>>Admin</option>
                            <option value="staff" <?php if ($listAdmin["role"]=="staff") echo 'selected';?>>Staff</option>
                        </select>
                    </div>
                </div>
                <div class="save-cancel">   
                    <button type="submit" name="editStaff">Lưu lại</button>
                    <a href="/CuoiKiWeb/admin/page/setting.php">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
    <?php }} ?>

    <?php if ($role === "admin") {?>
    <!-- Model add staff -->
    <div id="model-add-staff">
        <form action="setting.php" method="post" enctype="multipart/form-data">
            <div class="box-change">
                <h5>Thêm nhân viên</h5>
                <div class="row-edit">
                    <div>
                        <label for="">Họ và tên</label>
                        <input type="text" name="fullname" required>
                    </div>
                    <div>
                        <label for="">Tên đăng nhập</label>
                        <input type="text" name="adminname" required>
                    </div>
                    <div>
                        <label for="">Mật khẩu</label>
                        <input type="text" name="password" required>
                    </div>
                    <div>
                        <label for="">Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div>
                        <label for="">Số điện thoại</label>
                        <input type="text" name="phone" required>
                    </div>
                </div>
                <div class="save-cancel">   
                    <button type="submit" name="addStaff">Lưu lại</button>
                    <a href="/CuoiKiWeb/admin/page/setting.php">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
    <?php } ?>

    <script>
        document.querySelector('.change-info').addEventListener('click', function() {
            var modelEdit = document.getElementById('model-change-info');
            modelEdit.style.display = 'flex';
        })

        document.querySelector('.change-pass').addEventListener('click', function() {
            var modelEdit = document.getElementById('model-change-pass');
            modelEdit.style.display = 'flex';
        })

        document.querySelector('.add-staff').addEventListener('click', function() {
            var modelEdit = document.getElementById('model-add-staff');
            modelEdit.style.display = 'flex';
        })

        var urlParams = new URLSearchParams(window.location.search);
        var adminid = urlParams.get('adminid');

        if (adminid) {
            var modelEdit = document.getElementById('model-change-staff');
            modelEdit.style.display = 'flex';
        }

        function confirmDelete() {
            return confirm("Bạn có muốn xóa nhân viên này không?");
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