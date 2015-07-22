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
					var request = new XMLHttpRequest();
					request.onload = logProf;
					request.open("GET", "searchProfData.php?email=" + email, true);
					request.send();
				}
				function logProf(){
					var data= JSON.parse(this.responseText);
					$("#card1").html("<div class='title'>PROFILE INFO</div><div class='twocol'>"+
					"<span class='profileitem'><strong>Primary Email: </strong>"+data[0].email+"</span>"+
					"<span class='profileitem'><strong>User ID: </strong>"+data[0].userID+"</span>"+
					"<span class='profileitem'><strong>Last Login: </strong>"+data[0].lastLogin+"</span>"+
					"<span class='profileitem'><strong>Date Created: </strong>"+data[0].createdDate+"</span>"+
					"<span class='profileitem'><strong>Password Last Modified: </strong>"+data[0].passModDate+"</span>"+
					"<span class='profileitem'><strong>Organization ID: </strong>"+data[0].orgID+"</span></div>");
					document.getElementById("question").innerHTML = data[0].authQuestion;
					if(data[0].pwExpIn == 0)
						document.getElementById("card11").innerHTML = "<div class='title'>PASSWORD</div><div class='bigtext'>expires today!</div><br/>";
					else if(data[0].pwExpIn < 0)
						document.getElementById("card11").innerHTML = "<div class='title'>PASSWORD</div><div class='bigtext'>expired</div><br/>";
					else
						document.getElementById("card11").innerHTML = "<div class='title'>CHANGE PASSWORD IN</div><div class='bigtext'>"+data[0].pwExpIn+" days</div><br/>";
					
				}
				function getWorkspaces(email){
					var request = new XMLHttpRequest();
					request.onload = logWorkspaces;
					request.open("GET", "searchWorkData.php?email=" + email, true);
					request.send();
				}
				function logWorkspaces(){
					var data = JSON.parse(this.responseText);
					if(data == null)
						NProgress.done();
					var numSpaces = data.length;
					document.getElementById("numSpaces").innerHTML = (numSpaces+" workspaces");
					for(var i = 0; i<numSpaces; i++){
					document.getElementById("body2").innerHTML += 
					"<tr> <td><a href='https://services.intralinks.com/servlets/gud?page=WSPL&wsID="+data[i].id+"'>"+data[i].name+"</a></td>"+
					"<td>"+data[i].id+"</td>"+
					"<td>"+data[i].prodName+"</td>"+
					"<td>"+data[i].code+"</td>"+
					"<td>"+data[i].lastAccessed+"</td>"+
					"<td>"+data[i].accessCount+"<td></tr>";
					}
					NProgress.done();
				}
				function getAgent(email){
					var request = new XMLHttpRequest();
					request.onload = logAgent;
					request.open("GET", "searchAgent.php?email=" + email, true);
					request.send();
				}
				function logAgent(){
					var data = JSON.parse(this.responseText);
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
				$( "#guser" ).autocomplete({
					source: "search.php",
					minLength: 4,
					select: function( event, ui ) {
					clearData();
					NProgress.start();
					logName( ui.item ? ui.item.firstName + " " + ui.item.lastName: "Not found, input was " + this.value);	
					logEmail( ui.item ? "</span><span class='profileitem'><strong>Primary Email: </strong>" + ui.item.email					
					: "Not found, input was " + this.value);
					displayData();
					getProf(ui.item ? ui.item.email: "Not found, input was " + this.value);
					getWorkspaces(ui.item ? ui.item.email: "Not found, input was " + this.value);
					getAgent(ui.item ? ui.item.email: "Not found, input was " + this.value);
					getStati(ui.item ? ui.item.email: "Not found, input was " + this.value);
				}
				
				});
				function clearData(){
					document.getElementById("body2").innerHTML = "";
					document.getElementById("numSpaces").innerHTML = "workspaces";
				}
			
		
		});
		</script>
		<script>
		function clearInput(elementID) {
			document.getElementById(elementID).value = '';
		}
		function freeze(){
			if(document.getElementById("mrfreeze").innerHTML == "freeze")
				document.getElementById("mrfreeze").innerHTML = "melt";				
			else
				document.getElementById("mrfreeze").innerHTML = "freeze";
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
<?php			for($i = 1; $i < 13; $i++){
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
			
		});
		</script>
		<script>
			$(document).ready(function(){				//positioning function
				var lastTopped = 2;
<?php		
				for($i = 1; $i < 13; $i++){
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
					if(document.getElementById("mrfreeze").innerHTML == "freeze"){
<?php				for($i = 1; $i < 13; $i++){
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
			<button id="mrfreeze" href="#" class="m-btn rnd" onclick="freeze()">melt</button>
			<img id="logo" src="intralogo.jpg" alt="Intralinks" title= "click to restore default" >						<!--LOGO/RESTORE-->
			<div class="ui-widget" id="card10">
				<input id="guser" type="text" class="m-wrap m-ctrl-huge" placeholder="search client email">				<!--SEARCH BAR-->
				<button id="guserclear" href="#" class="m-btn rnd" onclick="clearInput('guser')">clear</button>
			</div>
			<div id='card8'></div>																					<!--NAME-->										
			
			
			<div id="data">
			<div id="card11" class="card">																						<!--DAYS UNTIL PASSWORD EXPIRES-->
			</div>
			<div class="card" id="card1">																				<!--PROFILE DATA-->
				<!--<span id="profleft">
				<span class="profileitem"><strong>Organization:</strong>  The Name of an Organization</span>
				<span class="profileitem"><strong>Primary Email:</strong> </span>
				<span class="profileitem"><strong>Industry:</strong> </span>
				<span class="profileitem"><strong>Title:</strong> </span>
				<span class="profileitem"><strong>Fax:</strong>(343)-fax-numb</span>
				</span>
				<span id="profright">
				<span class="profileitem"><strong>Phone:</strong> </span>
				<span class="profileitem"><strong>Alias email:</strong> aliasemail@intralinks.com</span>
				<span class="profileitem"><strong>Allow Concurrent:</strong> </span>
				<span class="profileitem"><strong>Alerts:</strong> </span>
				</span>-->
			</div>			
			<div id="card9">																							<!--SECURITY Q (temp question text)-->
				<span id="question"></span>
				<button id="toggle" class="m-btn  rnd">show</button>
			</div>						
			<div class="card" id="card7">																				<!--CLIENT AGENT DATA-->
				<div class="title">USER AGENT</div>
				<span><strong>most recent user agent:</strong> mozilla firefux</span><br/><br/>
				<span><strong>client OS:</strong> windows 95</span><br/><br/>
				<span><strong>IPA:</strong> 127.0.0.1</span><br/><br/>
				<span><strong>mount point:</strong> USA</span>
			</div>
			<div id="bottom">
			<div class="card" id="card2">																				<!--2ND LVL CARD-->
				<div class="title">EXCHANGES</div>
				<table class="table" id="workspace" style="width:100%">													<!--WORKSPACES-->
					<caption>WORKSPACES</caption>
					<tr>
						<th id="numSpaces">workspaces</th>
						<th>id</th>
						<th>product</th> 
						<th>product code</th>
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
			</div>
		</body>
</html>