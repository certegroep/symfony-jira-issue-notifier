<?php

declare(strict_types=1);

namespace CerteGroep\Component\Notifier\JiraIssue;

use CerteGroep\Component\Notifier\JiraIssue\DependencyInjection\Compiler\JiraIssueNotifierCompilerPass;
use CerteGroep\Component\Notifier\JiraIssue\Transport\JiraIssueTransportFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CerteGroepJiraIssueBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->services()
            ->set('notifier.transport_factory.jira-issue', JiraIssueTransportFactory::class)
            ->parent('notifier.transport_factory.abstract')
            ->tag('texter.transport_factory');
    }
}
