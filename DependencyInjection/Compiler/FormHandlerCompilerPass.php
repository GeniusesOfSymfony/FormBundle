<?php
namespace Gos\Bundle\FormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('form.handler') as $id => $tagAttributes) {
            $formHandler = $container->getDefinition($id);

            $formHandler
                ->addMethodCall('setFormFactory', array(new Reference('form.factory')))
                ->addMethodCall('setRequestStack', array(new Reference('request_stack')))
            ;

            foreach ($tagAttributes as $attributes) {
                $formHandler->addMethodCall('setFormName', array($attributes['form']));
            }
        }
    }
}
