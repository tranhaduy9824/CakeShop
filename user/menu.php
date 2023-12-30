<?php 
  require_once 'classes/connectMySql.php';
  require_once 'classes/users.php';
  require_once 'classes/carts.php';
  require_once 'classes/comments.php';
  require_once 'classes/sanphams.php';
  require_once 'classes/bill.php';
  require_once 'classes/messages.php';

  $connect = new ConnectMySql();
  $conn = $connect->getConnection();
?>

<div id="menu">
        <div class="menu">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                  <a class="navbar-brand" href="/CuoiKiWeb/user/index.php">Cake Shop</a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <form class="d-flex" role="search" action="/CuoiKiWeb/user/handle/search.php" method="post">
                        <input class="form-control me-2" type="text" name="search" placeholder="Tìm kiếm sản phẩm" value="<?php if (!empty($_GET["search"])) echo $_GET["search"]; else echo "";?>">
                        <button class="btn btn-outline-success" type="submit"><i class="fas fa-search" onclick="this.form.submit()"></i></button>
                    </form>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/CuoiKiWeb/user/pages/gioithieu.php">Giới thiệu</a>
                      </li>
                      <li class="nav-item sub-sanpham">
                        <a class="active" aria-current="page" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php">Sản phẩm</a>
                        <a class="" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu list-sub-sanpham">
                          <li style="<?php if (isset($_GET["type"]) && $_GET["type"]=="Sản phẩm đặc trưng") echo 'border: 2px solid white;';?>"><a class="dropdown-item" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php?type=Sản phẩm đặc trưng">Sản phẩm đặc trưng</a></li>
                          <li style="<?php if (isset($_GET["type"]) && $_GET["type"]=="Bánh sinh nhật") echo 'border: 2px solid white;';?>"><a class="dropdown-item" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php?type=Bánh sinh nhật">Bánh sinh nhật</a></li>
                          <li style="<?php if (isset($_GET["type"]) && $_GET["type"]=="Bánh tươi") echo 'border: 2px solid white;';?>"><a class="dropdown-item" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php?type=Bánh tươi">Bánh tươi</a></li>
                          <li style="<?php if (isset($_GET["type"]) && $_GET["type"]=="Bánh quy") echo 'border: 2px solid white;';?>"><a class="dropdown-item" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php?type=Bánh quy">Bánh bánh quy</a></li>
                          <li style="<?php if (isset($_GET["type"]) && $_GET["type"]=="Bánh mì") echo 'border: 2px solid white;';?>"><a class="dropdown-item" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php?type=Bánh mì">Bánh mì</a></li>
                          <li style="<?php if (isset($_GET["type"]) && $_GET["type"]=="Cà rem") echo 'border: 2px solid white;';?>"><a class="dropdown-item" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php?type=Cà rem">Cà rem</a></li>
                          <li style="<?php if (isset($_GET["type"]) && $_GET["type"]=="Bánh tráng miệng") echo 'border: 2px solid white;';?>"><a class="dropdown-item" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php?type=Bánh tráng miệng">Bánh tráng miệng</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li style="<?php if (isset($_GET["type"]) && $_GET["type"]=="Phụ kiện sinh nhật") echo 'border: 2px solid white;';?>"><a class="dropdown-item" href="/CuoiKiWeb/user/pages/Sản phẩm/sanphamphan.php?type=Phụ kiện sinh nhật">Phụ kiện sinh nhật</a></li>
                        </ul>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Cửa hàng</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Tin tức</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Trung thu 2023</a>
                      </li>
                      <li class="nav-item contact nav-link">Liên hệ</li>
                    </ul>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                          <?php if (isset($_COOKIE["userid"])) {
                            $userid = $_COOKIE["userid"];

                            $users = new Users();
                            $user = $users->getUserByUserId($userid);  
                          ?>
                          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- <i class="fas fa-user"></i> -->
                            <?php 
                              if (empty($user["avt"])) {
                                echo '<img class="avt" src="/ĐACS2/user/assets/img/avtmacdinh.jpg" alt="">'; 
                              } else {
                                  $avt=$user["avt"];
                                  $infoavt = getimagesizefromstring($avt);
                                  if (!empty($infoavt['mime'])) {
                                      $mime = $infoavt['mime'];
                                  } else $mime="";
                                  $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                  echo '<img class="avt" src="' .$avtsrc. '" alt="">';
                              }
                            ?>
                          </a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/CuoiKiWeb/user/handle/info.php">Thông tin</a></li>
                            <li><a class="dropdown-item" href="/CuoiKiWeb/user/handle/updatepass.php">Đổi mật khẩu</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/CuoiKiWeb/user/handle/logout.php">Đăng xuất</a></li>
                          </ul>
                          <?php } else {
                            echo '<a class="nav-link" href="/CuoiKiWeb/user/loginregister.php"><i class="fas fa-user"></i></a>';
                          } ?>
                        </li>
                        <li class="nav-item" class="check">
                            <a class="nav-link" href="/CuoiKiWeb/user/handle/bill.php"><i class="fa fa-tasks"></i></a>
                          </li>
                        <li class="nav-item cart">
                          <a class="nav-link disabled" aria-disabled="true"><i class="fas fa-shopping-cart"></i></a>
                        </li>
                      </ul>
                  </div>
                </div>
              </nav>    
        </div>
</div>