<!DOCTYPE html>
    <head>
        <title>API Tester</title>

		<link
			href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" 
			rel="stylesheet"  type="text/css" media="screen" title="no title" charset="utf-8">
		
		<script type="text/javascript" charset="utf-8"
			src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" charset="utf-8" 
			src="https://raw.github.com/twitter/bootstrap/master/docs/assets/js/bootstrap.min.js"></script>
			
		<script type="text/javascript" charset="utf-8">
		
			// Returns HTML for new form field
			var get_new_form_field = function(fieldname){
				return [
					'<div class="control-group">',
						'<label class="control-label">'+ fieldname +'</label>',
						'<div class="controls">',
							'<input type="text" name="'+ fieldname +'"/>',
							'<a href="#" class="remove-field">&times;</a>',
						'</div>',
					'</div>'
				].join("");
			};
			
			
			var hide_response = function(){
				$("#response, #response-header").hide();
			};
			
			
			var show_response_header = function(r){
				$("#response-header").find("pre").html(r.getAllResponseHeaders());
				$("#response-header").show();
			};
			
			
			var show_response = function(r){
				response = JSON.stringify(r,null,"\t");
				response = response.replace(/&/g, "&amp;")
									.replace(/</g, "&lt;")
									.replace(/>/g, "&gt;")
									.replace(/\\u000a/g," ");

				$("#response").find("pre").html(response);
				$("#response").show();
				
				show_response_header(r);
			};
			
			
			var show_error = function(r){
				response = strip(r.responseText);
				$("#response").find("pre").html(response);
				$("#response").show();
				
				show_response_header(r);
			};
			
			
			var strip = function(html){
	           var tmp = document.createElement("DIV");
	           tmp.innerHTML = html;
	           return tmp.textContent||tmp.innerText;
	        };
			
			
			
			
			var bind_dom_event = function(){
				
				$(document).delegate("#api-form a.remove-field", "click", function(e){
					e.preventDefault();
	                $(this).closest("div.control-group").remove();
	            });


	            $(document).delegate("#api-form a.remove-fields", "click", function(e){
	                e.preventDefault();
	                $("#form-fields").html("");
	            });
	
	
				$(document).delegate("#api-list a.simulate", "click", function(e){
	                e.preventDefault();

	                var self = $(this),
	                	fields       = self.attr("data-fields"),
						link         = self.attr("href"),
						requestType  = self.attr("request-type"),
						notes		 = self.attr("notes");

					$("#url").val(link);
					$("#type").val(requestType);

					if(notes == undefined){
						notes = '';
					}
					$("#notes").html(notes);

	                $("#form-fields").html("");
	                $("#response").html("");

	                $.each(fields.split(","), function(k, v){
	                    var fieldName = $.trim(v);

	                    if(fieldName != ""){
	                    	$("#form-fields").append(
								get_new_form_field(fieldName)
							);
	                    }                    
	                });                                 
	            });
	
	
				// Add new field
	            $(document).delegate("#new-field-form", "submit", function(e){
					e.preventDefault();

					var self = $(this),
						fields = self.find("input").val();

	                $.each(fields.split(","), function(k, v){
	                    var fieldName = $.trim(v);

	                    if(fieldName != ""){
	                    	$("#form-fields").append(
								get_new_form_field(fieldName)
							);
	                    }                   
					});
	            });
	
	
				/**
				 * Make API Request
				 */
	            $(document).delegate("#api-form", "submit", function(e){
					e.preventDefault();
					
	                var self = $(this),
	                    data = {},
						$button = self.find("button");

	                $.each(self.serialize().split("&"), function(k, v){
	                    var keyval  = v.split("="),
	                    	isArray = false;

	                    if(keyval[0].indexOf("%5B%5D") != -1){
	                        isArray = true;
	                    }

	                    keyval[0] = keyval[0].replace(/%5B%5D/g,"");

	                    if(isArray){
	                        if(data[keyval[0]]){
	                        	data[keyval[0]].push(unescape(keyval[1]));
	                        }
	                        else{
	                        	data[keyval[0]] = [unescape(keyval[1])];
	                        }
	                    }
	                    else{
	                        data[keyval[0]] = unescape(keyval[1]);                        
	                    }
	                });

	                var type    = data.type,
	                	baseurl = $.trim(data.baseurl),
	                    url     = $.trim(data.url);

	                delete data.type;
	                delete data.baseurl;                
	                delete data.url;

					
					$button.attr("disabled", "disabled").html("Please wait...");
					hide_response();

					$.ajax({
						type: type,
						url: decodeURIComponent(baseurl + url),
						data: data,
						success: function(r){
							$button.removeAttr("disabled").html("Make Request");
							show_response(r);
							//stringify the JSON o/p and display
						},
						error: function (r, status, error) {
							$button.removeAttr("disabled").html("Make Request");
							show_error(r);
						},
						dataType: "json"
					});
	            });
				
				
			}();
		
		
			$(document).ready(function(){
				// Initialize Accordian
				$("#api-list").collapse();
	        });
		</script>
		
		<style type="text/css" media="screen">
			.container{
				margin-top: 50px;
				font-family: "Lucida Grande", Tahoma, Verdana, Arial;
			}
			
			.spinner{
				display: none;
			}
			
			#api-form label{
				font-size: 12px;
				text-transform: capitalize;
			}
			
			#form-fields{
				margin-top: 10px;
				padding-top: 10px;
			}
			
			.remove-field{
				margin-left: 20px;
				font-weight: bold;
			}
			
			.remove-field:hover{
				text-decoration: none;
				opacity: 0.7;
			}
			
			#response,
			#response-header{
				display: none;
			}
		</style>

    </head>
    <body>
        <div class="container">
			<div class="row">
				
				<div class="span3">
					
					<div class="accordion" id="api-list">
						
						<div class="accordion-group">
					    	<div class="accordion-heading">
					      		<a class="accordion-toggle" 
									data-toggle="collapse" data-parent="#api-list" href="#user-apis">
					        		User
					      		</a>
					    	</div>
					    	<div id="user-apis" class="accordion-body collapse in">
					      		<div class="accordion-inner">
									<ol>
			            				<!-- login -->
			            				<li>
											<a href="signup" 
											   class="simulate" 
											   request-type="POST" 
											   data-fields="cell_number">
											   	Signup
											</a>            					
			            				</li>
			            				<!-- Logout -->
			            				<li>
											<a href="logout" 
											   class="simulate"
											   request-type="GET" 
											   data-fields="">
											   	Logout
											</a>            					
			            				</li>            				
			            				<!-- Registration -->
			            				<li>
											<a href="verify" 
											   class="simulate"
											   request-type="POST" 
											   data-fields="cell_number,verification_code">
											   	Verify
											</a>            					
			            				</li>            					
		        					</ol>
					      		</div>
					    	</div>
					  	</div>
					
						<div class="accordion-group">
					    	<div class="accordion-heading">
					      		<a class="accordion-toggle" 
									data-toggle="collapse" data-parent="#api-list" href="#friend-apis">
					        		User
					      		</a>
					    	</div>
					    	<div id="friend-apis" class="accordion-body collapse">
					      		<div class="accordion-inner">
									<ol>
			            				<!-- login -->
			            				<li>
											<a href="session/login" 
											   class="simulate" 
											   request-type="POST" 
											   data-fields="email,password">
											   	Login
											</a>            					
			            				</li>
			            				<!-- Logout -->
			            				<li>
											<a href="session/logout" 
											   class="simulate"
											   request-type="GET" 
											   data-fields="">
											   	Logout
											</a>            					
			            				</li>            				
			            				<!-- Registration -->
			            				<li>
											<a href="session/register" 
											   class="simulate"
											   request-type="POST" 
											   data-fields="family_title,name,email,password">
											   	Registration
											</a>            					
			            				</li>            					
		        					</ol>
					      		</div>
					    	</div>
					  	</div>
					</div>
					
					
				</div><!-- /end sidebar -->
				
				<div class="span9">
					
					<div class="well">
						<form id="api-form" class="form-horizontal">
							<div class="control-group">
								<label class="control-label">Base URL</label>
								<div class="controls">
									<input type="text" name="baseurl" value="http://localhost:8000/" 
										class="span6"/>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">Request Endpoint</label>
								<div class="controls">
									<select name="type" id="type" 
											class="span2" style="margin-right: 15px;">
										<option value="GET">GET</option>
		                                <option value="POST">POST</option>
										<option value="PUT">PUT</option>
		                                <option value="DELETE">DELETE</option>
		                            </select>
		
									<input type="text" name="url" id="url" 
										class="span4"/>
								</div>
							</div>


							<div id="form-fields"></div>
							
							<div class="form-actions">
								<button class="btn btn-primary">Make Request</button>
			                    <a href="javascript:void(0);" class="remove-fields">Remove all fields</a>
							</div>
		                </form>
		
						<hr>
		
						<form id="new-field-form" class="form-inline">
		                    <input type="text" placeholder="Field name" />
		                    <button class="btn">Add these fields</button>
		                    <p class="hint-block text-info">
		                    	To generate fields in bulk, enter comma separated list of field names.
								<em>e.g. field1, field2</em>
		                    </p>
		                    
		                </form>
					</div>
					
					
					<div id="response">
						<h6>Response</h6>
						<pre></pre>
					</div>
					<div id="response-header">
						<h6>Response Header</h6>
						<pre></pre>
					</div>
					
				</div>
				
			</div><!-- /end row -->
        </div> <!-- /end container -->
    </body>
</html>