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

use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("cs_menu.provider")
 * @DI\Tag("knp_menu.provider")
 */
class Provider implements MenuProviderInterface
{
    /**
     * @DI\Inject("knp_menu.factory")
     * @var FactoryInterface
     */
    public $factory = null;

    /**
     * @DI\Inject("cs_menu.builder")
     */
    public $builder;

    protected $items = array();

    /**
     * Retrieves a menu by its name
     *
     * @param  string                    $name
     * @param  array                     $options
     * @return \Knp\Menu\ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function get($name, array $options = array())
    {
        $method = $this->getMethod($name);

        $menu = isset($this->items[$name]) ? $this->items[$name] :  $this->builder->{$method}();

        if ($menu === null) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        return $menu;
    }

    public function addItem($name, $menu)
    {
        $this->items[$name] = $menu;
    }

    /**
     * Checks whether a menu exists in this provider
     *
     * @param  string $name
     * @param  array  $options
     * @return bool
     */
    public function has($name, array $options = array())
    {
        $method = $this->getMethod($name);

        return isset($this->items[$name]) || method_exists($this->builder, $method);
    }

    public function getMethod($name)
    {
        return strtolower($name).'Menu';
    }
}
