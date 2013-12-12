<?php
/**
 * this library has been created by Minhaz @Cistoner
 * contributer Abhinav @Cistoner
 * Anyone can use this library in their applications without any permissions
 * Further contributions will be welcomed.
 */
 
/**
 * Function to retrieve information from filename
 * @input param: (string)
 * @output: array of (string)s
 */
function getmovieDetails($str)
{
	$words = preg_split('/[.\s]/', $str);
        $words = array_filter($words, create_function('$var','return !(preg_match("/(?:HDTV|bluray|\w{2,3}rip)|(?:x264)|(?:AC\d)|(?:mp4|avi|mkv|flv)|(?:YIFY|H-?SBS|DTS)/Ui", $var));'));
     $str=join(' ', $words);
	/**
	 * declaring return variable as array
	 */
	$output = array();
	
	/**
	 * replace "." with spaces
	 */
	$str = str_replace("."," ",$str); 
	 
	/**
	 * code to get information like [s01e5] or (2x23)
	 * it extract infrmation of form
	 * season = 1
	 * episode_no = 5 from first case
	 * expecting movie not to contial both : in that case first one will be considered
	 */
	
	$regex = "/\[.*?\]|\(.*?\)/";
	preg_match_all($regex,$str,$out, PREG_PATTERN_ORDER);
	if(count($out) && count($out[0]) &&($out[0][0] != "" ))
	{
		foreach($out[0] as $o)
		{
			if(!preg_match("/\d{4}/",$o))
				$str = str_replace($o,"",$str);
			$regex = "/s(\d{1,2})e(\d{1,3})|(\d{1,2})x(\d{1,3})/Ui";
			preg_match_all($regex,$o,$secOut, PREG_PATTERN_ORDER);
			if(count($secOut) && count($secOut[0]))
			{
				if(count($secOut[1]) && ($secOut[1][0] != "" ))$output['season'] = $secOut[1][0];
				else if(count($secOut[3]) && ($secOut[3][0] != "" ))$output['season'] = $secOut[3][0];
				if(count($secOut[2]) && ($secOut[2][0] != "" ))$output['episode_no'] = $secOut[2][0];
				else if(count($secOut[4]) && ($secOut[4][0] != "" ))$output['episode_no'] = $secOut[4][0];
			}
		}
	}
	/**
	 * code to get information like season 05 episode 04
	 * it extract infrmation of form
	 * season = 05
	 * episode_no = 04 from first case
	 * expecting movie not to contial both : in that case first one will be considered
	 */
	$regex = "/season (\d{2}) episode (\d{2})/Ui";
	preg_match_all($regex,$str,$out, PREG_PATTERN_ORDER);
	if(count($out) && count($out[0]) &&($out[0][0] != "" ))
	{
		$output['season']=$out[1][0];
		$output['episode_no']=$out[2][0];
		
	}
	
	/**
	 * task 2: identify the part of movie
	 */
	$regex = "/\b\d /";
	preg_match_all($regex,$str,$out,PREG_PATTERN_ORDER);
	if(count($out) && count($out[0]))
	{
		$output['part'] = $out[0][0];
	}
	
	/**
	 * task 3: identify and replace resolution
	 */
	$regex = "/\d{1,4}p|\d{1,4}P/i";
	preg_match_all($regex,$str,$out, PREG_PATTERN_ORDER);
	if(count($out) && count($out[0]))
	{
		$str = str_replace($out[0][0],"",$str);
		$output['resolution'] = $out[0][0];
	}
	/**
	 * task 4: identify and replace movie year
	 */
	$regex = "/\(?\d{4}\)?/i";
	preg_match_all($regex,$str,$out, PREG_PATTERN_ORDER);
	if(count($out) && count($out[0]))
	{
		$str = str_replace($out[0][0],"",$str);
		$output['year'] = $out[0][0];
	}
	
	/**
	 * task 4: identify and replace 2d/3d i.e. dimension
	 */
	$regex = "/[0-9]{1}D|[0-9]{1}d/i";
	preg_match_all($regex,$str,$out, PREG_PATTERN_ORDER);
	if(count($out) && count($out[0]))
	{
		$str = str_replace($out[0][0],"",$str);
		$output['dimension'] = $out[0][0];
	}
	
	
	
	/**
	 * now save the filtered filename as movie title
	 */
	$output['title'] = $str;
	
	return $output;
} 

?>