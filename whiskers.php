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


		if(is_array($symbols)){

			foreach($symbols as $field => $symbol){
				if(is_string($symbol)){
					$template = preg_replace('/\[\[' . $field . '\]\]/', $symbol, $template);
				}
			}
		}
		$template = preg_replace('/\\[\\[(.*)\\]\\]/', '', $template);

		if($echo) echo $template;
		else return $template;	

	}

	public function parseExpression(){

		if(is_array($symbols)){

			foreach($symbols as $field => $symbol){
				$template = preg_replace('/\[\[' . $field . '\]\]/', $symbol, $template);
			}
		}
		$template = preg_replace('/\\[\\[[^#\\/].*\\]\\]/', '', $template);
		//$template = $this->clip($template);
		//$template = "<h1>Hi, I'm an HTML template</h1> <p>[[#if this =='that']] To be seen or not to be seen, that is the question[[/if]]</p>";
		// Find and evaluate conditional statements
		preg_match('/\\[\\[#if(.+)\\]\\][^\\[\\]\\/].+\\[\\[\\/if\\]\\]/s', $template, $ifmatches);

		if(!empty($ifmatches)){
			// Extract term first so to allow spaces in it
			preg_match('/[\'\"](.+)[\'\"]/', $ifmatches[1], $match);

			$term = $match[1];

			$ifmatches[1] = str_replace($match[0], "", $ifmatches[1]);
			
			list($variable, $operator) = explode(" ", ltrim($ifmatches[1]));

			$term = preg_replace('/\'/', '', $term);
			$term = preg_replace('/\"/', '', $term);

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
				echo $ifmatches[0];
				$template = str_replace("$ifmatches[0]", "", $template);
				//
			}
			$template = preg_replace('/\[\[(.*)\]\]/', '', $template);
		}
		$template = $this->clip($template);

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

		preg_match_all('/\[\[[^#\\[\\[\\/if\\]\\]](.*)\]\]/', $template, $matches);

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