<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
    function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    function getRandomColor() {
        return random_color_part() . random_color_part() . random_color_part();
    }

//    function getRandomColor() {
//        var letters = '0123456789ABCDEF'.split('');
//        var color = '#';
//        for (var i = 0; i < 6; i++ ) {
//            color += letters[Math.floor(Math.random() * 16)];
//        }
//        return color;
//    }

	$result = $conn->query("SELECT ItemName,sum(ItemQty) AS QTY FROM tb_order join tb_ordDetail on tb_order.OrderID = tb_ordDetail.OrderID WHERE tb_order.Status = 'pending' GROUP BY tb_ordDetail.ItemID");
    if ($result->num_rows > 0) {
        $outp = "";
        while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
            if ($outp != "") {
                $outp .= ",";
            }  
            $outp .= '{"label":"' . $rs["ItemName"].'",';
            $outp .= '"value":' . $rs["QTY"] . ',';
            $outp .= '"highlight":"#'.getRandomColor().'",';
            $outp .= '"color":"#'.getRandomColor().'"}';
        }
        $outp ='['.$outp.']';

        echo $outp;
    }else{
        echo "WHy no data!";
    }
    $conn->close();
?> 