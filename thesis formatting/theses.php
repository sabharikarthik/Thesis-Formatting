<?php
session_start();
$_SESSION['t_array'] =array(); 
$_SESSION['e_array'] = array(array());
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<script>

function clear()
{
	var divEle = document.getElementById("dynamic_space");
	divEle.innerHTML = "";
}
function add_title()
{
	var htmlString = "<table><tr><th>TITLE</th><th><div><input id = 'title_name' type='text'/></div></th><th><div><button onclick = 'add_t()'>add</button></div></th></tr></table>";
	var divEle = document.getElementById("dynamic_space");
	divEle.innerHTML = htmlString;
}

function add_t()
{
	
	var position = document.getElementById("previous_selection").value;
	var title = document.getElementById("title_name");
	var title_name = title.value;
	if (title !== null && title_name !== "")
	{
		var position1 = -1;
		var existingHTML = document.getElementById("word_reflector");
		var existingWord = document.getElementById("html_for_word");
		console.log(title_name);
		console.log(position);
		console.log(position1);
		$.ajax({
               url : 'new.php',
               type: 'POST',
			   data: { "add_element":"1", "element":title_name,"position1":position,"position2":position1},
               dataType: 'text',
               success : function(data){
				  // console.log(data);
					clear();
				
					var htmlcontent = data.split("~");
						existingHTML.innerHTML = htmlcontent[0];
						existingWord.innerHTML = htmlcontent[1];
						var data=htmlcontent[2];
						alert("title added successfully");
			   },
			   error: function(data){
        alert('fail');
		}});
	}
	if(title_name == "")
		alert("Enter title name");
}
function add_para()
{
	var htmlString = "<table><tr><th>TITLE:</th><th><input id='para_name' type='text'/></th></tr><tr><th>CONTENT:</th><th><textarea id='para_content' rows='4' cols='50'></textarea></th></tr><tr><th></th><th><button onclick = 'add_p()'>add</button></th></tr></table>";
	var divEle = document.getElementById("dynamic_space");
	divEle.innerHTML = htmlString;
}
function add_p()
{
	var position = document.getElementById("previous_selection").value;
	var index_of_splitter = position.indexOf("_");
	var position1, position2;
	if(index_of_splitter != -1)
	{
		position1 = position.slice(0,index_of_splitter);
		position2 = position.slice(index_of_splitter+1);
	}
	else
	{
		position1 = position;
		position2 = "-1";
	}
	var para = document.getElementById("para_name");
	var para_name = para.value;
	var content = document.getElementById("para_content").value;
	var add_numbering = "ny";
	if(!(document.getElementById('add_numbering').checked))
		add_numbering = "nn";
	if (para !== null && (para_name !== "" || content !== ""))
	{
		var existingHTML = document.getElementById("word_reflector");
		var existingWord = document.getElementById("html_for_word");
		console.log(para_name);
		console.log(position1);
		console.log(position2);
		$.ajax({
               url : 'new.php',
               type: 'POST',
			   data: { "add_para": "1", "para_name":para_name,"para_content":content,"position1":position1,"position2":position2,"add_numbering":add_numbering},
               dataType: 'text',
               success : function(data){
				  // console.log(data);
				   clear();
						var htmlcontent = data.split("~");
						existingHTML.innerHTML = htmlcontent[0];
						existingWord.innerHTML = htmlcontent[1];
						alert("paragraph added successfully");
			   },
			   error: function(data){
        alert('fail');
		}});
	}
	if(para_name == "" && content == "")
		alert("Enter either paragraph title or content or both");
}
function add_diagram()
{
	var htmlString ="<div class='diagram'>TITLE:<input id='diagram_name' style='margin-top:1%;height:25px;width:140px;margin-right:1%' type='text'/><input type = 'file' style='margin-top:1%;margin-right:2%' id = 'pic' accept='image/*' /></div><div><label for='height'>HEIGHT:</label><input type='text' style='margin-top:3%;height:25px;width:140px;margin-left:4px;margin-right:2%' name='height' id='height_size' Placeholder='Enter between 1 to 10'/><label for='width'>WIDTH :</label><input type='text' id='width_size' name='width' style='height:25px;width:140px;margin-top:3%' Placeholder='Enter between 1 to 10'/></div><div><button style='margin-top:3%;height:30px;width:100px'  onclick = 'add_d()'>add</button></div>";  
	var divEle = document.getElementById("dynamic_space");
	divEle.innerHTML = htmlString;
}
function add_d()
{
	var position = document.getElementById("previous_selection").value;
	var index_of_splitter = position.indexOf("_");
	var position1, position2;
	if(index_of_splitter != -1)
	{
		position1 = position.slice(0,index_of_splitter);
		position2 = position.slice(index_of_splitter+1);
	
	}
	else
	{
		position1 = position;
		position2 = "-1";
	
	}
	
	var diagram = document.getElementById("diagram_name");
	var diagram_name = diagram.value;
	var diagram_path = document.getElementById("pic").value;
	var height_size=document.getElementById('height_size').value;
	var width_size=document.getElementById('width_size').value;
	var add_numbering = "ny";
	if(!(document.getElementById('add_numbering').checked))
		add_numbering = "nn";
	if (diagram !== null  && diagram_path !== "" && height_size!=="" && width_size!=="")
	{
		var preview = document.querySelector('img');
		var file    = document.querySelector('input[type=file]').files[0];
		var reader  = new FileReader();
		var existingHTML = document.getElementById("word_reflector");
		var existingWord = document.getElementById("html_for_word");
		console.log(diagram_name);
		console.log(position1);
		console.log(position2);

		reader.addEventListener("load", function () {
		var diagram_path= reader.result;
		$.ajax({
               url : 'new.php',
               type: 'POST',
			   data: { "add_diagram": "1", "diagram_name":diagram_name,"diagram_path":diagram_path,"height_size":height_size,"width_size":width_size,"position1":position1,"position2":position2,"add_numbering":add_numbering},
               dataType: 'text',
               success : function(data){
				  // console.log(data);
				   clear();
				   
				   
						var htmlcontent = data.split("~");
						existingHTML.innerHTML = htmlcontent[0];
						existingWord.innerHTML = htmlcontent[1];
						var size_check=htmlcontent[2].split("?");
						var data=size_check[0];
						var possible=size_check[1];
                        var k=size_check[2];
						var key=size_check[3];
						if(data=="old")
					    alert("diagram added successfully");
				        else
						{
							if(possible!="not_able")
							{
							if(confirm("New Page Do u want to add")==true)
							{
							    var info_for_resize=htmlcontent[3];
						        var resize=info_for_resize.split("?");
						        var element_type=resize[0];
						        var para_name=resize[1];
						        var content=resize[2];
						       	var height=possible;
								var width=possible;
								$.ajax({
									type: 'POST',
                                     url : 'new.php',
                                     data:{"resize_image":"1","element_type":element_type,"para_name":para_name,"content":content,"height":height,"width":width,"k":k,"key":key},
			                         success : function(data){
				                            
				                               clear();
											   alert(data);
									 },error: function(data){alert(data);}
								    });
	
							}
							else
							{
								alert("added successfully");
								
							}
							}
							else
							{
								alert("New Page Not able to edit");
							}
						   
						}
			   },
			   error: function(data){
        alert('fail');
		}});
		}, false);

		if (file) {
			reader.readAsDataURL(file);
		}
				   
	}
	if(diagram_path == "")
		alert("select any diagram");
	if(height_size=="" || width_size=="")
		alert("Select Size");
}
function expand()
{
	
	var s_ele = document.getElementById("previous_selection");
	var s_ele_value = s_ele.value;
	var pbutton = document.getElementById(s_ele_value+"pbutton");
	var dbutton = document.getElementById(s_ele_value+"dbutton");
	pbutton.style.visibility = "visible";
	dbutton.style.visibility = "visible";
}
function remove()
{
	if(confirm("You are removing element.Proceed?")== true)
	{
		var position = document.getElementById("previous_selection").value;
		var index_of_splitter = position.indexOf("_");
		var position1, position2;
		if(index_of_splitter != -1)
		{
			position1 = position.slice(0,index_of_splitter);
			position2 = position.slice(index_of_splitter+1);
		}
		else
		{
			position1 = position;
			position2 = "-1";
		}
		var existingHTML = document.getElementById("word_reflector");
		var existingWord = document.getElementById("html_for_word");
		console.log(position1);
		console.log(position2);
		$.ajax({
			url : 'new.php',
			type: 'POST',
			data: { "remove_element": "1","position1":position1,"position2":position2},
			dataType: 'text',
			success : function(data){
			//console.log(data);
			clear();
			var htmlcontent = data.split("~");
						existingHTML.innerHTML = htmlcontent[0];
						existingWord.innerHTML = htmlcontent[1];
			alert("element removed");
			},
			error: function(data){
			alert('fail');
			}});
	}
}
function edit()
{
	    var position = document.getElementById("previous_selection").value;
		var index_of_splitter = position.indexOf("_");
		var position1, position2;
		if(index_of_splitter != -1)
		{
			position1 = position.slice(0,index_of_splitter);
			position2 = position.slice(index_of_splitter+1);
		}
		else
		{
			position1 = position;
			position2 = "-1";
		}
		var existingHTML = document.getElementById("word_reflector");
		var existingWord = document.getElementById("html_for_word");
		console.log(position1);
		console.log(position2);
		$.ajax({
			url : 'new.php',
			type: 'POST',
			data: { "edit_element": "1","position1":position1,"position2":position2},
			dataType: 'text',
			success : function(data){
			
			},
			error: function(data){
			alert('fail');
			}});
	
}
function selection_change(ele)
{
	
	var ps_ele = document.getElementById("previous_selection");
	var ps_ele_value = ps_ele.value;
	if(ps_ele_value != -1)
	{
		var p_div = document.getElementById(ps_ele_value+"d");
		var p_radio_ele = document.getElementById(ps_ele_value+"r");
		var p_ebutton = document.getElementById(ps_ele_value+"ebutton");
		var p_rbutton = document.getElementById(ps_ele_value+"rbutton");
		var p_edbutton=document.getElementById(ps_ele_value+"edbutton");
		var p_pbutton = document.getElementById(ps_ele_value+"pbutton");
		var p_dbutton = document.getElementById(ps_ele_value+"dbutton");
		if(p_ebutton !== null)
		{
			p_ebutton.disabled = true;
			p_rbutton.disabled =true;
			p_edbutton.disabled=true;
			p_pbutton.style.visibility = "hidden";
			p_dbutton.style.visibility = "hidden";
			p_div.style.backgroundColor = "transparent";
		}
	}
	var div = document.getElementById(ele+"d");
	var radio_ele = document.getElementById(ele+"r");
	var ebutton = document.getElementById(ele+"ebutton");
	var rbutton = document.getElementById(ele+"rbutton");
	var edbutton=document.getElementById(ele+"edbutton");
	var pbutton = document.getElementById(ele+"pbutton");
	var dbutton = document.getElementById(ele+"dbutton");
	if(radio_ele.checked)
	{
		div.style.backgroundColor = "teal";
		ebutton.disabled = false;
		rbutton.disabled = false;
		edbutton.disabled=false;
		ps_ele.value = ele;
	}
}
function download_word()
{
	console.log("download word");
	$.ajax({
			url : 'new.php',
			type: 'POST',
			data: { "download_word": "1"},
			dataType: 'text',
			success : function(data){
				console.log(data);
				if(data == "1")
					alert("success");
				document.getElementById('download').click();
				
			},
			error: function(data){
				alert('fail');
			}});
}

function download_pdf()
{
	$.ajax({
			url : 'new.php',
			type: 'POST',
			data: { "download_PDF": "1","download_pdftrial":"download_pdftrial"},
			dataType: 'text',
			success : function(data){
				if(data == "1")
					document.getElementById('download').click();
			},
			error: function(data){
			alert('fail');
			}});
}
function savethesis()
{
	
	$.ajax({
		url:"new.php",
		type:"POST",
		data:{"save_thesis":"1"},
		success:function(data){alert("saved");}
		
	});
}
function viewthesis()
{
	var filename=document.getElementById("search_name").value;
	var existingHTML = document.getElementById("word_reflector");
	var existingWord = document.getElementById("html_for_word");
	$.ajax({
		url:"new.php",
		type:"POST",
		data:{"view_thesis":"1","filename":filename},
		success:function(data){
			clear();
			var htmlcontent=data.split("~");
			existingHTML.innerHTML=htmlcontent[0];
			existingWord.innerHTML=htmlcontent[1];
			}
		
	});
}
function logout()
{
	
	$.ajax({
		url:"new.php",
		type:"POST",
		data:{"logout":"1"},
		success:function(data)
		{
			alert(data);
			window.location="index.php";
		}
		
	});
}

function viewfun()
{
	var name=document.getElementById("search_name").value;
	if(name!="")
		viewthesis();
}
</script>
<body style="background-color:#999966;" onload="viewfun()">
<div id ="main menu" style="background-color:#999966;">
<table>
<tr>
<th style="align='left';width:200px;">
	<button onclick="add_title()"><b>add title</b> </button>
</th>
<th style="align-left;">
<button onclick="savethesis()"><b>Save</b> </button>
</th>

<th style="align='right';width:600px;">
	<input type = "checkbox" id = "add_numbering" checked/>add_numbering
</th>
<th style="align='left'">
<button onclick="logout()"><b>LogOut</b> </button>
</th>
</tr>
</table>
</div>
<div id = "main_space" style="background-color:#999966;">
<table border = "1px">
<tr>
<th>
<div id="dynamic_space"  style="background-color:#ffffff;width:500px;height:500px"></div>
</th>
<th>
<div id="word_reflector" style="background-color:#ffffff;width:400px;overflow-x:scroll;height:500px"></div>
</th>
<th>
<div id="html_for_word" style="overflow-y: scroll;background-color:#ffffff; width:400px;height:500px"></div>
</th>
</table>
<div>
<table>
<tr>
<th></th>
<th><input type ="submit" id = "download_word" onclick ="download_word()" value = "Download PDF file"></th>
<th></th>
</tr>
</table>
</div>
</div>
<input type="hidden" id="previous_selection" value = -1 />
<input type="text" id="search_name" value="<?php if(isset($_GET['search_name']))
{
	echo $_GET['search_name'];
} ?>"/>
<a href="example1.pdf" download id="download" hidden></a>
</body>
</html>