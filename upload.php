	
	<?php
	if ($_FILES["file"]["error"] > 0)
	  {
		echo "<h3>Error: </h3>" . $_FILES["file"]["error"] . "<br />";
	  } else {
		echo "<h3>Upload: </h3>" . $_FILES["file"]["name"] . "<br />";
		echo "<h3>Type: </h3>" . $_FILES["file"]["type"] . "<br />";
		echo "<h3>Size: </h3>" . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		echo "<h3>Stored in: </h3>" . $_FILES["file"]["tmp_name"];
		
		
		echo "PROVERKI:" . "<br />"; //ovie mozam so SWITCH da gi napravam i treba da gi namestam za info kako da se prikazuva
		if($_FILES["file"]["type"] == "application/pdf")
			echo "PDF E";
		else if(contains("document", $_FILES["file"]["type"]))
			echo "DOC E";
		else if($_FILES["file"]["type"] == "text/plain")
			echo "TXT E";
		else
			echo "NEMAME FUNKCIONALNOST ZA DRUGI TIPOVI NA FAJLOVI";
		
		
		$filename = $_FILES["file"]["tmp_name"]; //ja naoga patekata kaj so e socuvan fajlot
		$handle = fopen($filename, "r"); //go otvara fajlot za da ima pristap do sodrzinata
		$contents = fread($handle, filesize($filename)); //ja zacuvuva sodrzinata vo promenliva
		
		echo "<br>" . "<h3>File content: </h3>";
		print $contents;

	  }
	  
	  function contains($needle, $haystack){ //da se najde document vo toa ogromnoto ime kaj type 
			return strpos($haystack, $needle) !== false;
		}
	?>
	
	<h3>Number of words, no numbers: </h3> 
	<h2><?php echo str_word_count($contents); ?></h2>
	<h3>Number of words, with numbers(Number of characters): </h3> 
	<h2>
		<?php
			$split_strings = preg_split('/[\ \n\,]+/', $contents);
			echo count($split_strings);
		?>
    </h2>
    <h3>Number of numbers found in the file: </h3> 
	<h2>
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
    </h2>	
	<h3> 	
		Reading time:
	</h3>
		<h2>
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
		</h2>
			<h3> 	
		Speaking time:
	</h3>
		<h2>
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
		</h2>
	<h3> 	
		Short words:
	</h3>
		<h2>
		<?php
			$split_strings = preg_split('/[\ \n\,]+/', $contents);
			$brojac = 0;
			foreach($split_strings as $el){
				if(strlen($el)<=3)
					$brojac++;
			}
			echo "The number of shorstest words in the file is  " . $brojac;
		?>
		</h2>
		<h3> 	
		Long words:
	</h3>
		<h2>
		<?php
			$split_strings = preg_split('/[\ \n\,]+/', $contents);
			$brojac = 0;
			foreach($split_strings as $el){
				if(strlen($el)>=7)
					$brojac++;
			}
			echo "The number of longest words in the file is " . $brojac;
		?>
		</h2>