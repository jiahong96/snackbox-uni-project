<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
	$result = $conn->query("SELECT sum(if(month(OrderDatetime) = 1, ItemQty, 0)) AS Jan, sum(if(month(OrderDatetime) = 2, ItemQty, 0)) AS Feb, sum(if(month(OrderDatetime) = 3, ItemQty, 0)) AS Mar, sum(if(month(OrderDatetime) = 4, ItemQty, 0)) AS Apr, sum(if(month(OrderDatetime) = 5, ItemQty, 0)) AS May, sum(if(month(OrderDatetime) = 6, ItemQty, 0)) AS Jun, sum(if(month(OrderDatetime) = 7, ItemQty, 0)) AS Jul, sum(if(month(OrderDatetime) = 8, ItemQty, 0)) AS Aug, sum(if(month(OrderDatetime) = 9, ItemQty, 0)) AS Sep, sum(if(month(OrderDatetime) = 10, ItemQty, 0)) AS Oct, sum(if(month(OrderDatetime) = 11, ItemQty, 0)) AS Nov, sum(if(month(OrderDatetime) = 12, ItemQty, 0)) AS 'Dec' FROM tb_order join tb_ordDetail on tb_order.OrderID = tb_ordDetail.OrderID WHERE tb_order.Status = 'pending' GROUP BY month(OrderDatetime)");
    if ($result->num_rows > 0) {
        $rs = $result->fetch_array(MYSQLI_ASSOC);
        echo "[".$rs['Jan'].",".$rs['Feb'].",".$rs['Mar'].",".$rs['Apr'].",".$rs['May'].",".$rs['Jun'].",".$rs['Jul'].",".$rs['Aug'].",".$rs['Sep'].",".$rs['Oct'].",".$rs['Nov'].",".$rs['Dec']."]";
    }
	$conn->close();
?> 

    