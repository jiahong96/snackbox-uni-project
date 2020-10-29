<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    if(isset($_POST['email']))
    {
        $email=$_POST['email'];
        $validate = "SELECT * FROM tb_member where (Email = '".$email."' or Username ='".$email."' )";
        $result = $conn->query($validate);
        if ($result->num_rows > 0) {
            $UserInfo = $result->fetch_array(MYSQLI_ASSOC);
            echo($UserInfo['Username']);
        }
    }
	$conn->close();
?>