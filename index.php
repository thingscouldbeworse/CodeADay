<?php

error_reporting(4);

function init_level_2( $name ){
	
	$filename = $name . "/Day1_level_2.txt";
	if( !(file_exists($filename)) ){
		$myfile = fopen( $name . "/Day1_level_2.txt", "w") or die("Unable to open " . $name . "/Day1_level_2.txt");
		$input_string = create_level_2( $name );
		$explanation = "A 'u' in this file means an up, a 'd' means a down. Write a program to count how many ups and downs are placed, with a down subtracting one and an up adding one." . PHP_EOL . "<br>";
		fwrite( $myfile, $explanation );
		fwrite( $myfile, $input_string );
		fclose( $myfile );
	}
}

function init_level_1( $name ){

	$myfile = fopen( $name . "/Day1_level_1.txt", "w") or die("Unable to open " . $name . "/Day1_level_1.txt");
	$explanation = "What's your name backwards?";
	fwrite( $myfile, $explanation );
	fclose( $myfile );
}

function check_level_1( $answer, $name ){

	$reversed = strrev( $name );
	if( $answer == $reversed ){
		return 1;
	}
	else{
		return 0;
	}
}

function check_level_2( $answer, $name ){

	$filename = '/home/pi/CodeADay/' . $name . "/Day1_level_2.txt";
	$file = file_get_contents($filename, true);
	$position = strpos( $file, "<br>" );
	//print( "position: " . $position . "<br>" );
	$sub_string = substr( $file, $position );

	$string_array = str_split( $sub_string );
	$overall = 0;
	foreach( $string_array as $character ){

		if( $character == "u" ){
			
			//print( "found " . $character . " so adding 1 to " . $overall . "<br>" );
			$overall = $overall + 1;
		}
		elseif( $character == "d" ){
			
			//print( "found " . $character . " so subtracting 1 from " . $overall . "<br>" );
			$overall = $overall - 1;
		}
	}
	//print( $overall	);
	if( $overall == $answer){
		return 1;
	}
	else{
		return 0;
	}

}

function create_level_2( $name ){

	$string_array = str_split( $name );

	$input_string = "";
	foreach( $string_array as $letter ){
		$queue = 0;
		while( $queue <= ord($letter) ){
			$random = mt_rand( 1, 10 );
			if( $random > 4 ){
				$input_string .= "u";
			}
			else{
				$input_string .= "d";
			}
			$queue = $queue + 1;
		}
	}
	return $input_string;
}

function write_scores( $scores, $name ){

	$file_handle = fopen("scores.txt", "w");
	$output = "";
	foreach( $scores as $student=>$score ){
			$score= trim(preg_replace('/\s\s+/', ' ', $score));
			$score = (int)$score;
			if( $student == $_GET['name'] ){

				(int)$score = (int)$score + 1;
			}
			$output .= $student . " " . (int)$score . "\n";
			print( $output . "<br>" );
		}
	fwrite( $file_handle, $output );
	fclose( $file_handle );
}

if( isset($_GET['name']) ){

	$file_handle = fopen("scores.txt", "rb");
	$scores = [];
	while (!feof($file_handle) ) {

		$line_of_text = fgets($file_handle);
		$parts = explode('=', $line_of_text);
		$full = $parts[0];
		$name = explode(" ", $full)[0];

		$scores[$name] = explode(" ", $full)[1];
	}


	print( "Hello " . $_GET['name'] . ", and welcome to Code a Day" . PHP_EOL . "<br>");
	$level = $scores[$_GET['name']];
	print( "You are on level " . $level . PHP_EOL . "<br>" );
	if( $level == 0 ){
		init_level_1( $_GET['name'] );
	}
	elseif( $level == 1 ){

		init_level_2( $_GET['name'] );
	}

	if( isset($_GET['answer']) ){
		if( $level == 0 ){

			$checked = check_level_1( $_GET['name'], $_GET['answer'] );
			if( $checked == 1 ){
				print( "Level 1 complete! On to level 2." . PHP_EOL . "<br>" );
			}
		}
		elseif( $level == 1 ){
			$checked = check_level_2( $_GET['answer'], $_GET['name'] );
			if( $checked == 1 ){
				print( "Level 2 complete! On to level 3." . PHP_EOL . "<br>" );
			}
			write_scores( $scores, $name );
		}
		else{
			print("There's something wrong with that answer" .PHP_EOL . "<br>" . "<br>" );
		}
		$output = "";
		foreach( $scores as $student=>$score ){
			$score= trim(preg_replace('/\s\s+/', ' ', $score));
			$score = (int)$score;
			if( $student == $_GET['name'] ){

				(int)$score = (int)$score + (int)$checked;
			}
			$output .= $student . " " . (int)$score;
			//print( $output . "<br>" );
		}
		fwrite( $file_handle, $output );
		fclose( $file_handle );
		
	}

	if( isset($_GET['debug']) ){

		foreach( $scores as $student=>$score ){
			$output .= $student . " " . $score;
		}
		fwrite( $file_handle, $output );
		fclose( $file_handle );

		print( "answer: " . $_GET['answer'] );
	}
	//print_r( $_GET );

}
else{
	print( "You haven't told me your name yet." . PHP_EOL . "<br>");
}

?>
