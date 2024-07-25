<?php

declare(strict_types=1);

namespace CerteGroep\Component\Notifier\JiraIssue\Transport;

use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportInterface;

class JiraIssueTransportFactory extends AbstractTransportFactory
{
    protected function getSupportedSchemes(): array
    {
        return ['jira', 'jira-issue'];
    }

    public function create(Dsn $dsn): TransportInterface
    {
        $scheme = $dsn->getScheme();

        if ('jira-issue' !== $scheme && 'jira' !== $scheme) {
            throw new UnsupportedSchemeException($dsn, 'jira-issue', $this->getSupportedSchemes());
        }

        return new JiraIssueTransport($dsn->getHost(), $dsn->getUser(), $dsn->getPassword());
    }
}
