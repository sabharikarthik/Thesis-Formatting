<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<style>
body{
background-image:url("green1.jpg");
background-repeat:no-repeat;
margin:auto;
}
.sub_div
{
width:500px;
height:500px;	
float:left;
position:relative;
margin-right:-25%;
margin-left:5%;
}
.login
{
	
	width:850px;
	height:550px;
	margin-left:20%;
	margin-top:5%;
	background-color:rgba(255,255,255,0.5);
	
}

input[type="text"],input[type="password"],input[type="button"],select
{
	width:255px;
	height:40px;
	top:40px;
	left:70px;
	position:relative;
	margin-top:15px;
	border:1px solid #1e85ae;
	text-align:center;
	font-size:15px;
}
input[type="radio"]
{
	top:40px;
	width:20px;
	height:17px;
	left:70px;
	position:relative;
	margin-top:15px;
	border:1px solid #1e85ae;
	text-align:center;
	font-size:15px;
}
input[type="button"]
{
background-color:#1e85ae;
border:2px solid transparent;	
}	
input[type="button"]:hover
{
	background-color:white;
	border:2px solid #1e85ae;
}
.loginform label
{
	
	top:40px;
	left:70px;
	position:relative;
	margin-top:15px;
	text-align:center;
	font-size:20px;
	font-weight:bold;
}
.login h2{
	text-align:center;
	top:40px;
	margin-left:-12%;
	font-size:30px;
	position:relative;
}
</style>
</head>
<body>
<div class="login">
<div class="sub_div">
<h2>LOGIN</h2>
<div class="loginform">
<input type="text" id="login_name" name="username" placeholder="ENTER USER-NAME"/>
</div>
<!-- <div class="loginform">
<input type="password" id="login_password" name="password" placeholder="ENTER PASSWORD"/>
</div> -->
<div class="loginform">
<table>
<tr>
<th><input type="radio" name="select" id="create_select" onchange="visible_create()"/>
<label for="select">CREATE</label></th>
<th>
<input type="radio" name="select" id="reload_select" onchange="visible_reload()"/>
<label for="select">RELOAD</label>
</th>
</tr>
</table>
</div>
<div class="loginform">
<input type="text" name="thesis_name" placeholder="Enter Thesis FileName" id="thesis_name" style="display:none"/>
</div>

<div class="loginform">
<select name="search_name"  id="search_name"></select>
</div>


<div class="loginform">
<input type="button" name="login" value="LOGIN" onclick="login_click()" id="login"/>
</div>
</div>

<div class="sub_div" id="signup">
<h2>SIGN UP</h2>
<div class="signupform">
<!-- <input type="text" name="firstname" id="name" placeholder="ENTER NAME">
</div>
<div class="signupform">
<input type="text" name="email" id="email" placeholder="ENTER E-MAIL">
</div>
<div class="signupform">
<input type="text" name="phno" id="phno" placeholder="ENTER PHONE NUMBER">
</div> -->
<div class="signupform">
<input type="text" name="username" id="username" placeholder="ENTER USER-NAME">
</div>
<!-- <div class="signupform">
<input type="password" name="password" id="password" placeholder="ENTER PASSWORD">
</div> -->
<div class="signupform">
<input type="button" name="login" value="REGISTER" id="signup" onclick="register()">
</div>
</div>
</div>
</body>
<script>

$('#search_name').append($('<option>', {
    value: 1,
    text: 'My option'
}));

var create=document.getElementById("thesis_name");
	create.style.display="none";
	var search=document.getElementById("search_name");
	search.style.display="none";

//$.ajax({
//			url:"new.php",
//			type:"POST",
	//		data:{"login_user":"1","thesis_name":thesis_name,"login_name":login_name,"login_password":login_password},
		//	success:function(data)
			//{
				//alert(data);
				//if(data=="Login Successfully")
				//{
					//    if(option=="create")
					//	{
					//	window.location="theses.php";
					//	}
					//	if(option=="reload")
					//	{
					//		
					//			window.location="theses.php?search_name="+search_name;
					//	}
						
					    
				//}
				//else
				//{
				//	window.location="index.php";
				//}
			//}
			
			
	//	});
		
		
function login_click()
{
	var login_name=document.getElementById("login_name").value;
	var login_password=document.getElementById("login_password").value;
	var thesis_name=document.getElementById("thesis_name").value;
	var search_name=document.getElementById("search_name").value;
	
	if(document.getElementById("create_select").checked==true)
	{
		var option="create";
	}
	if(document.getElementById("reload_select").checked==true)
	{
		var option="reload";
	}
	$.ajax({
			url:"new.php",
			type:"POST",
			data:{"login_user":"1","thesis_name":thesis_name,"login_name":login_name,"login_password":login_password},
			success:function(data)
			{
				alert(data);
				if(data=="Login Successfully")
				{
					    if(option=="create")
						{
						window.location="theses.php";
						}
						if(option=="reload")
						{
							
								window.location="theses.php?search_name="+search_name;
						}
						
					    
				}
				else
				{
					window.location="index.php";
				}
			}
			
			
		});
		
}

function visible_create()
{
	var create=document.getElementById("thesis_name");
	create.style.display="block";
	var search=document.getElementById("search_name");
	search.style.display="none";

}
function visible_reload()
{
	var create=document.getElementById("search_name");
	create.style.display="block";

	var search=document.getElementById("thesis_name");
	search.style.display="none";
}
function register()
{
	    var name=document.getElementById("name").value;
		var email=document.getElementById("email").value;
		var phone_no=document.getElementById("phno").value;
		var user_name=document.getElementById("username").value;
		var user_password=document.getElementById("password").value;
		$.ajax({
			url : 'new.php',
			type: 'POST',
			data: { "register_user": "1","name":name,"email":email,"phone_no":phone_no,"user_name":user_name,"user_password":user_password},
			dataType: 'text',
			success : function(data){
			alert(data);
			window.location="create_thesis.php";
			}	
		});
	
}		
</script>
</html>