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
        <div class="col-sm-12">
            <h2><b>WORD counting for specified word</b></h2><br><br>
			
			<form action="search_a_word_in_file_analysis.php" method="post" enctype="multipart/form-data">
            
			<div class="col-sm-8" style="background-color: white; padding-top: 30px; padding-bottom: 40px; border: 1px solid gray; border-radius: 8px;">
                <div class="col-sm-3">
                    <img src="img/file1.png" class="img-responsive">
                </div>
					<br/>
					<h4>Choose a file:</h4>
					<input type="file" name="file" id="file" /> 				 
					<br>
				   <label for="file"><h4>Write a word to search:</h4></label> 
				   <input type="input" name="word" id="word" /> 
				   <br/>
            </div>

            <div class="col-sm-3 text-center col-lg-offset-1"><br><br><br>
				<input type="submit" name="submit" value="C O U N T" class="btn-info btn-block btn-lg" id="upload" style="margin-bottom: 0px;"/>
            </div>
			</form>
        </div>

    </div>
</div><br><br><br>
<br><br>

<div class="navbar navbar-fixed-bottom" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>