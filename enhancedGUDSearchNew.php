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
		<script>
			$(function() {
				function log( message ) {
					$( "<div>" ).text( message ).prependTo( "#log" );
					$( "#log" ).scrollTop( 0 );
				}
				$( "#guser" ).autocomplete({
					source: "search.php",
					minLength: 2,
					select: function( event, ui ) {
					log( ui.item ?
					"USERNAME : " + ui.item.value + " EMAIL :" + ui.item.EMAIL + " FIRST NAME :" + ui.item.FIRST_NAME + " LAST NAME :" + ui.item.LAST_NAME + " CREATED :" + ui.item.CREATED_DATE:
					"Not found, input was " + this.value );
				}
			});
		});
		</script>
		<script>
		function clearBox(elementID){
			document.getElementById(elementID).innerHTML = "";
		}
		</script>
		<script>
		function clearInput(elementID) {
			document.getElementById(elementID).value = '';
		}
		</script>		
		<script>
		$(document).ready(function(){
			$("#toggle").click(function(){
				var answer = document.getElementById("toggle");
				var answerText = "42" + " (click to hide)";										//temp answer text
				if(answer.innerHTML == "show")
					answer.innerHTML = answerText;
				else
					answer.innerHTML = "show";
			});
		});
		</script>

		<script>
			$(document).ready(function(){		
<?php			for($i = 1; $i < 7; $i++){?>
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
		<img src="intralogo.jpg" alt="" id="logo">
			<div class="ui-widget">
				<input id="guser" type="text" class="m-wrap m-ctrl-huge" placeholder="search client email">
				<button id="guserclear" href="#" class="m-btn rnd" onclick="clearInput('guser')">clear</button>
			</div>
			
			<div id="name">First_Name Last_Name</div>											<!-- temp profile text -->
			
			<div id="links">
				<a href="templink.html" class="actionlink m-btn rnd">new password</a><br/>
				<a href="templink.html" class="actionlink m-btn rnd">update profile</a><br/>
				<a href="http://wildeastmusic.bandcamp.com" class="actionlink m-btn rnd">merge</a><br/>
				<a href="templink.html" class="actionlink m-btn rnd">suspend</a><br/>
				<a href="templink.html" class="actionlink m-btn rnd">deregister</a><br/>
				<a href="templink.html" class="actionlink m-btn rnd">clear new flag</a><br/>
				<a href="templink.html" class="actionlink m-btn rnd">new alias</a><br/>
			</div>
			<div class="ui-widget" id="profileinfo">
				<span id="profleft">
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
				</span>
			</div>			
			<div id="verify">
				<span id="question">What is the answer to life, the universe, and everything?</span> 				<!-- temp question text -->
				<button id="toggle" class="m-btn  rnd">show</button>
			</div>			
			<div id="auxprofile">
				<table class="table" id="dept" style="width:100%">
					<caption>DEPARTMENTS</caption>
					<tr>
						<th>name <a href="#hide1" class="hide" id="hide1">+</a>
							<a href="#show1" class="show" id="show1">-</a>
						</th>
						<th>org</th> 
						<th>description</th>
						<th>label</th>
					</tr>
					<tbody class="hidden" id="body1">
					<tr>
						<td>a dept</td>
						<td>an org</td>
						<td>some description</td>
						<td>a label</td>
					</tr>					
					</tbody>
				</table>
				<table class="table" id="workspace" style="width:100%">
					<caption>WORKSPACES</caption>
					<tr>
						<th>2 workspaces <a href="#hide2" class="hide" id="hide2">+</a>							<!--temp num workspaces-->
							<a href="#show2" class="show" id="show2">-</a>
						</th>											
						<th>host</th> 
						<th>role</th>
						<th>status</th>
						<th>last accessed</th>
						<th>support</th>
					</tr>
					<tbody class="hidden" id="body2">
					<tr>
						<td>a workspace</td> 
						<td>some host</td>
						<td>some role</td>
						<td>a status</td>
						<td>time accessed</td>
						<td>the support</td>
					</tr>					
					<tr>
						<td>a workspace2</td> 
						<td>some host2</td>
						<td>some role2</td>
						<td>a status2</td>
						<td>time accessed2</td>
						<td>the support2</td>
					</tr>					
					<tr>
						<td>a workspace3</td> 
						<td>some host3</td>
						<td>some role3</td>
						<td>a status3</td>
						<td>time accessed3</td>
						<td>the support3</td>
					</tr>					
					<tr>
						<td>a workspace4</td> 
						<td>some host4</td>
						<td>some role4</td>
						<td>a status4</td>
						<td>time accessed4</td>
						<td>the support4</td>
					</tr>					
					<tr>
						<td>a workspace5</td> 
						<td>some host5</td>
						<td>some role5</td>
						<td>a status5</td>
						<td>time accessed5</td>
						<td>the support5</td>
					</tr>				
					</tbody>
				</table>			
				
			</div>
			<div id="status">
			<div id="summary">	
				<h1>STATUS SUMMARY</h1>			
				<div id="statusdata">
					<p><strong>Il Status:</strong> </p>
					<p><strong>Created:</strong> </p>
					<p><strong>Flags:</strong> </p>
					<p><strong>arc last login(flex):</strong> </p>
					<p><strong>arc last login(smart client):</strong> </p>
					<p><strong>5.x last login:</strong> </p>
				</div>			
			</div>
			
				<table class="table" id="dept" style="width:100%">
					<caption>STATUS CHANGES</caption>
					<tr>
						<th>status change <a href="#hide3" class="hide" id="hide3">+</a>
							<a href="#show3" class="show" id="show3">-</a>
						</th>
						<th>IL status</th> 
					</tr>
					<tbody class="hidden" id="body3">
					<tr>
						<td>a change</td>
						<td>some status</td>
					</tr>					
					</tbody>
				</table>
				
				<table class="table" id="dept" style="width:100%">
					<caption>FLAG CHANGES</caption>
					<tr>
						<th>flag change <a href="#hide4" class="hide" id="hide4">+</a>
							<a href="#show4" class="show" id="show4">-</a>
						</th>
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
					</tbody>
				</table>
				
				<table class="table" id="dept" style="width:100%">
					<caption>CLOSE MATCHES</caption>
					<tr>
						<th>date <a href="#hide5" class="hide" id="hide5">+</a>
							<a href="#show5" class="show" id="show5">-</a>
						</th>
						<th>user</th>
						<th>resolved</th>
					</tr>
					<tbody class="hidden" id="body5">
					<tr>
						<td>a date</td>
						<td>a user</td>
						<td>someone</td>
					</tr>					
					</tbody>
				</table>
				
				<table class="table" id="dept" style="width:100%">
					<caption>PROFILE CHANGES</caption>
					<tr>
						<th>profile changed <a href="#hide6" class="hide" id="hide6">+</a>
							<a href="#show6" class="show" id="show6">-</a>
						</th>
						<th>user</th> 
						<th>action</th>
					</tr>
					<tbody class="hidden" id="body6">
					<tr>
						<td>a profile change</td>
						<td>a user</td>
						<td>an action</td>
					</tr>
					</tbody>
				</table>			
			</div>	
		</body>
</html>