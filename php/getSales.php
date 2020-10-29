<?php
//    Author  : Shereen (100065180)
//    Purpose : LSS-SnackBox
    include('dbconnect.php');
	$result = $conn->query("SELECT sum(if(month(OrderDatetime) = 1, Total, 0)) AS Jan, sum(if(month(OrderDatetime) = 2, Total, 0)) AS Feb, sum(if(month(OrderDatetime) = 3, Total, 0)) AS Mar, sum(if(month(OrderDatetime) = 4, Total, 0)) AS Apr, sum(if(month(OrderDatetime) = 5, Total, 0)) AS May, sum(if(month(OrderDatetime) = 6, Total, 0)) AS Jun, sum(if(month(OrderDatetime) = 7, Total, 0)) AS Jul, sum(if(month(OrderDatetime) = 8, Total, 0)) AS Aug, sum(if(month(OrderDatetime) = 9, Total, 0)) AS Sep, sum(if(month(OrderDatetime) = 10, Total, 0)) AS Oct, sum(if(month(OrderDatetime) = 11, Total, 0)) AS Nov, sum(if(month(OrderDatetime) = 12, Total, 0)) AS 'Dec' FROM tb_order WHERE Status = 'pending' GROUP BY month(OrderDatetime)");
    if ($result->num_rows > 0) {
        $rs = $result->fetch_array(MYSQLI_ASSOC);
        echo "[".$rs['Jan'].",".$rs['Feb'].",".$rs['Mar'].",".$rs['Apr'].",".$rs['May'].",".$rs['Jun'].",".$rs['Jul'].",".$rs['Aug'].",".$rs['Sep'].",".$rs['Oct'].",".$rs['Nov'].",".$rs['Dec']."]";
    }
	$conn->close();
?> 

    