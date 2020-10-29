<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    if(isset($_POST['username']))
    {
        $validate = "SELECT * FROM tb_member where Username ='".$_POST['username']."'";
        $result = $conn->query($validate);
        if ($result->num_rows > 0) {
            $update = "UPDATE tb_member SET JoinDate = '".date("Y-m-d H:i:s")."' WHERE Username='".$_POST['username']."'";			
            if ($conn->query($update) === TRUE) {
                 echo "Logout Successfully!";
            } else {
                echo "Error: " . $update . "<br>" . $conn->error;
            }
        } else {
            echo "Invalid Username" . " " .$validate . " " ."?";
        }
    }
	$conn->close();
?>