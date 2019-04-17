<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Application\Model\Usuario;
use Application\Model\UsuariosTable;
use Application\Model\Equipo;
use Application\Model\EquiposTable;
use Application\Model\Jugador;
use Application\Model\JugadoresTable;
use Application\Model\Partido;
use Application\Model\PartidosTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
		
		$this->initAcl($e);
		$e->getApplication()->getEventManager()->attach("route", array($this, "checkAcl"));
		
		$translator = $e->getApplication()->getServiceManager()->get("translator");
		$lang=new \Zend\Session\Container("lang");
		$translator->setLocale($lang->lang)->setFallbackLocale($lang->lang);
    }
	
	public function initAcl(MvcEvent $e){
		$acl = new \Zend\Permissions\Acl\Acl();
		
		$roles = require_once 'module/Application/config/acl.roles.php';
		
		foreach ($roles as $role => $resources){
			
			$role = new \Zend\Permissions\Acl\Role\GenericRole($role);
			
			$acl->addRole($role);
			
			foreach ($resources["allow"] as $resource) {
				if(!$acl->hasResource($resource)){
					$acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
				}
				
				$acl->allow($role, $resource);
			}
			
			foreach ($resources["deny"] as $resource) {
				if(!$acl->hasResource($resource)){
					$acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
				}
				
				$acl->deny($role, $resource);
			}
		}
		
		$e->getViewModel()->acl = $acl;
	}
	
	public function checkAcl(MvcEvent $e){
		$route = $e->getRouteMatch()->getMatchedRouteName();
		
		$auth = new \Zend\Authentication\AuthenticationService();
		$identity = $auth->getStorage()->read();
		
		if($identity!=false && $identity!=null){
			$userRole="admin";
		}else{
			$userRole="visitante";
		}
		
		if(!$e->getViewModel()->acl->isAllowed($userRole, $route)){
			$response = $e->getResponse();
			$response->getHeaders()->addHeaderLine("Location", $e->getRequest()->getBaseUrl()."/404");
			$response->setStatusCode(404);
		}
	}

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
	public function getServiceConfig(){
		return array(
			"factories" => array(
				"Application\Model\UsuariosTable" => function($sm){
					$tableGateway= $sm->get("UsuariosTableGateway");
					$table = new UsuariosTable($tableGateway);
					return $table;
				},
				"UsuariosTableGateway" => function($sm){
					$dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Usuario());
					return new TableGateway("usuarios", $dbAdapter, null, $resultSetPrototype);
				},
				"Application\Model\EquiposTable" => function($sm){
					$tableGateway= $sm->get("EquiposTableGateway");
					$table = new EquiposTable($tableGateway);
					return $table;
				},
				"EquiposTableGateway" => function($sm){
					$dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Equipo());
					return new TableGateway("equipo", $dbAdapter, null, $resultSetPrototype);
				},
				"Application\Model\JugadoresTable" => function($sm){
					$tableGateway= $sm->get("JugadoresTableGateway");
					$table = new JugadoresTable($tableGateway);
					return $table;
				},
				"JugadoresTableGateway" => function($sm){
					$dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Jugador());
					return new TableGateway("jugador", $dbAdapter, null, $resultSetPrototype);
				},
				"Application\Model\PartidosTable" => function($sm){
					$tableGateway= $sm->get("PartidosTableGateway");
					$table = new PartidosTable($tableGateway);
					return $table;
				},
				"PartidosTableGateway" => function($sm){
					$dbAdapter = $sm->get("Zend\Db\Adapter\Adapter");
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Partido());
					return new TableGateway("partidos", $dbAdapter, null, $resultSetPrototype);
				}
			)
		);
	}
}
