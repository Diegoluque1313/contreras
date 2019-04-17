<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\I18n\Validator as I18nValidator;

class FormAddUsuariosValidator extends InputFilter {

	public function __construct() {

		$this->add(array(
			"name" => "name",
			"required" => true,
			"filters" => array(
				array("name" => "StripTags"),
				array("name" => "StringTrim")
			),
			"validators" => array(
				array(
					"name" => "StringLength",
					"options" => array(
						"encoding" => "UTF-8",
						"min" => "5",
						"max" => "20",
						"messages" => array(
							\Zend\Validator\StringLength::INVALID => "Tu nombre está mal",
							\Zend\Validator\StringLength::TOO_SHORT => "Tu nombre tiene que llevar mas de 5 letras",
							\Zend\Validator\StringLength::TOO_LONG => "Tu nombre tiene que llevar menos de 20 letras",
						)
					)
				),
				array(
					"name" => "Alpha",
					"options" => array(
						"messages" => array(
							I18nValidator\Alpha::INVALID => "Tu nombre solo puede tener letras",
							I18nValidator\Alpha::NOT_ALPHA => "Tu nombre solo puede tener letras",
							I18nValidator\Alpha::STRING_EMPTY => "Tu nombre NO PUEDE ESTAR VACIO",
						)
					)
				)
			)
		));

		$this->add(array(
			"name" => "surname",
			"required" => true,
			"filters" => array(
				array("name" => "StripTags"),
				array("name" => "StringTrim")
			),
			"validators" => array(
				array(
					"name" => "StringLength",
					"options" => array(
						"encoding" => "UTF-8",
						"min" => "5",
						"max" => "20",
						"messages" => array(
							\Zend\Validator\StringLength::INVALID => "Tus apellidos está mal",
							\Zend\Validator\StringLength::TOO_SHORT => "Tus apellidos tienen que llevar mas de 5 letras",
							\Zend\Validator\StringLength::TOO_LONG => "Tus apellidos tienen que llevar menos de 20 letras",
						)
					)
				),
				array(
					"name" => "Alpha",
					"options" => array(
						"allowWhiteSpace" => true,
						"messages" => array(
							I18nValidator\Alpha::INVALID => "Tus apellidos solo puede tener letras",
							I18nValidator\Alpha::NOT_ALPHA => "Tus apellidos solo puede tener letras",
							I18nValidator\Alpha::STRING_EMPTY => "Tus apellidos NO PUEDE ESTAR VACIO",
						)
					)
				)
			)
		));

		$this->add(array(
			"name" => "description",
			"required" => true,
			"filters" => array(
				array("name" => "StripTags"),
				array("name" => "StringTrim")
			),
			"validators" => array(
				array(
					"name" => "StringLength",
					"options" => array(
						"allowWhiteSpace" => true,
						"encoding" => "UTF-8",
						"min" => "1",
						"messages" => array(
							\Zend\Validator\StringLength::INVALID => "Mete bien la descripcion",
							\Zend\Validator\StringLength::TOO_SHORT => "Mete bien la descripcion",
							\Zend\Validator\StringLength::TOO_LONG => "Tu nombre tiene que llevar menos de 20 letras",
						)
					)
				)
			)
		));

		$this->add(array(
			"name" => "email",
			"required" => true,
			"filters" => array(
				array("name" => "StringTrim")
			),
			"validators" => array(
				array(
					"name" => "EmailAddress",
					"options" => array(
						"allowWhiteSpace" => true,
						"messages" => array(
							\Zend\Validator\EmailAddress::INVALID_HOSTNAME => "Email no válido"
						)
					)
				),
			)
		));
	}

}
