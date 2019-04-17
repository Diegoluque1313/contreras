<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
	'router' => array(
		'routes' => array(
			'home' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action' => 'index',
					),
				),
			),
			// The following is a route to simplify getting started creating
			// new controllers and actions without needing to create a new
			// module. Simply drop new controllers in, and you can access them
			// using the path /application/:controller/:action
			'application' => array(
				'type' => 'Literal',
				'options' => array(
					'route' => '/application',
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Index',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => '/[:controller[/:action]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					),
				),
			),
			"prueba" => array(
				"type" => "Segment",
				'options' => array(
					'route' => '/prueba[/:id/:id2]',
					'constraints' => array(
						'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id2' => '[0-9_-]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Pruebas',
						'action' => 'index',
					),
				),
			),
			"get-form" => array(
				"type" => "Segment",
				'options' => array(
					'route' => '/get-form',
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Index',
						'action' => 'get-form-data',
					),
				),
			),
			"usuario" => array(
				"type" => "Segment",
				'options' => array(
					'route' => '/usuario[/[:action][/:id]]',
					"constraints" => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Usuario',
						'action' => 'index',
					),
				),
			),
			"equipo" => array(
				"type" => "Segment",
				'options' => array(
					'route' => '/equipo[/[:action][/:id]]',
					"constraints" => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Equipo',
						'action' => 'index',
					),
				),
			),
			"jugador" => array(
				"type" => "Segment",
				'options' => array(
					'route' => '/jugador[/[:action][/:equipo_id]]',
					"constraints" => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Jugador',
						'action' => 'index',
					),
				),
			),
			"partido" => array(
				"type" => "Segment",
				'options' => array(
					'route' => '/partido[/[:action][/:equipo_id]]',
					"constraints" => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'Partido',
						'action' => 'index',
					),
				),
			)
		),
	),
	'service_manager' => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'factories' => array(
			'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
		),
		'invokables' => array(
			"Zend\Authentication\AuthenticationService" => "Zend\Authentication\AuthenticationService"
		)
	),
	'translator' => array(
		'locale' => 'es_ES',
		'translation_file_patterns' => array(
			array(
				'type' => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern' => '%s.mo',
			),
//			array(
//				'type' => 'phparray',
//				'base_dir' => __DIR__ . '/../config/language',
//				'pattern' => '%s.php',
//			),
		),
	),
	'controllers' => array(
		'invokables' => array(
			'Application\Controller\Index' => Controller\IndexController::class,
			'Application\Controller\Pruebas' => Controller\PruebasController::class,
			'Application\Controller\Usuario' => Controller\UsuarioController::class,
			'Application\Controller\Equipo' => Controller\EquipoController::class,
			'Application\Controller\Jugador' => Controller\JugadorController::class,
			'Application\Controller\Partido' => Controller\PartidoController::class
		),
	),
	"controller_plugins" => array(
		'invokables' => array(
			'Plugins' => "Application\Controller\Plugin\Plugins",
		),
	),
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array(
			'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
			'error/404' => __DIR__ . '/../view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
	'view_helpers' => array(
		'invokables' => array(
			'lowercase' => 'Application\View\Helper\LowerCase',
		),
	),
	// Placeholder for console routes
	'console' => array(
		'router' => array(
			'routes' => array(
			),
		),
	),
);
