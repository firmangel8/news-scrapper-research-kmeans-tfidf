<?php
//error_reporting(E_ERROR | E_PARSE);
require_once 'vendor/autoload.php';
require_once 'scraplib/autoload.php';
require_once 'scraplib/simple_html_dom.php';

// require_once '../vendor/autoload.php';
// require_once '../vendor/autoload.php';
use Phpml\Clustering\KMeans;






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
        //load stopword library
        $filename = "modul/stopwords.txt";
        $fp = @fopen($filename, 'r'); 

        // Add each line to an array
        if ($fp) {
        $stoplist = explode("\n", fread($fp, filesize($filename)));
        }
     //check stopword
     foreach ($term as $t) {
        if (!in_array($t, $stoplist) && $t!="") {//stop words removal
            $term_clean[$x] = $t;
            $x++;
        }
     }

     return $term_clean;
}


//hitung tf-idf
function processTFIDF($docs,$term,$tf){
    $df = 0;
    $idf = 0;
    //var_dump($docs[0]);
    for($j=0;$j<sizeof($docs);$j++){
         foreach ($docs[$j] as $dt) {
            if($dt[0] == $term){
                $df++;
                break;
            }
        }
    
    }

    $idf = log((sizeof($docs)-1)/$df);
    $tfidf = round($tf*$idf,4);


    return $tfidf;
}


function preProcessing($key,$jml_halaman){

    include 'config/connect.php';

    $i=0;
    $doc_id = 0;


    $viewSearch = "<h4><i class=\"icon fa fa-search\"></i> Keyword: ".$key."</h4>";
    $key = str_replace(" ","+",$key);//replace space in keyword
   

    while ($i<=$jml_halaman){

        $url  = 'http://www.google.com/search?hl=id&safe=active&tbo=d&site=&source=hp&q='.$key.'&oq='.$key.'&start='.$i;
        $html = file_get_html($url);

        //echo "<br>=============== Halaman Ke-".$i." ==================== <br>";

        $linkObjs = $html->find('h3.r a');
        $des = $html->find('span.st');
        $a = 0;

        foreach ($linkObjs as $linkObj) {
            $title = trim($linkObj->plaintext);
            $link  = trim($linkObj->href);
            
            // if it is not a direct link but url reference found inside it, then extract
            if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
                $link = $matches[1];
            } else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
                continue;    
            }
            
            /*
            echo '<p>Title: ' . $title . '<br />';
            echo "Link: <a href=\"".$link."\" target=\"_blank\"> ". $link . "</a></p>"; 
            echo "Deskripsi : <br>";
            echo $des[$a]->outertext . '<br>';

            echo "Isi : <br>";
            
            */


            $html2 = file_get_html($link);  

            if($html2==TRUE){
                //$string = substr($html2->plaintext,0,1000);//ambil plain text
                //echo $string;

                //only tag p
                $content = "";
                foreach($html2->find('p') as $e){
                    $content .=  strip_tags($e->innertext);
                }
                
                $content = substr($content,0,1000);// ambil x char awal

                //var_dump($content);

                if ($content!="") {
                    $terms = preProcessSentence($content);

                    $terms_tf = array_count_values($terms);//hitung tf setiap kata
                    arsort($terms_tf); //sort besar ke kecil
                    
                    $terms_word=array_keys($terms_tf);//ubah keys menjadi value
                    
                    //echo "<br> Tertinggi adalah kata ".$terms_word[0]." dengan tf ".$terms_tf[$terms_word[0]] ;


                    $doc_title[$doc_id] = $title;

                    foreach ($terms_word as $k=>$w) {
                        $doc_term[$doc_id][$k][0]=$w; //terms
                        $doc_term[$doc_id][$k][1]=$terms_tf[$w]; //tf 
                        $doc_term[$doc_id][$k][2]=$link;
                    }

                    $doc_id++;
                }
                
              // if(!preg_match("/[^a-zA-Z0-9\s\p{P}]/", $html2->plaintext)) { //cegah karakter diluar alphanumeric
                    
                    //$terms[$a] = preProcessSentence($string);
               //}

            }else{
                echo "CAN NOT SCRAP THE WEBSITE!";
            }

             //echo "<hr>";
             $a++;
             

        }
        $i = $i+10;

    }

    $dataPayloadAnalysis = array ();
    // $dotp = 0 ; $maga = 0 ; $magb = 0;
    $kmeans = new KMeans(2);
    

    for($j=0;$j<$doc_id;$j++){
        $viewSearch .= "Website ke-".$j."<br>";
        $viewSearch .= "Website Title : ".$doc_title[$j]."<br>";
        $viewSearch .= "Terms : <br>";
        // $dataClustering = $kmeans->cluster($doc_term);
        foreach ($doc_term[$j] as $dt) {

            

            // //cosine similarity process
            // $dotp += 
            // //end cosine similarity process

            $tfidf = processTFIDF($doc_term,$dt[0],$dt[1]);
            
            // print_r($conn);
            //$query = "INSERT INTO words values (NULL, '$dt[0]', '$dt[1]', $tfidf, NULL)";
            $words_term = $dt[0];
            $words_tf = $dt[1];
            $urlWeb = $dt[2];
            $words_tfidf = $tfidf;
            $website_id = $j.'_'.$doc_title[$j];
            $query = "INSERT INTO words(words_id, words_term, words_tf, words_tfidf, website_id, website_url) VALUES (NULL,'$words_term','$words_tf','$words_tfidf','$website_id', '$urlWeb')";
            if($conn->query($query)){
                echo "success created";
                echo "<br>";

            }else{ echo "error"; echo "<br/>";}

            $dataPayloadAnalysis [] = array('tf'=>$dt[1],'tfidf'=>$tfidf);

            
            $viewSearch .= $dt[0]."( tf : ".$dt[1]." | tf-idf: ".$tfidf.")<br>";

            //to be continue insert ke database web dan term!!!
        }
        
        $viewSearch .= "========================= <br>";

    }
    // print_r($dt);
    
    // echo "<br>";
    // print_r('Your Data Cluster here => '.$dataClustering);

    return $viewSearch;

}



?>