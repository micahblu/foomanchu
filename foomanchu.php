<?php
/**
 *  [[ FooManChu ]]
 *       ____
 *      |    |
 *
 * A very light and completely workflow agnostic template engine
 *
 * @author Micah Blu
 * @version 0.0.3
 * @license MIT Style license
 * @copyright Micah Blu 
 */

class FooManChu{

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
		$this->PartialsPath = isset($options['partials_path']) ? $options['partials_path'] : dirname(__FILE__);
		$this->Ext = isset($options['template_ext']) ? $options['template_ext'] : 'fmc';
	}

	/**
	 * Render
	 *
	 * Renders a templates given a string and map array
	 *
	 * @param $template [String]
	 * @param $symbols [Array]
	 * @param $echo [Bool]
	 * @since 0.0.1
	 */
	public function render($template, $symbols = array(), $echo=true){

		$began = microtime();

		// Evaluate any statements
		preg_match_all('/(?<!\[)\[\[#(\w+)\s(.*?){2}\]\](.*?)\[{2}\/\1\]\](?!\])/s', $template, $statements);
		
		//print_r($statements[0][0]);

		if(!empty($statements)){
			$statement = isset($statements[1][0][0]) ? $statements[1][0] : null;
			$condition = isset($statements[2][0][0]) ? $statements[2][0] : null;

			// for now only support 'if'
			if(!$statment && $statement == "if"){
				if(in_array($condition, $symbols)){
					$template = str_replace($statements[0][0], $statements[3][0], $template); 
				}else{
					$template = str_replace($statements[0][0], '', $template);
				}
			}
		}

		// Replace all template tags with their matching symbol
		if(!empty($symbols)){
			foreach($symbols as $field => $symbol){
				if(is_string($symbol)){
					$template = preg_replace('/(?<!\[)\[\[' . $field . '\]\](?!\])/', $symbol, $template);
				}
			}
		}

		// If partials path is set, look for partials and render them
		if(isset($this->PartialsPath)){
			$this->Symbols = $symbols;
			//$template = preg_replace_callback('/\\[\\[#(.*)\]\]/', array(&$this, 'renderR'), $template);	
		}

		$ended = microtime();

		if($echo) echo $template;
		else return $template;
	}

	private function renderR(&$matches){

		$file = $this->PartialsPath . DIRECTORY_SEPARATOR . '_' . $matches[1] . "." . $this->Ext;

		if(file_exists($file)){
			$template = file_get_contents($file);
			return $this->render($template, $this->Symbols, false);
		}
	}
}