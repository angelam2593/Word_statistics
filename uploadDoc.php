<?php

	$myFile = "C:\Users\AngelaM\Documents\molbaKlara.docx";
    $fh = fopen($myFile, 'r');
    $theData = read_docx($myFile);
    fclose($fh);
    echo $theData;
	
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