<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    if(isset($_POST['type']) && $_POST['type'] == "dashboard" ){
        //check on the lastlogin datetime for admin
        $select = $conn->query("SELECT DATE_FORMAT(tb_member.JoinDate, '%Y-%m-%d %H:%i:%s') As DATETIME FROM tb_member where Username = 'ADMIN';");
        if ($select->num_rows > 0) {
            $DateInfo = $select->fetch_array(MYSQLI_ASSOC);
            //get new order
            $newOrder = $conn->query( "SELECT COUNT(*) As OrdNo FROM tb_order where OrderDatetime >= '".$DateInfo['DATETIME']."' AND Status = 'pending'");
            if ($newOrder->num_rows > 0) {
                $OderInfo = $newOrder->fetch_array(MYSQLI_ASSOC);
                $outp ='"'.$OderInfo['OrdNo'].'"';
            }
            //get new message 
            $newMessage = $conn->query( "SELECT COUNT(*) As MsgNo FROM tb_enquiry where Datetime >= '".$DateInfo['DATETIME']."'");
            if ($newMessage->num_rows > 0) {
                $MsgInfo = $newMessage->fetch_array(MYSQLI_ASSOC);
                $outp =$outp.",".'"'.$MsgInfo['MsgNo'].'"';
            }
            //get new member 
            $newmember = $conn->query( "SELECT COUNT(*) As MemNo FROM tb_member where (JoinDate >= '".$DateInfo['DATETIME']."' and Username != 'ADMIN')");
            if ($newmember->num_rows > 0) {
                $MemInfo = $newmember->fetch_array(MYSQLI_ASSOC);
                $outp =$outp.",".'"'.$MemInfo['MemNo'].'"';
            }
            echo '['.$outp.']';     
        } else {
            echo "Missing Admin?";
        }
    }
    if(isset($_POST['type']) && $_POST['type'] == "timeline" ){
        $outp = "";
        $select = $conn->query("SELECT DATE_FORMAT(tb_member.JoinDate, '%Y-%m-%d %H:%i:%s') As DATETIME FROM tb_member where Username = 'ADMIN';");
        if ($select->num_rows > 0) {
            $DateInfo = $select->fetch_array(MYSQLI_ASSOC);
            //SELECT tb_order.OrderDatetime, tb_member.username FROM tb_order join tb_member on tb_order.Email = tb_member.Email;
            $newOrder = $conn->query( "SELECT tb_order.OrderDatetime, tb_member.username FROM tb_order join tb_member on tb_member.Email = tb_order.Email where OrderDatetime >= '".$DateInfo['DATETIME']."' and Status='pending'");
            if ($newOrder->num_rows > 0) {
                while($OderInfo = $newOrder->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"datetime":"' . $OderInfo["OrderDatetime"] . '",';
                    $outp .= '"username":"' . $OderInfo["username"] . '",';
                    $outp .= '"status":"order",';
                    $outp .= '"message":" placed an order."}';
                }
            }
            //get new message 
            $newMessage = $conn->query( "SELECT Name, Message, Datetime FROM tb_enquiry where Datetime >= '".$DateInfo['DATETIME']."'");
            if ($newMessage->num_rows > 0) {
                while($MsgInfo = $newMessage->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"datetime":"' . $MsgInfo["Datetime"] . '",';
                    $outp .= '"username":"' . $MsgInfo["Name"] . '",';
                    $outp .= '"status":"message",';
                    $outp .= '"message":" drop a message: ' . $MsgInfo["Message"] . '"}';
                }
            }
            //get new member 
            $newmember = $conn->query( "SELECT Username,JoinDate FROM tb_member where JoinDate >= '".$DateInfo['DATETIME']."' and Username != 'ADMIN'");
            if ($newmember->num_rows > 0) {
                while($MemInfo = $newmember->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"datetime":"' . $MemInfo["JoinDate"] . '",';
                    $outp .= '"username":"' . $MemInfo["Username"] . '",';
                    $outp .= '"status":"member",';
                    $outp .= '"message":" has signed up."}';
                }
            }
            echo '['.$outp.']';
//            echo "why";
        }
    }
    if(isset($_POST['type']) && $_POST['type'] == "order"){
        if(isset($_POST['filterby']) && isset($_POST['value'])){
            $outp = "";
            if($_POST['filterby'] == "OrderDatetime"){ 
                $sql = "SELECT * ,tb_member.username FROM tb_order join tb_member on tb_member.Email = tb_order.Email where DATE_FORMAT(tb_order.".$_POST['filterby'].", '%d/%m/%Y')='".$_POST['value']."'";
            } 
            else{
                $sql = "SELECT * ,tb_member.username FROM tb_order join tb_member on tb_member.Email = tb_order.Email where tb_order.".$_POST['filterby']."='".$_POST['value']."'";
            }
            $Order = $conn->query($sql);
            if($Order->num_rows > 0){
                while($OderInfo = $Order->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"datetime":"' . $OderInfo["OrderDatetime"] . '",';
                    $outp .= '"username":"' . $OderInfo["username"] . '",';
                    $outp .= '"orderid":"' . $OderInfo["OrderID"] . '",';
                    $outp .= '"total":' . $OderInfo["Total"] . ',';
                    $outp .= '"status":"'. $OderInfo["Status"] .'"}';
                }
                echo '['.$outp.']';
            }else{
               echo "NoData!";
            }
        }
    }
    if(isset($_POST['type']) && $_POST['type'] == "orderD"){
        $outp = "";
        $sql = "SELECT * FROM tb_order join tb_orddetail on tb_order.OrderID = tb_orddetail.OrderID WHERE tb_order.OrderID='".$_POST['value']."'";
        $Order = $conn->query($sql);
        if($Order->num_rows > 0){
            while($OderInfo = $Order->fetch_array(MYSQLI_ASSOC)) {
                if ($outp != "") {
                    $outp .= ",";
                }
                $outp .= '{"orderid":"' . $OderInfo["OrderID"] . '",';
                $outp .= '"email":"' . $OderInfo["Email"] . '",';
                $outp .= '"status":"' . $OderInfo["Status"] . '",';
                $outp .= '"total":' . $OderInfo["Total"] . ',';
                $outp .= '"fname":"' . $OderInfo["FirstName"] . '",';
                $outp .= '"lname":"' . $OderInfo["LastName"] . '",';
                $outp .= '"add":"' . $OderInfo["StreetAdd"] . '",';
                $outp .= '"suburb":"' . $OderInfo["Suburb"] . '",';
                $outp .= '"postcode":"' . $OderInfo["Postcode"] . '",';
                $outp .= '"city":"' . $OderInfo["City"] . '",';
                $outp .= '"state":"' . $OderInfo["State"] . '",';
                $outp .= '"country":"' . $OderInfo["Country"] . '",';
                $outp .= '"phoneno":"' . $OderInfo["PhoneNo"] . '",';
                $outp .= '"no":"'. $OderInfo["OrderNo"] .'",';
                $outp .= '"item":"'. $OderInfo["ItemName"] .'",';
                $outp .= '"qty":'. $OderInfo["ItemQty"] .',';
                $outp .= '"price":'. $OderInfo["ItemPrice"] .'}';
               
            }
            echo '['.$outp.']';
        }else{
           echo "NoData!";
        }
    }
    if(isset($_POST['type']) && $_POST['type'] == "message"){
        if(isset($_POST['filterby']) && isset($_POST['value'])){
            $outp = "";
            if($_POST['filterby'] == "Datetime"){ 
                $sql = "SELECT * FROM tb_enquiry where DATE_FORMAT(".$_POST['filterby'].", '%d/%m/%Y')='".$_POST['value']."'";
            } 
            else{
                $sql = "SELECT * FROM tb_enquiry where ".$_POST['filterby']."='".$_POST['value']."'";
            }
            $Order = $conn->query($sql);
            if($Order->num_rows > 0){
                while($OderInfo = $Order->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"datetime":"' . $OderInfo["Datetime"] . '",';
                    $outp .= '"no":' . $OderInfo["No"] . ',';
                    $outp .= '"username":"' . $OderInfo["Name"] . '",';
                    $outp .= '"email":"' . $OderInfo["Email"] . '",';
                    $outp .= '"message":"' . $OderInfo["Message"] . '",';
                    $outp .= '"status":"'. $OderInfo["Status"] .'"}';
                }
                echo '['.$outp.']';
            }else{
               echo "NoData!";
            }
        } 
    }
    if(isset($_POST['type']) && $_POST['type'] == "orderUpdate"){
        if(isset($_POST['tableof']) && isset($_POST['value'])){
            if ($_POST['tableof'] == "tb_enquiry"){
                $checkNo = "SELECT * FROM ".$_POST['tableof']." where No = ".$_POST['value']."";
            }else{
                $checkNo = "SELECT * FROM ".$_POST['tableof']." where OrderID = '".$_POST['value']."'";
            }
            $result = $conn->query($checkNo);
            if ($result->num_rows > 0) { 
                if ($_POST['tableof'] == "tb_enquiry"){
                    $update = "UPDATE ".$_POST['tableof']." SET Status = '".$_POST['status']."' WHERE No=".$_POST['value']."";
                }else{
                    $update = "UPDATE ".$_POST['tableof']." SET Status = '".$_POST['status']."' WHERE OrderID='".$_POST['value']."'";
                }	
                if ($conn->query($update) === TRUE) {
                     echo "Updated Status Successfully!";
                } else {
                    echo "Error: " . $update . "<br>" . $conn->error;
                }
            }else{
                echo "Something Wrong!";
            }
        } 
    }
    if(isset($_POST['type']) && $_POST['type'] == "member"){
        if(isset($_POST['filterby']) && isset($_POST['value'])){
            $outp = "";
            if($_POST['filterby'] == "JoinDate"){ 
                $sql = "SELECT * FROM tb_member where DATE_FORMAT(".$_POST['filterby'].", '%d/%m/%Y')='".$_POST['value']."' and username != 'ADMIN' order by JoinDate";
            } 
            else if($_POST['filterby'] == "no"){
                $sql = "SELECT * FROM tb_member where username != 'ADMIN' order by JoinDate";
            }
            else{
                $sql = "SELECT * FROM tb_member where ".$_POST['filterby']."='".$_POST['value']."' and username != 'ADMIN' order by JoinDate";
            }
            $Order = $conn->query($sql);
            if($Order->num_rows > 0){
                $no = 1;
                while($OderInfo = $Order->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"no":' . $no . ',';
                    $outp .= '"username":"' . $OderInfo["Username"] . '",';
                    $outp .= '"Email":"' . $OderInfo["Email"] . '",';
                    $outp .= '"FirstName":"' . $OderInfo["FirstName"] . '",';
                    $outp .= '"LastName":"' . $OderInfo["LastName"] . '",';
                    $outp .= '"PhoneNo":"'. $OderInfo["PhoneNo"] .'"}';
                    $no = $no + 1;
                }
                echo '['.$outp.']';
            }else{
               echo "NoData!";
            }
        }
    }
    if(isset($_POST['type']) && $_POST['type'] == "box"){
        if(isset($_POST['filterby']) && isset($_POST['value'])){
            $outp = "";
            if($_POST['filterby'] == "Status2"){ 
                $sql = "SELECT * FROM tb_product where Status !='".$_POST['value']."'";
            } else{
                $sql = "SELECT * FROM tb_product where ".$_POST['filterby']." LIKE '%".$_POST['value']."%'";
//                $sql = "SELECT * FROM tb_product where ".$_POST['filterby']."='".$_POST['value']."'";
            }
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                while($Info = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($outp != "") {
                        $outp .= ",";
                    }
                    $outp .= '{"ItemID":"' . $Info["ItemID"] . '",';
                    $outp .= '"ItemName":"' . $Info["ItemName"] . '",';
                    $outp .= '"ItemDesc":"' . $Info["ItemDesc"] . '",';
                    $outp .= '"ItemPrice":' . $Info["ItemPrice"] . ',';
                    $outp .= '"ItemPath":"' . $Info["ItemImgPath"] . '",';
                    $outp .= '"Status":"' . $Info["Status"] . '"}';
                }
                echo '['.$outp.']';
            }else{
               echo "NoData!";
            }
        }
    }
    if(isset($_POST['type']) && $_POST['type'] == "editboxD"){
         if(isset($_POST['ItemID'])){
            $result = $conn->query( "SELECT * FROM tb_product where ItemID ='".$_POST['ItemID']."'");
            if ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
            $outp ='"'.$rs['ItemName'].'"'.",".'"'.$rs['ItemDesc'].'"'.",".''.$rs['ItemPrice'].''.",".'"'.$rs['Status'].'"'.",".'"'.$rs['ItemImgPath'].'"';
               echo '['.$outp.']';
            } else {
                echo "Something Wrong." . " " .$result . " " ."?";
            }
         }
    }
        
    if(isset($_POST['type']) && $_POST['type'] == "addbox"){
        if(isset($_POST['Name']) && isset($_POST['Desc'])&& isset($_POST['Price'])&& isset($_POST['Status'])){
            if($_POST['Path']!=""){
                $GenID = "SELECT COUNT(*) AS ItemID from tb_product";
                $Genresult = $conn->query($GenID);
                $Info = $Genresult->fetch_array(MYSQLI_ASSOC);
                if($Info['ItemID'] > 0){
                    $ID = sprintf('%06d', $Info['ItemID'] + 1);
                }else{
                    $ID = "000001";
                }
                $sql = "INSERT INTO tb_product (ItemID, ItemName, ItemDesc, ItemPrice, ItemImgPath, AddDate, Status, Datetime)VALUES('".$ID."','".$_POST['Name']."','".$_POST['Desc']."',".$_POST['Price'].",'".$_POST['Path']."',now(),'".$_POST['Status']."',now())";
                if ($conn->query($sql) === TRUE) {
                    echo "Add SnackBox successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }else{
                echo "Please choose your image path!";
            }
        }
    }
    if(isset($_POST['type']) && $_POST['type'] == "editbox"){
        if(isset($_POST['Name']) && isset($_POST['Desc'])&& isset($_POST['Price'])&& isset($_POST['Status'])&& isset($_POST['ItemID'])){
            if($_POST['Path']!=""){
                $checkBox = "SELECT * FROM tb_product where ItemID = '".$_POST['ItemID']."'";
                $result = $conn->query($checkBox);
                if ($result->num_rows > 0) { //insert to details
                   $update = "UPDATE tb_product SET ItemName= '".$_POST['Name']."',ItemDesc='".$_POST['Desc']."',ItemPrice=".$_POST['Price'].",Status = '".$_POST['Status']."',ItemImgPath='".$_POST['Path']."',Datetime=now() WHERE ItemID='".$_POST['ItemID']."'";			
                    if ($conn->query($update) === TRUE) {
                        echo "Edit SnackBox successfully!";
                    } else {
                        echo "Error: " . $update . "<br>" . $conn->error;
                    }  
                }
                else{
                    echo "Something wrong!";
                }
            }else{
                $checkBox = "SELECT * FROM tb_product where ItemID = '".$_POST['ItemID']."'";
                $result = $conn->query($checkBox);
                if ($result->num_rows > 0) { //insert to details
                   $update = "UPDATE tb_product SET ItemName= '".$_POST['Name']."',ItemDesc='".$_POST['Desc']."',ItemPrice=".$_POST['Price'].",Status = '".$_POST['Status']."',Datetime=now() WHERE ItemID='".$_POST['ItemID']."'";			
                    if ($conn->query($update) === TRUE) {
                        echo "Edit SnackBox successfully!";
                    } else {
                        echo "Error: " . $update . "<br>" . $conn->error;
                    }  
                }
                else{
                    echo "Something wrong!";
                }
            }
        }
    }

    if(isset($_POST['type']) && $_POST['type'] == "removebox"){
        if(isset($_POST['ItemID'])){
            $update = "UPDATE tb_product SET Status = 'Expired',Datetime=now() WHERE ItemID='".$_POST['ItemID']."'";			
            if ($conn->query($update) === TRUE) {
                echo "Remove SnackBox successfully!";
            } else {
                echo "Error: " . $update . "<br>" . $conn->error;
            }
        }
    }
	$conn->close();
?>