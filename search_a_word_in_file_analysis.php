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
        table, th, td {
            border-collapse: collapse;
            border: 1px solid black;
            padding: 4px 0px 4px 0px;
        }
		i {
			color: gray;
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
			 document.getElementById('Lorem').style.fontWeight = "900";
			 showText("#Lorem", "Word in FILE", 0, 100);
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
		<a href="info.php">
			<i class="fa fa-info-circle pull-right" aria-hidden="true" style="color: white; font-size: 25px; padding-top: 14px;"></i>
		</a>
    </div>
</nav>

<div class="container" style="margin-top: 80px;">
    <div class="row"><br>
        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
            <a href="search_a_word_in_file.php"><i class="fa fa-arrow-left" aria-hidden="true" style="font-size: 25px; margin-top: -10px; float: left;" ></i></a>
            <h1 class="text-center" id="Lorem"></h1>
			<br>					
			
			<!-- F-CIJA ZA CITANJE NA TEXT OD PDF -->
			<?php
				function decodeAsciiHex($input) {
					$output = "";

					$isOdd = true;
					$isComment = false;

					for($i = 0, $codeHigh = -1; $i < strlen($input) && $input[$i] != '>'; $i++) {
						$c = $input[$i];

						if($isComment) {
							if ($c == '\r' || $c == '\n')
								$isComment = false;
							continue;
						}

						switch($c) {
							case '\0': case '\t': case '\r': case '\f': case '\n': case ' ': break;
							case '%': 
								$isComment = true;
							break;

							default:
								$code = hexdec($c);
								if($code === 0 && $c != '0')
									return "";

								if($isOdd)
									$codeHigh = $code;
								else
									$output .= chr($codeHigh * 16 + $code);

								$isOdd = !$isOdd;
							break;
						}
					}

					if($input[$i] != '>')
						return "";

					if($isOdd)
						$output .= chr($codeHigh * 16);

					return $output;
				}
				function decodeAscii85($input) {
					$output = "";

					$isComment = false;
					$ords = array();
					
					for($i = 0, $state = 0; $i < strlen($input) && $input[$i] != '~'; $i++) {
						$c = $input[$i];

						if($isComment) {
							if ($c == '\r' || $c == '\n')
								$isComment = false;
							continue;
						}

						if ($c == '\0' || $c == '\t' || $c == '\r' || $c == '\f' || $c == '\n' || $c == ' ')
							continue;
						if ($c == '%') {
							$isComment = true;
							continue;
						}
						if ($c == 'z' && $state === 0) {
							$output .= str_repeat(chr(0), 4);
							continue;
						}
						if ($c < '!' || $c > 'u')
							return "";

						$code = ord($input[$i]) & 0xff;
						$ords[$state++] = $code - ord('!');

						if ($state == 5) {
							$state = 0;
							for ($sum = 0, $j = 0; $j < 5; $j++)
								$sum = $sum * 85 + $ords[$j];
							for ($j = 3; $j >= 0; $j--)
								$output .= chr($sum >> ($j * 8));
						}
					}
					if ($state === 1)
						return "";
					elseif ($state > 1) {
						for ($i = 0, $sum = 0; $i < $state; $i++)
							$sum += ($ords[$i] + ($i == $state - 1)) * pow(85, 4 - $i);
						for ($i = 0; $i < $state - 1; $i++)
							$ouput .= chr($sum >> ((3 - $i) * 8));
					}

					return $output;
				}
				function decodeFlate($input) {
					return @gzuncompress($input);
				}

				function getObjectOptions($object) {
					$options = array();
					if (preg_match("#<<(.*)>>#ismU", $object, $options)) {
						$options = explode("/", $options[1]);
						@array_shift($options);

						$o = array();
						for ($j = 0; $j < @count($options); $j++) {
							$options[$j] = preg_replace("#\s+#", " ", trim($options[$j]));
							if (strpos($options[$j], " ") !== false) {
								$parts = explode(" ", $options[$j]);
								$o[$parts[0]] = $parts[1];
							} else
								$o[$options[$j]] = true;
						}
						$options = $o;
						unset($o);
					}

					return $options;
				}
				function getDecodedStream($stream, $options) {
					$data = "";
					if (empty($options["Filter"]))
						$data = $stream;
					else {
						$length = !empty($options["Length"]) ? $options["Length"] : strlen($stream);
						$_stream = substr($stream, 0, $length);

						foreach ($options as $key => $value) {
							if ($key == "ASCIIHexDecode")
								$_stream = decodeAsciiHex($_stream);
							if ($key == "ASCII85Decode")
								$_stream = decodeAscii85($_stream);
							if ($key == "FlateDecode")
								$_stream = decodeFlate($_stream);
						}
						$data = $_stream;
					}
					return $data;
				}
				function getDirtyTexts(&$texts, $textContainers) {
					for ($j = 0; $j < count($textContainers); $j++) {
						if (preg_match_all("#\[(.*)\]\s*TJ#ismU", $textContainers[$j], $parts))
							$texts = array_merge($texts, @$parts[1]);
						elseif(preg_match_all("#Td\s*(\(.*\))\s*Tj#ismU", $textContainers[$j], $parts))
							$texts = array_merge($texts, @$parts[1]);
					}
				}
				function getCharTransformations(&$transformations, $stream) {
					preg_match_all("#([0-9]+)\s+beginbfchar(.*)endbfchar#ismU", $stream, $chars, PREG_SET_ORDER);
					preg_match_all("#([0-9]+)\s+beginbfrange(.*)endbfrange#ismU", $stream, $ranges, PREG_SET_ORDER);

					for ($j = 0; $j < count($chars); $j++) {
						$count = $chars[$j][1];
						$current = explode("\n", trim($chars[$j][2]));
						for ($k = 0; $k < $count && $k < count($current); $k++) {
							if (preg_match("#<([0-9a-f]{2,4})>\s+<([0-9a-f]{4,512})>#is", trim($current[$k]), $map))
								$transformations[str_pad($map[1], 4, "0")] = $map[2];
						}
					}
					for ($j = 0; $j < count($ranges); $j++) {
						$count = $ranges[$j][1];
						$current = explode("\n", trim($ranges[$j][2]));
						for ($k = 0; $k < $count && $k < count($current); $k++) {
							if (preg_match("#<([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+<([0-9a-f]{4})>#is", trim($current[$k]), $map)) {
								$from = hexdec($map[1]);
								$to = hexdec($map[2]);
								$_from = hexdec($map[3]);

								for ($m = $from, $n = 0; $m <= $to; $m++, $n++)
									$transformations[sprintf("%04X", $m)] = sprintf("%04X", $_from + $n);
							} elseif (preg_match("#<([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+\[(.*)\]#ismU", trim($current[$k]), $map)) {
								$from = hexdec($map[1]);
								$to = hexdec($map[2]);
								$parts = preg_split("#\s+#", trim($map[3]));
								
								for ($m = $from, $n = 0; $m <= $to && $n < count($parts); $m++, $n++)
									$transformations[sprintf("%04X", $m)] = sprintf("%04X", hexdec($parts[$n]));
							}
						}
					}
				}
				function getTextUsingTransformations($texts, $transformations) {
					$document = "";
					for ($i = 0; $i < count($texts); $i++) {
						$isHex = false;
						$isPlain = false;

						$hex = "";
						$plain = "";
						for ($j = 0; $j < strlen($texts[$i]); $j++) {
							$c = $texts[$i][$j];
							switch($c) {
								case "<":
									$hex = "";
									$isHex = true;
								break;
								case ">":
									$hexs = str_split($hex, 4);
									for ($k = 0; $k < count($hexs); $k++) {
										$chex = str_pad($hexs[$k], 4, "0");
										if (isset($transformations[$chex]))
											$chex = $transformations[$chex];
										$document .= html_entity_decode("&#x".$chex.";");
									}
									$isHex = false;
								break;
								case "(":
									$plain = "";
									$isPlain = true;
								break;
								case ")":
									$document .= $plain;
									$isPlain = false;
								break;
								case "\\":
									$c2 = $texts[$i][$j + 1];
									if (in_array($c2, array("\\", "(", ")"))) $plain .= $c2;
									elseif ($c2 == "n") $plain .= '\n';
									elseif ($c2 == "r") $plain .= '\r';
									elseif ($c2 == "t") $plain .= '\t';
									elseif ($c2 == "b") $plain .= '\b';
									elseif ($c2 == "f") $plain .= '\f';
									elseif ($c2 >= '0' && $c2 <= '9') {
										$oct = preg_replace("#[^0-9]#", "", substr($texts[$i], $j + 1, 3));
										$j += strlen($oct) - 1;
										$plain .= html_entity_decode("&#".octdec($oct).";");
									}
									$j++;
								break;

								default:
									if ($isHex)
										$hex .= $c;
									if ($isPlain)
										$plain .= $c;
								break;
							}
						}
						$document .= "\n";
					}

					return $document;
				}

				function pdf2text($filename) {
					$infile = @file_get_contents($filename, FILE_BINARY);
					if (empty($infile))
						return "";

					$transformations = array();
					$texts = array();

					preg_match_all("#obj(.*)endobj#ismU", $infile, $objects);
					$objects = @$objects[1];

					for ($i = 0; $i < count($objects); $i++) {
						$currentObject = $objects[$i];

						if (preg_match("#stream(.*)endstream#ismU", $currentObject, $stream)) {
							$stream = ltrim($stream[1]);

							$options = getObjectOptions($currentObject);
							if (!(empty($options["Length1"]) && empty($options["Type"]) && empty($options["Subtype"])))
								continue;

							$data = getDecodedStream($stream, $options); 
							if (strlen($data)) {
								if (preg_match_all("#BT(.*)ET#ismU", $data, $textContainers)) {
									$textContainers = @$textContainers[1];
									getDirtyTexts($texts, $textContainers);
								} else
									getCharTransformations($transformations, $data);
							}
						}
					}

					return getTextUsingTransformations($texts, $transformations);
				}
				?> 
				
				
				<!-- F-CIJA ZA CITANJE NA TEXT OD MicrosoftWord FILE -->
				<?php
					function read_docx($filename){
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
				
				<!-- F-CIJA ZA CITANJE NA TEXT OD MicrosoftWord FILE .DOC -->
				<?php
					function read_doc_file($filename) {
						 if(file_exists($filename))
						{
							if(($fh = fopen($filename, 'r')) !== false ) 
							{
								$headers = fread($fh, 0xA00);
								   $n1 = ( ord($headers[0x21C]) - 1 );
								   $n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );
								   $n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );
								   $n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );
								   $textLength = ($n1 + $n2 + $n3 + $n4);
								   $extracted_plaintext = fread($fh, $textLength);
								   return nl2br($extracted_plaintext);
								}
							}   
						}
				?>
				
				
				
			<div class="col-lg-8 text-center col-lg-offset-2">
				<table style="width:100%" class="text-center">
					
				<?php 
				$word = $_REQUEST['word'];				
				
				if ($_FILES["file"]["error"] > 0){
					echo "<h3>Error: </h3>" . $_FILES["file"]["error"] . "<br />";
				  } else {
					if($_FILES["file"]["type"] == "application/pdf"){	
						$filename = $_FILES["file"]["tmp_name"];
						$handle = fopen($filename, "r");
						$contents = fread($handle, filesize($filename));
						$contents = pdf2text($filename);
						check_file_font($contents);
					}
					else if(contains("document", $_FILES["file"]["type"])){						
						$filename = $_FILES["file"]["tmp_name"];
						$handle = fopen($filename, "r"); 
						$contents = fread($handle, filesize($filename));
						$contents = read_docx($filename);
						check_file_font($contents);
					}
					else if($_FILES["file"]["type"] == "text/plain"){
						$filename = $_FILES["file"]["tmp_name"]; 
						$handle = fopen($filename, "r"); 
						$contents = fread($handle, filesize($filename)); 
						check_file_font($contents);
						
					}
					else if($_FILES["file"]["type"] == "application/msword"){
						$filename = $_FILES["file"]["tmp_name"];
						$handle = fopen($filename, "r"); 
						$contents = fread($handle, filesize($filename)); 
						$contents = read_doc_file($filename);
						check_file_font($contents);
					}
					else{
						echo "<script> alert('There is no functionality provided for this kind of files! Please insert another type of file.');
										   window.location ='search_a_word_in_file.php'; </script>";
					}
				  }
				  
				$flag_font = False;
				$dol_ascii = str_split($word);
				
				foreach($dol_ascii as $d){
					if(ord($d) >= 65 && ord($d) <= 122)
						continue;
					else{
						$flag_font = True;
						break;
					}
				}

				if($flag_font == True){
					echo "<script> alert('Enter a word in the standard font!');
					window.location ='search_a_word_in_file.php'; </script>";
					exit();
				}
				else{
					echo "<center><h3>The <b>word</b> will be searching for in the file is: " . "<strong><u>" . $word . "</u></strong></h3></center><br><br>";
				}
				 
					function contains($needle, $haystack){
						return strpos($haystack, $needle) !== false;
					}
					
					function check_file_font($contents){
						$cont = trim($contents);
						$sodrzina = preg_split('/[\s,]+/', $cont);
						$sodrzina = preg_replace('#[[:punct:]]#', ' ', $sodrzina);
						$flag_font1 = False;
						
						foreach($sodrzina as $d){
							
							$posebno_zbor = str_split($d);
								foreach($posebno_zbor as $p){
									if(ord($p) >= 65 && ord($p) <= 122)
										continue;
									else if(is_numeric($p)){
										continue;
									}
									else if(ord($p) == 32)
										continue;
									else{
										$flag_font1 = True;
										break;
									}
								}
						}
						
						if($flag_font1 == True){
							echo "<script> alert('Upload a file in the standard font!');
								window.location ='search_a_word_in_file.php'; </script>";
						}
					}
				?>
				<tr style="background-color: #D0D0D0;">
					<td><b>File name</b></td>
					<td> 
						<?php echo $_FILES["file"]["name"];	?>
					</td>
				</tr>
				<tr style="background-color: #D0D0D0;">
					<th class="text-center">Word has appeared in the text</th>				
					<td>
						<?php
							$contents = strtolower($contents);
							$word = strtolower($word);
							$split_strings = preg_split('/[\s,]+/', $contents);
							$test = str_replace(array('?',"!",",",";",":",".","@","#","$","%","^","&","*","(",")","[","]","{","}","<",">","/","\\","+","-","=","_","~","`","|","\'","\""), "", $contents);	
								
								$razdeli_test = preg_split('/[\s,]+/', $test);
								$counter1 = 0;
								$word = strtolower($word);
								
								foreach($razdeli_test as $str){
									if(strcmp($word, strtolower($str)) == 0){
										$counter1+=1;
									}
								}
								echo $counter1 . " times";
						?>
					</td>
				</tr>
				<tr style="background-color: #D8D8D8;">
					<th class="text-center">Number of total chars the word owns</th>
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
				<tr style="background-color: #E0E0E0;">
					<th class="text-center">Number of different chars the word owns</td>
					<td>
						<?php
							echo strlen(count_chars($word, 3));
						?>
					</td>
				</tr>
				<tr style="background-color: #E8E8E8;">
					<th class="text-center">Number of numerals chars the word owns</th>
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
				<tr style="background-color: #F0F0F0;">
					<th class="text-center">Word appears at the beginning of the sentence</th>
					<td>
						<?php
							$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
                                                           
                            $sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $contents);
                            $contents = strtolower($contents);
                            $word = strtolower($word);
                            $counterBeginning = 0;                         
                           
                            foreach($sentences as $recenica){
                                $recenica = str_replace(".", "", $recenica);
                                $r = explode(" ", $recenica);                              
                               
                                $s = 0;
                                $test_niza = str_split($r[0]);
                                foreach($test_niza as $kar){
                                    $s += ord($kar);
                                }
                               
                                $s1 = 0;
                                $test_niza1 = str_split($word);
                                foreach($test_niza1 as $kar1){
                                    $s1 += ord($kar1);
                                }
                               
                               
                                if(strcmp(strtolower($r[0]), strtolower($word)) == 0){
                                    $counterBeginning ++;
                                }
                                else if(strcmp(strtolower($r[0]), strtolower($word)) < 0){
                                    $s -= 23;
                                    if($s == $s1)
                                        $counterBeginning += 1;
                                }
                            }
                           
                            echo $counterBeginning . " times";
                        ?>
					</td>
				</tr>
				<tr style="background-color: #F8F8F8;">
					<th class="text-center">Word appears at the end of the sentence</th>
					<td>
					<?php
						$counterEnd = 0;
						$contents = strtolower($contents);
							$word = strtolower($word);
						foreach($sentences as $recenica){
							$recenica = str_replace(".", "", $recenica);
							$recenica = str_replace("?", "", $recenica);
							$recenica = str_replace("!", "", $recenica);
							$r = explode(" ", $recenica);
							if(strcmp(strtolower($r[count($r)-1]), $word) == 0)
								$counterEnd += 1;
						}
						echo $counterEnd . " times";
					?>
					</td>
				</tr>	
			</table>	
		</div>
	</div>
    </div>
</div><br><br><br>
<br><br>

<div class="navbar navbar-fixed-bottom" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>