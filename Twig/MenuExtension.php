<?php

/*
 * This file is part of the CSMenuBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\MenuBundle\Twig;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("cs_menu.twig")
 * @DI\Tag("twig.extension")
 */
class MenuExtension extends \Twig_Extension
{
    /**
     * @DI\Inject("cs_menu.renderer")
     */
    public $renderer;

    /**
     * @DI\Inject("cs_menu.provider")
     */
    public $provider;

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array('cs_menu' => new \Twig_Function_Method($this, 'menu', array('is_safe' => array('html'))));
    }

    /**
     * Converts a string to singular
     * @param string $text
     */
    public function menu($type, array $options = array())
    {
        $menu = $this->provider->get($type);

        return $this->renderer->render($menu, $options);
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'twig.extension';
    }
}
