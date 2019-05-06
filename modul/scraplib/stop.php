<?php
// Open the file

$filename = "stopwords_id.txt";
$fp = @fopen($filename, 'r'); 

// Add each line to an array
if ($fp) {
   $array = explode("\n", fread($fp, filesize($filename)));
}

//var_dump($array);


if (in_array("Aditra", $array)) {
    echo "Termasuk Stop Words!";
}else{
	echo "Bukan Stop Words!";
}

?>
