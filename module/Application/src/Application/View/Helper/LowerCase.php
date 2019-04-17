<?php
namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class LowerCase extends AbstractHelper{
	
	public function __invoke($str){
		return trim(strtolower($str));
	}
}