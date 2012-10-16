<?php

/*
 * This file is part of the CSMenuBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\MenuBundle\Driver\Annotation;

use CS\MenuBundle\Annotation;
use CS\MenuBundle\Event\ConfigureMenuEvent;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Security\Core\Util\ClassUtils;

use Knp\Menu\ItemInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("cs_menu.annotation_driver")
 */
class Menu
{
    private $builders = array();

    /**
     * @DI\Inject("service_container")
     */
    public $container;

    /**
     * @DI\Observe("kernel.controller");
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) { //return if no controller

            return;
        }

        $object = new \ReflectionClass(ClassUtils::getRealClass($controller[0]));// get controller
        $method = $object->getMethod($controller[1]);// get method

        $reader = $this->container->get('annotation_reader');

        foreach (array_merge($reader->getClassAnnotations($object), $reader->getMethodAnnotations($method)) as $configuration) { //Start of annotations reading

            if ($configuration instanceof Annotation\Menu) {
                $name = strtolower($configuration->block);
                $menu_method = $name.'Menu';

                $menu_builder = $controller[0]->get("cs_menu.builder");
                $menu = $menu_builder->get($menu_method);

                $factory = $menu_builder->getFactory();

                list($bundleName, $className, $methodName) = explode(':', $configuration->menu);

                $builder = $this->getBuilder($bundleName, $className);

                $methodName = strtolower($methodName).'Menu';

                if (!method_exists($builder, $methodName)) {
                    throw new \InvalidArgumentException(sprintf('Method "%s" was not found on class "%s" when rendering the "%s" menu.', $methodName, $className, $name));
                }

                $menu = $builder->$methodName($menu, $this->container->get('request')->attributes->get('_route_params'));

                if (!$menu instanceof ItemInterface) {
                    throw new \InvalidArgumentException(sprintf('Method "%s" did not return an ItemInterface menu object for menu "%s"', $methodName, $name));
                }

                $namespace = 'menu.'.str_replace(array('\\', 'controller', '__', 'action'), array('_', '', '_', ''), strtolower(get_class($controller[0]). '_' . $controller[1])).'.'.$name;

                $controller[0]->get('event_dispatcher')->dispatch($namespace, new ConfigureMenuEvent($factory, $menu));

                $controller[0]->get("cs_menu.provider")->addItem($name, $menu);
            }
        }
    }

    protected function getBuilder($bundleName, $className)
    {
        $name = sprintf('%s:%s', $bundleName, $className);

        if (!isset($this->builders[$name])) {
            $class = null;
            $logs = array();
            $bundles = array();

            foreach ($this->container->get('kernel')->getBundle($bundleName, false) as  $bundle) {
                $try = $bundle->getNamespace().'\\Menu\\'.$className;
                if (class_exists($try)) {
                    $class = $try;
                    break;
                }

                $logs[] = sprintf('Class "%s" does not exist for menu builder "%s".', $try, $name);
                $bundles[] = $bundle->getName();
            }

            if (null === $class) {
                if (1 === count($logs)) {
                    throw new \InvalidArgumentException($logs[0]);
                }

                throw new \InvalidArgumentException(sprintf('Unable to find menu builder "%s" in bundles %s.', $name, implode(', ', $bundles)));
            }

            $builder = new $class();
            if ($builder instanceof ContainerAwareInterface) {
                $builder->setContainer($this->container);
            }

            $this->builders[$name] = $builder;
        }

        return $this->builders[$name];
    }
}
