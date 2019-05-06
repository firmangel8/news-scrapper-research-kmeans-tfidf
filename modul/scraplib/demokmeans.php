<?php


ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

// include the library
require_once "KMeans/Space.php";
require_once "KMeans/Point.php";
require_once "KMeans/Cluster.php";

// prepare 50 2D points to be clustered
/*
$points = [
	[80,55],[86,59],[19,85],[41,47],[57,58],
	[76,22],[94,60],[13,93],[90,48],[52,54],
	[62,46],[88,44],[85,24],[63,14],[51,40],
	[75,31],[86,62],[81,95],[47,22],[43,95],
	[71,19],[17,65],[69,21],[59,60],[59,12],
	[15,22],[49,93],[56,35],[18,20],[39,59],
	[50,15],[81,36],[67,62],[32,15],[75,65],
	[10,47],[75,18],[13,45],[30,62],[95,79],
	[64,11],[92,14],[94,49],[39,13],[60,68],
	[62,10],[74,44],[37,42],[97,60],[47,73],
];

*/


// $points = [
// 	[80,55,86,59],[19,85,41,47],[57,58,76,22],[94,60,13,93],[90,48,52,54],
// 	[62,46,88,44],[85,24,63,14],[51,40,75,31],[86,62,81,95],[47,22,43,95],
// 	[71,19,17,65],[69,21,59,60],[59,12,15,22],[49,93,56,35],[18,20,39,59],
// 	[50,15,81,36],[67,62,32,15],[75,65,10,47],[75,18,13,45],[30,62,95,79],
// 	[64,11,92,14],[94,49,39,13],[60,68,62,10],[74,44,37,42],[97,60,47,73],
// ];

$points = array(array(80,55,86,59), array(19,85,41,47),array(57,58,76,22),array(94,60,13,93),array(90,48,52,54),
					array(62,46,88,44),array(85,24,63,14),array(51,40,75,31),array(86,62,81,95),array(47,22,43,95),
					array(71,19,17,65),array(69,21,59,60),array(59,12,15,22),array(49,93,56,35),array(18,20,39,59),
					array(50,15,81,36),array(67,62,32,15),array(75,65,10,47),array(75,18,13,45),array(30,62,95,79),
					array(64,11,92,14),array(94,49,39,13),array(60,68,62,10),array(74,44,37,42),array(97,60,47,73),
);
print_r($points);
// exit();


echo "Initialize points...\n";


/*
$points = [];
for ($i=0; $i < $n = 1000000; $i++) {
	$points[] = [mt_rand(0, 100), mt_rand(0, 100)];
	//printf("\r%.2f%%", ($i / $n) * 100);
} */

echo "\nDone.";
echo "\nCreating Space...\n";

// create a 2-dimentions space
$space = new KMeans\Space(4);

// add points to space
foreach ($points as $i => $coordinates) {
	$space->addPoint($coordinates);
	//printf("\r%.2f%%", ($i / $n) * 100);
}

print_r($space);

echo "done... <br><br>";
echo "Determining clusters";

// cluster these 50 points in 3 clusters
$clusters = $space->solve(2, KMeans\Space::SEED_DEFAULT, function () {
	echo ".";
});

echo "\n\n<br>";

// display the cluster centers and attached points
foreach ($clusters as $i => $cluster){
	echo "Cluster ke-".$i." dengan centroid [".round($cluster[0],2).",".round($cluster[1],2)."] dan jumlah anggota ".count($cluster)."<br>";

	foreach ($cluster as $c) {
		echo "[".$c[0].",".$c[1].",".$c[2].",".$c[3]."] ";
	}

	echo "<br>";
	//var_dump($cluster);
	//printf("Cluster %s [%d,%d]: %d points\n", $i, $cluster[0], $cluster[1], count($cluster));
}










