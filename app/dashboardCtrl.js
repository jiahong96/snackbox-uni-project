app.filter("offset", function() {
	return function(input, start) {
		start = parseInt(start, 10);
		return input.slice(start);
	};
});

app.controller("dashboardCtrl", function($scope,$http,$rootScope,toaster,$location,$interval,$filter) {
    $scope.datePicker = $filter('date')(new Date(), 'dd/MM/yyyy');
    $scope.callAtInterval = function() {
        //refer every 5s
        var url = "php/dashboard.php";
        var data = $.param({type: 'timeline' });
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.infos= response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $interval( function(){ $scope.callAtInterval(); }, 5000);
  
    $scope.menutoogle = function(){
        $('#wrapper').toggleClass('toggled');
    }
    $scope.getdashboard = function(){
        var url = "php/dashboard.php";
        var data = $.param({type: 'dashboard' });
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
//                $scope.infos= response.data;
                var str = response.data;
                $scope.order = str[0];
                $scope.message = str[1];
                $scope.user = str[2];
            },
            function(response) {
                //$scope.msg = "Service not Exists";
            });
    }
    $scope.getdashboard();
    $scope.callAtInterval();
 
    $scope.defaultFilter = function(){
        var url = "php/dashboard.php";
        var data = $.param({type: 'order',filterby: 'Status', value:'pending' });
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.ords= response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.filterorder = function(filterby,value){
        var url = "php/dashboard.php";
        var data = $.param({type: 'order',filterby: filterby, value: value});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            toaster.pop('warning', "WARNING", response.data, 5000);
            $scope.ords= response.data;
            $scope.datePicker = $filter('date')(new Date(), 'dd/MM/yyyy');
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.defaultFilter();
    $scope.updateDetails = function(ID){
        var url = "php/dashboard.php";
        var data = $.param({type: 'orderD',value: ID});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.ordDss= response.data;
            $scope.orderid = $scope.ordDss[0].orderid;
            $scope.email = $scope.ordDss[0].email;
            $scope.status = $scope.ordDss[0].status;
            $scope.total = $scope.ordDss[0].total;
            $scope.name = $scope.ordDss[0].fname + " " +$scope.ordDss[0].lname;
            $scope.add1 = $scope.ordDss[0].add;
            $scope.add2 = $scope.ordDss[0].suburb;
            $scope.add3 = $scope.ordDss[0].postcode+ " "+$scope.ordDss[0].city+ ","+$scope.ordDss[0].state +","+$scope.ordDss[0].country;
            $scope.phoneno = $scope.ordDss[0].phoneno;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
     $scope.defaultFilter2 = function(){
        var url = "php/dashboard.php";
        var data = $.param({type: 'message',filterby: 'Status', value:'N' });
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            //toaster.pop('warning', "WARNING", response.data, 5000);
            $scope.mgss= response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.filtermsg = function(filterby,value){
        var url = "php/dashboard.php";
        var data = $.param({type: 'message',filterby: filterby, value: value});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            //toaster.pop('warning', "WARNING", response.data, 5000);
            $scope.mgss= response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.defaultFilter2();
    $scope.updateStatus = function(table,value,status){
        var url = "php/dashboard.php";
        var data = $.param({type: 'orderUpdate',tableof: table, value: value, status:status});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.mgs= response.data;
            if($scope.mgs=="Updated Status Successfully!"){
                toaster.pop('success', "Update Status", response.data, 5000);
                $scope.defaultFilter();
                $scope.defaultFilter2();
            }
        },
        function(response) {
            $scope.msg = "Service not Exists";
        });
    }
    $scope.defaultFilter3= function(){
        var url = "php/dashboard.php";
        var data = $.param({type: 'member',filterby: 'no',value: 'no'});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
           // toaster.pop('warning', "WARNING", response.data, 5000);
            $scope.mem= response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.filtermem = function(filterby,value){
        var url = "php/dashboard.php";
        var data = $.param({type: 'member',filterby: filterby, value: value});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
           // toaster.pop('warning', "WARNING", response.data, 5000);
            $scope.mem= response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.defaultFilter3();
    $scope.defaultFilter4= function(){
        var url = "php/dashboard.php";
        var data = $.param({type: 'box',filterby: 'Status2',value: 'Expired'});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.box= response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.filterbox = function(filterby,value){
        var url = "php/dashboard.php";
        var data = $.param({type: 'box',filterby: filterby, value: value});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.box= response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.Display = "View";
   
    $scope.defaultFilter4();
    //prev{{n}}next
    $scope.noPerPage = 5;
    $scope.currentPage = 0;
    $scope.currentPageM = 0;
    $scope.currentPageS = 0;
    $scope.currentPageB = 0;
    $scope.range = function(table) { 
        if (table=="order"){
            if (Math.ceil($scope.ords.length/$scope.noPerPage)>5){
           var rangeSize = 5;
            }else{
                 var rangeSize = Math.ceil($scope.ords.length/$scope.noPerPage);
            }
            var ret = [];
            var start = $scope.currentPage;
        }else if (table=="message"){
            if (Math.ceil($scope.mgss.length/$scope.noPerPage)>5){
           var rangeSize = 5;
            }else{
                 var rangeSize = Math.ceil($scope.mgss.length/$scope.noPerPage);
            }
            var ret = [];
            var start = $scope.currentPageM;
        }else if (table=="member"){
            if (Math.ceil($scope.mem.length/$scope.noPerPage)>5){
                var rangeSize = 5;
            }else{
                var rangeSize = Math.ceil($scope.mem.length/$scope.noPerPage);
            }
            var ret = [];
            var start = $scope.currentPageS;
        }else if (table=="box"){
            if (Math.ceil($scope.box.length/$scope.noPerPage)>5){
                var rangeSize = 5;
            }else{
                var rangeSize = Math.ceil($scope.box.length/$scope.noPerPage);
            }
            var ret = [];
            var start = $scope.currentPageB;
        }
        if (start > $scope.pageCount(table) - rangeSize ) {
            start = $scope.pageCount(table) - rangeSize + 1;
        }
        for (var i=start; i<start + rangeSize; i++) {
            ret.push(i);
        }
        return ret;
    };
    $scope.prevPage = function(table) {
        if (table=="order"){
            if ($scope.currentPage > 0) {
                $scope.currentPage--;
            }
        }else if (table=="message"){
            if ($scope.currentPageM > 0) {
                $scope.currentPageM--;
            }
        }else if (table=="member"){
            if ($scope.currentPageS > 0) {
                $scope.currentPageS--;
            }
        }else if (table=="box"){
            if ($scope.currentPageB > 0) {
                $scope.currentPageB--;
            }
        }
    };
    $scope.prevPageDisabled = function(table) {
        if (table=="order"){
            return $scope.currentPage === 0 ? "disabled" : "";
        }else if (table=="message"){
            return $scope.currentPageM === 0 ? "disabled" : "";
        }else if (table=="member"){
            return $scope.currentPageS === 0 ? "disabled" : "";
        }else if (table=="box"){
            return $scope.currentPageB === 0 ? "disabled" : "";
        }
    };
    $scope.nextPage = function(table) {
        if (table=="order"){
            if ($scope.currentPage < $scope.pageCount('order')) {
                $scope.currentPage++;
            }
        }else if (table=="message"){
            if ($scope.currentPageM < $scope.pageCount('message')) {
                $scope.currentPageM++;
            }
        }else if (table=="member"){
            if ($scope.currentPageS < $scope.pageCount('member')) {
                $scope.currentPageS++;
            }
        }else if (table=="box"){
            if ($scope.currentPageB < $scope.pageCount('box')) {
                $scope.currentPageB++;
            }
        }
    };
    $scope.nextPageDisabled = function(table) {
        if (table=="order"){
            return $scope.currentPage === $scope.pageCount('order') ? "disabled" : "";
        }else if (table=="message"){
            return $scope.currentPageM === $scope.pageCount('message') ? "disabled" : "";
        }else if (table=="member"){
            return $scope.currentPageS === $scope.pageCount('member') ? "disabled" : "";
        }else if (table=="box"){
            return $scope.currentPageS === $scope.pageCount('box') ? "disabled" : "";
        }
    };

    $scope.pageCount = function(table) {
        if (table=="order"){
            return Math.ceil($scope.ords.length/$scope.noPerPage) - 1;
        }else if (table=="message"){
            return Math.ceil($scope.mgss.length/$scope.noPerPage) - 1;
        }else if (table=="member"){
            return Math.ceil($scope.mem.length/$scope.noPerPage) - 1;
        }else if (table=="box"){
            return Math.ceil($scope.box.length/$scope.noPerPage) - 1;
        }
    };
    $scope.setPage = function(n,table) { 
        if (table=="order"){
            $scope.currentPage = n; 
        }else if (table=="message"){
            $scope.currentPageM = n; 
        }else if (table=="member"){
            $scope.currentPageS = n; 
        }else if (table=="box"){
            $scope.currentPageB = n; 
        }
    };
});
app.controller("snackboxCtrl", function($scope,$http,$rootScope,toaster,$location,$interval,$filter) {
    $scope.Add = function(){
        $scope.Display = "Add";
        $scope.name = null;
        $scope.desc = null;
        $scope.price = null;
        $scope.status = "Active";
        document.getElementById("name").disabled = false;
        document.getElementById("desc").disabled = false;
        document.getElementById("price").disabled = false;
        document.getElementById("status").disabled = false;
        document.getElementById("path").disabled = false;
    }
    $scope.Remove = function(ItemID,ItemName,ItemDesc,ItemPrice,Status,Path){
        $scope.Display = "Remove";
        $scope.ItemID = ItemID;
        document.getElementById("nameR").value = ItemName;
        document.getElementById("descR").value = ItemDesc;
        document.getElementById("priceR").value = ItemPrice;
        document.getElementById("statusR").value = Status;
    }
    $scope.Edit = function(ItemID){
        $scope.Display = "Edit";
        var url = "php/dashboard.php";
        var data = $.param({type: 'editboxD',ItemID:ItemID});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            var str = response.data;//["SnackBox-Thailand","Snacks from Thailand",80,"OnGoing","snackbox.png"]
            $scope.ItemID = ItemID;
            document.getElementById("nameE").value = str[0];
            document.getElementById("descE").value = str[1];
            document.getElementById("priceE").value = str[2];
            document.getElementById("statusE").value = str[3];
            document.getElementById("pathE").value = str[4];
            $scope.msg =response.data;
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });         
    }
    $scope.cancel = function(){
        document.getElementById("AddForm").reset();
        $scope.Display = "View";
        $scope.AddForm.$setPristine();
        $scope.AddForm.$setUntouched();
    }
    $scope.cancelE = function(){
        $scope.Display = "View";
    }
    $scope.AddToDB = function(Name,Desc,Price,Status){
        var path = document.getElementById("path").value;
        //C:\fakepath\slide1.png
        var index = path.indexOf( "fakepath" );
        if (index > 0){
            var res = path.substring(12);
        }else{
             var res = path;
        }
        var url = "php/dashboard.php";
        var data = $.param({type: 'addbox',Name : Name,Desc: Desc,Price: Price,Status: Status,Path: res});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.msg= response.data;
            if($scope.msg=="Add SnackBox successfully"){
                $scope.Display = "View";
                $scope.defaultFilter4();
                document.getElementById("AddForm").reset();
                toaster.pop('success', "Info",  $scope.msg, 5000);
            }else if ($scope.msg =="Please choose your image path!"){
                toaster.pop('error', "Error",  $scope.msg, 5000);
            }
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    //EditToDB(ItemID,nameE,descE,priceE,statusE)
    $scope.EditToDB = function(ItemID,name,desc,price,status){
        var name = document.getElementById("nameE").value;
        var desc = document.getElementById("descE").value;
        var price = document.getElementById("priceE").value;
        var status = document.getElementById("statusE").value;
        var path = document.getElementById("pathE").value;
        var index = path.indexOf( "fakepath" );
        if (index > 0){
            var res = path.substring(12);
        }else{
             var res = path;
        }
        var url = "php/dashboard.php";
        var data = $.param({type: 'editbox',ItemID :ItemID,Name : name,Desc: desc,Price: price,Status: status,Path: res });
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.msg= response.data;
            if($scope.msg =="Edit SnackBox successfully!"){
                $scope.Display = "View";
                $scope.defaultFilter4();
                toaster.pop('success', "Info",  $scope.msg, 5000);
            }else{
                toaster.pop('success', "Info",  $scope.msg, 5000);
            }
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    $scope.RemoveDB = function(ItemID){
        var url = "php/dashboard.php";
        var data = $.param({type: 'removebox',ItemID :ItemID });
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.msg= response.data;
            toaster.pop('success', "Info",  $scope.msg, 5000);
            $scope.Display = "View";
            $scope.defaultFilter4();
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    
});
app.controller("salesCtrl", function($scope,$http) {
     $http.get('php/getSales.php')
    .then (
        function(response) {
            $scope.sales = response.data;
        },
        function(response) {
           // $scope.msg = "Service not Exists";
        });
    $http.get('php/getQtySales.php')
    .then (
        function(response) {
            $scope.qtysales = response.data;
        },
        function(response) {
           // $scope.msg = "Service not Exists";
        });
    $http.get('php/getQtyItem.php')
    .then (
        function(response) {
            $scope.qtyItem = response.data;
        },
        function(response) {
           // $scope.msg = "Service not Exists";
        });
    
    
    $scope.callChart = function(){
        Chart.defaults.global.responsive = true;
        // pie chart data
        $http.get('php/getQtyItem.php')
        .then (
            function(response) {
                $scope.qtyItem = response.data;
            },
            function(response) {
               // $scope.msg = "Service not Exists";
            });
        var pieData = $scope.qtyItem;
        var pieOptions = {
            segmentShowStroke : false,
            animateScale : true
        }
        var snackbox= document.getElementById("snackbox").getContext("2d");
        new Chart(snackbox).Pie(pieData, pieOptions);
        $http.get('php/getQtySales.php')
        .then (
            function(response) {
                $scope.qtysales = response.data;
            },
            function(response) {
               // $scope.msg = "Service not Exists";
            });
        
        // bar chart data
        var barData = {
            labels : ["Jan","Feb","Mar","Apr","May","Jun","Aug","Sep","Oct","Nov","Dec"],
            datasets : [
                {
                    fillColor : "#48A497",
                    strokeColor : "#48A4D1",
                    data :$scope.qtysales
                }
            ]
        }
        var income = document.getElementById("income").getContext("2d");
        new Chart(income).Bar(barData);
        
        $http.get('php/getSales.php')
        .then (
            function(response) {
                $scope.sales = response.data;
            },
            function(response) {
               // $scope.msg = "Service not Exists";
            });
        
        // line chart data
        var SalesData = {
            labels : ["Jan","Feb","Mar","Apr","May","Jun","Aug","Sep","Oct","Nov","Dec"],
            datasets : [
            {
                fillColor : "rgba(172,194,132,0.4)",
                strokeColor : "#ACC26D",
                pointColor : "#fff",
                pointStrokeColor : "#9DB86D",
                data:$scope.sales
            }
        ]
        }
        var sales = document.getElementById('salesbymonth').getContext('2d');
        new Chart(sales).Line(SalesData);
    }


});