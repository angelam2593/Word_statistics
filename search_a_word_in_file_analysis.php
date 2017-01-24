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
            <a class="navbar-brand" href="index.html">
                <img src="img/word_stats.png" class="img-responsive" style="margin-top: -5px;">
            </a>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 110px;">
    <div class="row">
        <div class="col-sm-12">
			<table style="width:100%" class="text-center">
				<?php
				$word = $_REQUEST['word'];
				echo "<h3>The <b>word</b> will be searching for in the file is: " . "<strong><u>" . $word . "</u></strong></h3><br><br>";
				
				if ($_FILES["file"]["error"] > 0){
					echo "<h3>Error: </h3>" . $_FILES["file"]["error"] . "<br />";
				  } else {
					//echo "<h3>Upload: </h3>" . $_FILES["file"]["name"] . "<br />";
					//echo "<h3>Type: </h3>" . $_FILES["file"]["type"] . "<br />";
					//echo "<h3>Size: </h3>" . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
					//echo "<h3>Stored in: </h3>" . $_FILES["file"]["tmp_name"];
					
					
					//echo "PROVERKI:" . "<br />"; //ovie mozam so SWITCH da gi napravam i treba da gi namestam za info kako da se prikazuva
					if($_FILES["file"]["type"] == "application/pdf"){	
						//echo "PDF E";
						$filename = $_FILES["file"]["tmp_name"]; //ja naoga patekata kaj so e socuvan fajlot
						$handle = fopen($filename, "r"); //go otvara fajlot za da ima pristap do sodrzinata
						$contents = fread($handle, filesize($filename)); //ja zacuvuva sodrzinata vo promenliva
					}
					else if(contains("document", $_FILES["file"]["type"])){
						//echo "DOC E";
						
						$filename = $_FILES["file"]["tmp_name"];
						$handle = fopen($filename, "r"); 
						$contents = fread($handle, filesize($filename));
						$contents = read_docx($filename); //preprocesiranje na docx fajlot da se vmetre readable sodrzina vo promenliva		
					}
					else if($_FILES["file"]["type"] == "text/plain"){
						//echo "TXT E";
						$filename = $_FILES["file"]["tmp_name"]; 
						$handle = fopen($filename, "r"); 
						$contents = fread($handle, filesize($filename)); 
					}
					else{
						echo "NEMAME FUNKCIONALNOST ZA DRUGI TIPOVI NA FAJLOVI";
					}
				  }
				  
					function contains($needle, $haystack){ //da se najde document vo toa ogromnoto ime kaj type 
						return strpos($haystack, $needle) !== false;
					}
					
					function read_docx($filename){ // ja najdov na http://stackoverflow.com/questions/10646445/read-word-document-in-php
						$striped_content = '';
						$content = '';
						if(!$filename || !file_exists($filename)) return false;
						$zip = zip_open($filename);
						if (!$zip || is_numeric($zip)) return false;
						while ($zip_entry = zip_read($zip)) {
							if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
							if (zip_entry_name($zip_entry) != "word/document.xml") continue;
							$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							zip_entry_close($zip_entry);
						}
						zip_close($zip);      
						$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
						$content = str_replace('</w:r></w:p>', "\r\n", $content);
						$striped_content = strip_tags($content);
						return $striped_content;
					} 
				?>
			  
				<?php 
					//funkciite koi gi koristime tuka se pretezno od http://www.w3schools.com/php/func_string_count_chars.asp i nekoi gotovi od php za stringovi
				?>
				<tr>
					<th>Word has appeared in the text</th>
					<td>
						<?php
							$split_strings = preg_split('/[\ \n\,]+/', $contents);
							$counter1 = 0;
							$word = strtolower($word);
							foreach($split_strings as $str){
								if(strcmp($word, strtolower($str)) == 0){
									$counter1+=1;
								}
							}
							echo $counter1 . " times";
						?>
					</td>
				</tr>
				<tr>
					<th>Number of total chars the word owns</th>
					<td>
						<?php
							$charsInWord =  count_chars($word,1);
							$counter2 = 0;
							foreach($charsInWord as $key=>$value){
								$counter2 += $value;
							}
							echo $counter2;
						?>
					</td>
				</tr>
				<tr>
					<th>Number of different chars the word owns</td>
					<td>
						<?php
							echo strlen(count_chars($word,3));
						?>
					</td>
				</tr>
				<tr>
					<th>Number of numerals chars the word owns</th>
					<td>
						<?php
							$charsInString = str_split($word);
							$counter3 = 0;
							foreach($charsInString as $ch){
								if(is_numeric($ch)){
									$counter3 += 1;
								}
							}
							echo $counter3;
						?>
					</td>
				</tr>
				<tr>
					<th>Word appears at the begining od the sentence</th>
					<td>
						<?php
							$sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $contents);
							$counterBeginning = 0;
							$counterEnd = 0;
							foreach($sentences as $recenica){
								$recenica = str_replace(".", "", $recenica);//da se izvadi tockata od posledniot string
								$r = explode(" ", $recenica);
								if(strcmp(strtolower($r[0]), $word) == 0)
									$counterBeginning += 1;
								else if(strcmp(strtolower($r[count($r)-1]), $word) == 0)
									$counterEnd += 1;
							}
							echo $counterBeginning . " times";
						?>
					</td>
				</tr>
				<tr>
					<th>Number of times the word appears at the end od the sentence</th>
					<td>
					<?php
						echo $counterEnd . " times";
					?>
					</td>
				</tr>	
			</table>	
		</div>

    </div>
</div><br><br><br>
<br><br>

<div class="navbar navbar-fixed-bottom navbar-inverse" style="padding-top: 15px; color: dimgray">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>