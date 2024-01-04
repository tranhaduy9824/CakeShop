<?php 
    $admins = new Admins();

    $connect = new ConnectMySql();
    $conn = $connect->getConnection();

    if ($_COOKIE["adminid"]) {
        $adminid=$_COOKIE["adminid"];
    }

    $listAdmin = $admins->getAdminById($adminid);
?>

<div id="logout">
    <div class="box-logout">
        <a href="/CuoiKiWeb/admin/page/partials/logout.php"><i class="fas fa-sign-out-alt"></i></a>
    </div>
</div>
<div id="menu">
    <div class="adminname">
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
        <p style="text-transform: capitalize;"><b><?php echo ($listAdmin["role"]==="admin") ? "Quản lý" : "Nhân viên"?></b></p>
        <p><b><?php echo $listAdmin["fullname"];?></b></p>
        <p>Chúc mừng bạn trở lại</p>
    </div>
    <hr>
    <div class="list-menu">
        <ul>
            <li><i class="fas fa-shopping-cart"></i>POS Bán Hàng</li>
            <li><a href="/CuoiKiWeb/admin/page/index.php"><i class="fas fa-tachometer-alt"></i>Bảng điều khiển</a></li>
            <li><a href="/CuoiKiWeb/admin/page/user-mng.php"><i class="fas fa-users"></i>Quản lý khách hàng</a></li>
            <li><a href="/CuoiKiWeb/admin/page/product-mng.php"><i class="fas fa-tag"></i>Quản lý sản phẩm</a></li>
            <li><a href="/CuoiKiWeb/admin/page/bill-mng.php"><i class="fas fa-tasks"></i>Quản lý đơn hàng</a></li>
            <li><a href="/CuoiKiWeb/admin/page/contact.php"><i class="fas fa-running"></i>Hỗ trợ</a></li>
            <li><a href="/CuoiKiWeb/admin/page/setting.php"><i class="fas fa-cog"></i>Cài đặt</a></li>
        </ul>
    </div>
</div>