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

    if (isset($_COOKIE["userid"])) {
        $userid=$_COOKIE["userid"];
    }

    $sql="SELECT * FROM messages WHERE senderid=:senderid or receiverid=:receiverid ";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':senderid', $userid);
    $stmt->bindParam(':receiverid', $userid);
    $stmt->execute();
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);  
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
                foreach ($result as $row) {
                    if ($row["senderid"]==$userid) {
                        echo '<div class="send">' .$row["content"]. '</div>';
                    } else {
                        echo '<div class="receive">' .$row["content"]. '</div>';
                    }
                }
            ?>
        </div>
        <div class="bottom">
            <form class="box-message" action="/ĐACS2_NEW/user/handle/handleContact.php" method="post">
                <div class="bottom-input">
                    <input type="text" placeholder="Aa" name="content" required>
                    <button type="submit" name="send-mess"><i class="far fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>