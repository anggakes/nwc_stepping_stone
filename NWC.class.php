<?php 

class NWC{

	public $qSupply = array();
	public $qDemand = array();
	public $hasil = array();
	public $demand = array();



	public function __construct($qSupply, $qDemand, $demand){

		$this->qSupply 	= $qSupply;
		$this->qDemand 	= $qDemand;
		$this->demand	= $demand;

	}

	public function exec(){
		$this->cekDummy();
		$data["qSupply"] = $this->qSupply;
		$data["qDemand"] = $this->qDemand;
		$data["demand"]	= $this->demand;
		$data['hasilDemand'] = $this->isiKosong($this->count());
		return $data;
	}

	public function cekDummy(){

	$totalSupply = $this->totalForDummy($this->qSupply);
	$totalDemand = $this->totalForDummy($this->qDemand);;

//pengecekan jika terjadi dummy
    if ($totalSupply > $totalDemand) {

        $selisih = $totalSupply - $totalDemand;
        
        $this->qDemand[] = $selisih;

        //status dummy

        for ($f = 0; $f < count($this->qSupply); $f++) {
            //set value dummy
            $this->demand[$f][count($this->qDemand)-1] = 0;
        }

    } else if ($totalDemand > $totalSupply) {

        $selisih = $totalDemand - $totalSupply;
        
        $this->qSupply[] = $selisih;
        //status dummy

        for ($f = 0; $f < count($this->qDemand); $f++) {
            //set value dummy
            $this->demand[count($this->qSupply)-1][$f] = 0;
        }
        
    }
   
	}

	private function totalForDummy($ds){
		$total = 0;
		for ($l = 0; $l < count($ds); $l++) {
        	$total += $ds[$l];
    	}
    	return $total;
	}

	public function count(){
		$x = array();
		$a = $this->qSupply;
		$r = $this->qDemand;
		$count = 0;
		$i = 0;
		$j = 0;

		
		do {
	        if (@$a[$i] < 
	        	@$r[$j] ) {
	            $x[$i][$j] = $a[$i];
	            $r[$j] = $r[$j] - $a[$i];
	            $a[$i] = 0;
	            $i++;
	            $count++;
	        } else if (@$a[$i] > 
	        	@$r[$j]) {
	            $x[$i][$j] = $r[$j];
	            $a[$i] = $a[$i] - $r[$j];
	            $r[$j] = 0;
	            $j++;
	            $count++;
	        } else {
	            $x[$i][$j] = @$a[$i];
	            $a[$i] = 0;
	            $r[$j] = 0;
	            $i++;
	            $j++;
	            $count++;
	        }

   		 } while ($count < (count($this->qDemand) + count($this->qSupply)) - 1);	

   		 return $x;
	}

	public function isiKosong($x){
		$return = array();
		for($i =0; $i<count($this->qSupply);$i++){
			for($j =0; $j<count($this->qDemand);$j++){
				if( isset($x[$i][$j]) ){
					$return[$i][$j] = $x[$i][$j];
				}else{
					$return[$i][$j] = 0;
				}
			}		
		}
		return $return;
	}


}


?>