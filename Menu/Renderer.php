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

use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Silex\Voter\RouteVoter;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("cs_menu.renderer");
 * @DI\Tag(name="knp_menu.renderer", attributes={"alias"="cs_menu"})
 */
class Renderer extends ListRenderer
{
    /**
     * @DI\InjectParams({"container" = @DI\Inject("service_container")});
     */
    public function __construct($container)
    {
        $voter = new RouteVoter($route);

        $voter->setRequest($container->get('request'));

        $matcher = new Matcher();
        $matcher->addVoter($voter);

        parent::__construct($matcher, array('currentClass' => 'active'));
    }
}
