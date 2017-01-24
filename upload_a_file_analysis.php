<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
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

            <div class="col-lg-2 col-sm-2 col-md-2 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <img src="img/my_documents.png" class="img-responsive">
            </div>
			
			
			
			
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
				

            <div class="col-sm-7 col-md-7 col-lg-7 text-center col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                <table style="width:100%">
                    <tr>
						<td><b>File name</b></td>
						<td> 
							<?php echo $_FILES["file"]["name"];	?>
						</td>
					</tr>
					
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
										
										$result = pdf2text($filename);
										//echo $result . "<br/>";											//go printa textot od pdf file-ot
										
										$pecati = str_word_count($result);
										echo $pecati . "<br/>";										
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
										
										$pecati = str_word_count($contents);
										echo $pecati;
									}
									else{
										echo "NEMAME FUNKCIONALNOST ZA DRUGI TIPOVI NA FAJLOVI<br>";
									}
								  }
								  
								  function contains($needle, $haystack){ 			//da se najde document vo toa ogromnoto ime kaj type 
										return strpos($haystack, $needle) !== false;
									}
								?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Numbers</b></td>
                        <td id="no_numbers">
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);		//sandra
									$count_num = 0;
									$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $contents);
									$split_strings = preg_split('/[\ \n\,]+/', $brisi_interpuncii);			//pravi niza od zborovi
									
									foreach($split_strings as $str){
										if(is_numeric($str)){
											$count_num += 1;
										}
									}									
									echo $count_num;
								}
								
								else if($_FILES["file"]["type"] == "application/pdf"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $result);		//sandra
									$count_num = 0;
									$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $result);
									$split_strings = preg_split('/[\ \n\,]+/', $brisi_interpuncii);			//pravi niza od zborovi
									
									foreach($split_strings as $str){
										if(is_numeric($str)){
											$count_num += 1;
										}
									}									
									echo $count_num;
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Reading time</b></td>
                        <td id="reading_time">
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$split_strings = preg_split('/[\ \n\,]+/', $contents);
									if(count($split_strings)<275){
										echo "Less than a minute";	
									}
									else if(count($split_strings)==275){
										echo "For a minute";
									}
									else{
										$temp = count($split_strings)/275;
										echo "Approximately " . round($temp, 1) . " min";
									}
								}
								
								else if($_FILES["file"]["type"] == "application/pdf"){
									$split_strings = preg_split('/[\ \n\,]+/', $result);
									if(count($split_strings)<275){
										echo "Less than a minute";	
									}
									else if(count($split_strings)==275){
										echo "For a minute";
									}
									else{
										$temp = count($split_strings)/275;
										echo "Approximately " . round($temp, 1) . " min";
									}
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Speaking time</b></td>
                        <td id="speaking_time">
							<?php
							if($_FILES["file"]["type"] == "text/plain"){
								$split_strings = preg_split('/[\ \n\,]+/', $contents);
								if(count($split_strings)<180){
									echo "Less than a minute";	
								}
								else if(count($split_strings)==180){
									echo "For a minute";	
								}
								else{
									$temp = count($split_strings)/180;
									echo "Approximately " . round($temp, 1) . " min";
								}
							}
							
							else if($_FILES["file"]["type"] == "application/pdf"){
								$split_strings = preg_split('/[\ \n\,]+/', $result);
								if(count($split_strings)<180){
									echo "Less than a minute";	
								}
								else if(count($split_strings)==180){
									echo "For a minute";	
								}
								else{
									$temp = count($split_strings)/180;
									echo "Approximately " . round($temp, 1) . " min";
								}
							}
						?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Short words</b></td>
                        <td id="short_words">
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $contents);
									$split_strings = preg_split('/[\ \n\,]+/', $brisi_interpuncii);			//pravi niza od zborovi
									$brojac = 0;
									
									foreach($split_strings as $el){
										if(strlen($el)<=3){
											if(!is_numeric($el)){
												$brojac += 1;
											}
										}
									}
									echo $brojac - 1;
								}
								
								else if($_FILES["file"]["type"] == "application/pdf"){
									$brisi_interpuncii_pdf = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $result);
									$split_strings_pdf = preg_split('/[\ \n\,]+/', $brisi_interpuncii_pdf);			//pravi niza od zborovi
									$brojac_pdf = 0;
									
									foreach($split_strings_pdf as $el){
										if(strlen($el)<=3){
											if(!is_numeric($el)){
												$brojac_pdf += 1;
											}
										}
									}
									echo $brojac_pdf - 1;
								}
							?>
						</td>
                    </tr>
					<tr>
                        <td><b>Long words</b></td>
                        <td id="long_words">
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $contents);
									$split_strings = preg_split('/[\ \n\,]+/', $brisi_interpuncii);			//pravi niza od zborovi
									$brojac = 0;
									
									foreach($split_strings as $el){
										if(strlen($el)>=7){
											if(!is_numeric($el)){
												$brojac++;
											}
										}
									}
									echo $brojac;
								}
								else if($_FILES["file"]["type"] == "application/pdf"){
									$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $result);
									$split_strings = preg_split('/[\ \n\,]+/', $brisi_interpuncii);			//pravi niza od zborovi
									$brojac = 0;
									
									foreach($split_strings as $el){
										if(strlen($el)>=7){
											if(!is_numeric($el)){
												$brojac++;
											}
										}
									}
									echo $brojac;
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Sentences</b></td>
                        <td id="no_sentences">
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
								
									function countSentences($str){
										return preg_match_all('/[^\s](\.|\!|\?)(?!\w)/', $str, $match);
									}
									
									$res = countSentences($remove_new_line);
									echo $res; 
								}
								else if($_FILES["file"]["type"] == "application/pdf"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $result);
								
									function countSentences($str){
										return preg_match_all('/[^\s](\.|\!|\?)(?!\w)/', $str, $match);
									}
									
									$res = countSentences($remove_new_line);
									echo $res; 
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Whitespaces</b></td>
                        <td>
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
									$count_whitespaces = substr_count($remove_new_line, " ");
									echo $count_whitespaces; 
								}
								else if($_FILES["file"]["type"] == "application/pdf"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $result);
									$count_whitespaces = substr_count($remove_new_line, " ");
									echo $count_whitespaces - 1; 
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Characters (with spaces)</b></td>
                        <td>
							<?php 
								if($_FILES["file"]["type"] == "text/plain"){
									echo strlen($contents);
								}
								else if($_FILES["file"]["type"] == "application/pdf"){
									echo $count_whitespaces + (strlen($remove_new_line) - $count_whitespaces) - 1;
								}
							?>
						</td>
                    </tr>
                    <tr>
                    <td><b>Characters (no spaces)</b></td>
                        <td id="no_chars_without_whitespaces">
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
									$split_strings = preg_split('/[\ \n\,]+/', $remove_new_line);			//pravi niza od zborovi
									
									$resultNoWhitespacesChars = strlen($remove_new_line) - $count_whitespaces;
									echo $resultNoWhitespacesChars;
								}
								else if($_FILES["file"]["type"] == "application/pdf"){
									$remove_new_line_pdf = preg_replace('/[\ \n]+/', ' ', $result);
									$split_strings = preg_split('/[\ \n\,]+/', $remove_new_line_pdf);			//pravi niza od zborovi
									
									$noWhitespaces_pdf = strlen($remove_new_line_pdf) - $count_whitespaces;
									echo $noWhitespaces_pdf;
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Length of longest sentence</b></td>
                        <td>
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
									$split_strings = preg_split('/[\s,]+/', $remove_new_line);
									$sobiraj_zborovi = 0;
									$niza = array(); 
									$max_niza = 0;
									
									for($i = 0; $i < sizeof($split_strings); $i++)
									{									
										if(strpos($split_strings[$i], '.')){
											$sobiraj_zborovi += strlen($split_strings[$i]);
											array_push($niza, $sobiraj_zborovi);
											$sobiraj_zborovi = 0;
										}
										else{
											$sobiraj_zborovi += strlen($split_strings[$i]);
										}
									}
									
									for($j = 0; $j < sizeof($niza); $j++){
										$max_niza = $niza[0];
										
										if($niza[$j] > $max_niza)
											$max_niza = $niza[$j];
									}
									echo $max_niza;
								}
								else if($_FILES["file"]["type"] == "application/pdf"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $result);
									$split_strings = preg_split('/[\s,]+/', $remove_new_line);
									$sobiraj_zborovi = 0;
									$niza = array(); 
									$max_niza = 0;
									
									for($i = 0; $i < sizeof($split_strings); $i++)
									{									
										if(strpos($split_strings[$i], '.')){
											$sobiraj_zborovi += strlen($split_strings[$i]);
											array_push($niza, $sobiraj_zborovi);
											$sobiraj_zborovi = 0;
										}
										else{
											$sobiraj_zborovi += strlen($split_strings[$i]);
										}
									}
									
									for($j = 0; $j < sizeof($niza); $j++){
										$max_niza = $niza[0];
										
										if($niza[$j] > $max_niza)
											$max_niza = $niza[$j];
									}
									echo $max_niza;
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Length of shortest sentence</b></td>
                        <td id="shortest_sentence">
							<?php			
								if($_FILES["file"]["type"] == "text/plain"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
									$split_strings = preg_split('/[\s,]+/', $remove_new_line);
									$sobiraj_zborovi = 0;
									$niza = array(); 
									$min_niza = 0;
									
									for($i = 0; $i < sizeof($split_strings); $i++)
									{									
										if(strpos($split_strings[$i], '.')){
											$sobiraj_zborovi += strlen($split_strings[$i]);
											array_push($niza, $sobiraj_zborovi);
											$sobiraj_zborovi = 0;
										}
										else{
											$sobiraj_zborovi += strlen($split_strings[$i]);
										}
									}
									
									for($j = 0; $j < sizeof($niza); $j++){
										$min_niza = $niza[0];
										
										if($niza[$j] < $min_niza)
											$min_niza = $niza[$j];
									}
									echo $min_niza;
								}
								else if($_FILES["file"]["type"] == "application/pdf"){
									$remove_new_line = preg_replace('/[\ \n]+/', ' ', $result);
									$split_strings = preg_split('/[\s,]+/', $remove_new_line);
									$sobiraj_zborovi = 0;
									$niza = array(); 
									$min_niza = 0;
									
									for($i = 0; $i < sizeof($split_strings); $i++)
									{									
										if(strpos($split_strings[$i], '.')){
											$sobiraj_zborovi += strlen($split_strings[$i]);
											array_push($niza, $sobiraj_zborovi);
											$sobiraj_zborovi = 0;
										}
										else{
											$sobiraj_zborovi += strlen($split_strings[$i]);
										}
									}
									
									for($j = 0; $j < sizeof($niza); $j++){
										$min_niza = $niza[0];
										
										if($niza[$j] < $min_niza)
											$min_niza = $niza[$j];
									}
									echo $min_niza;
								}
							?>
						</td>
                    </tr>
                    <tr>
                        <td><b>Average word length</b></td>
                        <td id="average_word_length">
							<?php
								if($_FILES["file"]["type"] == "text/plain"){
									$split_strings = preg_split('/[\ \n\,]+/', $contents);
									$sum = 0;
									foreach($split_strings as $str){
										$sum += strlen($str);
									}
									$rezultat = ($sum - substr_count($contents, ' '))/count($split_strings);
									echo number_format((float)$rezultat, 2, '.', ''); 
								}
								else if($_FILES["file"]["type"] == "application/pdf"){
									$split_strings = preg_split('/[\ \n\,]+/', $result);
									$sum = 0;
									foreach($split_strings as $str){
										$sum += strlen($str);
									}
									$rezultat = ($sum - substr_count($result, ' '))/count($split_strings);
									echo number_format((float)$rezultat, 2, '.', ''); 
								}
							?>
						</td>
                    </tr>
                </table>
            </div><br>
        </div>
    </div>
</div>

<div class="navbar" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
    <p class="text-center">&copy; Copyrights FINKI</p>
</div>
</body>
</html>	