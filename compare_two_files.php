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
				var val = $('#file1').val(); 
				var val2 = $('#file2').val(); 
				
				if(val=='' && val2=='') 
				{ 
					alert("Empty input FILE 1 and FILE 2 with standard font!"); 
				} 
				else if(val=='' && val2!='') 
				{ 
					alert("Empty input FILE 1  with standard font!"); 
				}
				else if(val!='' && val2=='') 
				{ 
					alert("Empty input FILE 2  with standard font!"); 
				}
				else{
					showLoading();
					$.ajax({
						type: "POST",
						url: "compare_two_files_analysis.php",
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
					var val = $('#file3').val(); 
					var val2 = $('#file4').val(); 
					
					if(val=='' && val2=='') 
					{ 
						alert("Empty input FILE 1 and FILE 2 with special font!"); 
					} 
					else if(val=='' && val2!='') 
					{ 
						alert("Empty input FILE 1  with special font!"); 
					}
					else if(val!='' && val2=='') 
					{ 
						alert("Empty input FILE 2  with special font!"); 
					}
					else{
						showLoading();
						$.ajax({
							type: "POST",
							url: "compare_two_files_analysis_utf_8.php",
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
		<form action="compare_two_files_analysis.php" method="post" enctype="multipart/form-data">
			<div class="panel-group col-lg-6 col-sm-6 col-md-6">
				<div class="panel panel-success">
				  <div class="panel-body" style="padding-left: 40px; padding-bottom: 40px;">
								<form action="compare_two_files_analysis.php" method="post" enctype="multipart/form-data" style="float: left;">
								  <label for="file"><h3><b>
								  <span data-toggle="tooltip" title="Example: English, Roman alphabet">
									<img src="img/info-green.png" id="info_blue" width="25px;;" style="margin-top:-10px;"> 
								  </span>
								  Choose two files with standard font:</b></h3></label>
								  <br><br>
								  <input type="file" name="file1" id="file1"/>
								  <br />
								  <input type="file" name="file2" id="file2"/>
								</form>
						  </div>
						  <div class="panel-heading text-center">
							<input type="submit" name="submit" value="C O M P A R E &thinsp;&thinsp; F I L E S" id="upload" class="btn btn-lg btn-success"/>
						  </div>
						</div>
					</div>
		</form>
		
		<form action="compare_two_files_analysis_utf_8.php" method="post" enctype="multipart/form-data">
			<div class="panel-group col-lg-6 col-sm-6 col-md-6">
						<div class="panel panel-info" id="div_special_font">
						  <div class="panel-body" style="padding-left: 40px; padding-bottom: 40px;">
								<form action="compare_two_files_analysis_utf_8.php" method="post" enctype="multipart/form-data" style="float: left;">
								  <label for="file"><h3><b>
								  <span data-toggle="tooltip" title="Example: macedonian, lithuanian, french,...">
									<img src="img/info.png" id="info_blue" width="24px;" style="margin-top:-10px;">
								</span>
								  Choose two files with special font:</b></h3></label>
								  <br><br>
								  <input type="file" name="file1" id="file3"/>
								  <br />
								  <input type="file" name="file2" id="file4"/>
								</form>
						  </div>
						  <div class="panel-heading text-center">
							<input type="submit" name="submit" value="C O M P A R E &thinsp;&thinsp; F I L E S" id="upload2" class="btn btn-lg btn-info"/>
						  </div>
						</div>
					</div>
		</form>
		</div>
			<h5 class="text-center" style="color: gray;"><b>* If you want to be diplayed correct statistics for your file, please choose the correct input file on the screen</b></h5>
	</div><br><br><br><br>
</div>
   

<div class="navbar" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>