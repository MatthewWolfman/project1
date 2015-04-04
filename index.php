<!DOCTYPE html>
	<html>
		<head>
			<title>
				Directory
			</title>
		</head>
		<body>
			<?php
				class CSV  {
					public $csvArray = array();
					static $instance;
					private function __construct(){
					}
					public static function getCSV(){
						if(!isset($instance)){
							self::$instance = new CSV();
						}
						return self::$instance;
					}				
					public function setCSV($s){
						if (($handle = fopen($s, "r")) !== FALSE) {
						
							# Set the parent multidimensional array key to 0.
							$nn = 0;
							while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
								# Count the total keys in the row.
								$c = count($data);
								# Populate the multidimensional array.
								$this->csvArray[$nn] = array();
								for ($x=0;$x<$c;$x++)
								{
									$this->csvArray[$nn][$x] = $data[$x];
								}
								$nn++;
							}
							fclose($handle);
						}
					}
					public function getArray(){
						return $this->csvArray;
					}
				}				
				class Record {
					private $recordHolder = array();
					private $output = "";
					public function __construct($array, $header){				
						$this->recordHolder = $array;
						for($i=0;$i<count($header);$i++){
							$this->output .= $header[$i] . "     |    " . $this->recordHolder[$i] . "<br>";
						}
					}
					public function getOutput(){
						return $this->output;
					}	
				}
				class RecordFactory {
					public function __construct(){
					}
					public function buildRecord($values, $header){
						return new Record($values, $header);
					}
				}
				class Page {
					function __construct(){
						$values = array();
						$a = CSV::getCSV();
						$a->setCSV("hd2014.csv");
						$b = new RecordFactory();
						
						for($i = 1; $i<count($a->getArray());$i++){
							$values[$i-1] = $b->buildRecord($a->getArray()[$i],$a->getArray()[0]);
						}
						if(!isset($_GET['page'])){
							for($i = 1; $i<count($a->getArray());$i++){
								echo '<a href="'. "project1.php?page=". $i . '">' . $a->getArray()[$i][1] . '</a><br/>';
							}
						} else {
							echo $values[$_GET['page']-1]->getOutput();
						}
						
					}
				}
				$p = new Page();
				?>
		</body>
	</html>