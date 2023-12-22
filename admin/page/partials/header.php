<?php 
    $server="localhost";
    $user="root";
    $pass="";
    $db="dacs2";
    try {
        $conn=new PDO("mysql:host=$server;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Lỗi: " .$e->getMessage();
    }

    if ($_COOKIE["adminid"]) {
        $adminid=$_COOKIE["adminid"];
    }

    $sql="SELECT * FROM admins WHERE adminid=:adminid";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':adminid', $adminid);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
?>

<div id="logout">
    <div class="box-logout">
        <a href="/ĐACS2_NEW/admin/page/partials/logout.php"><i class="fas fa-sign-out-alt"></i></a>
    </div>
</div>
<div id="menu">
    <div class="adminname">
        <?php 
            if (empty($result["avt"])) {
                echo '<img src="/ĐACS2_NEW/admin/img/avtmacdinh.jpg" alt="">';
            }
            else {
                $avt = $result["avt"];
                $infoavt = getimagesizefromstring($avt);
                if (!empty($infoavt['mime'])) {
                    $mime = $infoavt['mime'];
                } else $mime="";
                $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                echo '<img src="' .$avtsrc. '" alt="">';
            }
        ?>
        <p><b><?php echo $result["fullname"];?></b></p>
        <p>Chúc mừng bạn trở lại</p>
    </div>
    <hr>
    <div class="list-menu">
        <ul>
            <li><i class="fas fa-shopping-cart"></i>POS Bán Hàng</li>
            <li><a href="/ĐACS2_NEW/admin/page/index.php"><i class="fas fa-tachometer-alt"></i>Bảng điều khiển</a></li>
            <li><a href="/ĐACS2_NEW/admin/page/user-mng.php"><i class="fas fa-users"></i>Quản lý khách hàng</a></li>
            <li><a href="/ĐACS2_NEW/admin/page/product-mng.php"><i class="fas fa-tag"></i>Quản lý sản phẩm</a></li>
            <li><a href="/ĐACS2_NEW/admin/page/bill-mng.php"><i class="fas fa-tasks"></i>Quản lý đơn hàng</a></li>
            <li><a href="/ĐACS2_NEW/admin/page/contact.php"><i class="fas fa-running"></i>Hỗ trợ</a></li>
            <li><a href="/ĐACS2_NEW/admin/page/setting.php"><i class="fas fa-cog"></i>Cài đặt</a></li>
        </ul>
    </div>
</div>