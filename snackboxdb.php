<?php
	$myServer = "localhost"; 
	$User = "root";
    $Pass = "";
	$Database = "snackboxdb";
    //CREATE DATABASE IF NOT EXISTS $Database;
	//Connect to MySQL Database
	$Link = mysql_connect($myServer,$User,$Pass) or die (mysql_error());
	if($Link)
	{
		if(mysql_select_db($Database))
		{
			CreateTable($Link);
		}
		else //Database not exist
		{
			$CreateDb = "CREATE DATABASE ".$Database;
			mysql_query($CreateDb);
			mysql_select_db($Database);
			CreateTable($Link);
		}
	}
	else
	{
		echo "Fail to connect with MySql Database Server";
	}
	
	function CreateTable($Link)
	{
        $tableList = array("tb_member", "tb_product","tb_order","tb_ordDetail","tb_enquiry");
		$TableQuery = array(
	/*tb_member*/	       
            "CREATE TABLE tb_member(
                Email VARCHAR(64) NOT NULL, 
                Username VARCHAR(12) NOT NULL, 
                Password VARCHAR(32) NOT NULL, 
                FirstName VARCHAR(40),
                LastName VARCHAR(40),
                PhoneNo VARCHAR(12), 
                JoinDate DATETIME,
                LastestLogin DATETIME,
                PRIMARY KEY (Email))",
    /*tb_product*/	
            "CREATE TABLE tb_product(
                ItemID VARCHAR(12) NOT NULL, 
                ItemName VARCHAR(40) NOT NULL,
                ItemDesc VARCHAR(40) NOT NULL,
                ItemPrice DOUBLE NOT NULL, 
                ItemImgPath VARCHAR(64),
                AddDate DATE,
                Status VARCHAR(10),
                Datetime DATETIME,
                PRIMARY KEY (ItemID))",
	/*tb_order*/	
            "CREATE TABLE tb_order(
                OrderID VARCHAR(12) NOT NULL, 
                Email VARCHAR(64) NOT NULL,
                Total DOUBLE,
                Status VARCHAR(10),
                FirstName VARCHAR(40),
                LastName VARCHAR(40),
                StreetAdd VARCHAR(40),
                Suburb VARCHAR(40),
                Postcode VARCHAR(5),
                City VARCHAR(20),
                State VARCHAR(20),
                Country VARCHAR(20),
                PhoneNo VARCHAR(12), 
                OrderDatetime DATETIME,
                PRIMARY KEY (OrderID))",
    /*tb_ordDetail*/	
            "CREATE TABLE tb_ordDetail(
                OrderID VARCHAR(12) NOT NULL, 
                OrderNo INTEGER NOT NULL,  
                ItemID VARCHAR(12) NOT NULL,
                ItemName VARCHAR(40) NOT NULL,
                ItemPrice DOUBLE NOT NULL,
                ItemQty INTEGER NOT NULL,
                ItemTotal DOUBLE NOT NULL,
                PRIMARY KEY (OrderID, OrderNo),
	            FOREIGN KEY (OrderID) REFERENCES tb_order(OrderID))",         
	/*tb_enquiry*/ 
            "CREATE TABLE tb_enquiry(
                No BIGINT PRIMARY KEY AUTO_INCREMENT, 
                Email VARCHAR(64), 
                Name VARCHAR(40), 
                PhoneNo VARCHAR(12),
                Message VARCHAR(1000),
                Status CHAR(1),
                Datetime DATETIME)");
		$TableListing = "SHOW TABLES";
		$TableResult = mysql_query($TableListing);
		for($i=0 ; $i < mysql_num_rows($TableResult) ; $i++)
		{
			$TableRow = mysql_fetch_array($TableResult);
			$MyTableList[] = $TableRow['Tables_in_snackboxdb'];
		}
		
		//check the existing table in my database;
		for($i=0 ; $i<count($tableList) ; $i++)
		{
			if(mysql_num_rows($TableResult) > 0)
			{
				if(!(in_array($tableList[$i],$MyTableList)))
					mysql_query($TableQuery[$i]);
			}
			else
					mysql_query($TableQuery[$i]);
		}
		//Create temporary account "Administrator"
		$CheckAcc = "SELECT * FROM tb_member WHERE Email = 'admin'";
		$CheckResult = mysql_query($CheckAcc);
		if(mysql_num_rows($CheckResult) == 0)
		{
			$AddUser = "INSERT INTO tb_member (Email, Username, Password, PhoneNo, JoinDate, LastestLogin) VALUES ('admin@gmail.com','ADMIN','".md5("password")."','0164885233',DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s'),DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s'))";
            mysql_query($AddUser);
            
            $AddSnack = "INSERT INTO tb_product (ItemID, ItemName, ItemDesc, ItemPrice, ItemImgPath, AddDate, Status, Datetime) VALUES ('000001','SnackBox-Malaysia','Snacks from Malaysia',80,'snackbox.png',now(),'Active',now() )";
            mysql_query($AddSnack);
            
            $AddSnack = "INSERT INTO tb_product (ItemID, ItemName, ItemDesc, ItemPrice, ItemImgPath, AddDate, Status, Datetime) VALUES ('000002','SnackBox-Thailand','Snacks from Thailand',80,'snackbox.png',now(),'Active',now() )";
            mysql_query($AddSnack);
		}
	}
?>
