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
        }
    </style>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$('#upload').bind("click",function() 
			{ 
				var val = $('#uploadFile').val(); 
				if(val=='') 
				{ 
					alert("Empty input file"); 
				} 
				else{
					$('#upload').post("upload_a_file_analysis.php");
				}
				return false; 
			}); 
		});
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
    </div>
</nav>

<div class="container" style="margin-top: 110px;">
    <div class="row">
        <div class="col-sm-11">
            <h2><b>File statistics</b></h2><br><br>

			<div class="col-sm-2">
				<img src="img/upload_file.png" class="img-responsive"/>
			</div>
						
            <div class="col-sm-5 col-sm-offset-1 col-lg-offset-1" style="background-color: white; padding-top: 30px; padding-bottom: 40px; padding-left: 40px; border: 1px solid lightgray; border-radius: 8px;">
                <form action="upload_a_file_analysis.php" method="post" enctype="multipart/form-data">
				  <label for="file"><h3><b>Choose a file:</b></h3></label>
				  <input type="file" name="file" id="uploadFile"/>
				  <br />
				  <input type="submit" name="submit" value="Submit" id="upload"/>
				</form>
				<br>
            </div>
        </div>
    </div>
</div>

<div class="navbar navbar-fixed-bottom" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>