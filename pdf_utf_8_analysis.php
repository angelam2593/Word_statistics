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

<?php			
	$filename = $_FILES["file"]["tmp_name"];
	$handle = fopen($filename, "r"); 
	$contents = fread($handle, filesize($filename)); //do tuka
								
	$contents = pdf2text($filename);
	
	$line= $contents;
	echo "<br>";
	echo "Sodrzina: " . $contents."<br><br>";
	
	//function str_word_count_utf8($str) {
	//  return count(preg_split('~[^\p{L}\p{N}\']+~u',$str));
	//}
	
	//words
	$line = str_replace("\n", " ", $line);
	$str = explode(" ", $line);

	function my_word_count($str) {
	  $mystr = str_replace("\xC2\xAD",'', $str);
	  return preg_match_all('~[\p{L}\'\-]+~u', $mystr);
	}
	$count_words = my_word_count($contents);
	echo "<b>Broj na zborovi:</b> " . $count_words;
	echo "<br>";
	
	//numbers
	$counter2 = 0;
	foreach($str as $el){
		if(is_numeric($el))
			$counter2 ++;
	}
	echo "<b>Broj na broevi:</b> " . $counter2 . "\n";
	echo "<br>";
	
	//reading time
	$contents = preg_replace("#[[:punct:]]#", "", $contents);
	$str_bez_interp = explode(" ", $contents);
	echo "<b>Reading time</b>: ";
	if(count($str_bez_interp)<275){
		echo "Less than a minute";	
	}
	else if(count($str_bez_interp)==275){
		echo "For a minute";
	}
		else{
	$temp = count($str_bez_interp)/275;
		echo "Approximately " . round($temp, 1) . " min";
	}
	echo "<br>";	
	
	//speaking time
	$contents = preg_replace("#[[:punct:]]#", "", $contents);
	$str_bez_interp = explode(" ", $contents);
	echo "<b>Speaking time</b>: ";
	if(count($str_bez_interp)<180){
		echo "Less than a minute";	
	}
	else if(count($str_bez_interp)==180){
		echo "For a minute";	
	}
	else{
		$temp = count($str_bez_interp)/180;
		echo "Approximately " . round($temp, 1) . " min";
	}
	echo "<br>";
	
	//sentences
	$counter3 = 0;
	foreach($str as $s){
		if(preg_match('/[.!?;]/u', $s)){
			$counter3 += 1;
		}
	}
	echo "<b>Sentences: </b>" . $counter3; 
	echo "<br>";
	
	//short words
	$counter4 = 0;
	$str_i = preg_replace('#[[:punct:]]#', '', $str);
	foreach($str_i as $s){
		if(is_numeric($s))
			continue;
		if(strlen(utf8_decode($s))>=1 && strlen(utf8_decode($s))<=3)
		{
			$counter4 += 1;
			//echo $s . " ";
		}
	}
	
	echo "<b>Short words: </b>" . $counter4;
	echo "<br>";
	
	//long words
	$counter5 = 0;
	$str_i = preg_replace('#[[:punct:]]#', '', $str);
	
	foreach($str_i as $s){
		if(is_numeric($s))
			continue;
		if(strlen(utf8_decode($s))>=7)
		{
			$counter5 += 1;
			//echo $s . " ";
		}
	}
	
	echo "<b>Long words: </b>" . $counter5;
	echo "<br>";
	
	//whitespaces
	$count_whitespaces = substr_count($contents, " ");
	$count_newline = mb_substr($contents, 0, "\n", 'UTF-8');
	$whitespaces = $count_whitespaces + $count_newline;
	echo "<b>Whitespaces: </b>" . ($whitespaces-2);
	echo "<br>";
	
	//chars(with spaces)
	$brisi_nov_red = str_replace("\n", "", $contents);
	$no_spaces = mb_strlen(utf8_decode($brisi_nov_red)) - $whitespaces;
	$pom = $no_spaces + $whitespaces;
	echo "<b>Characters with spaces: </b>" . ($pom+1);
	echo "<br>";
	
	//chars(without spaces)
	$brisi_nov_red = str_replace("\n", "", $contents);
	$no_spaces = mb_strlen(utf8_decode($contents)) - $whitespaces;
	echo "<b>Characters without spaces: </b>" . ($no_spaces+1);
	echo "<br>";
	
	//longest sentence
								$sobiraj_zborovi = 0;
								$niza = array(); 
								$max_niza = 0;
								
								for($i = 0; $i < sizeof($str); $i++)
								{									
									if(strpos($str[$i], '.') || strpos($str[$i], '!') || strpos($str[$i], '?') || strpos($str[$i], '?!')){
										$sobiraj_zborovi += mb_strlen($str[$i]);
										array_push($niza, $sobiraj_zborovi);
										$sobiraj_zborovi = 0;
									}
									else{
										$sobiraj_zborovi += mb_strlen($str[$i]);
									}
								}
								//print_r($niza)."<br>";
									
								for($j = 0; $j < sizeof($niza); $j++){
									$max_niza = $niza[0];
									
									if($niza[$j] > $max_niza)
										$max_niza = $niza[$j];
								}
	
	echo "<b>Longest sentence: </b>" . $max_niza;
	echo "<br>";
	
	//shortest sentence
								$sobiraj_zborovi = 0;
								$niza = array(); 
								$min_niza = 0;
								
								for($i = 0; $i < sizeof($str); $i++)
								{									
									if(strpos($str[$i], '.') || strpos($str[$i], '!') || strpos($str[$i], '?') || strpos($str[$i], '?!')){
										$sobiraj_zborovi += mb_strlen($str[$i]);
										array_push($niza, $sobiraj_zborovi);
										$sobiraj_zborovi = 0;
									}
									else{
										$sobiraj_zborovi += mb_strlen($str[$i]);
									}
								}
								//print_r($niza)."<br>";
									
								for($j = 0; $j < sizeof($niza); $j++){
									$min_niza = $niza[0];
									
									if($niza[$j] < $min_niza)
										$min_niza = $niza[$j];
								}
	
	echo "<b>Shortest sentence: </b>" . $min_niza;
	echo "<br>";
	
	
	//average words length
	$sum = 0;
	$str_i = preg_replace('#[[:punct:]]#', '', $str);
	foreach($str_i as $s){
		$sum += mb_strlen(utf8_decode($s));
	}
	$pom1 = $sum/count($str_i);
	echo "<b>Average words length: </b>" . number_format((float)$pom1, 2, '.', '');
	
?>