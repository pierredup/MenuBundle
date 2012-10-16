<?php

/*
 * This file is part of the CSMenuBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CS\MenuBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class BuilderCompilerPass implements CompilerPassInterface {

	public function process(ContainerBuilder $container)
	{
		if (!$container->hasDefinition('cs_menu.builder')) {
			return;
		}

		$definition = $container->getDefinition(
				'cs_menu.builder'
		);

		$taggedServices = $container->findTaggedServiceIds(
				'cs_menu.builder'
		);

		foreach ($taggedServices as $id => $attributes) {
			$definition->addMethodCall(
					'addBuilder',
					array(new Reference($id))
			);
		}
	}

}