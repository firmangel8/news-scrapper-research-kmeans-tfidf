<?php
    

    function coreProcess($solver){
        include 'config/connect.php';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        require_once "scraplib/KMeans/Space.php";
        require_once "scraplib/KMeans/Point.php";
        require_once "scraplib/KMeans/Cluster.php";

        $getSpace = "SELECT DISTINCT(website_id) FROM words";
        $getPointerSpace = "SELECT MIN(space_counter) as spacer FROM sv_space_counter_payload";

        $counterSpace = $conn->query($getSpace);
        $counterPointerSpace = $conn->query($getPointerSpace);

        foreach($counterPointerSpace as $spacer){
            $spaceSize = $spacer['spacer'];
        }
        $space = new KMeans\Space($spaceSize);

        $payload = array();
        $payloadPoints = array();

        foreach($counterSpace as $pointer){
            $pointerSerial = $pointer['website_id'];
            $getInstances = "SELECT words_tfidf, website_url FROM words WHERE website_id='$pointerSerial' LIMIT $spaceSize";
            foreach($conn->query($getInstances) as $dataPointer){
                $payload[] = $dataPointer['words_tfidf'];
            }
            array_push($payloadPoints, $payload);
            $payload = array();
        }

        foreach ($payloadPoints as $i => $coordinates) {
            $space->addPoint($coordinates);
        }
        
        $clusters = $space->solve($solver, KMeans\Space::SEED_DEFAULT);

        $payloadResult = array();
        $instanceResult = array();

        foreach ($clusters as $i => $cluster){
            // echo "Cluster ke-".$i." dengan centroid [".round($cluster[0],2).",".round($cluster[1],2)."] dan jumlah anggota ".count($cluster);
            foreach ($cluster as $c) {
                // echo "[".$c[0].",".$c[1].",".$c[2].",".$c[3]."] ";
                $instanceResult[] = array(
                    "C0" => $c[0],
                    "C1" => $c[1],
                    "C2" => $c[2],
                    "C3" => $c[3],
                );
                // print_r($c);
            }
            // echo "<br/>";
            $x = round($cluster[0],2);
            $y = round($cluster[1],2);

              
            $payloadResult[]=array(
                "indexer" => $i,
                "counter" => count($cluster),
                "positionX" => $x,
                "positionY" => $y, 
                "cluster" => $instanceResult
            );
            $instanceResult = array();

            
        }
        return $payloadResult;
        // return $payloadPoints;
        // return 1;
    }

    
    // print_r(coreProcess(4));
    // echo "testr";
    // print_r(oreProcess(4));

    
   
?>