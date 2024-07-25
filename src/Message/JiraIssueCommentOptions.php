<?php

declare(strict_types=1);

namespace CerteGroep\Component\Notifier\JiraIssue\Message;

use CerteGroep\Component\Notifier\JiraIssue\EmojisEnum;
use DH\Adf\Node\Block\Document;
use DH\Adf\Node\Node;
use JiraCloud\Issue\Comment;
use Symfony\Component\Notifier\Message\MessageOptionsInterface;

class JiraIssueCommentOptions implements MessageOptionsInterface
{
    private Node|Document $doc;

    public function toArray(): array
    {
        return [
            'body' => $this->doc->jsonSerialize(),
        ];
    }

    public function getRecipientId(): ?string
    {
        return '...';
    }

    public function getComment(): Comment
    {
        return (new Comment())->setBodyByAtlassianDocumentFormat($this->doc);
    }

    public static function doc(): Document
    {
        return new Document();
    }

    public function generic(string|array|Node|Document $body): self
    {
        $doc = self::doc();

        if (is_string($body)) {
            $doc = $doc->paragraph()->text($body)->end();
        }

        if (is_array($body)) {
            foreach ($body as $line) {
                $doc = $doc->paragraph()->text($line)->end();
            }
        }

        return $this->do($doc);
    }

    public function success(string $body): self
    {
        return $this->panel($body, 'success');
    }

    public function info(string $body): self
    {
        return $this->panel($body, 'info');
    }

    public function warning(string $body): self
    {
        return $this->panel($body, 'warning');
    }

    public function error(string $body): self
    {
        return $this->panel($body, 'error');
    }

    public function emojiText(string $emoji, string $text): self
    {
        return $this->do($this->doc()
            ->paragraph()
                ->emoji($emoji)
                ->text(' '.$text)
            ->end());
    }

    public function checklist(
        array $checkList,
        ?string $introText = null,
        string $on = 'check_mark',
        string $off = 'cross_mark'
    ): self {
        $doc = $this->doc();

        if (null !== $introText) {
            $doc = $doc->paragraph()->text($introText)->end();
        }

        foreach ($checkList as $label => $checked) {
            $doc = $doc
                ->paragraph()
                    ->emoji($checked ? $on->value : $off->value)
                    ->text(' '.$label)
                ->end();
        }

        return $this->do($doc);
    }

    public function custom(Node|Document $body): self
    {
        return $this->do($body);
    }

    private function panel(string $body, string $type): self
    {
        $body = self::doc()->panel($type)
            ->paragraph()
                ->text($body)
            ->end()
        ->end();

        return $this->do($body);
    }

    private function do(Node|Document $body): self
    {
        $this->doc = $body;

        return $this;
    }
}
