app.controller("contactCtrl", function ($scope,$http,toaster) {
    $scope.pageClass = 'page-contact';
	$scope.submit = function(name,email,phone,message){
        var url = "php/contact.php";
        var data = $.param({name: name, email: email, phone:phone, message:message});
        var config = {headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then(function (response) { //First function handles success
            $scope.msg = response.data;
            if($scope.msg=="Message Send successfully"){
                toaster.pop('success', "Info", $scope.msg, 10000);
                $scope.name = "";
                $scope.email = "";
                $scope.phone = "";
                $scope.message = "";
                $scope.sentMessage.$setPristine();
                $scope.sentMessage.$setUntouched();
            }
        }, function (response) {    //Second function handles error
            $scope.msg = "Service not Exists";
        });
	}
});