<?php

namespace Gos\Bundle\FormBundle;

use Gos\Bundle\FormBundle\DependencyInjection\Compiler\FormHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GosFormBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FormHandlerCompilerPass());

        parent::build($container);
    }
}
