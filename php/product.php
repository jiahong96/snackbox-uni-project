<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
	$result = $conn->query("SELECT * FROM tb_product WHERE Status = 'Active';");

	$outp = "";
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "") {
            $outp .= ",";
        }
		$outp .= '{"ID":"' . $rs["ItemID"] . '",';
        $outp .= '"Name":"' . $rs["ItemName"] . '",';
        $outp .= '"Desc":"' . $rs["ItemDesc"] . '",';
        $outp .= '"Path":"' . $rs["ItemImgPath"] . '",';
        $outp .= '"Price":'  . $rs["ItemPrice"] . '}'; 
	}
	$outp ='['.$outp.']';
	$conn->close();

	echo($outp);
?> 

    