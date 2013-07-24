<!DOCTYPE html>
<html ng-app="cn">
<head>
	<title>Cloud Note</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/flat-ui.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('stylesheets/style.css') }}">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span4">
				<h2>Cloud Note</h2>
				
				<div class="todo mrm">
                    <div class="todo-search">
                        <input class="todo-search-field" type="search" value="" placeholder="Search" ng-model="search">
                    </div>
                    <ul>
                        <li id="buttonNewNote">
                            <div class="todo-icon fui-new"></div>
                            <div class="todo-content">
                                <h4 class="todo-name">
                                   <a href="#/notes/create">Create New</a>
                                </h4>
                                Write new note
                            </div>
                        </li>

                        <li id="buttonBrowse">
                            <div class="todo-icon fui-list"></div>
                            <div class="todo-content">
                                <h4 class="todo-name">
                                   <a href="#/notes/browse">Browse Notes</a>
                                </h4>
                                Browse all stored notes
                            </div>
                        </li>
                        <li id="buttonReport">
                            <div class="todo-icon fui-radio-unchecked"></div>
                            <div class="todo-content">
                                <h4 class="todo-name">
                                   <a href="">Reports</a>
                                </h4>
                                Application Reports
                            </div>
                        </li>

                        <li id="buttonLogout">
                            <div class="todo-icon fui-lock"></div>
                            <div class="todo-content">
                                <h4 class="todo-name">
                                    <a href="#/login">Logout</a>
                                </h4>
                                Exit from the application
                            </div>
                        </li>
                    </ul>
                 
                </div>

			</div>

			<div class="span8" ng-view>
    			
			</div>
		</div>
	</div>
   
	<script src="js/angular.min.js"></script>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/app.js"></script>
 
</body>
</html>