var cn = angular.module('cn', []);


cn.config(function ($routeProvider) {
	$routeProvider
		.when('/notes/create',
		{	
			controller: 'NoteCreate',
			templateUrl: '/views/notes/create.html'	
		})
		.when('/notes/browse',
		{	
			controller: 'NoteBrowse',
			templateUrl: '/views/notes/browse.html'	
		})
		.when('/login',
		{
			controller: 'LoginController',
			templateUrl: '/views/useraccount/login.html'	
		})
		.when('/signup',
		{
			controller: 'SignupController',
			templateUrl: '/views/useraccount/signup.html'	
		})
		.when('/forgotpassword',
		{
			controller: 'ForgotPasswordController',
			templateUrl: '/views/useraccount/forgotpassword.html'	
		})
		.when('/notes/update/:id',
		{
			controller: 'NoteUpdateController',
			templateUrl: '/views/notes/edit.html'	
		})
		.when('/notes/show/:id',
		{
			controller: 'NoteShowController',
			templateUrl: '/views/notes/show.html'	
		})
		.otherwise({ redirectTo: '/login'});
});


cn.controller('NoteBrowse', function($scope, $http){
	
	$http
		.get('/views/notes/getAllNote.php')
		.success(function(data){
			console.log(data);
			$scope.notes = data;
		});

	$scope.delete = function(id){
		$scope.loading = true;
		$http
			.get('/notes/destroy/' + id)
			.success(function(){
				for (var i = 0 ; i < $scope.notes.length; i++)
				{
					console.log($scope.notes[i])
					if (id == $scope.notes[i].id)
					{
						
						$scope.notes.splice(i,1);
						break;
					}
				}
				$scope.loading = false;
			})
	};



});

cn.controller('NoteCreate', function($scope, $http, $location){

	$scope.create= function(note){
		note.tag = "x";
		$http
			.post('notes/create', note)
			.success(function(){
				$location.path('/notes/browse');
			});
	};
});

cn.controller('LoginController', function($scope, $location, $http){
	$scope.usernames = [
		{username: 'bhaskar', password: 'bhaskar', email: 'bhaskar@gmail.com'},
		{username: 'ashok', password: 'ashok', email: 'ashok@gmail.com'},
		{username: 'xyz', password: 'password', email: 'xyz@gmail.com'}
	];


	$scope.authenticate = function(login){
		var flag = 0;
		$http
			.post('user/login', login)
			.success(function(data, status){
				$location.path('/notes/create');
			})
			.error(function(errorMessage, status, headers, config){
				if (status = 401)
				{
					$scope.isError = true;
				}
			});
	};
});

cn.controller('SignupController', function($scope, $location, $http){
	$scope.usernames = [
		{username: 'bhaskar', password: 'bhaskar', email: 'bhaskar@gmail.com'},
		{username: 'ashok', password: 'ashok', email: 'ashok@gmail.com'},
		{username: 'xyz', password: 'password', email: 'xyz@gmail.com'}
	];

	$scope.create = function(signup){
		$http
			.post('/user/create',signup)
			.success(function(){
				$location.path('/login');
			});
	};
});

cn.controller('ForgotPasswordController', function($scope){
	$scope.usernames = [
		{username: 'bhaskar', password: 'bhaskar', email: 'bhaskar@gmail.com'},
		{username: 'ashok', password: 'ashok', email: 'ashok@gmail.com'},
		{username: 'xyz', password: 'password', email: 'xyz@gmail.com'}
	];

	$scope.regenerate = function(email){
		var email_exist = 0;

		for (var i=0 ; i < $scope.usernames.length; i++)
		{
			if (email == $scope.usernames[i].email)
			{
				email_exist = 1;
				console.log('Your Password is ' + $scope.usernames[i].password);
				break;
			}
		}

		if (email_exist == 0)
		{
			alert('Email Not Exist');
		}
	};
});

cn.controller('NoteUpdateController', function($scope, $routeParams, $http, $location){
	$http
		.get('/notes/show/' + $routeParams.id)
		.success(function(data){
			$scope.note = data.notes;
		});

	$scope.update = function(note){
		$http
			.put('/notes/update/' + note.id,note)
			.success(function(){
				$location.path('/notes/browse')
			});
	};
});

cn.controller('NoteShowController', function($scope, $routeParams, $http, $location){
	$http
		.get('/notes/show/' + $routeParams.id)
		.success(function(data){
			$scope.note = data.notes;
		});
});




