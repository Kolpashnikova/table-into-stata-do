<?php
/**
 * Created by PhpStorm.
 * User: kamila
 * Date: 9/29/18
 * Time: 4:13 PM
 */

$table = array_map('str_getcsv', file('translated.csv'));

$variable_name = [];
$variable_label =[];
$variable_values = [];

$labels="";

for($i=0; $i<count($table); $i++){
    if(!empty($table[$i][1])){
        $variable_name[] = trim($table[$i][1]);
        $labels .='%%%%';

        if(!empty($table[$i][2])){
            $variable_label[] = trim($table[$i][2]);
            for($j=3; $j<count($table[$i]); $j++){
                if(!empty($table[$i][$j]) && strpos($table[$i][$j],"(")!==false){
                    $labels .= trim($table[$i][$j]).'#;;#';
                    continue 2;
                }
            }
        }else{
            $variable_label[] = trim($table[$i][3]);
            for($j=4; $j<count($table[$i]); $j++){
                if(!empty($table[$i][$j]) && strpos($table[$i][$j],"(")!==false){
                    $labels .= trim($table[$i][$j]).'#;;#';
                    continue 2;
                }
            }
        }
    }else{
        for($j=3; $j<count($table[$i]); $j++){
            if(!empty($table[$i][$j]) && strpos($table[$i][$j],"(")!==false){
                $labels .= trim($table[$i][$j]).'#;;#';
                continue 2;
            }
        }
    }


}


$variable_values=explode('%%%%',$labels);

unset($variable_values[0]);
$variable_values = array_values($variable_values);

for($k=0; $k<count($variable_values); $k++){
    if(!empty($variable_values[$k])){
        $variable_values[$k] = substr($variable_values[$k],0,-4);
    }
}

var_dump($variable_values);

var_dump(count($variable_values));
var_dump(count($variable_name));
var_dump(count($variable_label));

$string="";

for($i=0; $i<count($variable_label); $i++){
    if(!empty($string)){
        $string = substr($string,0,-5);
        $string .="\n";
    }
    $string .= "lab var ".$variable_name[$i]." \"".$variable_label[$i]."\"\n";
    if(!empty($variable_values[$i])){
        $string .= "lab def ".$variable_name[$i]." ///\n";
        $vals=explode('#;;#',$variable_values[$i]);
        foreach($vals as $v){
            if(!empty($v)){
                $v=str_replace('%',"",$v);
                $number = get_numbers($v);
                foreach($number as $n){
                    $string .=$n;
                }
                $string .=" \"".$v."\" ///\n";
            }
        }
    }

}

if(!empty($string)){
    $string = substr($string,0,-5);
    $string .="\n";
}

file_put_contents('first_dataset.do.txt',$string);


function get_numbers($str){
    preg_match('/\d+/', $str, $matches);
        return $matches;
}

