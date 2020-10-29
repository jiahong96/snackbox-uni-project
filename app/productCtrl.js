app.controller("productCtrl", function($scope,$http,$rootScope,toaster) {
    $http.get('php/product.php')
    .then (
        function(response) {
            $scope.posts = response.data;
        },
        function(response) {
            $scope.msg = "Service not Exists";
        });
    
    $scope.ToCart = function(ID){ 
        if($rootScope.loggedIn == null || $rootScope.loggedIn == ""){
            toaster.pop('warning', "Hi", "Please Sign In First", 15000);   
        }else{
            var url = "php/order.php";
            var data = $.param({ProdID: ID, username:$rootScope.loggedIn});
            var config = {
                headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
            $http.post(url, data, config)
            .then(function (response) { //First function handles success
                $scope.msg = response.data;
                if($scope.msg =="Add to Cart successfully"){
                    toaster.pop('success', "Info", $scope.msg, 5000); 
                }else{
                    toaster.pop('warning', "WARNING", response.data, 5000);
                }
            }, function (response) {    //Second function handles error
                $scope.msg = "Service not Exists";
            });
        }
    };
});