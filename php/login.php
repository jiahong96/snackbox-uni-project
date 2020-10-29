<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    if(isset($_POST['email']) || isset($_POST['pass']))
    {
        $email=$_POST['email'];
		$pass=md5($_POST['pass']);
        
        $validate = "SELECT * FROM tb_member where (Email = '".$email."' or Username ='".$email."' )and Password = '".$pass."'";
        $result = $conn->query($validate);
        if ($result->num_rows > 0) {
            $update = "UPDATE tb_member SET LastestLogin = '".date("Y-m-d H:i:s")."' 
            WHERE (Email='".$_POST['email']."' or Username='".$_POST['email']."')";			
            if ($conn->query($update) === TRUE) {
                 echo "Sign In Successfully!";
            } else {
                echo "Error: " . $update . "<br>" . $conn->error;
            }
        } else {
            echo "Invalid Email or Password.";
        }
    }
	$conn->close();
?>