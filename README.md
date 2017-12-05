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

## Build from Source
Recommended method for gitlab < 9.0

1. clone repository
  ```sh
  git clone https://github.com/lesstif/jira-smart-commiter.git && cd jira-smart-commiter
  ```

1. change gitlab api library version in the composer.json
   
   if gitlab >= 9.0(API V4), then use "^9.6"
  ```sh
  "require": {
        "m4tthumphrey/php-gitlab-api": "^9.6",
  ```
  
   if gitlab < 9.0(API V3), then use "~8.0"
  ```sh
  "require": {
        "m4tthumphrey/php-gitlab-api": "~8.0",
  ```

1. install composer dependency
  ```sh
  composer install
  ```

## download


# Usage

TODO


# See also
* https://confluence.atlassian.com/adminjiraserver/enable-smart-commits-938846894.html
* https://docs.gitlab.com/ee/user/project/integrations/jira.html
