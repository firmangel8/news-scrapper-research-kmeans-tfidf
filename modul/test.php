<?php
//error_reporting(E_ERROR | E_PARSE);

require_once 'scraplib/autoload.php';
require_once('scraplib/simple_html_dom.php');
//include('modul/preprocess.php');
//load stopword library
// Add each line to an array

$teks="Perjalanan wisata pertama dilakukan oleh Maha Rsi Markandeya dari tanah Jawa 
       untuk tujuan penyebaran Agama Hindu di Pulau Bali pada abad ke 8 Masehi. 
       Lalu terdapat juga beberapa Tokoh Spiritual lainnya datang ke Pulau Bali untuk 
       tujuan yang sama setelahnya. ";
//print_r($stoplist);

function preProcessSentence($text){
    //remove punctuation
     $text = preg_replace("/(?![.=$'€%-])\p{P}/u", " ", trim($text));

     //stem
     $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
     $stemmer  = $stemmerFactory->createStemmer();
     $text   = $stemmer->stem($text);

     //tokenization
     $term = explode(" ", $text);
     $x = 0;
     $filename = "modul/stopwords.txt";
     $fp = @fopen($filename, 'r'); 
     $stoplist = explode("\n", fread($fp, filesize($filename)));
     //check stopword
     foreach ($term as $t) {
        if (!in_array($t, $stoplist) && $t!="") {//stop words removal
            $term_clean[$x] = $t;
            $x++;
        }
     }

     return $term_clean;
}
print_r(preProcessSentence($teks));
?>