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
        require_once '../classes/connectMySql.php';
        require_once '../classes/admins.php';
        require_once '../classes/users.php';
        require_once '../classes/bill.php';
        require_once '../classes/messages.php';
        require_once '../classes/sanphams.php';

        $users = new Users();
        $messages = new Messages();

        if (isset($_COOKIE["adminid"])) {
            $adminid=$_COOKIE["adminid"];
        }

        if (isset($_GET["search"]) && !empty($_GET["search"])) {
            $search=$_GET["search"];
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
                
                $listUser = $messages->searchUsers($search);

                foreach ($listUser as $row) {
                    $listMessage = $messages->getMessagesBySenderId($row["userid"]);
                }
            } else {
                $listMessage = $messages->getMessagesByRoleSender();
            }
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
                            if ($listMessage) {
                                $foundUser = false;
                                foreach ($listMessage as $row) {
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
                                
                                $listUser = $users->getUserById($userid);

                                $lastMessage = $messages->getLastMessage($userid);
                                echo '<div class="item-user">
                                        <input hidden type="text" value="' .$listUser["userid"]. '" name="userid">';
                                        if (empty($listUser["avt"])) {
                                            echo '<img src="/ĐACS2/user/assets/img/avtmacdinh.jpg" alt="">'; 
                                        } else {
                                            $avt=$listUser["avt"];
                                            $infoavt = getimagesizefromstring($avt);
                                            if (!empty($infoavt['mime'])) {
                                                $mime = $infoavt['mime'];
                                            } else $mime="";
                                            $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                            echo '<img src="' .$avtsrc. '" alt="">';
                                        }
                                        echo '<div class="info-user">
                                            <p><b>' .$listUser["fullname"]. '</b></p>';
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

                        $listMessage = $messages->getMessages($userid);

                        $listUser = $users->getUserById($userid);
                ?>
                <div class="content-right">
                    <div class="info-user-send">
                        <div class="info-user">
                            <?php 
                                if (empty($listUser["avt"])) {
                                    echo '<img src="/ĐACS2/user/assets/img/avtmacdinh.jpg" alt="">'; 
                                } else {
                                    $avt=$listUser["avt"];
                                    $infoavt = getimagesizefromstring($avt);
                                    if (!empty($infoavt['mime'])) {
                                        $mime = $infoavt['mime'];
                                    } else $mime="";
                                    $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                    echo '<img src="' .$avtsrc. '" alt="">';
                                }
                            ?>
                            <h4><?php echo $listUser["fullname"];?></h4>
                        </div>
                        <div class="more-btn">
                            <i class="fas fa-ellipsis-h"></i>
                        </div>
                    </div>
                    <div class="box-chat">
                        <div class="info-user">
                            <?php 
                                if (empty($listUser["avt"])) {
                                    echo '<img src="/ĐACS2/user/assets/img/avtmacdinh.jpg" alt="">'; 
                                } else {
                                    $avt=$listUser["avt"];
                                    $infoavt = getimagesizefromstring($avt);
                                    if (!empty($infoavt['mime'])) {
                                        $mime = $infoavt['mime'];
                                    } else $mime="";
                                    $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                    echo '<img src="' .$avtsrc. '" alt="">';
                                }
                            ?>
                            <h4><?php echo $listUser["fullname"];?></h4>
                        </div>
                        <div class="content-chat">
                            <?php 
                                foreach ($listMessage as $row) {
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
            header("Location: /CuoiKiWeb/admin/index.php");
            exit();
        }
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./partials/contact.js"></script>
</body>
</html>