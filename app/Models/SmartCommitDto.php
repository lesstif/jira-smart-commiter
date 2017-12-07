<?php
namespace App\Models;

/**
 * Smart Commit Comment Format.
 *
 * @See https://confluence.atlassian.com/bitbucket/processing-jira-software-issues-with-smart-commit-messages-298979931.html
 *
 * Class SmartCommitDto
 * @package App\Models
 */
class SmartCommitDto
{
    public $transitions_comment = '{{USER}} Issue {{TRANSITION}} with {{COMMIT}}';
    public $transitions = [
        [
            'keywords' => [
                'resolve',
                'fix',
            ],
            'status' => "Resolved",
        ],
        [
            'keywords' => [
                'close',
                '닫음',
            ],
            'status' => "Closed",
        ],
        [
            'keywords' => [
                'Done',
            ],
            'status' => "Done",
        ],
    ];

    public $referencing_comment = '{{USER}} mentioned this issue in {{COMMIT}}';
    public $referencing = [
        'ref',
        '참조',
    ];

    public $merging_comment = '{{USER}} {{COMMIT_MESSAGE}} with  issue in {{COMMIT}}';
    public $merging = [
        'merge',
    ];

    public $time_keywords = [
        '#time',
        '#worklog',
        '#작업시간',
    ];

}