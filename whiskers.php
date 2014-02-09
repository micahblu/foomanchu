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
				//echo $field	 . "=" . $var . "<br />";
				$template = preg_replace("/\[\[$field\]\]/", $symbol, $template);
			}
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

		preg_match_all('/\[\[(.*)\]\]/', $template, $matches);

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