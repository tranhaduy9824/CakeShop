<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/header.css">
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
    ?>

    <?php if (isset($_COOKIE["adminid"]))  {?>
    <!-- Main -->
    <div id="main">
        <!-- Header -->
        <?php include 'partials/header.php' ?>

        <!-- Content -->
        <div id="content">
            <div class="row1">
                <p><b>Bảng Điều Khiển</b></p>
                <div id="clock"></div>
            </div>
            <div class="row2">
                <div class="box-left">
                    <div class="statistics">
                        <div>
                            <?php 
                                $sql="SELECT * FROM users";
                                $stmt=$conn->prepare($sql);
                                $stmt->execute();
                                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="part">
                                <i class="fas fa-users"></i>
                                <div class="info-part">
                                    <h4>Tổng khách hàng</h4>
                                    <p><b><?php echo count($result); ?> khách hàng</b></p>
                                    <p>Tổng số khách hàng được quản lý</p>
                                </div>
                            </div>
                            <?php 
                                $sql="SELECT * FROM sanphams";
                                $stmt=$conn->prepare($sql);
                                $stmt->execute();
                                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="part">
                                <i class="fas fa-database"></i>
                                <div class="info-part">
                                    <h4>Tổng sản phẩm</h4>
                                    <p><b><?php echo count($result); ?> sản phẩm</b></p>
                                    <p>Tổng số sản phẩm được quản lý</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <?php 
                                $sql="SELECT * FROM bill";
                                $stmt=$conn->prepare($sql);
                                $stmt->execute();
                                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="part">
                                <i class="fas fa-shopping-bag"></i>
                                <div class="info-part">
                                    <h4>Tổng đơn hàng</h4>
                                    <p><b><?php echo count($result);?> đơn hàng</b></p>
                                    <p>Tổng số hóa đơn bán hàng trong tháng</p>
                                </div>
                            </div>
                            <div class="part">
                                <i class="fas fa-exclamation-circle"></i>
                                <div class="info-part">
                                    <h4>Sắp hết hàng</h4>
                                    <p><b>0 sản phẩm</b></p>
                                    <p>Số sản phẩm cảnh báo hết cần nhập thêm.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order-status">
                        <h3>Tình trạng đơn hàng</h3>
                        <div>
                            <?php 
                                $sql="SELECT * FROM bill ORDER BY idbill DESC LIMIT 4";
                                $stmt=$conn->prepare($sql);
                                $stmt->execute();
                                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <table class="order">
                                <tr>
                                    <th>ID đơn hàng</th>
                                    <th>Tên khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                                <?php 
                                    foreach ($result as $row) {
                                        $stmt=$conn->prepare("SELECT * FROM users WHERE userid=:userid");
                                        $stmt->bindParam(':userid', $row["userid"]);
                                        $stmt->execute();
                                        $result2=$stmt->fetch(PDO::FETCH_ASSOC);
                                    echo '<tr>
                                    <td>' .$row["idbill"]. '</td>
                                    <td>' .$result2["fullname"]. '</td>
                                    <td>' .$row["totalbill"]. '</td>
                                    <td>' .$row["status"]. '</td>
                                    </tr>';
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="new-users">
                        <h3>Khách hàng mới</h3>
                        <div>
                            <?php 
                                $sql="SELECT * FROM users ORDER BY userid DESC LIMIT 4";
                                $stmt=$conn->prepare($sql);
                                $stmt->execute();
                                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <table class="users">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khách hàng</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                </tr>
                                <?php 
                                    foreach ($result as $row) {
                                    echo '<tr>
                                    <td>#' .$row["userid"]. '</td>
                                    <td>' .$row["fullname"]. '</td>
                                    <td>' .$row["email"]. '</td>
                                    <td>' .$row["phone"]. '</td>
                                    </tr>';
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box-right">
                    <div class="revenue">
                        <h3>Thống kê 6 tháng doanh thu</h3>
                        <div class="box-revenue">
                            <img src="https://scontent.fdad2-1.fna.fbcdn.net/v/t1.15752-9/370249967_1752538951840838_1689210647502427926_n.png?_nc_cat=107&ccb=1-7&_nc_sid=8cd0a2&_nc_ohc=Vad7iLYOVqIAX_Zs3tR&_nc_ht=scontent.fdad2-1.fna&oh=03_AdR0po4UAQB0jc2oNO9nySMWKleRTXMqAi7NQret5tEaOQ&oe=657A4B9E" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } else {
            header("Location: /ĐACS2_NEW/admin/index.php");
            exit();
        }
    ?>

    <script src="./main.js"></script>
</body>
</html>