<?php						

	$filename = $_FILES["file"]["tmp_name"];
	$handle = fopen($filename, "r"); 
	$contents = fread($handle, filesize($filename)); //do tuka
	
	$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
	$content = str_replace('</w:r></w:p>', "\r\n", $content);
	$striped_content = strip_tags($content);
	$content = $striped_content;
			
	//$contents = fopen_utf8($filename);
	
	$line= $contents;
	echo "<br>";
	echo "Sodrzina: " . $contents."<br><br>";
	/*if(mb_detect_encoding($line, "UTF-8") == TRUE){
		$flag = 1;
	}
	else{
		$flag = 0;
	}
	echo "<b>Detect UTF-8: </b>" . $flag;
	echo "<br>";*/
	
	function str_word_count_utf8($str) {
	  return count(preg_split('~[^\p{L}\p{N}\']+~u',$str));
	}
	
	
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
		}
	}
	
	echo "<b>Short words: </b>" . ($counter4-1);
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
		}
	}
	
	echo "<b>Long words: </b>" . $counter5;
	echo "<br>";
	
	//whitespaces
	$count_whitespaces = substr_count($contents, " ");
	$count_newline = mb_substr($contents, 0, "\n", 'UTF-8');
	$whitespaces = $count_whitespaces + $count_newline;
	echo "<b>Whitespaces: </b>" . $whitespaces;
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
									if(strpos($str[$i], '.')){
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
									if(strpos($str[$i], '.')){
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