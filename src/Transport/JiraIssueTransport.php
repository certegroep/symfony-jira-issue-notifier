<?php

declare(strict_types=1);

namespace CerteGroep\Component\Notifier\JiraIssue\Transport;

use CerteGroep\Component\Notifier\JiraIssue\Message\IssueCommentMessage;
use CerteGroep\Component\Notifier\JiraIssue\Message\JiraIssueCommentOptions;
use JiraCloud\Configuration\ArrayConfiguration;
use JiraCloud\Issue\IssueService;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JiraIssueTransport extends AbstractTransport
{
    private IssueService $issues;

    public function __construct(
        private readonly string $jiraHost,
        string $username,
        string $accessToken,
        private readonly ?LoggerInterface $logger = null,
        ?HttpClientInterface $client = null,
        ?EventDispatcherInterface $dispatcher = null
    ) {
        $this->host = sprintf('https://%s', $this->jiraHost);

        parent::__construct($client, $dispatcher);

        $config = new ArrayConfiguration([
            'jiraHost' => $this->host,
            'jiraUser' => $username,
            'personalAccessToken' => $accessToken,
        ]);

        $this->issues = new IssueService($config);
    }

    public function __toString(): string
    {
        return sprintf('jira-issue://%s', $this->jiraHost);
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof IssueCommentMessage
            && (
                null === $message->getOptions()
                || $message->getOptions() instanceof JiraIssueCommentOptions
            );
    }

    protected function doSend(MessageInterface $message): SentMessage
    {
        try {
            if (!$message instanceof IssueCommentMessage) {
                throw new RuntimeException('Message is not a Jira Issue comment message');
            }

            $comment = $this->issues->addComment($message->getSubject(), $message->getOptions()->getComment());
            $commentId = $comment->id;
        } catch (\Exception $e) {
            $this->logger?->error($e->getMessage());
            throw $e;
        }

        $sendMessage = new SentMessage($message, (string) $this);
        $sendMessage->setMessageId(sprintf("%s::%s", $message->getSubject(), $commentId));

        return $sendMessage;
    }
}
