var app = angular.module('myApp',["ngAnimate", "ngRoute", "toaster"]);
app.config(function($routeProvider){
    $routeProvider
    .when("/home",{
        templateUrl: "template/home.html",
        controller: "homeCtrl"   
        
    })
    .when("/about",{
        templateUrl: "template/about.html",
        controller: "aboutCtrl"
    })
    .when("/product",{
        templateUrl: "template/product.html",
        controller: "productCtrl"
        
    })
    .when("/contact",{
        templateUrl: "template/contact.html",
        controller: "contactCtrl"
      
    })
    .when("/mybag",{
        templateUrl: "template/myaccount.html",
        controller: "accountCtrl"
    })
    
    .when("/myaccount",{
        resolve:{
            "check":function($location, $rootScope){
                if(!$rootScope.loggedIn){   // is emtpy
                    $location.path('/home')
                }else if ($rootScope.loggedIn.toLowerCase() == "admin"){
                    $location.path('/dashboard')
                }
            }
        },
        templateUrl: "template/myaccount.html",
        controller: "accountCtrl"
    })
    .when("/dashboard",{
         resolve:{
            "check":function($location, $rootScope){
                if(!$rootScope.loggedIn){   // is emtpy
                    $location.path('/home')
                }else if ($rootScope.loggedIn.toLowerCase() == "admin"){
                    $location.path('/dashboard')
                }
            }
        },
        templateUrl: "template/dashboard.html",
        controller: "dashboardCtrl"
    })
    .otherwise({
        redirectTo: "home"
    });
});
app.controller("homeCtrl", function ($scope) {
    $scope.pageClass = 'page-home';
});
app.controller("aboutCtrl", function ($scope) {
    $scope.pageClass = 'page-about';
});
app.controller("SigninCtrl", function ($scope, $http, $location, $rootScope, toaster) {
    $scope.email = null;
    $scope.pass = null;
    $scope.checkLogin = function(email, pass){    // define methods
        var url = "php/login.php";
        var data = $.param({email: email, pass: pass});
        var config = {
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then(function (response) { //First function handles success
            $scope.msg = response.data;
            if($scope.msg=="Sign In Successfully!"){
                $scope.getUsername($scope.email);
                $('#mySignInModal').modal('hide');
                $scope.email = "";
                $scope.pass = "";
                $scope.LoginForm.$setPristine();
                $scope.LoginForm.$setUntouched();
                toaster.pop('success', "Info", "Welcom Back! ", 5000);
                $scope.msg = "";
            }
        }, function (response) {    //Second function handles error
            $scope.msg = "Service not Exists";
        });
    };
    
    $scope.getUsername = function(email){
        var url = "php/getUsername.php";
        var data = $.param({email: email});
        var config = {
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then(function (response) { //First function handles success
            $rootScope.loggedIn = response.data;
            $location.path("/myaccount");
        }, function (response) {    //Second function handles error
            $scope.msg = "Service not Exists";
        });
    };
    
});

app.controller("SignUpCtrl", function ($scope, $http, toaster) {
    $scope.email = null;
    $scope.pass = null;
    $scope.username = null;
    $scope.signup = function(email, pass, username){    // define methods
        var url = "php/signup.php";
        var data = $.param({email: email, pass: pass, username:username});
        var config = {
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
        $http.post(url, data, config)
        .then(function (response) { //First function handles success
            $scope.msg = response.data;
            if($scope.msg=="Sign Up successfully"){
                $scope.email = "";
                $scope.pass = "";
                $scope.username = "";
                $scope.SignUpForm.$setPristine();
                $scope.SignUpForm.$setUntouched();
                $('#mySignUpModal').modal('hide');
                $('#mySignInModal').modal('show');
                toaster.pop('success', "Info", $scope.msg, 5000); 
                $scope.msg = "";
            }
        }, function (response) {    //Second function handles error
            $scope.msg = "Service not Exists";
        });
    };
    
    $scope.reset = function(){
        $scope.email = "";
        $scope.pass = "";
        $scope.username = "";
        $scope.msg = "";
        $scope.SignUpForm.$setPristine();
        $scope.SignUpForm.$setUntouched();
    };
});

app.controller("myCtrl", function ($scope, $location, $http, $rootScope, toaster) {
	$scope.logout = function (){
        if ($rootScope.loggedIn == "ADMIN"){
            var url = "php/logout.php";
            var data = $.param({username: $rootScope.loggedIn});
            var config = {
                headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}
            $http.post(url, data, config)
            .then(function (response) { //First function handles success
                $scope.msg = response.data;
                if($scope.msg=="Logout Successfully!"){
                    $rootScope.loggedIn = null;
                    $location.path("/home");
                }
            }, function (response) {    //Second function handles error
                $scope.msg = "Service not Exists";
            });
        }
        else{
            $rootScope.loggedIn = null;
            $location.path("/home");
        }
	}
    $scope.cart = function(){
        $('#acc').removeClass('active');
        $('#Info').removeClass('fade in active');
        $('#cart').addClass('active');
        $('#MyBag').addClass('fade in active');
    }
    $scope.acc = function(){
        $('#cart').removeClass('active');
        $('#MyBag').removeClass('fade in active');
        $('#acc').addClass('active');
        $('#Info').addClass('fade in active');
    }
});

app.directive("pwCheck", function() {
    return {
        require: "ngModel",
        scope: {otherModelValue: "=pwCheck"}, 
        link: function(scope, element, attributes, ctrl) {
            ctrl.$validators.pwCheck = function(modelValue) {
                return modelValue == scope.otherModelValue;
            };
            scope.$watch("otherModelValue", function() {
                ctrl.$validate();
            });
        }
    };
});

app.directive("datepicker", function () {
  return {
    restrict: "A",
    require: "ngModel",
    link: function (scope, elem, attrs, ngModelCtrl) {
      var updateModel = function (dateText) {
        scope.$apply(function () {
          ngModelCtrl.$setViewValue(dateText);
        });
      };
      var options = {
        dateFormat: "dd/mm/yy",
        onSelect: function (dateText) {
          updateModel(dateText);
        }
      };
      elem.datepicker(options);
    }
  }
});

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_CN/sdk.js#xfbml=1&version=v2.6";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs'));