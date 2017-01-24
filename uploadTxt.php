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
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            background-color: whitesmoke;
            padding: 4px 0px 4px 0px;
        }
    </style>
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

<div class="container" style="margin-top: 80px;">
    <div class="row">
        <div class="col-sm-12">
            <h2><b>File statistics</b></h2><br><br>

            <div class="col-sm-2 col-lg-offset-1">
                <img src="img/my_documents.png" class="img-responsive">
            </div>

            <div class="col-sm-7 text-center col-lg-offset-1">
                <table style="width:100%">
                    <tr>
                        <td><b>Words</b></td>
                        <td id="no_words">
							<?php
								if ($_FILES["file"]["error"] > 0){
									echo "<h3>Error: </h3>" . $_FILES["file"]["error"] . "<br />";
								  } else {
									
									//echo "<br><br>PROVERKI:" . "<br />"; //ovie mozam so SWITCH da gi napravam i treba da gi namestam za info kako da se prikazuva
									if($_FILES["file"]["type"] == "application/pdf"){	
										//echo "PDF E";
										$filename = $_FILES["file"]["tmp_name"]; //ja naoga patekata kaj so e socuvan fajlot
										$handle = fopen($filename, "r"); //go otvara fajlot za da ima pristap do sodrzinata
										$contents = fread($handle, filesize($filename)); //ja zacuvuva sodrzinata vo promenliva
									}
									else if(contains("document", $_FILES["file"]["type"])){
										//echo "DOC E<br>";
										$filename = $_FILES["file"]["tmp_name"];
										$handle = fopen($filename, "r"); 
										$contents = fread($handle, filesize($filename)); 
									}
									else if($_FILES["file"]["type"] == "text/plain"){
										//echo "TXT E<br>";
										$filename = $_FILES["file"]["tmp_name"]; 
										$handle = fopen($filename, "r"); 
										$contents = fread($handle, filesize($filename)); 
									}
									else{
										echo "NEMAME FUNKCIONALNOST ZA DRUGI TIPOVI NA FAJLOVI<br>";
									}
									
									//header('Content-type: text/plain; charset=utf-8');
									//echo "<hr><h3>File content: </h3>";
									//print $contents;
								  }
								  
								  function contains($needle, $haystack){ //da se najde document vo toa ogromnoto ime kaj type 
										return strpos($haystack, $needle) !== false;
									}
									echo str_word_count($contents);
								?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Numbers</b></td>
                        <td id="no_numbers">
							<?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents);
								$count_num = 0;
								foreach($split_strings as $str){
									if(is_numeric($str)){
										$count_num += 1;
									}
								}
								echo $count_num;	
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Reading time</b></td>
                        <td id="reading_time">
							<?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents);
								if(count($split_strings)<275){
									echo "The file will be read for less than a minute";	
								}
								else if(count($split_strings)==275){
									echo "The file will be read for exactly a minute";	
								}
								else{
									$temp = count($split_strings)/275;
									echo "The file will be read for approximately " . round($temp, 1) . " min";
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Speaking time</b></td>
                        <td id="speaking_time">
							<?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents);
							if(count($split_strings)<180){
								echo "The file will be spoken for less than a minute";	
							}
							else if(count($split_strings)==180){
								echo "The file will be spoken for exactly a minute";	
							}
							else{
								$temp = count($split_strings)/180;
								echo "The file will be spoken for approximately " . round($temp, 1) . " min";
							}
						?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Short words</b></td>
                        <td id="short_words">
							<?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents);
								$brojac = 0;
								
								foreach($split_strings as $el){
									if(strlen($el)<=3){
										$brojac++;
									}
								}
								echo $brojac;
							?>
						</td>
                    </tr>
					<tr>
                        <td><b>Long words</b></td>
                        <td id="long_words">
							<?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents);
								$brojac = 0;
								foreach($split_strings as $el){
									if(strlen($el)>=7)
										$brojac++;
								}
								echo $brojac;
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Sentences</b></td>
                        <td id="no_sentences">
							<?php			
								function countSentences($str){
									return preg_match_all('/[^\s](\.|\!|\?)(?!\w)/',$str,$match);
								}
								
								$res = countSentences($contents);
								echo $res; 
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Whitespaces</b></td>
                        <td id="no_whitespaces">
							<?php
								$count_whitespaces = substr_count($contents, ' ');
								echo $count_whitespaces; 
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Characters (with whitespaces)</b></td>
                        <td id="no_chars_with_whitespaces">
							<?php echo strlen($contents); ?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Characters (without whitespaces)</b></td>
                        <td id="no_chars_without_whitespaces">
							<?php
								$resultNoWhitespacesChars = strlen($contents) - $count_whitespaces;
								echo $resultNoWhitespacesChars;
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Length of longest sentence</b></td>
                        <td id="longest_sentence">
							<?php			
								$sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $contents); //tuka gi vadi tocka, zapirka, prasalnik, izvincnik
								$max = strlen($sentences[0]);
								foreach($sentences as $recenica){
									if(strlen($recenica) > $max)
										$max = strlen($recenica);
								}
								echo $max; 
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Length of shortest sentence</b></td>
                        <td id="shortest_sentence">
							<?php			
								$sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $contents); //tuka gi vadi tocka, zapirka, prasalnik, izvincnik
								$min = strlen($sentences[0]);
								foreach($sentences as $recenica){
									if(strlen($recenica) < $min)
										$min = strlen($recenica);
								}
								echo $min;
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Average word length</b></td>
                        <td id="average_word_length">
							<?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents);
								$sum = 0;
								foreach($split_strings as $str){
									$sum += strlen($str);
								}
								$result = ($sum - substr_count($contents, ' '))/count($split_strings);
								echo number_format((float)$result, 2, '.', ''); 
							?>
						</td>
                    </tr>
                </table>
            </div><br>
        </div>
    </div>
</div>

<div class="navbar navbar-fixed-bottom navbar-inverse" style="padding-top: 15px; color: dimgray;">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>	