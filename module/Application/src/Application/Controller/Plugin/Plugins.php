<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Plugins extends AbstractPlugin{
	
	public function hoy(){
	   return date("Y-m-d");
	}
	
	public function existe($var){
		$var=trim($var);
		if($var!=null && $var!=false && !empty($var)){
			
		}else{
			$var = false;
		}
		return $var;
	}
}
