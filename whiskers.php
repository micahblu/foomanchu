<?php
/**
 * [[Whiskers]]
 * A very light and completely workflow agnostic template engine
 *
 * @author Micah Blu
 * @version 0.0.1
 */

class Whiskers{

	public function __construct(){

	}

	/**
	 * whisk
	 * Renders a templates given a string and map array
	 * @since 0.0.1
	 * @param $template [String]
	 * @param $symbols [Array]
	 * @param $echo [Bool]
	 */
	public function whisk($template, $symbols, $echo=true){

		$regex = '\\[\\['                          // Opening bracket
					. '(\\[?#?)'                         // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
					. "(.*)"                     				 // 2: Shortcode name
					. '(?![\\w-])'                       // Not followed by word character or hyphen
					. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
					.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
					.     '(?:'
					.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
					.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
					.     ')*?'
					. ')'
					. '(?:'
					.     '(\\/)'                        // 4: Self closing tag ...
					.     '\\]'                          // ... and closing bracket
					. '|'
					.     '\\]'                          // Closing bracket
					.     '(?:'
					.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
					.             '[^\\[]*+'             // Not an opening bracket
					.             '(?:'
					.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
					.                 '[^\\[]*+'         // Not an opening bracket
					.             ')*+'
					.         ')'
					.         '\\[\\[\\/\\2\\]'             // Closing shortcode tag
					.     ')?'
					. ')'
					. '(\\]\\]?)';
		
		
		///print_r($symbols);
		if(is_array($symbols)){

			foreach($symbols as $field => $symbol){
				//echo $field	 . "=" . $var . "<br />";
				$template = preg_replace('/\[\[$field\]\]/', $symbol, $template);
			}
		}
		$template = $this->clip($template);

		preg_match_all("/$regex/", $template, $matches);

		foreach($matches[0] as $match){

			// Check for if clause
			preg_match( '/\\[\\[#if(.+)\\]\\]/', $match, $ifmatches);

			if(!empty($ifmatches)){

				list($variable, $operator, $term) = explode(" ", ltrim($ifmatches[1]));

				$term = str_replace('\'', '', $term);
				$term = str_replace('\"', '', $term);

				if($operator == "=" || $operator == "=="){
					if($symbols[$variable] == $term){
						$evaluates = true;
					}else{
						$evaluates = false;
					}
				}elseif($operator == "!=") {
					if($symbols[$variable] != $term){
						$evaluates = true;
					}else{
						$evaluates = false;
					}
				}
				
				if(!$evaluates){
					$template = str_replace("$match", "", $template);
					$template = preg_replace('/\[\[(.*)\]\]/', '', $template);

				}
				//echo $condition . " " . $operator . " " . $term . " !<br />";
			}
		}

		

		if($echo) echo $template;
		else return $template;
	}

	/**
	 * clip
	 * clip remaining whiskers
	 * @since 0.0.1
	 * @param $template [String]
	 */
	private function clip($template){

		preg_match_all('/\[\[[^#](.*)\]\]/', $template, $matches);

		foreach($matches[0] as $match){
			$template = str_replace($match, "", $template);
		}
		return $template;
	}

	/**
	 * clipComments
	 * clip '\/* *\/ and // style cmments
	 */
	private function clipComments($template){

		preg_match_all('/\/\*(.*)\*\//', $template, $matches);

		die(print_r($matches));
		foreach($matches[0] as $match){
			$template = str_replace($match, "", $template);
		}
		/*
		preg_match_all('/\/\/.*$/', $template, $matches);

		foreach($matches[0] as $match){
			$template = str_replace($match, "", $template);
		}*/

		return $template;
	}
}