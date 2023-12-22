<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/contact.css">
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

        if (isset($_COOKIE["adminid"])) {
            $adminid=$_COOKIE["adminid"];
        }

        try {
            $conn=new PDO("mysql:host=$server;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Lỗi: " .$e->getMessage();
        }
    ?>

    <?php if (isset($_COOKIE["adminid"])) {?>
    <!-- Main -->
    <div id="main">
        <!-- Header -->
        <?php include './partials/header.php';?>

        <?php 
            if (isset($_GET["search"]) && !empty($_GET["search"])) {
                $search=$_GET["search"];
                $sql1="SELECT * FROM users WHERE fullname LIKE :fullname";
                $stmt1=$conn->prepare($sql1);
                $stmt1->bindValue(':fullname', '%' . $search . '%');
                $stmt1->execute();
                $result1=$stmt1->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result1 as $row) {
                    $sql="SELECT * FROM messages WHERE senderid=:senderid";
                    $stmt=$conn->prepare($sql);
                    $stmt->bindValue(':senderid', $row["userid"]);
                }
            } else {
                $sql="SELECT * FROM messages WHERE roleSender=:roleSender";
                $stmt=$conn->prepare($sql);
                $stmt->bindValue(':roleSender', "user");
            }
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <!-- Content -->
        <div id="content">
            <div class="box-content">
                <div class="content-left">
                    <form action="contact.php" method="get">
                        <div class="search">
                            <input type="text" name="search" placeholder="Tìm kiếm người dùng" onchange="this.fomr.submit()" value="<?php if (!empty($_GET["search"])) echo $search; else echo "";?>">
                            <i class="fas fa-search"></i>
                        </div>
                    </form>
                    <div class="list-user">
                        <?php 
                            $listUserSend = [];
                            if ($result) {
                                $foundUser = false;
                                foreach ($result as $row) {
                                    if (isset($row["roleSender"]) && $row["roleSender"] == "user") {
                                        $foundUser = true;
                                        if (!in_array($row["senderid"], $listUserSend)) {
                                            array_push($listUserSend, $row["senderid"]);
                                        }
                                    }
                                }
                                if (!$foundUser) {
                                    echo "<h4 style=\"text-align: center;\">Không tìm thấy người dùng</h4>";
                                }
                            } else {
                                echo "<h4 style=\"text-align: center;\">Không tìm thấy người dùng</h4>";
                            }
                            foreach ($listUserSend as $key => $userSendID) {
                                $userid = $userSendID;
                                $sql="SELECT * FROM users WHERE userid=:userid";
                                $stmt=$conn->prepare($sql);
                                $stmt->bindParam(':userid', $userid);
                                $stmt->execute();
                                $result=$stmt->fetch(PDO::FETCH_ASSOC);

                                $sql2 = "SELECT * FROM messages WHERE senderid=:senderid or receiverid=:receiverid ORDER BY timestamp DESC LIMIT 1";
                                $stmt2 = $conn->prepare($sql2);
                                $stmt2->bindParam(':senderid', $userid);
                                $stmt2->bindParam(':receiverid', $userid);
                                $stmt2->execute();
                                $lastMessage = $stmt2->fetch(PDO::FETCH_ASSOC);
                                echo '<div class="item-user">
                                        <input hidden type="text" value="' .$result["userid"]. '" name="userid">';
                                        if (empty($result["avt"])) {
                                            echo '<img src="/ĐACS2/user/assets/img/avtmacdinh.jpg" alt="">'; 
                                        } else {
                                            $avt=$result["avt"];
                                            $infoavt = getimagesizefromstring($avt);
                                            if (!empty($infoavt['mime'])) {
                                                $mime = $infoavt['mime'];
                                            } else $mime="";
                                            $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                            echo '<img src="' .$avtsrc. '" alt="">';
                                        }
                                        echo '<div class="info-user">
                                            <p><b>' .$result["fullname"]. '</b></p>';
                                            if ($lastMessage) {
                                                if ($lastMessage["roleSender"] == "user") {
                                                    echo '<p class="new-mess">' . $lastMessage["content"] . '</p>';
                                                } else {
                                                    echo '<p class="new-mess">Bạn: ' . $lastMessage["content"] . '</p>';
                                                }
                                            } 
                                        echo '</div>
                                    </div>';
                            }
                        ?>
                    </div>
                </div>
                
                <?php 
                    if (isset($_GET["userid"])) {
                        $userid=$_GET["userid"];

                        $sql="SELECT * FROM messages WHERE senderid=:senderid or receiverid=:receiverid";
                        $stmt=$conn->prepare($sql);
                        $stmt->bindParam(':senderid', $userid);
                        $stmt->bindParam(':receiverid', $userid);
                        $stmt->execute();
                        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

                        $sql1="SELECT * FROM users WHERE userid=:userid";
                        $stmt1=$conn->prepare($sql1);
                        $stmt1->bindParam(':userid', $userid);
                        $stmt1->execute();
                        $result1=$stmt1->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="content-right">
                    <div class="info-user-send">
                        <div class="info-user">
                            <?php 
                                if (empty($result1["avt"])) {
                                    echo '<img src="/ĐACS2/user/assets/img/avtmacdinh.jpg" alt="">'; 
                                } else {
                                    $avt=$result1["avt"];
                                    $infoavt = getimagesizefromstring($avt);
                                    if (!empty($infoavt['mime'])) {
                                        $mime = $infoavt['mime'];
                                    } else $mime="";
                                    $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                    echo '<img src="' .$avtsrc. '" alt="">';
                                }
                            ?>
                            <h4><?php echo $result1["fullname"];?></h4>
                        </div>
                        <div class="more-btn">
                            <i class="fas fa-ellipsis-h"></i>
                        </div>
                    </div>
                    <div class="box-chat">
                        <div class="info-user">
                            <?php 
                                if (empty($result1["avt"])) {
                                    echo '<img src="/ĐACS2/user/assets/img/avtmacdinh.jpg" alt="">'; 
                                } else {
                                    $avt=$result1["avt"];
                                    $infoavt = getimagesizefromstring($avt);
                                    if (!empty($infoavt['mime'])) {
                                        $mime = $infoavt['mime'];
                                    } else $mime="";
                                    $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                    echo '<img src="' .$avtsrc. '" alt="">';
                                }
                            ?>
                            <h4><?php echo $result1["fullname"];?></h4>
                        </div>
                        <div class="content-chat">
                            <?php 
                                foreach ($result as $row) {
                                    if ($row["roleSender"]!=="user") {
                                        echo '<div class="send">'.$row["content"]. '</div>';
                                    } else {
                                        echo '<div class="receive">'.$row["content"]. '</div>';
                                    }
                                }
                            ?>  
                        </div>
                    </div>
                    <form class="box-message" action="./partials/handleContact.php" method="post">
                        <input type="text" name="userid" hidden value="<?php echo $userid;?>">
                        <div class="box-send">
                            <div class="more-send">
                                <div class="send-file">
                                    <input type="file" id="file-input">
                                    <label for="file-input">
                                        <i class="fas fa-image"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="bottom-input">
                                <input type="text" placeholder="Aa" name="content" required>
                                <button type="submit" name="send-mess"><i class="far fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php 
                    } else {
                        echo '<div class="content-right">
                            <h1>Hỗ trợ</h1>
                        </div>';
                    }
                ?>
            </div>
        </div>
    </div>
    <?php 
        } else {
            header("Location: /ĐACS2_NEW/admin/index.php");
            exit();
        }
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./partials/contact.js"></script>
</body>
</html>