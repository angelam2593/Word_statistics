<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
				var val = $('#file').val(); 
				var val_word = $('#word').val();
				
				if(val=='' && val_word=='') 
				{ 
					alert("Empty input file and word for search"); 
				} 
				else if(val=='' && val_word!='') 
				{ 
					alert("Empty input file"); 
				} 
				else if(val!='' && val_word=='') 
				{ 
					alert("Empty input word for search"); 
				} 
				else{
					showLoading();
					$.ajax({
						type: "POST",
						url: "search_a_word_in_file_analysis.php",
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
		
		$(document).ready(function() {
			$('#upload2').bind("click",function() 
			{ 
				var val = $('#file2').val(); 
				var val_word = $('#word2').val();
				
				if(val=='' && val_word=='') 
				{ 
					alert("Empty input file and word for search"); 
				} 
				else if(val=='' && val_word!='') 
				{ 
					alert("Empty input file"); 
				} 
				else if(val!='' && val_word=='') 
				{ 
					alert("Empty input word for search"); 
				} 
				else{
					showLoading();
					$.ajax({
						type: "POST",
						url: "search_a_word_in_file_analysis_utf_8.php",
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
			<br><br><br>
		<form action="search_a_word_in_file_analysis.php" method="post" enctype="multipart/form-data">
			<div class="panel-group col-lg-6 col-sm-6 col-md-6">
				<div class="panel panel-warning" id="div_special_font">
						  <div class="panel-body" style="padding-left: 40px; padding-bottom: 40px;">
								<form action="search_a_word_in_file_analysis.php" method="post" enctype="multipart/form-data" style="float: left;">
								  <label for="file"><h3><b>
								  <span data-toggle="tooltip" title="Example: English, Roman alphabet">
									<img src="img/info_yellow.png" width="25px;;" style="margin-top:-10px;"> 
								</span>
								  Choose file with standard font:</b></h3></label>
								  <br><br>
								  <input type="file" name="file" id="file"/>
								  <br />
								  <label for="file"><h4>Write a word:</h4></label> 
								   <input type="input" name="word" id="word" style="border-radius: 5px;"/> 
								   <br/>
								</form>
						  </div>
						  <div class="panel-heading text-center">
							<input type="submit" name="submit" value="C O U N T" class="btn-warning btn-block btn-lg" id="upload" style="margin-bottom: 0px;"/>
						  </div>
						</div>
					</div>
		</form>
		
		<form action="search_a_word_in_file_analysis_utf_8.php" method="post" enctype="multipart/form-data">
			<div class="panel-group col-lg-6 col-sm-6 col-md-6">
				<div class="panel panel-danger" id="div_special_font">
						  <div class="panel-body" style="padding-left: 40px; padding-bottom: 40px;">
								<form action="search_a_word_in_file_analysis_utf_8.php" method="post" enctype="multipart/form-data" style="float: left;">
								  <label for="file"><h3><b>
								  <span data-toggle="tooltip" title="Example: macedonian, lithuanian, french,...">
									<img src="img/info_red.png" id="info_blue" width="24px;" style="margin-top:-10px;">
								</span>
								  Choose file with special font:</b></h3></label>
								  <br><br>
								  <input type="file" name="file" id="file2"/>
								  <br />
								  <label for="file"><h4>Write a word:</h4></label> 
								   <input type="input" name="word2" id="word2" style="border-radius: 5px;"/> 
								   <br/>
								</form>
						  </div>
						  <div class="panel-heading text-center">
							<input type="submit" name="submit" value="C O U N T" class="btn-danger btn-block btn-lg" id="upload2" style="margin-bottom: 0px;"/>
						  </div>
						</div>
					</div>
		</form>
		</div>
 		<h5 class="text-center" style="color: gray;"><b>* If you want to be diplayed correct statistics for your file, please choose the correct input file on the screen</b></h5>
    </div><br><br><br>
</div>

<div class="navbar" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>