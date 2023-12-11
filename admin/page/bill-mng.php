<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/bill-mng.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head>
<body>
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

        if (isset($_GET['delete'])) {
            $idbill = $_GET['delete'];

            if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
                $sql="DELETE FROM bill WHERE idbill=:idbill";
                $stmt=$conn->prepare($sql);
                $stmt->bindParam(':idbill', $idbill);
                $stmt->execute();

                header("Location: bill-mng.php");
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

            if (isset($_POST["delivery"])) {
                $delivery = $_POST["delivery"];
            } else {
                $delivery = "";
            }
            if (isset($_POST["status"])) {
                $status = $_POST["status"];
            } else {
                $status = "";
            }
            
            if (!empty($_GET["search"])) {
                $search = $_GET["search"];
                if (empty($delivery) && empty($status)) {
                    $sql = "SELECT * FROM bill WHERE fullname LIKE :search LIMIT :start, :limit";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':search', '%' . $search . '%');
                } else {
                    $sql = "SELECT * FROM bill WHERE fullname LIKE :search";
                    if (!empty($delivery)) {
                        $sql .= " AND delivery = :delivery";
                    }
                    if (!empty($status)) {
                        $sql .= " AND status = :status";
                    }
                    $sql .= " LIMIT :start, :limit";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue(':search', '%' . $search . '%');
                    if (!empty($delivery)) {
                        $stmt->bindParam(':delivery', $delivery);
                    }
                    if (!empty($status)) {
                        $stmt->bindParam(':status', $status);
                    }
                }
            } else {
                if (empty($delivery) && empty($status)) {
                    $sql = "SELECT * FROM bill LIMIT :start, :limit";
                    $stmt = $conn->prepare($sql);
                } else {
                    $sql = "SELECT * FROM bill WHERE 1=1";
                    if (!empty($delivery)) {
                        $sql .= " AND delivery = :delivery";
                    }
                    if (!empty($status)) {
                        $sql .= " AND status = :status";
                    }
                    $sql .= " LIMIT :start, :limit";
                    $stmt = $conn->prepare($sql);
                    if (!empty($delivery)) {
                        $stmt->bindParam(':delivery', $delivery);
                    }
                    if (!empty($status)) {
                        $stmt->bindParam(':status', $status);
                    }
                }
            }
            
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <!-- Content -->
        <div id="content">
            <div class="row1">
                <p><b>Danh sách đơn hàng</b></p>
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
                        <form action="bill-mng.php" method="post">
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
                    <div class="choose">
                        <form action="bill-mng.php" method="post">
                            <label for="">Trạng thái</label>
                            <select name="status" id="" onchange="this.form.submit()">
                                <option value="Chưa thanh toán" <?php if ($status=="Chưa thanh toán" && $delivery!="Đã giao") echo 'selected'; if ($delivery=="Đã giao") echo 'disabled';?>>Chưa thanh toán</option>
                                <option value="Đã thanh toán" <?php if ($status=="Đã thanh toán" && $delivery!="Đã giao") echo 'selected'; if ($delivery=="Đã giao") echo 'disabled';?>>Đã thanh toán</option>
                                <option value="" <?php if ($status==""||$delivery=="Đã giao") echo 'selected';?>>Tất cả</option>
                            </select>
                            <label for="">Giao hàng</label>
                            <select name="delivery" id="" onchange="this.form.submit()">
                                <option value="Chưa giao" <?php if ($delivery=="Chưa giao") echo 'selected';?>>Chưa giao</option>
                                <option value="Đã giao" <?php if ($delivery=="Đã giao") echo 'selected';?>>Đã giao</option>
                                <option value="" <?php if ($delivery=="") echo 'selected';?>>Tất cả</option>
                            </select>
                        </form>
                    </div>
                    <div class="search">
                        <form action="bill-mng.php" method="get">
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
                            <th>ID đơn hàng</th>
                            <th>Họ và tên</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                            <th>Phương thức thanh toán</th>
                            <th>Tổng tiền đơn hàng</th>
                            <th>Trạng thái</th>
                            <th>Ghi chú</th>
                            <th>Giao hàng</th>
                            <th>Thời gian</th>
                            <th>Chức năng</th>
                        </tr>
                        <?php 
                            foreach ($result as $row) {
                                echo '<tr>
                                <td>
                                    <input type="checkbox" class="checkbox-item">
                                </td>
                                <td>' .$row["idbill"]. '</td>
                                <td>' .$row["fullname"]. '</td>
                                <td>' .$row["phone"]. '</td>
                                <td>' .$row["email"]. '</td>
                                <td>' .$row["address"]. '</td>
                                <td>' .$row["typepay"]. '</td>
                                <td>' .$row["totalbill"]. '</td>
                                <td>' .$row["status"]. '</td>
                                <td>' .$row["note"]. '</td>
                                <td>' .$row["delivery"]. '</td>
                                <td>' .$row["time"]. '</td>';
                                echo '<td><div>';
                                if ($row["delivery"]=="Chưa giao") {
                                    echo '<a class="delete" href="bill-mng.php?delete=' .$row["idbill"]. '&confirm=true" onclick="return confirmDelete()"><i class="fas fa-trash"></i></a>';
                                }
                                echo '<a class="more-info" href="bill-mng.php?idbill=' .$row["idbill"]. '"><i class="fas fa-eye"></i></a>';
                                echo '</div></td>
                                </tr>';
                            }
                        ?>
                    </table>
                </div>
                <?php 
                    $sql="SELECT * FROM bill";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute();
                    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="change-page">
                    <p>Hiện <?php echo $start+1;?> đến <?php if (count($result)>$start+$limit) echo $start+$limit; else echo count($result);?> của <?php echo count($result);?> danh mục</p>
                    <div class="btn-change">
                        <?php 
                            for ($i=1;$i<=ceil((count($result)/$limit));$i++) {
                                echo '<div><a class="edit-link" href="bill-mng.php?page=' .$i. '">' .$i. '</a></div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
        if (isset($_GET["idbill"])) {
            $idbill=$_GET["idbill"];
        }
        $sql="SELECT * FROM bill WHERE idbill=:idbill";
        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':idbill', $idbill);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
    ?>

    <!-- Model more info bill -->
    <div id="model-more-info">
        <div class="box-more">
            <h5>ID đơn hàng <?php echo $result["idbill"];?>: <?php echo $result["fullname"];?></h5>
            <div class="row-more">
                <table>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                    </tr>
                    <?php  
                        $str = $result["sanphams"];
                        $numbers = explode(", ", $str);
                        $count = count($numbers);
                        foreach ($numbers as $index => $number) {
                            if ($index == $count - 1) {
                                continue;
                            }
                            $parts = explode("-", $number);
                            $idsp = $parts[0];
                            $countsp = $parts[1];
                            $sql1="SELECT  * FROM sanphams WHERE idsp=:idsp";
                            $stmt1=$conn->prepare($sql1);
                            $stmt1->bindParam(':idsp', $idsp);
                            $stmt1->execute();
                            $result1=$stmt1->fetch(PDO::FETCH_ASSOC);
                            echo '<tr>';
                            echo '<td>' .$result1["namesp"]. '</td>';
                            echo '<td>' .$countsp. '</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
            <div class="exit">   
                <a href="/ĐACS2_NEW/admin/page/bill-mng.php">Thoát</a>
            </div>
        </div>
    </div>
    <?php } ?>

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
            return confirm("Bạn có muốn xóa đơn hàng này không");
        }

        var urlParams = new URLSearchParams(window.location.search);
        var idsp = urlParams.get('idbill');

        if (idsp) {
            var modelEdit = document.getElementById('model-more-info');
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