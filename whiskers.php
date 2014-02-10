<?php
/**
 * [[Whiskers]]
 * A very light and completely workflow agnostic template engine
 *
 * @author Micah Blu
 * @version 0.0.1
 * @license MIT Style license
 * @copyright Micah Blu 
 */

class Whiskers{

	/**
	 * PartialsPath
	 *
	 * Directory path to lookup partials
	 *
	 * @access private
	 * @since 0.0.2
	 */
	private $PartialsPath = '';

	private $Symbols;

	private $Template;

	private $Ext;

	/**
	 * Constructor
	 *
	 * Setup options
	 *
	 * @access public
	 * @param $options [Array]
	 * @since 0.0.2
	 */
	public function __construct($options = array()){
		$this->PartialsPath = isset($options['partials_path']) ? $options['partials_path'] : null;
		$this->Ext = isset($options['template_ext']) ? $options['template_ext'] : 'wtp';
	}

	/**
	 * whisk
	 *
	 * Renders a templates given a string and map array
	 *
	 * @access public
	 * @param $template [String]
	 * @param $symbols [Array]
	 * @param $echo [Bool]
	 * @since 0.0.1
	 */
	public function whisk($template, $symbols = array(), $echo=true){

		if(!empty($symbols)){
			foreach($symbols as $field => $symbol){
				if(is_string($symbol)){
					//echo $symbol . "<br /><hr />";
					$template = preg_replace('/\[\[' . $field . '\]\]/', $symbol, $template);
				}
			}
		}

		// If partials path is set, look for partials and render them
		if(isset($this->PartialsPath)){
			$this->Symbols = $symbols;
			$template = preg_replace_callback('/\\[\\[#(.*)\]\]/', array(&$this, 'whiskR'), $template);	
		}

		$template = preg_replace('/\\[\\[(.*)\\]\\]/', '', $template);

		if($echo) echo $template;
		else return $template;
	}

	private function whiskR(&$matches){

		$file = $this->PartialsPath . DIRECTORY_SEPARATOR . '_' . $matches[1] . "." . $this->Ext;

		if(file_exists($file)){

			$template = file_get_contents($file);
			return $this->whisk($template, $this->Symbols, false);

		}
	}

	/**
	 * clip
	 *
	 * Remove symbol placeholders
	 *
	 * @param $template [String]
	 * @since 0.0.1
	 */
	private function clip($template){

		preg_match_all('/\[\[[^#\\[\\[\\/if\\]\\]](.*)\]\]/', $template, $matches);

		foreach($matches[0] as $match){
			$template = str_replace($match, "", $template);
		}
		return $template;
	}

}