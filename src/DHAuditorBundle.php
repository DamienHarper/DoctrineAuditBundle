<?php

namespace DH\AuditorBundle;

use DH\AuditorBundle\DependencyInjection\Compiler\AddProviderCompilerPass;
use DH\AuditorBundle\DependencyInjection\Compiler\StorageConfigurationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DHAuditorBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddProviderCompilerPass());
        $container->addCompilerPass(new StorageConfigurationCompilerPass());
    }
}
