<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    if(isset($_POST['username']) && isset($_POST['getInfo']))
    {
        $outp = "";
        $username=$_POST['username'];
        if($_POST['getInfo'] == "account"){
            $result = $conn->query( "SELECT * FROM tb_member where Username ='".$username."'");
            if ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
                $outp ='"'.$rs['FirstName'].'"'.",".'"'.$rs['LastName'].'"'.",".'"'.$rs['Email'].'"'.",".'"'.$rs['PhoneNo'].'"';
               echo '['.$outp.']';
            } else {
                echo "Something Wrong." . " " .$result . " " ."?";
            }
        }else if($_POST['getInfo'] == "order"){
            $result = $conn->query( "SELECT DATE_FORMAT(tb_order.OrderDatetime, '%d/%m/%y') AS DATE, tb_order.orderID, tb_order.Total, tb_order.Status, tb_member.Username FROM tb_order join tb_member on tb_order.Email = tb_member.email where tb_member.Username ='".$username."' and status !='ordering' order by DATE");
            if ($result->num_rows > 0) {
                while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"date":"' . $rs["DATE"] . '",';
                    $outp .= '"orderid":"' . $rs["orderID"] . '",';
                    $outp .= '"status":"' . $rs["Status"] . '",';
                    $outp .= '"total":160}';
                   // $outp .= '"total":' . $rs["Total"] . '}'; 
                }
                $outp ='['.$outp.']';
                echo($outp);
            }else{
                echo "Empty!";
            }
        }
        else if($_POST['getInfo'] == "cart"){   
            //SELECT tb_order.orderID, tb_ordDetail.OrderNo, tb_ordDetail.ItemID, tb_ordDetail.ItemName, tb_ordDetail.ItemPrice, tb_ordDetail.ItemQty, tb_ordDetail.ItemTotal from tb_order join tb_member on tb_order.Email = tb_member.email join tb_orddetail on tb_order.orderID = tb_ordDetail.orderID where tb_member.Username ='sen' and status ='ordering'
            $result = $conn->query( "SELECT tb_order.orderID, tb_ordDetail.OrderNo, tb_ordDetail.ItemID, tb_ordDetail.ItemName, tb_ordDetail.ItemPrice, tb_ordDetail.ItemQty, tb_ordDetail.ItemTotal from tb_order join tb_member on tb_order.Email = tb_member.email join tb_orddetail on tb_order.orderID = tb_ordDetail.orderID where tb_member.Username ='".$username."' and status ='ordering' order by tb_ordDetail.OrderNo");
            if ($result->num_rows > 0) {
                $no = 1;
                while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"OrderID":"' . $rs["orderID"] . '",';
                    $outp .= '"ItemNo":"' . $no . '",';
                    $outp .= '"OrderNo":"' . $rs["OrderNo"] . '",';
                    $outp .= '"ItemID":"' . $rs["ItemID"] . '",';
                    $outp .= '"ItemName":"' . $rs["ItemName"] . '",';
                    $outp .= '"ItemPrice":' . $rs["ItemPrice"] . ',';
                    $outp .= '"ItemQty":' . $rs["ItemQty"] . ',';
                    $outp .= '"ItemTotal":' . $rs["ItemTotal"] . '}'; 
                    $no = $no + 1;
                }
                $outp ='['.$outp.']';
                echo($outp);
            }else{
                echo "Empty!";
            }
        } 
        else if($_POST['getInfo'] == "updateQty"){
            if(isset($_POST['OrderID']) && isset($_POST['OrderNo']) && isset($_POST['qty'])){
                $validate = "SELECT * FROM tb_ordDetail WHERE OrderID='".$_POST['OrderID']."' AND OrderNo=".$_POST['OrderNo'].";";
                $result = $conn->query($validate);
                if ($result->num_rows > 0) {
                    $rs = $result->fetch_array(MYSQLI_ASSOC);
                    $sql = "UPDATE tb_ordDetail SET ItemQty = " . round($_POST['qty']) . ",
                    ItemTotal= " . round($_POST['qty'])*$rs["ItemPrice"] . " WHERE OrderID='".$_POST['OrderID']."' AND OrderNo=".$_POST['OrderNo'].";";
                     if ($conn->query($sql) === TRUE) {
                        echo "Quantity updated successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }else{
                    echo "Error: " . $validate . "<br>" . $conn->error;
                }
            }else{
                echo "Missing data!";
            }
        } 
        else if($_POST['getInfo'] == "RemoveItem"){
            if(isset($_POST['OrderID']) && isset($_POST['OrderNo'])){
                $validate = "SELECT * FROM tb_ordDetail WHERE OrderID='".$_POST['OrderID']."' AND OrderNo=".$_POST['OrderNo'].";";
                $result = $conn->query($validate);
                if ($result->num_rows > 0) {
                    $rs = $result->fetch_array(MYSQLI_ASSOC);
                    $sql = "DELETE FROM tb_ordDetail WHERE OrderID='".$_POST['OrderID']."' AND OrderNo=".$_POST['OrderNo'].";";
                    if ($conn->query($sql) === TRUE) {
                        echo "Remove Item successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }else{
                    echo "Error: " . $validate . "<br>" . $conn->error;
                }
            }else{
                echo "Missing data!";
            }
        }
    }
	$conn->close();
?>