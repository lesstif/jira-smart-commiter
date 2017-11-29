# External JIRA Smart Commiter.

External JIRA smart commiter.

# How it works

- run manually or lachunched via task scheduler(crontab, windows task manager, etc...)
- connect DVCS Repository(gitlab, github, bitbucket, etc...) and check out new commit and scan commit message .
- commenting and status transition on the relative jira issue.

## Pros 
* Support gitlab integration with smart commit.
* Don't need DVCS commit hook.
* Don't need JIRA DVCS Connector settings per project.
* Customizable smart commit keyword and workflow.

## Cons
* Need PHP
* to many jira issue comment.

# Requirements

* PHP >= 7.1 with bottom extensions.
  * sqlite
  * curl
* [php jira-rest-client](https://github.com/lesstif/php-jira-rest-client)
* [laravel zero](https://github.com/laravel-zero/laravel-zero)

# Installation

TODO

# Usage

TODO


# See also
* https://confluence.atlassian.com/adminjiraserver/enable-smart-commits-938846894.html
* https://docs.gitlab.com/ee/user/project/integrations/jira.html
