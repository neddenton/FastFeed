<!--Intralinks
	Enhanced GUD
	
	Ned Denton
-->

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Enhanced GUD Search</title>		
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
		<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
		<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
		<link rel="stylesheet" href="enhancedGUD.css">
		<style>
			.ui-autocomplete-loading {
				background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
			}
		</style>
		<script src='nprogress.js'></script>
		<link rel='stylesheet' href='nprogress.css'/>
		<script>
		<?php 	
				$id = 12346;			//hardcoded user ID (to save formatting)
		?>
			$primeemail = "";
			$(function() {				//function to autocomplete and display all fields
				function displayData(){
					$("#data").show();
				}
				function logName(name) {
					$("#card8").text(name);					
				}				
				function logEmail(email){
					$("#card1").html(email);
				}
				function getProf(email){
					$primeemail = email;
					var request = new XMLHttpRequest();
					request.onload = logProf;
					request.open("GET", "searchProfData.php?email=" + email, true);
					request.send();
				}
				function logProf(){
					var data= JSON.parse(this.responseText);
					console.log(data);
					$("#card1").html("<div class='title'>PROFILE INFO</div><div class='twocol'>"+
					"<span class='profileitem'><strong>Primary Email: </strong>"+data[0].EMAIL_ADDRESS+"</span>"+
					"<span class='profileitem'><strong>User ID: </strong>"+data[0].USER_ID+"</span>"+
					"<span class='profileitem'><strong>Last Login: </strong>"+data[0].LAST_LOGIN_SUCCESS+"</span>"+
					"<span class='profileitem'><strong>Date Created: </strong>"+data[0].CREATED_DATE+"</span>"+
					"<span class='profileitem'><strong>Password Last Modified: </strong>"+data[0].PASSWORD_MODIFIED_DATE+"</span>"+
					"<span class='profileitem'><strong>Organization ID: </strong>"+data[0].ORGANIZATION_ID+"</span>"+
					"<span class='profileitem'><strong>Organization Name: </strong>"+data[0].ORG_NAME+"</span>"+
					"<span class='profileitem'><strong>Telephone: </strong>"+data[0].TELEPHONE_NUMBER+"</span>"+
					"<span class='profileitem'><strong>Mobile Phone: </strong>"+data[0].MOBILE_PHONE+"</span>"+
					"<span class='profileitem'><strong>Fax Number: </strong>"+data[0].FAX_NUMBER+"</span></div>");
					document.getElementById("question").innerHTML = data[0].authQuestion;
					if(data[0].pwExpIn == 0)
						document.getElementById("card11").innerHTML = "<div class='title'>PASSWORD</div><div class='bigtext'>expires today!</div><br/>";
					else if(data[0].pwExpIn < 0)
						document.getElementById("card11").innerHTML = "<div class='title'>PASSWORD</div><div class='bigtext'>expired</div><br/>";
					else
						document.getElementById("card11").innerHTML = "<div class='title'>CHANGE PASSWORD IN</div><div class='bigtext'>"+data[0].PW_EXPIRING_IN+" days</div><br/>";
					var request = new XMLHttpRequest();
					request.onload = logPersonel;
					request.open("GET", "getPersonel.php?orgid=" + data[0].ORGANIZATION_ID, true);
					request.send();
				}
				function getWorkspaces(email, sorter){
					console.log(sorter);
					console.log(email);
					var request = new XMLHttpRequest();
					request.onload = logWorkspaces;
					request.open("GET", "searchWorkData.php?email=" + email + "&sorter="+sorter, true);
					request.send();
				}
				function logWorkspaces(){
					var data = JSON.parse(this.responseText);
					document.getElementById("body2").innerHTML = "";
					console.log(data);
					if(data == null)
						var nully = null;
					else{
					var numSpaces = data.length;
					document.getElementById("numSpaces").innerHTML = (numSpaces+" workspaces");
					for(var i = 0; i<numSpaces; i++){
						$icon = data[i].code;
						if(data[i].code == 0)
							$icon = "<img width='20px' height='30px' src='ilp.jpg'>";
						else if(data[i].code == 60)
							$icon = "<img width='20px' height='30px' src='courier.jpg'>";
						else if(data[i].code == 61)
							$icon = "<img width='20px' height='30px' src='via.jpg'>";
						else if(data[i].code == 11 || data[i].code == 14)
							$icon = "<img width='20px' height='30px' src='manda.jpg'>";
					document.getElementById("body2").innerHTML += 
					"<tr> <td><a href='https://services.intralinks.com/servlets/gud?page=WSPL&wsID="+data[i].id+"'>"+data[i].name+"</a>  "+$icon+"</td>"+
					"<td>"+data[i].id+"</td>"+
					"<td>"+data[i].prodName+"</td>"+
					"<td>"+data[i].lastAccessed+"</td>"+
					"<td>"+data[i].accessCount+"<td></tr>";
					}
					}
				}
				function getAgent(id){
					document.getElementById("card7").innerHTML = "<div class='title'>USER AGENT</div>";
					NProgress.configure({ parent: '#card7' });
					var request = new XMLHttpRequest();
					request.onload = logAgent;
					request.open("GET", "searchAgent.php?id=" + id, true);
					request.send();
				}
				function logAgent(){
					var data = JSON.parse(this.responseText);
					var ipa = data.result._raw.substring(0, Math.min(data.result._raw.indexOf(","), data.result._raw.indexOf(" ")));
					var mid1 = data.result._raw.substring(Math.min(data.result._raw.indexOf(","), data.result._raw.indexOf(" ")), data.result.length);
					if(mid1.indexOf(" \"GET") != -1){
						var mid2 = mid1.substring(mid1.indexOf(" \"GET"), mid1.length);
						var mid3 = mid2.substring(mid2.indexOf("https"), mid2.length);
					}
					else{
						var mid2 = mid1.substring(mid1.indexOf("HTTP"), mid1.length);
						var mid3 = mid2.substring(mid2.indexOf(" \"-")+2, mid2.length);
						mid3 = mid3.substring(mid3.indexOf("https"), mid3.length);
					}
					var mid4 = mid3.substring(mid3.indexOf(" \""), mid3.length);				
					var useragent = mid4.substring(mid4, mid4.indexOf("\" \"")).substring(2);
					document.getElementById("card7").innerHTML = "<div class='title'>USER AGENT</div>"+
				"<span><strong>Last Known IPA: </strong>"+ data.result._raw.substring(0,Math.min(data.result._raw.indexOf(","), data.result._raw.indexOf(" "))) +"</span><br/>"+
				"<span><strong>Last Known User Agent: </strong>"+useragent.replace(/ \(/g, "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(")+"</span><br/>"+
				"<span><strong>Last Known Time of Access: </strong>"+data.result._time+ "</span><br/>"+
				"<span><strong>Last Known Source of Data: </strong>"+ data.result.index+"</span>";
					NProgress.done();
				}
				function getStati(email){
					var request = new XMLHttpRequest();
					request.onload = logStati;
					request.open("GET", "searchStati.php?email=" + email, true);
					request.send();
				}
				function logStati(){
					var data = JSON.parse(this.responseText);
				}
				function getCSM(email){
					var request = new XMLHttpRequest();
					request.onload = logCSM;
					request.open("GET", "getCSM.php?email=" + email, true);
					request.send();
				}
				function logCSM(){
					var data = JSON.parse(this.responseText);
					document.getElementById("card15").innerHTML = "<div class='title'>SPECIAL INSTRUCTION</div><br/>";//+data[0].INSTRUCTION;
				}
				function logPersonel(){
					var data = JSON.parse(this.responseText);
					document.getElementById("card16").innerHTML = "<div class='title'>INTRALINKS CONTACTS</div><br/>";//+data[0].CSM+data[0].SALES;
				}
				$( "#guser" ).autocomplete({
					source: "search.php",
					minLength: 4,
					select: function( event, ui ) {
					clearData();
					NProgress.done();
					NProgress.start();
					logName( ui.item ? ui.item.firstName + " " + ui.item.lastName: "Not found, input was " + this.value);	
					logEmail( ui.item ? "</span><span class='profileitem'><strong>Primary Email: </strong>" + ui.item.email					
					: "Not found, input was " + this.value);
					displayData();
					getProf(ui.item ? ui.item.email: "Not found, input was " + this.value);
					getWorkspaces(ui.item ? ui.item.email: "Not found, input was " + this.value, document.getElementById("sorter").value);
					NProgress.configure({ parent: '#card7' });
					getAgent(ui.item ? ui.item.id: "Not found, input was " + this.value);
					getCSM(ui.item ? ui.item.id: "Not found, input was " + this.value);
					//getStati(ui.item ? ui.item.email: "Not found, input was " + this.value);
				}
				
				});
				function clearData(){
					document.getElementById("body2").innerHTML = "";
					document.getElementById("numSpaces").innerHTML = "workspaces";
				}
				
				document.getElementById("sorter").onchange = function(){
					getWorkspaces($primeemail, document.getElementById("sorter").value);
				};
			
		
		});
		</script>
		<script>
		function clearInput(elementID) {
			document.getElementById(elementID).value = '';
		}
		function freeze(){
			$fireHtml = "<img src='flame.png' id='fire'>";
			$iceHtml = "<img src='snowflake.png' id='snow'>";
			if(document.getElementById("mrfreeze").value == 2){
				document.getElementById("mrfreeze").innerHTML = $fireHtml;
				document.getElementById("mrfreeze").value = 1;
			}				
			else{
				document.getElementById("mrfreeze").innerHTML = $iceHtml;
				document.getElementById("mrfreeze").value = 2;
			}
		}
		</script>
		<script>
		$(document).ready(function(){					//function to toggle security question/restore default format/switch search mode
			$("#toggle").click(function(){
				var answer = document.getElementById("toggle");
				var answerText = "42" + " (click to hide)";										//temp answer text
				if(answer.innerHTML == "show")
					answer.innerHTML = answerText;
				else
					answer.innerHTML = "show";
			});
			$("#logo").click(function(){
<?php			for($i = 1; $i < 17; $i++){
?>					var card<?=$i?> = document.getElementById("card<?=$i?>");
					card<?=$i?>.style.top ="0px";
					card<?=$i?>.style.left = "0px";
					card<?=$i?>.style.zIndex = 0;			
					$.ajax({
						type: "POST",
						url: "logPosition.php",
						data: {thisTop: 0, thisLeft: 0, thisZ: 0, iterant: <?=$i?>, id: <?=$id?>}
						});
					
<?php			}
?>			
			});
			$("#logo").hover(attivioIn, attivioOut);
			
			function attivioIn(){
				var request = new XMLHttpRequest();
					request.onload = loadAttivio;
					request.open("GET", "getAttivio.php", true);
					request.send();
				document.getElementById("attiviotable").style.display = "block";
			}
			function attivioOut(){
				document.getElementById("attiviotable").style.display = "none";
			}
			function loadAttivio(){
				var data = JSON.parse(this.responseText);
						var ocrsmall = data.ocrsmall;
						var ocrmedium = data.ocrmedium;
						var ocrlarge = data.ocrlarge;
						var pdfsmall = data.pdfsmall;
						var pdfmedium = data.pdfmedium;
						var pdflarge = data.pdflarge;
						document.getElementById("attivio").innerHTML = 
						"<colgroup span=\"4\" class=\"columns\"></colgroup><tr><th>Attivio</th><th>Small</th><th>Medium</th><th>Large</th></tr>"+
			"<tr><td>OCR</td><td>"+ocrsmall+"</td>"+
    
			"<td>"+ocrmedium+"</td>"+
    
			"<td>"+ocrlarge+"</td>"+
  
			"</tr>"+
  
			"<tr>"+
    
			"<td>Attachments</td>"+
			
			"<td>"+pdfsmall+"</td>"+
    
			"<td>"+pdfmedium+"</td>"+
    
			"<td>"+pdflarge+"</td></tr>";
			}
		});
		</script>
		<script>
			$(document).ready(function(){	
				document.getElementById("attiviosearch").onclick = searchAttivio;
			});		
			function searchAttivio(){
				if(document.getElementById("attivioinput").value == "")
					document.getElementById("attivioresults").innerHTML = "";
				else{
				var request = new XMLHttpRequest();
					request.onload = logAttivioDoc;
					request.open("GET", "searchAttivioDocs.php?id=" + document.getElementById("attivioinput").value, true);
					request.send();
				}
				
			}
			function logAttivioDoc(){
				var data = JSON.parse(this.responseText);
				document.getElementById("attivioresults").innerHTML = "<strong>Doc Name:</strong> "+data[0].name+"<br/>"+
					"<strong> Satus:</strong> "+data[0].status+"<br/>"+
					"<strong> Date Created:</strong> "+data[0].date+"<br/>"+
					"<strong> File Extension:</strong> "+data[0].ext+"<br/>"+
					"<strong> Page Count:</strong> "+data[0].pagecount+"<br/>";
			}
		</script>
		<script>
			$(document).ready(function(){				//positioning function
				var lastTopped = 2;
<?php		
				for($i = 1; $i < 17; $i++){
?>
				var card<?=$i?> = document.getElementById("card<?=$i?>");
				card<?=$i?>.style.position = "relative";
<?php			
				//session_start();
				if(true){				//$_SESSION["loggedIn"] == 							//if logged in, then pulls position from mySQL
					$con=mysqli_connect("localhost","edenton","646S5mShzvvJNb7c", "edenton");
					$qstring = "SELECT TOP_".$i.", LEFT_".$i.", Z_".$i." FROM positioning WHERE ID = '".$id."'";
					$result = mysqli_query($con, $qstring);
					if($row  = mysqli_fetch_assoc($result))
						{
							$row['TOP_'.$i]=(int)$row['TOP_'.$i];
							$top = $row['TOP_'.$i];
							$row['LEFT_'.$i]=(int)$row['LEFT_'.$i];
							$left = $row['LEFT_'.$i];
							$row['Z_'.$i]=(int)$row['Z_'.$i];
							$z = $row['Z_'.$i];
						?>
					card<?=$i?>.style.top = <?=$top?>+"px";
					card<?=$i?>.style.left = <?=$left?>+"px";
					card<?=$i?>.style.zIndex = <?=$z?>;
					var lastTopped = Math.max(lastTopped, <?=$z?>);
					<?php
						}
						else{	?>
							card<?=$i?>.style.top = "0px";
							card<?=$i?>.style.left = "0px";
							card<?=$i?>.style.zIndex = "0";
						<?php							
						}						
				}
					else{
?>						var lastTopped = 2;
						card<?=$i?>.style.top = "0";
						card<?=$i?>.style.left = "0";
<?php				} ?>
				card<?=$i?>.onmouseup = dragMouseUp;
				card<?=$i?>.onmousedown = dragMouseDown;
				card<?=$i?>.onmousemove = dragMouseMove;
<?php			} ?>
				document.body.style.position = "relative";
				function dragMouseMove(event){
					if(this.dragging) {
						var x = event.clientX - this.prevX;
						var y = event.clientY - this.prevY;
						var oldX = parseInt(window.getComputedStyle(this).left);
						var oldY = parseInt(window.getComputedStyle(this).top);
						this.style.top = y + oldY + "px";
						this.style.left = x + oldX + "px";
						this.prevX = event.clientX;
						this.prevY = event.clientY;
					}
				};
				function dragMouseDown(event){
					if(document.getElementById("mrfreeze").value == 1){
<?php				for($i = 1; $i < 17; $i++){
?>					if(card<?=$i?>.style.zIndex == "")
						card<?=$i?>.style.zIndex =  1;
						
<?php				}
?>					if(parseInt(this.style.zIndex) < lastTopped){
						this.style.zIndex = lastTopped+1;
						lastTopped++;
					}
					if(this.className == "card")
						this.style.boxShadow = "6px 12px 8px #888888";
					this.dragging = true;
					this.prevX = event.clientX;
					this.prevY = event.clientY;
					}
				}	
				function dragMouseUp(){
					this.dragging = false;
					if(this.className == "card")
						this.style.boxShadow = "2px 8px 5px #888888";						
					var thisTop = window.getComputedStyle(this).top;
					var thisLeft = window.getComputedStyle(this).left;
					var thisZ = window.getComputedStyle(this).zIndex;
					var iterant = this.id.substring(4);					
					$.ajax({											//log position in mySQL once changed
						type: "POST",
						url: "logPosition.php",
						data: {thisTop: thisTop, thisLeft: thisLeft, thisZ: thisZ, iterant: iterant, id: <?=$id?>}
						});
				};	
			});
		</script>
		<script>
			$(document).ready(function(){						//function to drop down tables
<?php			for($i = 1; $i < 8; $i++){
?>
				$("#hide<?=$i?>").click(function(){
					document.getElementById("body<?=$i?>").className = 'unhidden';
					document.getElementById("hide<?=$i?>").style.display = 'none';
					document.getElementById("show<?=$i?>").style.display = 'inline';
				});
				$("#show<?=$i?>").click(function(){
					document.getElementById("body<?=$i?>").className = 'hidden';
					document.getElementById("hide<?=$i?>").style.display = 'inline';
					document.getElementById("show<?=$i?>").style.display = 'none';
				});
<?php 			} 
?>
			});
		</script>
		<link href="css/m-styles.min.css" rel="stylesheet">
		</head>
		<body>
			<button id="mrfreeze" href="#" class="m-btn rnd" onclick="freeze()" value="2"; title= "click to toggle card dragging"><img src="snowflake.png" id="snow"></button>
			<img id="logo" src="intralogo.jpg" alt="Intralinks" title= "click to restore default">						<!--LOGO/RESTORE-->
			<div class="ui-widget" id="card10">
				<input id="guser" type="text" class="m-wrap m-ctrl-huge" placeholder="search client email">				<!--SEARCH BAR-->
				<button id="guserclear" href="#" class="m-btn rnd" onclick="clearInput('guser')">clear</button>
			</div>			
			<div id="attiviotable" class="card">																		<!--Attivio Stuff-->
			<table id="attivio"></table>
			
			</p>
			</div>
			<div id='card8'></div>																					<!--NAME-->	
			<div id="card13">
			<input id="attivioinput" type="text" class="m-wrap m-ctrl-medium" placeholder="search doc id">				<!--ATTIVIO SEARCH BAR-->
				<button id="attiviosearch" href="#" class="m-btn rnd" onclick="searchAttivio()">search</button>
				<div class="card" id="attivioresults"></div>
			</div>			
			<div id="data">
			
			<div id="card11" class="card">																						<!--DAYS UNTIL PASSWORD EXPIRES-->
			</div>
			<div id="card15" class="card"></div>																			<!--SPECIAL INSTRUCTION-->
			<div id="card16" class="card"></div>																		<!--INTRALINKS CONTACTS-->
			<div class="card" id="card1">																				<!--PROFILE DATA-->
			</div>			
			<div id="card9">																							<!--SECURITY Q (temp question text)-->
				<span id="question"></span>
				<button id="toggle" class="m-btn  rnd">show</button>
			</div>						
			<div class="card" id="card7"></div>																				<!--CLIENT AGENT DATA-->
			<div id="bottom">
			<div class="card" id="card2">																				<!--2ND LVL CARD-->
				<div class="title">EXCHANGES</div>
				
				<table class="table" id="workspace" style="width:100%">													<!--WORKSPACES-->
					<caption>WORKSPACES</caption>
					<select id="sorter" title="sort by">
				<option value="lastlogin">last login</option>
				<option value="name">name</option>
				<option value="accesscount">access count</option>
				<option value="product">product name</option>
				<option value="id">id</option>
				</select>
					<tr>
						<th id="numSpaces">workspaces</th>
						<th>id</th>
						<th>product</th> 
						<th>last accessed</th>
						<th><span class="end">access count</span><a href="#hide2" class="hide" id="hide2">+</a>							
							<a href="#show2" class="show" id="show2">-</a>
						</th>
					</tr>
					<tbody class="hidden" id="body2">					
					</tbody>
				</table>
				<table class="table" id="businessgroups" style="width:100%">											<!--BUSINESS GROUPS-->
					<caption>BUSINESS GROUPS</caption>
					<tr>
						<th>group</th> 
						<th><span class="end">something else</span>
							<a href="#hide7" class="hide right" id="hide7">+</a>
							<a href="#show7" class="show right" id="show7">-</a>
							</th>
					</tr>
					<tbody class="hidden" id="body7">
					<tr>
						<td>a group</td>
						<td>a thing</td>
					</tr>
					<tr>
						<td>a group</td>
						<td>a thing</td>
					</tr>
					<tr>
						<td>a group</td>
						<td>a thing</td>
					</tr>
					<tr>
						<td>a group</td>
						<td>a thing</td>
					</tr>
					<tr>
						<td>a group</td>
						<td>a thing</td>
					</tr>
					</tbody>
				</table>
				
			</div>
			<div class="card" id="card3">																				<!--3RD LVL CARD-->
				<div id="summary">	
				<h1>STATUS SUMMARY</h1>																					<!--STATUS SUMMARY-->
				<div id="statusdata">
					<p><strong>Il Status:</strong> </p>
					<p><strong>Created:</strong> </p>
					<p><strong>Flags:</strong> </p>
					<p><strong>arc last login(flex):</strong> </p>
					<p><strong>arc last login(smart client):</strong> </p>
					<p><strong>5.x last login:</strong> </p>
				</div>			
			</div>
			</div>
			
			<div id="lowest">																							<!--3RD LVL CARDS-->
			<div class="card" id="card4">																				<!--SPECIAL INSTRUCTION-->
				<div class="title">SPECIAL INSTRUCTION</div>
				<p>----data-----</p>
			</div>
			<div class="card" id="card5">																				<!--RECENT ALERTS-->
				<div class="title">MOST RECENT ALERTS</div>
				<p>----data-----</p>
			</div>
			<div class="card" id="card6">																				<!--DEPARTMENTS-->
			<table class="table" id="dept" style="width:100%">														
						<caption class="title">DEPARTMENTS</caption>
						<tr>	
							<th>name</th>
							<th>org</th> 
							<th>description</th>
							<th><span class="end">label</span><a href="#hide1" class="hide" id="hide1">+</a>
								<a href="#show1" class="show" id="show1">-</a>
							</th>
						</tr>
					<tbody class="hidden" id="body1">
					<tr>
						<td>a dept</td>
						<td>an org</td>
						<td>some description</td>
						<td>a label</td>
					</tr>
					<tr>
						<td>a dept</td>
						<td>an org</td>
						<td>some description</td>
						<td>a label</td>
					</tr>	
					<tr>
						<td>a dept</td>
						<td>an org</td>
						<td>some description</td>
						<td>a label</td>
					</tr>	
					<tr>
						<td>a dept</td>
						<td>an org</td>
						<td>some description</td>
						<td>a label</td>
					</tr>	
					</tbody>
				</table>
				
			</div>
			<div class="card" id ="card12">
				<div class="title">TICKET HISTORY</div>
				<p>----data-----</p>
				
			</div>
			</div>
			<div id="righty">
			<div class="card" id="card14">
				<table class="table" id="statuschanges" style="width:100%">												<!--STATUS CHANGES-->
					<caption>STATUS CHANGES</caption>
					<tr>
						<th><a href="#hide3" class="hide left" id="hide3">+</a>
							<a href="#show3" class="show left" id="show3">-</a>
							<span class="end">status change</span></th>
						<th>IL status</th> 
					</tr>
					<tbody class="hidden" id="body3">
					<tr>
						<td>a change</td>
						<td>some status</td>
					</tr>	
					<tr>
						<td>a change</td>
						<td>some status</td>
					</tr>
					<tr>
						<td>a change</td>
						<td>some status</td>
					</tr>
					<tr>
						<td>a change</td>
						<td>some status</td>
					</tr>
					<tr>
						<td>a change</td>
						<td>some status</td>
					</tr>
					<tr>
						<td>a change</td>
						<td>some status</td>
					</tr>
					</tbody>
				</table>
				
				<table class="table" id="flag" style="width:100%">														<!--FLAG CHANGES-->
					<caption>FLAG CHANGES</caption>
					<tr>
						<th><a href="#hide4" class="hide left" id="hide4">+</a>
							<a href="#show4" class="show left" id="show4">-</a>
							<span class="end">flag change</span></th>
						<th>flag</th>
						<th>flag resolution</th>
						<th>resolved by</th>
					</tr>
					<tbody class="hidden" id="body4">
					<tr>
						<td>a change</td>
						<td>some flag</td>
						<td>some resolution</td>
						<td>someone</td>
					</tr>
					<tr>
						<td>a change</td>
						<td>some flag</td>
						<td>some resolution</td>
						<td>someone</td>
					</tr>	
					<tr>
						<td>a change</td>
						<td>some flag</td>
						<td>some resolution</td>
						<td>someone</td>
					</tr>	
					<tr>
						<td>a change</td>
						<td>some flag</td>
						<td>some resolution</td>
						<td>someone</td>
					</tr>	
					<tr>
						<td>a change</td>
						<td>some flag</td>
						<td>some resolution</td>
						<td>someone</td>
					</tr>	
					</tbody>
				</table>
				
				<table class="table" id="matches" style="width:100%">													<!--CLOSE MATCHES-->
					<caption>CLOSE MATCHES</caption>
					<tr>
						<th><a href="#hide5" class="hide left" id="hide5">+</a>
							<a href="#show5" class="show left" id="show5">-</a>
							<span class="end">date</span></th>
						<th>user</th>
						<th>resolved</th>
					</tr>
					<tbody class="hidden" id="body5">
					<tr>
						<td>a date</td>
						<td>a user</td>
						<td>someone</td>
					</tr>
					<tr>
						<td>a date</td>
						<td>a user</td>
						<td>someone</td>
					</tr>	
					<tr>
						<td>a date</td>
						<td>a user</td>
						<td>someone</td>
					</tr>	
					<tr>
						<td>a date</td>
						<td>a user</td>
						<td>someone</td>
					</tr>	
					<tr>
						<td>a date</td>
						<td>a user</td>
						<td>someone</td>
					</tr>	
					</tbody>
				</table>
				
				<table class="table" id="profilechanges" style="width:100%">											<!--PROFILE CHANGES-->
					<caption>PROFILE CHANGES</caption>
					<tr>
						<th><a href="#hide6" class="hide left" id="hide6">+</a>
							<a href="#show6" class="show left" id="show6">-</a>
							<span class="end">profile changed</span></th>
						<th>user</th> 
						<th>action</th>
					</tr>
					<tbody class="hidden" id="body6">
					<tr>
						<td>a profile change</td>
						<td>a user</td>
						<td>an action</td>
					</tr>
					<tr>
						<td>a profile change</td>
						<td>a user</td>
						<td>an action</td>
					</tr>
					<tr>
						<td>a profile change</td>
						<td>a user</td>
						<td>an action</td>
					</tr>
					<tr>
						<td>a profile change</td>
						<td>a user</td>
						<td>an action</td>
					</tr>
					<tr>
						<td>a profile change</td>
						<td>a user</td>
						<td>an action</td>
					</tr>
					</tbody>
				</table>			
			</div>
			</div>
			</div>
		</body>
</html>