<?php
error_reporting(E_ERROR | E_PARSE);

function googleSearch ($key,$jml_halaman){
    
    require_once('modul/scraplib/simple_html_dom.php');

    $i=0;
    $doc_id = 0;

    $viewSearch = "<h4><i class=\"icon fa fa-search\"></i> Keyword: ".$key."</h4>";

    $key = str_replace(" ","+",$key);//replace space in keyword

    $web_no = 1;

    while ($web_no<=$jml_halaman){

        $url  = 'http://www.google.com/search?hl=id&safe=active&tbo=d&site=&source=hp&q='.$key.'&oq='.$key.'&start='.$i;
        $html = file_get_html($url);


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
            
            $viewSearch .= "<div class=\"callout callout-info\">";
            $viewSearch .= "<h4><i class=\"icon fa fa-check\"></i> #".$web_no." Title: ". $title . "</h4><br />";
            $viewSearch .= "<b>Link:</b> <a href=\"".$link."\" target=\"_blank\"> ". $link . "</a><br>"; 
            $viewSearch .= "<b>Description : </b><br>";
            $viewSearch .= $des[$a]->outertext . '<br>';


            $html2 = file_get_html($link);  

            if($html2==TRUE){
                
            }else{
                $viewSearch .= "CAN NOT SCRAP THE WEBSITE CONTENT!";
            }


            $viewSearch .= "</div>";
            
            $a++;

            if($web_no<$jml_halaman){
                $web_no++;
            }else{
                break 2;
            }
            
             

        }
        $i = $i+10;

    }


    return $viewSearch;
}

?>