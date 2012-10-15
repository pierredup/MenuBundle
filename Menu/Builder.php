<?php

/*
 * This file is part of the CSMenuBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
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
    /**
     * @DI\Inject("service_container");
     */
    public $container;

    public function getFactory()
    {
        $factory = $this->container->get('knp_menu.factory');

        return $factory;
    }

    public function sidebarMenu()
    {
        $factory = $this->getFactory();

        $menu = $factory->createItem('root');

        $menu->addChild('Dashboard', array('route' => '_dashboard'));

        $menu->setChildrenAttributes(array('class' => 'nav nav-list'));

        return $menu;
    }

    public function topMenu()
    {
        $factory = $this->getFactory();

        $menu = $factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Home', array('route' => '_dashboard'));
        $menu->addChild('Clients', array('route' => '_client_index'));

        return $menu;
    }
}
