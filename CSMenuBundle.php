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

namespace CS\MenuBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use CS\MenuBundle\DependencyInjection\Compiler\BuilderCompilerPass;

class CSMenuBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new BuilderCompilerPass());
	}
}
