<?php
    include('dbconnect.php');
    if(isset($_POST['name']) || isset($_POST['email']) || isset($_POST['phone']) || isset($_POST['message']))
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message']; 
        $sql = "INSERT INTO tb_enquiry(Email, Name, PhoneNo, Message, Status,Datetime) 
        VALUES('".$email."','".$name."','".$phone."','".$message."','New',now());";
        if ($conn->query($sql) === TRUE) {
            // Create the email and send the message
//            $to = 'thirteen_233@hotmail.com'; 
//            $subject = "Website Contact Form:  $name";
//            $message = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email\n\nPhone: $phone\n\nMessage:\n$message";
//            $headers = 'From: thirteen_233@hotmail.com'."\n". 'Reply-To:'. $email."";	
//            mail($to,$subject,$message,$headers);
            echo "Message Send successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }			
?>