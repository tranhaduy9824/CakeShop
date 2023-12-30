<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/user-mng.css">
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

        $bill = new Bill();
        $users = new Users();

        if (isset($_COOKIE["role"])) {
            $role = $_COOKIE["role"];
        }

        if (isset($_COOKIE["userid"])) {
            $userid = $_COOKIE["userid"];
        }

        
        if (isset($_GET["search"])) {
            $search = $_GET["search"];
        }

        if (isset($_COOKIE["role"]) && $_COOKIE["role"]=="admin") {
            if (isset($_GET["delete"]) && $_GET["confirm"] == true) {
                $userid = $_GET["delete"];
    
                $listBill = $bill->getBillByUserId($userid);
                if (count($listBill)==0) {
                    $users->deleteUserByUserId($userid);
                } else {
                    echo '<script>alert("Người dùng đang có đơn hàng, không thể xóa!"); window.location.href="user-mng.php";</script>';
                }
            }
    
            if (isset($_GET["block"])) {
                $userid=$_GET["block"];
                $status=1;
    
                $users->handleBlock($status, $userid);

                header("Location: user-mng.php");
                exit();
            }
            
            if (isset($_GET["openblock"])) {
                $userid=$_GET["openblock"];
                $status=0;
    
                $users->handleBlock($status, $userid);

                header("Location: user-mng.php");
                exit();
            }
    
            if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["edituser"])) {
                $userid=$_POST["userid"];
                $username=$_POST["username"];
                $fullname=$_POST["fullname"];
                $password=$_POST["password"];
                $email=$_POST["email"];
                $phone=$_POST["phone"];
    
                $users->changeInfoUser($userid, $username, $fullname, $password, $email, $phone);
    
                header("Location: user-mng.php");
                exit();
            }
        }
    ?>

    <?php if (isset($_COOKIE["adminid"])) {?>
    <!-- Main -->
    <div id="main">
        <!-- Header -->
        <?php include 'partials/header.php'?>

        <?php 
            if (isset($_POST["countshow"])) {
                $limit=$_POST["countshow"];
            } else {
                $limit=10;
            }
            $page=isset($_GET["page"]) ? $_GET["page"] : 1;
            $start=($page-1)*$limit;

            $listUser = $users->searchUser($start, $limit);
        ?>
        <!-- Content -->
        <div id="content">
            <div class="row1">
                <p><b>Danh sách người dùng</b></p>
                <div id="clock"></div>
            </div>
            <div class="row2">
                <div class="btn-element">
                    <div class="btn btn-delete-all">
                        <i class="fas fa-trash"></i>
                        <a href="">Xóa tất cả</a>
                    </div>
                </div>
                <div class="show-search">
                    <div class="show">
                        <form action="user-mng.php" method="post">
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
                        <form action="user-mng.php" method="get">
                            <label for="">
                                Tìm kiếm: 
                                <input type="text" name="search" placeholder="Tên người dùng" onchange="this.form.submit()" value="<?php if (!empty($_GET["search"])) echo $search; else echo ""; ?>">
                            </label>
                        </form>
                    </div>
                </div>
                <div class="show-info">
                    <table>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all" onclick="selectAllCheckboxes()">
                            </th>
                            <th>ID người dùng</th>
                            <th>Tên đăng nhập</th>
                            <th>Họ và tên</th>
                            <th>Mật khẩu</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Ảnh đại diện</th>
                            <th>Chức năng</th>
                        </tr>
                        <?php 
                            foreach ($listUser as $row) {
                                echo '<tr>
                                <td>
                                    <input type="checkbox" class="checkbox-item">
                                </td>
                                <td>' .$row["userid"]. '</td>
                                <td>' .$row["username"]. '</td>
                                <td>' .$row["fullname"]. '</td>
                                <td>' .$row["password"]. '</td>
                                <td>' .$row["email"]. '</td>
                                <td>' .$row["phone"]. '</td>';
                                if ($row["status"]==0) {
                                    echo '<td style="color: #31a24c;">Hoạt động</td>';
                                } else {
                                    echo '<td style="color: red">Đã chặn</td>';
                                }
                                if (empty($row["avt"])) {
                                    echo '<td></td>';
                                } else {
                                    $avt=$row["avt"];
                                    $infoavt = getimagesizefromstring($avt);
                                    if (!empty($infoavt['mime'])) {
                                        $mime = $infoavt['mime'];
                                    } else $mime="";
                                    $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                    echo '<td>    
                                        <img src="' .$avtsrc. '" alt="">
                                    </td>';
                                }
                                echo '<td><div>
                                    <a href="user-mng.php?delete=' .$row["userid"]. '&confirm=true" onclick="return confirmDelete()"><i class="fas fa-trash"></i></a>
                                    <a href="user-mng.php?userid=' .$row["userid"]. '"><i class="fas fa-edit"></i></a>';
                                if ($row["status"]==0) {
                                    echo '<a href="user-mng.php?block=' .$row["userid"]. '"><i class="fas fa-toggle-on btn-block"></i></i></a>';
                                } else {
                                    echo '<a href="user-mng.php?openblock=' .$row["userid"]. '"><i class="fas fa-toggle-off btn-open-block"></i></a>';
                                }
                                echo '</div></td>
                                </tr>';
                            }
                        ?>
                    </table>
                </div>
                <?php 
                    $listUser = $users->getUsers();
                ?>
                <div class="change-page">
                    <p>Hiện <?php echo $start+1;?> đến <?php if (count($listUser)>$start+$limit) echo $start+$limit; else echo count($listUser);?> của <?php echo count($listUser);?> danh mục</p>
                    <div class="btn-change">
                        <?php 
                            for ($i=1;$i<=ceil((count($listUser)/$limit));$i++) {
                                echo '<div><a class="edit-link" href="user-mng.php?page=' .$i. '">' .$i. '</a></div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
        if (isset($_GET["userid"])) {
            $userid=$_GET["userid"];
            $listUser = $users->getUserById($userid);
        }
        if ($listUser) {
    ?>

    <?php if ($role === "admin") {?>
    <!-- Model edit product -->
    <div id="model-edit">
        <form action="user-mng.php" method="post">
            <div class="box-edit">
                <h5>Chỉnh sửa thông tin người dùng</h5>
                <div class="row-edit">
                    <div>
                        <label for="">Mã người dùng</label>
                        <input type="hidden" name="userid" value="<?php echo $listUser["userid"];?>">
                        <input type="number" disabled name="userid" value="<?php echo $listUser["userid"];?>">
                    </div>
                    <div>
                        <label for="">Tên đăng nhập</label>
                        <input type="text" name="username" required value="<?php echo $listUser["username"]?>">
                    </div>
                    <div>
                        <label for="">Họ và tên</label>
                        <input type="text" name="fullname" required value="<?php echo $listUser["fullname"]?>">
                    </div>
                    <div>
                        <label for="">Mật khẩu</label>
                        <input type="text" name="password" required value="<?php echo $listUser["password"]?>">
                    </div>
                    <div>
                        <label for="">Email</label>
                        <input type="email" name="email" required value="<?php echo $listUser["email"]?>">
                    </div>
                    <div>
                        <label for="">Số điện thoại</label>
                        <input type="phone" name="phone" required value="<?php echo $listUser["phone"]?>">
                    </div>
                </div>
                <div class="save-cancel">   
                    <button type="submit" name="edituser">Lưu lại</button>
                    <a href="/ĐACS2_NEW/admin/page/user-mng.php">Hủy bỏ</a>
                </div>
            </div>
        </form>
    </div>
    <?php 
            }
        } 
    ?>

    <?php } else {
            header("Location: /ĐACS2_NEW/admin/index.php");
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
            return confirm("Bạn có muốn xóa người dùng này không");
        }

        var urlParams = new URLSearchParams(window.location.search);
        var idsp = urlParams.get('userid');

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