app.controller("accountCtrl", function($scope,$http,$rootScope,toaster,$location) {
    $scope.getAcc =function(){
        //MyAccount
        var url = "php/getInfo.php";
        var data = $.param({username:  $rootScope.loggedIn, getInfo: 'account' });
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            //$scope.infos= response.data;
            var str = response.data;
            $scope.firstname = str[0];
            $scope.lastname = str[1];
            $scope.email = str[2];
            $scope.mobile = str[3];
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
        //Order History
        var url = "php/getInfo.php";
        var data = $.param({username:  $rootScope.loggedIn, getInfo: 'order'});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.odr= response.data;
            if ($scope.odr == "Empty!"){
                $scope.msgO = "You haven't purchased anything from the LSS-Snackbox marketplace!";
                $scope.odr = "";
            }
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    //My Cart
    $scope.getAcc();
    $scope.getCart = function(){
        var url = "php/getInfo.php";
        var data = $.param({username:  $rootScope.loggedIn, getInfo: 'cart'});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
            $scope.odrD = response.data;
            if ($scope.odrD == "Empty!"){
                $scope.msgC = "Your Shopping Cart is empty!";
                $scope.odrD = "";
            }else{
                $scope.getTotal();
            }
        },
        function(response) {
            //$scope.msg = "Service not Exists";
        });
    }
    
    $scope.getCart();
    $scope.updateQty = function(OrderID,OrderNo,qty){
        var url = "php/getInfo.php";
        var data = $.param({username:  $rootScope.loggedIn, OrderID:OrderID, OrderNo:OrderNo, qty:qty, getInfo: 'updateQty'});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
                $scope.msg = response.data;
                toaster.pop('success', "Info", $scope.msg, 5000); 
                $scope.msg = "";
                $scope.getCart();
                $scope.getTotal();
            },
            function(response) {
                //$scope.msg = "Service not Exists";
            });
    }
    $scope.RemoveItem = function(OrderID,OrderNo){
        var url = "php/getInfo.php";
        var data = $.param({username:  $rootScope.loggedIn, OrderID:OrderID, OrderNo:OrderNo, getInfo: 'RemoveItem'});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then (function(response) {
                $scope.msg = response.data;
                toaster.pop('success', "Info", $scope.msg, 5000); 
                $scope.getCart();
                $scope.getTotal();
            },
            function(response) {
                //$scope.msg = "Service not Exists";
            });
    }
    
    $scope.getTotal = function(){
        var total = 0;
        for(var i = 0; i < $scope.odrD.length; i++){
            total += ($scope.odrD[i].ItemPrice * $scope.odrD[i].ItemQty);
            $scope.OrderId = $scope.odrD[i].OrderID;
        }
        $scope.total = total;
    }

    $scope.order = function(OrderId, fname, lname, address, suburb, city, state, postcode, country, phone,total){
        var url = "php/account.php";
        var data = $.param({type: 'order', OrderId: OrderId, FirstName: fname, LastName:lname,StreetAdd: address, Suburb: suburb, City: city, State: state, Postcode:postcode, Country: country, Country: country, Phone: phone, Total:total});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then(function (response) { //First function handles success
            $scope.msg = response.data;
            if($scope.msg=="Order placed Successfully!"){
                toaster.pop('success', "Info", $scope.msg, 5000); 
                $scope.getCart();
                $scope.getTotal();
                $scope.clear2();
                $('#PlaceOrderpModal').modal('hide');
            }
        }, function (response) {    //Second function handles error
            $scope.msg = "Service not Exists";
        });
    }
    $scope.clear2 = function(){
        $scope.fname = "";
        $scope.lname = "";
        $scope.address = "";
        $scope.suburb = "";
        $scope.city = "";
        $scope.state = "";
        $scope.postcode = "";
        $scope.country = "";
        $scope.phone = "";
        $scope.msg = "";
        $scope.orderForm.$setPristine();
        $scope.orderForm.$setUntouched();
	}
    $scope.shop = function(){
        $location.path("/product");
    }
    $scope.enable = function(){
        $scope.all = true;
        $scope.update = true;
        $scope.msg = "";
    }
    $scope.disable = function(){
        $scope.all = false;
        $scope.update = false;
    }
    
	$scope.submit = function(loggedIn,firstname,lastname,mobile){
        var url = "php/account.php";
        var data = $.param({type: 'update', username: loggedIn, firstname: firstname, lastname:lastname, mobile:mobile});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then(function (response) { //First function handles success
            $scope.msg = response.data;
            if($scope.msg=="Update Successfully!"){
                toaster.pop('success', "Info", $scope.msg, 5000);
                $scope.msg = "";
                $scope.disable();
                $scope.getAcc();
            }
        }, function (response) {    //Second function handles error
            $scope.msg = "Service not Exists";
        });
	}
    
    $scope.clear = function(){
        $scope.currentP = "";
        $scope.confirmP = "";
        $scope.newP = "";
        //$scope.msg = "";
        $scope.PassForm.$setPristine();
        $scope.PassForm.$setUntouched();
	}
    
    $scope.updateP = function(loggedIn,currentP,confirmP){
        var url = "php/account.php";
        var data = $.param({type: 'password', username: loggedIn, currentP: currentP, confirmP:confirmP});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then(function (response) { //First function handles success
            $scope.msg = response.data;
            if($scope.msg=="Password Changed Successfully!"){
                toaster.pop('success', "Info", $scope.msg, 5000);
                $scope.msg = "";
                $scope.clear();
            }
        }, function (response) {    //Second function handles error
            $scope.msg = "Service not Exists";
        });
	}
});
