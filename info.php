<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Word Statistics</title>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fonts/css/font-awesome.css">
    <link rel="stylesheet" href="fonts/css/font-awesome.min.css">

    <style>
        body{
            background-image: url("img/words-blog2.jpg");
			font-family: "Calibri";
        }
        hr {
            border: 0;
            height: 1px;
            background: #333;
            background-image: linear-gradient(to right, #ccc, #333, #ccc);
        }
    </style>

	<script>
		 var showText = function (target, message, index, interval) {
			 if (index < message.length) {
				 $(target).append(message[index++]);
				 setTimeout(function () { showText(target, message, index, interval); }, interval);
			 }
		 }

		 $(function () {
			 showText("#Lorem", "Functions used for statistics", 0, 50);
		 });		
	</script>
	
	<script>
		$( function() {
			$( "#accordion" ).accordion();
		} );
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

    <div class="container" style="margin-top: 110px;">
        <div class="row">
            <div class="col-sm-12">
				<div class="row">
				<div class="col-lg-10 col-md-10 col-md-offset-1 col-lg-offset-1">
					<h1 id="Lorem"></h1>
					<br>
					
					<div id="accordion">
					  <h3>Function 1: <b><i>Words</i></b></h3>
					  <div>
						<p>
						<i>Words</i> displays the total number of every word in the text. Here unities of numberical characters are not included, just unities of characters formed as words. 
						</p>
					  </div>
					  <h3>Function 2: <b><i>Numbers</i></b></h3>
					  <div>
						<p>
						<i>Numbers</i> displays the total number of every number in the text.
						</p>
					  </div>
					  <h3>Function 3: <b><i>Reading time</i></b></h3>
					  <div>
						<p>
						<i>Reading time</i> displays the total amount of reading time in minutes that is required for a person to read the text. It is based on the average reading speed of 275 words per minute.
						</p>
					  </div>
					  <h3>Function 4: <b><i>Speaking time</i></b></h3>
					  <div>
						<p>
							<i>Speaking time</i> displays the total amount of speaking time in minutes that is required for a person to vocally read the text. It is based on the average speaking speed of 180 words per minute.
						</p>
					  </div>
					  <h3>Function 5: <b><i>Short words</i></b></h3>
					  <div>
						<p>
						 <i>Short words</i> displays the total amount of short words that are present in the text. Under the term "short words" are defined words that have a length smaller or equal to 3.
						</p>
					  </div>
					  <h3>Function 6: <b><i>Long words</i></b></h3>
					  <div>
						<p>
						 <i>Long words</i> displays the total amount of long words that are present in the text. Under the term "long words" are defined words that have a length greater or equal to 7.
						</p>
					  </div>
					  <h3>Function 7: <b><i>Sentences</i></b></h3>
					  <div>
						<p>
						 <i>Sentences</i> displays the total number of every sentence in the text. Sentences can be divided by punctuations and whitespaces (spaces, new lines, tabs).
						</p>
					  </div>
					  <h3>Function 8: <b><i>Whitespaces</i></b></h3>
					  <div>
						<p>
						<i>Whitespaces</i> displays the total number of every space between words in the text.
						</p>
					  </div>
					  <h3>Function 9: <b><i>Characters (with spaces)</i></b></h3>
					  <div>
						<p>
						<i>Characters(with spaces)</i> displays the total number of every character in the text and the spaces between the characters.
						</p>
					  </div>
					  <h3>Function 10: <b><i>Characters (no spaces)</i></b></h3>
					  <div>
						<p>
						<i>Characters(without spaces)</i> displays the total number of every character in the text without the spaces between the characters.
						</p>
					  </div>
					  <h3>Function 11: <b><i>Length of longest sentence</i></b></h3>
					  <div>
						<p>
						<i>Length of the longest sentence</i> displays the length of the sentence in the text that containts the highest number of words.
						</p>
					  </div>
					  <h3>Function 12: <b><i>Length of shortest sentence</i></b></h3>
					  <div>
						<p>
						<i>Length of the shotest sentence</i> displays the length of the sentence in the text that containts the lowest number of words.
						</p>
					  </div>
					  <h3>Function 13: <b><i>Average words length</i></b></h3>
					  <div>
						<p>
						<i>Average words length</i> displays the average lengt of every word that is present in the text.
						</p>
					  </div>
					  <h3>Function 14: <b><i>Word has appeared in the text</i></b></h3>
					  <div>
						<p>
						<i>Word has appeared in the text</i> displays the total number of times the word has appeared in the text.
						</p>
					  </div>
					  <h3>Function 15: <b><i>Number of total chars the word owns</i></b></h3>
					  <div>
						<p>
						<i>Number of total chars the word owns</i> displays the total number of characters (both identical and different) the inserted word owns.
						</p>
					  </div>
					  <h3>Function 16: <b><i>Number of different chars the word owns</i></b></h3>
					  <div>
						<p>
						<i>Number of total chars the word owns</i> displays the diverse number of characters the inserted word owns.
						</p>
					  </div>
					  <h3>Function 17: <b><i>Number of numerals chars the word owns</i></b></h3>
					  <div>
						<p>
						<i>Number of total chars the word owns</i> displays the total number of numerical characters the inserted word owns.
						</p>
					  </div>
					  <h3>Function 18: <b><i>Word appears at the beginning of the sentence</i></b></h3>
					  <div>
						<p>
						<i>Word appears at the beginning of the sentence</i> displays the number of times the inserted word appears at the beginning of a sentence.
						</p>
					  </div>
					  <h3>Function 19: <b><i>Word appears at the end of the sentence</i></b></h3>
					  <div>
						<p>
						<i>Word appears at the end of the sentence</i> displays the number of times the inserted word appears at the end of a sentence.
						</p>
					  </div>
					  <h3>Additional info: <b><i>Standard font</i></b></h3>
					  <div>
						<p>
							Under the term <i>standard font</i> is defined the classical Latin alphabet. For instance, any file that is written in English falls under this category.
						</p>
					  </div>
					  <h3>Additional info: <b><i>Specific font</i></b></h3>
					  <div>
						<p>
							Under the term <i>specific font</i> are defined alphabets different from the Lation alphabet. For instance, any file that is written in Macedonian, Spanish, Serbian, Bulgarian, German and many others, falls under this category.
						</p>
					  </div>
					   <h3>Functionality No. 1: <b><i>Upload a file</i></b></h3>
					  <div>
						<p>
							This functionality provides 2 options: uploading a file that is written in a standard font or uploading a file that is written in a specific font. After the file is uploaded, a table of statistical information about the file's data is displayed.
						</p>
					  </div>
					  <h3>Functionality No. 2: <b><i>Compare two files</i></b></h3>
					  <div>
						<p>
							This functionality provides 2 options, comparing statistical data from 2 files that are written both either in a standard or in a specific font. 
						</p>
					  </div>
					  <h3>Functionality No. 3: <b><i>Search a word in a file</i></b></h3>
					  <div>
						<p>
							This functionality provides 2 options that allow searching a word and displaying data about it regarding it's presence in the uploaded file. The word and the uploaded file can written either in a standard or in a specific font.
						</p>
					  </div>
					   <h3>About us <b><i></i></b></h3>
					  <div>
						<p>
							We are students at the Faculty of Computer Science and Engineering in Skopje, Macedonia. If you would like to contact us you can write on our e-mails:
							<br>
							nastovska.sandra@students.finki.ukim.mk
							<br>
							madzhirova.angela@students.finki.ukim.mk
						</p>
					  </div>
					</div>
				</div>
				</div>
			</div>
        </div>
    </div><br><br><br>
    <br><br>

    <div class="navbar" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
        <p class="text-center">&copy; Copyrights FINKI</p>
    </div>
</body>
</html>