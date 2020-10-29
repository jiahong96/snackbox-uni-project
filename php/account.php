<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    if(isset($_POST['type']) && $_POST['type'] == "update" ){
        if(isset($_POST['username']) || isset($_POST['firstname']) || isset($_POST['lastname']) || isset($_POST['mobile'])){
            $validate = "SELECT * FROM tb_member where Username ='".$_POST['username']."'";
            $result = $conn->query($validate);
            if ($result->num_rows > 0) {
                $update = "UPDATE tb_member SET FirstName = '".$_POST['firstname']."',  
                LastName = '".$_POST['lastname']."',PhoneNo = '".$_POST['mobile']."'
                WHERE Username='".$_POST['username']."'";			
                if ($conn->query($update) === TRUE) {
                     echo "Update Successfully!";
                } else {
                    echo "Error: " . $update . "<br>" . $conn->error;
                }
            } else {
                echo "Invalid Username." . " " .$validate . " " ."?";
            }
        }
    }
    if(isset($_POST['type']) && $_POST['type'] == "password" ){
        if(isset($_POST['username']) || isset($_POST['currentP']) || isset($_POST['confirmP'])){
            $validate = "SELECT * FROM tb_member where Username ='".$_POST['username']."'";
            $result = $conn->query($validate);
            if ($result->num_rows > 0) {
                $UserInfo = $result->fetch_array(MYSQLI_ASSOC);
                if ($UserInfo['Password']== md5($_POST['currentP'])){
                    $update = "UPDATE tb_member SET Password = '".md5($_POST['confirmP'])."'
                    WHERE Username='".$_POST['username']."'";			
                    if ($conn->query($update) === TRUE) {
                         echo "Password Changed Successfully!";
                    } else {
                        echo "Error: " . $update . "<br>" . $conn->error;
                    }
                }
                else{
                    echo "Current Password is wrong!";
                }
            }else {
                echo "Invalid Username." . " " .$validate . " " ."?";
            }
        }
    }

    if(isset($_POST['type']) && $_POST['type'] == "order" ){
        $checkNo = "SELECT * FROM tb_order where OrderID = '".$_POST['OrderId']."'";
        $result = $conn->query($checkNo);
        if ($result->num_rows > 0) { 
            $update = "UPDATE tb_order SET FirstName = '".$_POST['FirstName']."', 
            LastName = '".$_POST['LastName']."',PhoneNo = '".$_POST['Phone']."',
            StreetAdd = '".$_POST['StreetAdd']."',Suburb = '".$_POST['Suburb']."',Postcode = '".$_POST['Postcode']."',
            City = '".$_POST['City']."',Country = '".$_POST['Country']."', Status = 'pending',
            State = '".$_POST['State']."',Total = ".$_POST['Total'].",
            OrderDatetime = now() WHERE OrderID='".$_POST['OrderId']."'";			
            if ($conn->query($update) === TRUE) {
                 echo "Order placed Successfully!";
            } else {
                echo "Error: " . $update . "<br>" . $conn->error;
            }
        }else{
            echo "Something Wrong!";
        }
    }

	$conn->close();
?>