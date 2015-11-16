<?php 


class SteppingStone{

	public $nwc;

	public function __construct($nwc){

			$this->nwc = $nwc;
	}

	public function extract(){
		$hasilDemand = $this->nwc['hasilDemand'];
		$banyakDemand = count($this->nwc['qDemand']);
		$banyakSupply = count($this->nwc['qSupply']);

		$data = array();
		for($i = 0; $i<$banyakSupply; $i++){
			for($j = 0; $j<$banyakDemand; $j++){
				if($hasilDemand[$i][$j] == 0){
					$data['zero'][]= [$i,$j] ;
				}else{
					$data['fill'][]= [$i,$j] ;
				}
			}	
		}
		//echo $banyakDemand;
		//print_r($this->nwc['qDemand']);
		// /echo $banyakSupply;
		return $data;
	}

	public function exec(){

		$this->count();

		return $this->nwc;
	}

	public function count(){
		//print_r (json_encode($this->nwc['hasilDemand']));
		// /echo "<br><br><br>";
		$count = 0;
		do{
			
			$extract = $this->extract();
			$zero = $extract['zero'];
			$fill = $extract['fill'];
			$path = $this->getMinusTerendah($zero,$fill);
			
			if($path != null){
				$this->puterKe($path);
			}
			// /echo "ZERO => ".json_encode($zero). " | ";
			// /print_r (json_encode($this->nwc['hasilDemand']));
			// /echo "<br><br><br>";
			$count++;
		}while($path != null And $count<5);


		return $this->nwc['hasilDemand'];

	}

	public function puterKe($path){

		//cari minus terkecil
		$kecil = 999999;
		$opt = "plus";
		foreach ($path as $key => $value) {
			
			if($opt == "plus"){
				$opt = "minus";
				//$x = $this->nwc['hasilDemand'][$value[0]][$value[1]]*1;
			}else{
				$opt = "plus";

				$x = $this->nwc['hasilDemand'][$value[0]][$value[1]];
				if($x <= $kecil){
					$kecil = $x;
				}
			}

			
		}

		// benerin isi nya
		
		$opt = "plus";
		foreach ($path as $key => $value) {
			
			if($opt == "plus"){
				$opt = "minus";
				$this->nwc['hasilDemand'][$value[0]][$value[1]] += $kecil;
			}else{
				$opt = "plus";

				$this->nwc['hasilDemand'][$value[0]][$value[1]]-=$kecil;
			}
		}
	}

	//mencari minus terendah, 
	//@return array dari titik yang akan di proses 

	public function getMinusTerendah($zero,$fill){
		

		$ttm = 0; //titikMinusTerendah
		$pathReturn = array();
		foreach ($zero as $z) {
				
				$path = $this->path($z,$fill);
				$tt = $this->hitungMinusTerendah($path);
				//echo $tt." + ";
				if($tt<=$ttm){
					$ttm = $tt;
					$pathReturn = $path;
				}
				//$path;

		}

		//echo json_encode($pathReturn)." ----- ";
		return $pathReturn;
		
	}

	private function isAdaNode($seRowCol,$point){
		$adacol = false;
		$adarow = false;
		foreach ($seRowCol as $key => $value) {
			if($value[0] == $point[0]){
				$adarow = true;
			}
			if($value[1] == $point[1]){
				$adarow = true;
			}
		}

		if($adarow AND $adacol){
			return true;
		}else{
			return false;
		}
	}

	public function path($pointZero, $fill){
		$path = array();
		$point = $pointZero;
		$fill[] = $pointZero;
		$last[] = $pointZero;
		$node = NULL;
		
		$hapusIdx = NULL;
		$bisadiubah = false;
		$dari = "kiri";
		do{
			//echo $this->cariKe($point);
			//print_r(json_encode($point));	
				//print_r(json_encode($last));
			
				$seRowCol = array();
				foreach ($fill as $key=> $value) {
					if($value[0] == $point[0] OR $value[1]==$point[1]){
						$seRowCol[$key] = $value;
					}
				}
				//print_r(json_encode($seRowCol));
				$cariKe = $this->cariKe($point) ;
			
					
					
					if($cariKe == "kanan" ){
						//cari dlu kekanan
						$ketemu = false;
						foreach ($seRowCol as $key => $value) {
							if($value[1]>$point[1]){
								$point = $value;
									
								$last[] = $fill[$key];
								

								unset($fill[$key]);
								$ketemu = true;
								$dari = "kiri";
							}
						}
						//cari kebawah kalo dk ada di kanan
						if(!$ketemu){
							if($dari != "bawah"){
							foreach ($seRowCol as $key => $value) {
								if($value[0]>$point[0]){
									$point = $value;
									
									$last[] = $fill[$key];
									unset($fill[$key]);
									$ketemu = true;
									$dari = "atas";
								}
							}
							}else{
								return null;
							}
						}

						

					}elseif($cariKe == "bawah"){
						//cari kebawah
						$ketemu = false;
						$awalPoint = $point;
						foreach ($seRowCol as $key => $value) {
								if($value[0]>$point[0]){
									$point = $value;
									
									$last[] = $fill[$key];
									unset($fill[$key]);
									$ketemu = true;
								}

								if($value == $pointZero and  $value[0] > $awalPoint[0]){
									$point = $value;
									if(isset($fill[$key])){
									$last[] = $fill[$key];
									unset($fill[$key]);
									}
									

									$ketemu = true;
									break;
								}

								$dari = "atas";
						}
						//cari ke kiri
						
						if(!$ketemu){
							if($dari != "kiri"){
								$hapusIdx = count($last)-2;
								//echo count($last)-2;
								//$path= array();
								//$fill[] = $last[count($last)-2];
								$point =  $last[count($last)-2];
								//print_r($point);
								$seRowCol = array();
								foreach ($fill as $key=> $value) {
									if($value[0] == $point[0] OR $value[1]==$point[1]){
										$seRowCol[$key] = $value;
									}
								}
								//print_r(json_encode($seRowCol));
								$ketemu = false;
								foreach ($seRowCol as $key => $value) {
										if($value[0]>$point[0]){
											$point = $value;
											
											$last[] = $fill[$key];
											unset($fill[$key]);
											$ketemu = true;
											//print_r($point);

										}
								}
								$dari = "kanan";
							}else{
								return null;
							}
						}
						
						// masih dak ketemu ?
					


					}elseif($cariKe == "kiri"){
						//cari kekiri
						$ketemu = false;
						foreach ($seRowCol as $key => $value) {
								if($value[1]<$point[1]){
									$point = $value;
									
									$last[] = $fill[$key];
									unset($fill[$key]);
									$ketemu = true;
									$dari = "kanan";
								}
						}
						//cari ke keatas
						if(!$ketemu){
							if($dari != "atas"){
							foreach ($seRowCol as $key => $value) {
								if($value[0]<$point[0]){
									$point = $value;
									
									$last[] = $fill[$key];
									unset($fill[$key]);
									$ketemu = true;
									$dari = "bawah";
								}
							}
						}else{
							return null;
						}
						}
						// masih dak ketemu ?
						
					}else{
						
						//cari keatas
						$ketemu = false;
						//print_r(json_encode($point));
						//echo " - ";
						$awalPoint = $point;
						foreach ($seRowCol as $key => $value) {
							
								if($value[0]<$point[0]){
									$point = $value;
									
									$last[] = $fill[$key];
									unset($fill[$key]);
									$ketemu = true;
									//print_r(json_encode($value));	
								}
								// /print_r(json_encode($point));
								// /echo "<br>";
								//echo " =+= ";
								// /print_r(json_encode($pointZero));
								// /echo " ########### ";
								if($value == $pointZero and  $value[0] < $awalPoint[0]){
									$point = $value;

									if(isset($fill[$key])){
									$last[] = $fill[$key];

									unset($fill[$key]);
									}
									$ketemu = true;
									break;
								}
								$dari = "bawah";
							}

						


							if(!$ketemu){
								if($dari != "kanan"){
								$hapusIdx = count($last)-2;
								//echo count($last)-2;
								//$path= array();
								//$fill[] = $last[count($last)-2];
								$point =  $last[count($last)-2];
								//print_r($point);
								$seRowCol = array();
								foreach ($fill as $key=> $value) {
									if($value[0] == $point[0] OR $value[1]==$point[1]){
										$seRowCol[$key] = $value;
									}
								}
								//print_r(json_encode($seRowCol));
								$ketemu = false;
								foreach ($seRowCol as $key => $value) {
								if($value[0]<$point[0]){
									$point = $value;

									$last[] = $fill[$key];
									unset($fill[$key]);
									$ketemu = true;
								}
							}

							$dari = "kiri";

						}
						}
					}
			
			//print_r(json_encode($point));
			//echo "<-point";
			//echo $dari;
			if(!$ketemu){
				return null;
			}
			//print_r(json_encode($seRowCol));
			//echo "<br>";
			//echo $this->cariKe($point);
			$path[] = $point;

			//Ambek point yang terdekat 
			// cari kanan bawah
			//print_r(json_encode($path));	
			//echo json_encode($fill)."<br>";
		}while($point != $pointZero);//berarti balik lagi sudah

		//tuker element array terakhir ke element pertama
		
		// /print_r($hapusIdx);
		
		if(!is_null($hapusIdx)){
			//echo "hapus";
			unset($path[$hapusIdx]);
		}
		
		array_unshift($path,$pointZero);
		array_pop($path);

		//print_r(json_encode($path));
		//echo "<= path <br>";

		//if(is_null($hapusIdx)){
			return $path;
		//}
	}

	private function cariKe($point){

		$maxD = count($this->nwc['qDemand'])-1;
		$maxS = count($this->nwc['qSupply'])-1;

		if($point[0]<$maxS AND $point[1]<$maxD){
			
			return "kanan";	
					
		}
				//cek ke bawah
		elseif($point[0]<$maxS AND $point[1]==$maxD){
			return "bawah";
		}
		//cek kekiri
		elseif($point[0]==$maxS AND $point[1]>0){
			return "kiri";
		}
		//cek ke atas
		elseif($point[0]==$maxS AND $point [1]==$maxD){
			return "atas";
		}


	}

	public function hitungMinusTerendah($path){

		$total = 0;
		$demand = $this->nwc['demand'];
		//print_r($demand);
		//echo " - ";
		$opt = "plus";

		if($path == null){
			return 0;
		}

		foreach ($path as $key => $value) {
			if($opt == "plus"){
				$total+=$demand[$value[0]][$value[1]];
				$opt = "minus";
			}elseif($opt == "minus"){
				$total-=$demand[$value[0]][$value[1]];
				$opt = "plus";
			}
		
		}

		return $total;
	}
	 
}

?>