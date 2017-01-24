<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Word Statistics</title>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fonts/css/font-awesome.css">
    <link rel="stylesheet" href="fonts/css/font-awesome.min.css">

    <style>
        body{
            background-image: url("img/words-blog2.jpg");
			font-family: "Calibri";
        }
		.btn-file {
			position: relative;
			overflow: hidden;
		}
		i {
			color: gray;
		}
		#loading{
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
		}
   </style>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$('#upload').bind("click",function() 
			{ 
				var val = $('#uploadFile').val(); 
				if(val=='') 
				{ 
					alert("Error input file with standard font!"); 
				} 
				else{
					showLoading();
					$.ajax({
						type: "POST",
						url: "upload_a_file_analysis.php",
						enctype: 'multipart/form-data',
						data: {
							file: myfile
						},
						success: function () {
							hideLoading();
						},
						error  : function (a) {
							hideLoading();
							alert("An error occured while uploading data.\n error code : "+a.statusText);
						}
					});
				}
				return false; 
			}); 
			
			$('#upload2').bind("click",function() 
			{ 
				var val = $('#uploadFileUTF8').val(); 
				if(val=='') 
				{ 
					alert("Error input file with specific font!"); 
				} 
				else{
					showLoading();
					$.ajax({
						type: "POST",
						url: "upload_a_file_analysis_utf_8.php",
						enctype: 'multipart/form-data',
						data: {
							file: myfile
						},
						success: function () {
							hideLoading();
						},
						error  : function (a) {
							hideLoading();
							alert("An error occured while uploading data.\n error code : "+a.statusText);
						}
					});
				}
				return false; 
			}); 
		});
	</script>
	
	<script>
		function showLoading(){
			document.getElementById("loading").style = "visibility: visible";
		}
		function hideLoading(){
			document.getElementById("loading").style = "visibility: hidden";
		}
	</script>
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="home.php">
                <img src="img/word_stats.png" class="img-responsive" style="margin-top: -5px;">
            </a>
        </div>
		<a href="info.php">
			<i class="fa fa-info-circle pull-right" aria-hidden="true" style="color: white; font-size: 25px; padding-top: 14px;"></i>
		</a>
    </div>
</nav>

<div class="container" style="margin-top: 110px;" style="visibility: hidden;">
	<img id='loading' src='img/loading-gif.gif' style='visibility: hidden;'>
</div>

<div class="container" style="margin-top: 0px; visibility: visible;">
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
			<a href="home.php"><i class="fa fa-arrow-left" aria-hidden="true" style="font-size: 25px; margin-top: -10px; float: left;" ></i></a>
            <h1 class="text-center" id="Lorem"></h1>
			<br><br>
			<div class="panel-group col-lg-6 col-sm-6 col-md-6">
				<div class="panel panel-success">
				  <div class="panel-body" style="padding-left: 40px; padding-bottom: 40px;">
						<form action="upload_a_file_analysis.php" method="post" enctype="multipart/form-data">
						  <label for="file"><h3><b>Choose a file:</b></h3></label>
						  <input type="file" name="file" id="uploadFile"/>
						  <br />
						  <input type="submit" name="submit" value="SUBMIT" id="upload" class="btn-success btn-lg"/>
						</form>
						<img src="img/docu.png" width="28%" style="float: right; margin-top: -135px;">
				  </div>
				  <div class="panel-heading text-center">
					 <h2>
						<span data-toggle="tooltip" title="Example: English, Roman alphabet">
							<img src="img/info-green.png" id="info_blue" width="25px;;" style="margin-top:-10px;"> 
						</span> STANDARD &nbsp;FONT</h2></div>
				</div>
			</div>
			
			<div class="panel-group col-lg-6 col-sm-6 col-md-6">
				<div class="panel panel-info">
				  <div class="panel-body" style="padding-left: 40px; padding-bottom: 40px;">
						<form action="upload_a_file_analysis_utf_8.php" method="post" enctype="multipart/form-data">
						  <label for="file"><h3><b>Choose a file:</b></h3></label>
						  <input type="file" name="file" id="uploadFileUTF8"/>
						  <br />
						  <input type="submit" name="submit" value="SUBMIT" id="upload2" class="btn-info btn-lg"/>
						</form>
						<img src="img/docu.png" width="28%" style="float: right; margin-top: -135px;">
				  </div>
				  <div class="panel-heading text-center">
					<h2>
						<span data-toggle="tooltip" title="Example: macedonian, lithuanian, french,...">
							<img src="img/info.png" id="info_blue" width="24px;" style="margin-top:-10px;">
						</span> SPECIAL &nbsp;FONT</h2></div>
				</div><br><br>
			</div>
			<br>
        </div>
		<h5 class="text-center" style="color: gray;"><b>* If you want to be diplayed correct statistics for your file, please choose the correct input file on the screen</b></h5>
    </div><br>
</div>

<div class="navbar" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>