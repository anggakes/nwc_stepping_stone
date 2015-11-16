<?php

include_once("NWC.class.php");
include_once("SteppingStone.class.php");

$qs = $_POST['y'];
$qd = $_POST['x'];
$d 	= $_POST['a'];


$nwc= new NWC($qs, $qd, $d);

$nwc1 = $nwc->exec();
//print_r(json_encode($nwc1));

/*
$nwc = [
	"qSupply"=>[40,60,50],
	"qDemand"=>[20,30,50,50],
	"demand"=>[[4,6,8,8],[6,8,6,7],[5,7,6,8]],
	"hasilDemand"=>[[10,30,0,0],[0,0,50,10],[10,0,0,40]]
];
*/
//print_r(json_encode($nwc1));

$steppingStone = new SteppingStone($nwc1);


$ss = $steppingStone->exec();
//print_r(json_encode($ss));
function createViewTable($ss){

	$table = "<table class='table' border='1'>";
	// header table 

	$table .= "<thead>";
	$table .= "<tr>";
	$table .= "<td> Supply/Demand</td>";
		
		for($i=0;$i<count($ss['qDemand']);$i++){

			$table .= "<td>Demand ".($i+1)."</td>";
		}
	$table .= "<td> Q Supply</td>";	
	$table .= "</tr>";
	$table .= "</thead>";
	$demand = $ss["demand"];
	$hasilDemand = $ss["hasilDemand"];
	//print_r(json_encode($demand));
	for($h=0 ; $h<count($ss['qSupply']);$h++){
		$table .= "<tr>";
		$table .= "<td> Supply ".($h+1)."</td>";
			
			for($i=0;$i<count($ss['qDemand']);$i++){

				$table .= "<td>
<table ><tr ><td  rowspan='2' width='50'>" . $hasilDemand[$h][$i] . '</td><td style="border-left:1px solid;border-bottom:1px solid;" >' . $demand[$h][$i] . "</td></tr><tr><td>&nbsp;</td></tr></table>

				</td>";
			}
		$table .= "<td>". $ss["qSupply"][$h] . "</td>";	
		$table .= "</tr>";
	}

	$table .= "<tr>";
	$table .= "<td> Q Demand</td>";
			
	for($i=0;$i<count($ss['qDemand']);$i++){

		$table .= "<td>". $ss["qDemand"][$i] . "</td>";	
	}
	$table .= "</tr>";

	// body table 
	$table .= "</table>";

	return $table;
}

function total($ss){
	$demand = $ss["demand"];
	$hasilDemand = $ss["hasilDemand"];
	$total = 0;
	//print_r(json_encode($demand));
	for($h=0 ; $h<count($ss['qSupply']);$h++){
	
			for($i=0;$i<count($ss['qDemand']);$i++){

				if($hasilDemand[$h][$i] > 0){
					$total += $hasilDemand[$h][$i]*$demand[$h][$i];
				}
			}

	}

	return $total;

}

function toRp($nilai){
	return number_format($nilai,0,'.',',');
}

echo createViewTable($ss);
echo "<strong>Total : Rp. ". toRp(total($ss))."</strong>";


?>