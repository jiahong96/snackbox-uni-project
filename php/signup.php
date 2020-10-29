<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    if(isset($_POST['email']) || isset($_POST['pass']) || isset($_POST['username']))
    {
        $email=$_POST['email'];
		$pass=md5($_POST['pass']);
        $username=$_POST['username'];
        $validate = "SELECT * FROM tb_member where Email = '".$email."'";
        $result = $conn->query($validate);
        if ($result->num_rows > 0) {
            echo "This Email are sign up before!";
        }else{
           $validate = "SELECT * FROM tb_member where Username = '".$username."'";
            $result = $conn->query($validate);
            if ($result->num_rows > 0) {
                echo "Username already taken";
            }else{
                $sql = "INSERT INTO tb_member(Email, Username, Password, JoinDate) 
                VALUES('". strtolower($email) ."','". $username ."','" . $pass ."','".date("Y-m-d H:i:s")."');";
                if ($conn->query($sql) === TRUE) {
                    echo "Sign Up successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        } 
    }
	$conn->close();
?>