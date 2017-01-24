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

    <div class="container" style="margin-top: 110px;">
        <div class="row">
            <div class="col-sm-10">
                <h2><b>Word statistics of two files</b></h2><br><br>

                <table style="width:100%" class="text-center">
                    <tr>
					    <th></th>
					    <th class="text-center"><h4><b>File 1</b></h4></th>
					    <th class="text-center"><h4><b>File 2</b></h4></th>
				    </tr>
					<tr>
                        <td><b>Type</b></td>
                        <td>
                            <?php 
								if ($_FILES["file1"]["error"] > 0){
									echo "<h3>Error: </h3>" . $_FILES["file1"]["error"] . "<br />";
								  } else {
									echo $_FILES["file1"]["type"];
									
									//echo "PROVERKI:" . "<br />"; //ovie mozam so SWITCH da gi napravam i treba da gi namestam za info kako da se prikazuva
									if($_FILES["file1"]["type"] == "application/pdf"){	
										//echo "PDF E";
										$filename = $_FILES["file1"]["tmp_name"]; //ja naoga patekata kaj so e socuvan fajlot
										$handle = fopen($filename, "r"); //go otvara fajlot za da ima pristap do sodrzinata
										$contents = fread($handle, filesize($filename)); //ja zacuvuva sodrzinata vo promenliva
									}
									else if(contains("document", $_FILES["file1"]["type"])){
										//echo "DOC E";
										$filename = $_FILES["file1"]["tmp_name"];
										$handle = fopen($filename, "r"); 
										$contents = fread($handle, filesize($filename));
										$contents = read_docx($filename); //preprocesiranje na docx fajlot da se vmetre readable sodrzina vo promenliva		
									}
									else if($_FILES["file1"]["type"] == "text/plain"){
										//echo "TXT E";
										$filename = $_FILES["file1"]["tmp_name"]; 
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
                        </td>
						<td>
                            <?php
								if ($_FILES["file2"]["error"] > 0){
									echo "<h3>Error: </h3>" . $_FILES["file2"]["error"] . "<br />";
								  } else {
									echo $_FILES["file2"]["type"];
									
									//echo "PROVERKI:" . "<br />"; //ovie mozam so SWITCH da gi napravam i treba da gi namestam za info kako da se prikazuva
									if($_FILES["file2"]["type"] == "application/pdf"){	
										//echo "PDF E";
										$filename2 = $_FILES["file2"]["tmp_name"]; //ja naoga patekata kaj so e socuvan fajlot
										$handle2 = fopen($filename2, "r"); //go otvara fajlot za da ima pristap do sodrzinata
										$contents2 = fread($handle2, filesize($filename2)); //ja zacuvuva sodrzinata vo promenliva
									}
									else if(contains("document", $_FILES["file2"]["type"])){
										//echo "DOC E";
										$filename2 = $_FILES["file2"]["tmp_name"];
										$handle2 = fopen($filename2, "r"); 
										$contents2 = fread($handle2, filesize($filename2));
										$contents2 = read_docx($filename2); //preprocesiranje na docx fajlot da se vmetre readable sodrzina vo promenliva		
									}
									else if($_FILES["file2"]["type"] == "text/plain"){
										//echo "TXT E";
										$filename2 = $_FILES["file2"]["tmp_name"]; 
										$handle2 = fopen($filename2, "r"); 
										$contents2 = fread($handle2, filesize($filename2)); 
									}
									else{
										echo "NEMAME FUNKCIONALNOST ZA DRUGI TIPOVI NA FAJLOVI";
									}
								  }
							?>
                        </td>
                    </tr>
					<tr>
                        <td><b>Size</b></td>
                        <td>
                            <?php $size = ($_FILES["file1"]["size"] / 1024);
								echo round($size, 2) . " Kb<br />";	?>
                        </td>
						<td>
                            <?php $size = ($_FILES["file2"]["size"] / 1024);
								echo round($size, 2) . " Kb<br />";	?>
                        </td>
                    </tr>
					<tr>
                        <td><b>Words</b></td>
                        <td>
                            <?php echo str_word_count($contents); ?>
                        </td>
						<td>
                            <?php echo str_word_count($contents2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Numbers</b></td>
                        <td>
                            <?php
								$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
								$count_num = 0;
								$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $contents);
								$split_strings = preg_split('/[\ \n\,]+/', $brisi_interpuncii);			//pravi niza od zborovi
								
								foreach($split_strings as $str){
									if(is_numeric($str)){
										$count_num += 1;
									}
								}
								echo $count_num;	
							?>
                        </td>
						<td>
                            <?php
								$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents2);
								$count_num = 0;
								$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $contents2);
								$split_strings = preg_split('/[\ \n\,]+/', $brisi_interpuncii);			//pravi niza od zborovi
								
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
                        <td>
                            <?php
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
							?>
                        </td>
						<td>
                            <?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents2);
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
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Speaking time</b></td>
                        <td>
                            <?php
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
							?>
                        </td>
						<td>
                            <?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents2);
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
							?>
                        </td>
                    </tr>
					<tr>
                        <td><b>Long words</b></td>
                        <td>
                            <?php
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
							?>
                        </td>
						<td>
                            <?php
								$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $contents2);
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
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Short words</b></td>
                        <td>
                            <?php
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
								echo $brojac;
							?>
                        </td>
						<td>
                            <?php
								$brisi_interpuncii = preg_replace('/[^a-zA-Z 0-9]+/', ' ', $contents2);
								$split_strings = preg_split('/[\ \n\,]+/', $brisi_interpuncii);			//pravi niza od zborovi
								$brojac = 0;
								
								foreach($split_strings as $el){
									if(strlen($el)<=3){
										if(!is_numeric($el)){
											$brojac += 1;
										}
									}
								}
								echo $brojac;
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Sentences</b></td>
                        <td>
                            <?php
								function countSentences($str){
									return preg_match_all('/[^\s](\.|\!|\?)(?!\w)/',$str,$match);
								}
								$res = countSentences($contents);
								echo $res; 
							?>
                        </td>
						<td>
                            <?php			
								$res = countSentences($contents2);
								echo $res; 
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Whitespaces</b></td>
                        <td>
                            <?php
								$count_whitespaces = substr_count($contents, ' ');
								echo $count_whitespaces; 
							?>
                        </td>
						<td>
                            <?php			
								$split_strings = preg_split('/[\ \n\,]+/', $contents2);
								$br = count($split_strings) - 1;
								echo $br; 
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Characters (with spaces)</b></td>
                        <td>
                            <?php echo strlen($contents); ?>
                        </td>
						<td>
                            <?php
								$strArray = count_chars($contents2,0);
								$brojacFile2 = 0;
								foreach ($strArray as $key=>$value){
									$brojacFile2 += $value;
							   }
								echo $brojacFile2; 
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Characters (no spaces)</b></td>
                        <td>
                            <?php
								$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);		//sandra
								$split_strings = preg_split('/[\ \n\,]+/', $remove_new_line);			//pravi niza od zborovi
								
								$resultNoWhitespacesChars = strlen($remove_new_line) - $count_whitespaces;
								echo $resultNoWhitespacesChars;
							?>
                        </td>
						<td>
                            <?php
								$resultNoWhitespacesChars = $brojacFile2 - $br;
								echo $resultNoWhitespacesChars; 
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Length of longest sentence</b></td>
                        <td>
                            <?php
								$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
								$split_strings = preg_split('/[\s,]+/', $remove_new_line);
								$sobiraj_zborovi = 0;
								$niza = array(); 
								
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
							?>
                        </td>
						<td>
                            <?php
								$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents2);
								$split_strings = preg_split('/[\s,]+/', $remove_new_line);
								$sobiraj_zborovi = 0;
								$niza = array(); 
								
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
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Length of shortest sentence</b></td>
                        <td>
                            <?php			
								$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents);
								$split_strings = preg_split('/[\s,]+/', $remove_new_line);
								$sobiraj_zborovi = 0;
								$niza = array(); 
								
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
							?>
                        </td>
						<td>
                            <?php			
								$remove_new_line = preg_replace('/[\ \n]+/', ' ', $contents2);
								$split_strings = preg_split('/[\s,]+/', $remove_new_line);
								$sobiraj_zborovi = 0;
								$niza = array(); 
								
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
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Average word length</b></td>
                        <td>
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
						<td>
                            <?php
								$split_strings = preg_split('/[\ \n\,]+/', $contents2);
								$sum = 0;
								foreach($split_strings as $str){
									$sum += strlen($str);
								}
								$result = ($sum - substr_count($contents2, ' '))/count($split_strings);
								echo number_format((float)$result, 2, '.', ''); 
							?>
                        </td>
                    </tr>
                </table>
            </div>

        </div>

        
    </div><br><br><br>
    <br><br>

    <div class="navbar" style="padding-top: 15px; color: dimgray; margin-bottom: 0px; background-color: black; border-radius: 0px; opacity: 0.8; margin-top: 40px;">
        <p class="text-center">&copy; Copyrights FINKI</p>
    </div>
</body>
</html>