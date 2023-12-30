<?php 
    require_once 'classes/connectMySql.php';
    require_once 'classes/users.php';
    require_once 'classes/carts.php';
    require_once 'classes/comments.php';
    require_once 'classes/sanphams.php';
    require_once 'classes/bill.php';
    require_once 'classes/messages.php';

    if (isset($_COOKIE["userid"])) {
        $userid=$_COOKIE["userid"];

        $messages = new Messages();
        $listMessage = $messages->getMessages($userid);
    }
?>

    <div id="logo-chat">
        <div class="chat box-chat">
            <i class="fab fa-facebook-messenger"></i>
        </div>
        <div class="close-contact">
            <i class="fas fa-times"></i>
        </div>
    </div>
    <div id="box-chat">
        <div class="top">
            <div class="top-left">
                <p>Cake Shop</p>
            </div>
            <div class="top-right">
                <i class="fas fa-minus close-boxchat"></i>
                <i class="fas fa-times close-contact"></i>
            </div>
        </div>
        <div class="center">
            <h3><i>Cake shop - Liên hệ để giải đáp thắc mắc</i></h3>
            <?php 
                if (isset($listMessage)) {
                    foreach ($listMessage as $row) {
                        if ($row["senderid"]==$userid) {
                            echo '<div class="send">' .$row["content"]. '</div>';
                        } else {
                            echo '<div class="receive">' .$row["content"]. '</div>';
                        }
                    }
                }
            ?>
        </div>
        <div class="bottom">
            <form class="box-message" action="/CuoiKiWeb/user/handle/handleContact.php" method="post">
                <div class="bottom-input">
                    <input type="text" placeholder="Aa" name="content" required>
                    <button type="submit" name="send-mess"><i class="far fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>