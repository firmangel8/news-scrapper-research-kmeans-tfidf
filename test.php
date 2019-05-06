<?php 
    require_once 'vendor/autoload.php';
    use Phpml\Clustering\KMeans;

    $kmeans = new KMeans(2);
    $samples = [[1, 1], [8, 7], [1, 2], [7, 8], [2, 1], [8, 9]];
    $kmeans = new KMeans(2);
    $data = $kmeans->cluster($samples);
    // print_r($data);
    foreach($data as $d){
        echo $d[0][0];
        echo "<br>";
    }
?>
