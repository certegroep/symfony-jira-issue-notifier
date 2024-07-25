<?php

declare(strict_types=1);

namespace CerteGroep\Component\Notifier\JiraIssue\Message;

use JiraCloud\Issue\Issue;
use Symfony\Component\Notifier\Message\PushMessage;

class IssueCommentMessage extends PushMessage
{
    private ?string $transport = null;

    public function __construct(
        private readonly string|Issue $issueOrKey,
        private ?JiraIssueCommentOptions $options = null,
    ) {
    }

    public function getRecipientId(): ?string
    {
        return $this->issueOrKey instanceof Issue
            ? $this->issueOrKey->key
            : $this->issueOrKey;
    }

    public function getSubject(): string
    {
        return $this->issueOrKey instanceof Issue
            ? $this->issueOrKey->key
            : $this->issueOrKey;
    }

    public function getOptions(): ?JiraIssueCommentOptions
    {
        return $this->options;
    }
}
