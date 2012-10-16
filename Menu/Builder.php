<?php

/*
 * This file is part of the CSMenuBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za> / CustomScripts
 *
 * @author Pierre du Plessis <info@customscripts.co.za>
 * @link(https://www.github.com/CustomScripts/MenuBundle)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\MenuBundle\Menu;

use Symfony\Component\DependencyInjection\ContainerAware;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("cs_menu.builder")
 */
class Builder extends ContainerAware
{

	protected $builders = array();

	public function addBuilder($builder)
	{
		$this->builders[] = $builder;
	}

    /**
     * @DI\Inject("service_container");
     */
    public $container;

    public function getFactory()
    {
        $factory = $this->container->get('knp_menu.factory');

        return $factory;
    }

    public function has($mehtod)
    {
    	if(empty($this->builders))
    	{
    		throw new \Exception("No menu builders defined! perhaps you forgot to create a service and tag it with 'cs_menu.builder'?");
    	}

    	foreach($this->builders as $builder)
    	{
    		if(method_exists($builder, $method))
    		{
    			return $builder;
    		}
    	}

    	return false;
    }

    public function get($method)
    {
    	if(($builder = $this->has($method)) === false)
    	{
    		throw new \Exception(sprintf("No builders specify the '%s' method", $method));
    	}

    	return call_user_func(array($builder, $method));
    }
}
