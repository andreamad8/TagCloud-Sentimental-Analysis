<?php
ini_set('max_execution_time', 300);

// test
/*
$_POST['ngram'][0]="pippo";
$_POST['ngram'][1]="pluto";
$_POST['ngram'][2]="paper";
$_POST['ngram'][3]="poker";
$_POST['ngram'][4]="innovative";
$_POST['ngram'][5]="happy";
$_POST['ngram'][6]="italy";
$_POST['ngram'][7]="asdas";
$_POST['ngram'][8]="aaaaaa";
*/

// remove white space
$_POST['ngram']=array_filter($_POST['ngram']);

// create the argument fo getngram.py
$arg=$_POST['ngram'][0].",";
for ($i=1; $i < count($_POST['ngram'])-1; $i++) { 
	$arg.=$_POST['ngram'][$i].",";
}
$arg.=$_POST['ngram'][count($_POST['ngram'])-1];

// call  getngram.py with arg e create the result

$mystring = exec('python getngrams.py'." ".$arg." ",$val, $retval);
$final_to_send=array();
$max = array_fill(1, count($_POST['ngram']),0);
$min = array_fill(1, count($_POST['ngram']),1);
for ($i=0; $i < count($val) ; $i++) { 
	array_push($final_to_send, explode(",", $val[$i]));
	if($i!=0){
		for ($j=1; $j < count($final_to_send[$i]); $j++) { 
			if($final_to_send[$i][$j]>$max[$j]){
				$max[$j]=$final_to_send[$i][$j];
			}
			if($final_to_send[$i][$j]<$min[$j]){
				$min[$j]=$final_to_send[$i][$j];
			}
		}
		
	}
	
}


// create the arg for patt.py
$arg="";
for ($i=1; $i < count($final_to_send[0])-1; $i++) { 
	$arg.=$final_to_send[0][$i].",";
}
$arg.=$final_to_send[0][count($final_to_send[0])-1];

// call patt.py

$mystring = exec('python patt.py'." ".$arg." ",$val_sent, $retval);
if(!isset($val_sent[1])){
$val_sent=[0,0,0,0,0,0,0,0,0,0,0,0];
}
// create json
$json="{";
$num_column=count($final_to_send[0]);
$num_row=count($final_to_send)-1;
for ($i=1; $i < $num_column; $i++) { 
	$json.='"'.$final_to_send[0][$i].'":[';
	$json.='{"sentiment":"'.$val_sent[$i-1].'"},';
	for ($j=1; $j < $num_row-1; $j++) { 
		$val=$final_to_send[$j][$i]*10000;
		$json.='{"'.$final_to_send[$j][0].'":"'.$val.'"},';
	}
	$val=$final_to_send[$num_row][$i]*10000;
	$json.='{"'.$final_to_send[$num_row][0].'":"'.$val.'"}';
	if($i== $num_column-1){ $json.="]";}
	else{$json.="],";}
}
$json.="}";
print_r($json);
?>
