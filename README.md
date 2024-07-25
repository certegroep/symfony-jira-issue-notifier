# Jira Issue Comment Notifier

Provides [Atlassian Jira](https://www.atlassian.com/software/jira) integration
for Symfony Notifier. To post comments on Jira Issues.

## DSN example

```
ATLASSIAN_JIRA_DSN=jira-issue://{user}@{access_token}:{your_atlassian_slug}.atlassian.net
```

where:
 - `{user}` is the account email or username to post comments by
 - `{access_token}` is the password or access token for the account
 - `{your_atlassian_slug}` is the name of your Atlassian URL (probably ending on .atlassian.net)

## Adding text to a Message

With this Jira Issue Comment, you can use the `JiraIssueComment` class:

```php
use CerteGroep\Component\Notifier\JiraIssue\Message\IssueCommentMessage;
use CerteGroep\Component\Notifier\JiraIssue\Message\JiraIssueCommentOptions;

$options = (new JiraIssueCommentOptions())->generic('This is a test...');
$message = (new IssueCommentMessage('KEY-1234', $options));

$texter->send($message);
```

## Multiple supported comment types

### Emoji Text

Add a comment with an emoji icon in front.  
Use the emoji key/name to represent the right icon. See Jira comment box for all emojis you can use.

```php
$options = (new JiraIssueCommentOptions())->emojiText('tada', 'Fixed it!');
```

### Panels

A grean box with a checkmark before the text message

```php
$options = (new JiraIssueCommentOptions())->success('Fixed it!');
```

A box with an info-icon before the text message

```php
$options = (new JiraIssueCommentOptions())->info('Did you know...');
```

An orange/yellow box with an exclamation-triangle before the text message

```php
$options = (new JiraIssueCommentOptions())->warning('Uh oh! Check this out...');
```

A red box with a cross before the text message

```php
$options = (new JiraIssueCommentOptions())->error('Something went wrong');
```

### Checklist comment

By default the text items with `true` will appear with a check-mark icon in front and the items with a `false` flag will appear
with a cross-mark icon.

```php
$options = (new JiraIssueCommentOptions())->checklist([
    'List item 1' => true,
    'List item 2' => true,
    'List item 3' => false,
    'List item 4' => true,
]);
```

Addional you can add some introduction text to the checklist;

```php
$options = (new JiraIssueCommentOptions())->checklist([
    'List item 1' => true,
    'List item 2' => true,
    'List item 3' => false,
    'List item 4' => true,
], 'This is why we did that...');
```

To change the default `true/false` icons;

```php
$options = (new JiraIssueCommentOptions())->checklist([
    'List item 1' => true,
    'List item 2' => true,
    'List item 3' => false,
    'List item 4' => true,
], null, 'partying_face', 'worried');
```

### Custom format

The format of message is built by the [Atlassian Document Format](https://developer.atlassian.com/cloud/jira/platform/apis/document/structure/).

Which is implemented by [Damien Harper's ADF Tools](https://github.com/DamienHarper/adf-tools/blob/main/doc/index.md). See the documentation for more details about that.

```php
$options = (new JiraIssueCommentOptions())->custom(
    JiraIssueCommentOptions::doc()
        ->paragraph()
            ->text('This is a text line')
        ->end()
);
```