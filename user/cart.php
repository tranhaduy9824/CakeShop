        <div id="cart">
            <div class="box-cart">
                <?php 
                    if (isset($_COOKIE["userid"]) && $opendetailcart>0) {
                        $userid=$_COOKIE["userid"];

                        $sql="SELECT * FROM carts WHERE userid=:userid";
                        $stmt=$conn->prepare($sql);
                        $stmt->bindParam(':userid', $userid);
                        $stmt->execute();
                        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        $totalcart=0;

                        echo '<h3>Giỏ hàng của bạn</h3>';
                        echo '<div class="top-cart">';
                        foreach ($result as $row) {
                        echo '<div class="item-cart">
                            <div class="img-item">';
                            $image=$row["imagesp"];
                            $imageinfo=getimagesizefromstring($image);
                            $mime=$imageinfo['mime'];
                            $imagesrc='data:' .$mime. ';base64,' .base64_encode($image);
                                echo '<img src="' .$imagesrc. '" alt="">
                            </div>
                            <div class="text-item">
                                <p>' .$row["namesp"]. '</p>
                                <p>Số lượng: ' .$row["number"]. '</p>
                                <p>' .$row["total"]. '</p>
                            </div>
                        </div>';
                        $total=floatval(str_replace(",", "", $row["total"]));
                        $totalcart+=$total;
                        }
                        $totalcart=number_format($totalcart, 0, ",", ",") . " VND";
                        echo '</div>';
                        echo '<div class="bottom-cart">';
                        echo '<div class="total-cart">
                            <p>Tạm tính:</p>
                            <p>' .$totalcart. '</p>
                        </div>
                        <div class="btn-item">
                            <div class="btn-detail">Chi tiết</div>
                            <div class="btn-pay">Thanh toán</div>
                        </div>';
                        echo '</div>';
                    }
                    else {
                        echo '<h3>Giỏ hàng của bạn</h3>';
                        echo '<p>Giỏ hàng của bạn chưa có sản phẩm nào</p>';
                        echo '<div class="buysp">';
                        echo '<a href="/ĐACS2_NEW/user/pages/Sản phẩm/sanphamphan.php">Mua sản phẩm</a>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>