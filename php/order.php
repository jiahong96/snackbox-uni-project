<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    if(isset($_POST['ProdID']) || isset($_POST['username']))
    {
        function InsertD($Id,$conn,$email){
            $Prod = "SELECT * FROM tb_product WHERE ItemID='".$_POST['ProdID']."'";
            $result = $conn->query($Prod);
            if ($result->num_rows > 0) {
                $ProdD = $result->fetch_array(MYSQLI_ASSOC);
                $chekDup = "SELECT tb_order.orderID, tb_order.Email, tb_ordDetail.ItemID,tb_ordDetail.ItemName FROM tb_order join tb_ordDetail on tb_order.orderID = tb_ordDetail.orderID WHERE tb_order.orderID = '".$Id."' AND tb_order.Email= '".$email."' AND tb_ordDetail.ItemID = '".$ProdD['ItemID']."'";
                $rsDup = $conn->query($chekDup);
                if ($rsDup->num_rows > 0) { 
                    $ProdD = $rsDup->fetch_array(MYSQLI_ASSOC);
                    echo $ProdD['ItemName']." already inside your cart!";
                }else{
                    $checkNo = "SELECT OrderNo FROM tb_ordDetail where OrderID = '".$Id."' order by OrderNo DESC";
                    $result = $conn->query($checkNo);
                    if ($result->num_rows > 0) { //tb_ordDetail have this id recordss
                        $OrdD = $result->fetch_array(MYSQLI_ASSOC);
                        $OrdNo = $OrdD['OrderNo']+1;
                    }else{
                        $OrdNo = 1;
                    }
                    $sql = "INSERT INTO tb_ordDetail (OrderID, OrderNo, ItemID, ItemName, ItemPrice, ItemQty, ItemTotal)VALUES('".$Id."',".$OrdNo.",'".$ProdD['ItemID']."','".$ProdD['ItemName']."',".$ProdD['ItemPrice'].",1,".$ProdD['ItemPrice'].")";
                    if ($conn->query($sql) === TRUE) {
                        echo "Add to Cart successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } 
            }else{
                 echo "No this ItemID";
            }        
        }
        function InsertH($Id,$conn,$email){
            $sql = "INSERT INTO tb_order(OrderID, Email, Status, OrderDatetime) 
                            VALUES('".$Id."','".$email."','ordering','".date("Y/m/d H:i:s")."');";
            if ($conn->query($sql) === TRUE) {
                $herderG = true;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            if ($herderG == true){
                InsertD($Id,$conn,$email);
            }
            else{
                echo "Something wrong!";
            }
        }
        $username=$_POST['username'];
        $validate = "SELECT * FROM tb_member where username = '".$username."'";
        $result = $conn->query($validate);
        if ($result->num_rows > 0) {
            $rs = $result->fetch_array(MYSQLI_ASSOC);
            $OldOrder = "SELECT * FROM tb_order where Email = '".$rs['Email']."'and DATE_FORMAT(OrderDatetime,'Y/m/d') = DATE_FORMAT(now(),'Y/m/d') and Status = 'ordering' ";
            $result = $conn->query($OldOrder);
            if ($result->num_rows > 0) { //insert to details
                $old = $result->fetch_array(MYSQLI_ASSOC);
                InsertD($old['OrderID'],$conn,$rs['Email']);
            }else{//insert new order and add in details
                $GenID = "SELECT COUNT(*) AS OrderNo from tb_order where DATE_FORMAT(OrderDatetime,'y') = DATE_FORMAT(now(),'y')";
                $Genresult = $conn->query($GenID);
                $OrderInfo = $Genresult->fetch_array(MYSQLI_ASSOC);
                if($OrderInfo['OrderNo'] > 0){
                    //$ID = "Y".date('y');
                    $ID = "Y".date('y').sprintf('%06d', $OrderInfo['OrderNo'] + 1);
                    InsertH($ID,$conn,$rs['Email']);
                }else{
                    $ID = "Y".date('y')."000001";
                    InsertH($ID,$conn,$rs['Email']);
                }
            }
        }else{
            echo "Allien!";
        } 
    }
    
	$conn->close();
?>