<?php

function handleEquation($eq) {

$p = eval('return '.$eq.';');
return $p;
	
}


function Calculate($string) {

    //Remove all the spaces first
    $string = str_replace(" ","", $string);
	
    $eq_array = array();
	$eq_ans_array = array();
	$bracket_array = array();
	$end_bracket_array = array();
    
    //Find all bracket number
    $bracket_position = -1;
    $eq_count = 0;
	
	while($bracket_position !== false)
	{
		$bracket_position += 1;
		$bracket_position = strpos($string, '(', $bracket_position);
		
		if($bracket_position !== false)
		{
		  $bracket_array += [$bracket_position => 0];
		}
		
		//$bracket_array += $bracket_position;
	}
	
	$end_bracket_position = -1;
	
	while($end_bracket_position !== false)
	{
		$end_bracket_position += 1;
		$end_bracket_position = strpos($string, ')', $end_bracket_position);
		if($end_bracket_position !== false)
		{
		  $end_bracket_array += [$end_bracket_position => 0];
		}
		
		//$end_bracket_array += end_bracket_position;
	}
	
	
	
	$keys_bracket_array = array_keys($bracket_array);
	$keys_end_bracket_array = array_keys($end_bracket_array);
	
	for($x = 0; $x < count($bracket_array); $x++)
	{
		$start_x_position = $keys_bracket_array[$x];
		$end_bracket_x_position = $keys_end_bracket_array[$x];
		$nested_count = 0;
		$got_nested = false;
		
		$nested_position = 0;
		
		//To check if got nested, and cater
		for($y = $x + 1; $y < count($bracket_array); $y++)
		{
		  
			if($keys_bracket_array[$y] <= $end_bracket_x_position)
			{
				$got_nested = true;
				$nested_count += 1;
			}
			else
			{
				$nested_count = $y;
			}
		}
		
		$bracket_array[$start_x_position] = $nested_count;
	}
	
	$values_bracket_array = array_values($bracket_array);
	$values_end_bracket_array = array_values($end_bracket_array);
	
	
	for($x = 0; $x < count($bracket_array); $x++)
	{
	  
	  $values_bracket_array = array_values($bracket_array);
		
		$key = $keys_bracket_array[$x];
		$value = $values_bracket_array[$x];
		
		$start_bracket_position = $key;
		$end_bracket_position = 0;
		
		//echo $value;
		
		
		if($value > 0)
		{
			$got_nested_position = $x + $value;
			
			$end_bracket_position = $keys_end_bracket_array[$got_nested_position];
			//echo $end_bracket_position;
			
			//Need to reduce back the position for the next few nested bracket
			for($z = 0; $z < $value; $z++)
			{
				$nested_position_to_adjust = $x + 1 + $z;
				$key_nested_position_to_adjust = $keys_bracket_array[$nested_position_to_adjust];
				
				//$values_bracket_array[$key_nested_position_to_adjust] -1;
				$bracket_array[$key_nested_position_to_adjust] -= 1;
			}
			
		}
		else if($value == 0)
		{
			$end_bracket_position = $keys_end_bracket_array[$x];
		}
		else if($value < 0)
		{
			//nested bracket, Need to - back the bracket from position
			
			$nested_position = $x + $value;
			
			if($nested_position >= 0)
			{
				$end_bracket_position = $keys_end_bracket_array[$nested_position];
			}
			else 
			{
				//something wrong use back same first
				$end_bracket_position = $keys_end_bracket_array[$x];
			}
			
			
		}
		$eq_array += [substr($string, $start_bracket_position, ($end_bracket_position - $start_bracket_position + 1))  => $value];
		$eq_ans_array += [substr($string, $start_bracket_position, ($end_bracket_position - $start_bracket_position + 1))  => 0];		
		
	}
	
	//Sort array by the value so that can replace nested bracket first
	ksort($eq_array);
	
	$replace_string = $string;
	
	$change_word_array = array();
	
	foreach($eq_array as $key => $value)
	{
	  //echo $key;
	  foreach($change_word_array as $changekey => $changevalue)
	  {
	    $key = str_replace($changekey,$changevalue, $key);
	  }
	  
		$eq_without_bracket = substr($key, 1, strlen($key) - 2);
		$answer_eq = handleEquation($eq_without_bracket);
		$eq_ans_array[$key] = $answer_eq;
		
		$replace_string = str_replace($key,$answer_eq, $replace_string);
		echo $replace_string . "\n";
		
	  $change_word_array = [$key => $answer_eq];
		
	}
	$result = handleEquation($replace_string);
	return $result;

}

$result = Calculate("5*(7+(5*2))");
echo $result;