# External JIRA Smart Commiter.

[![Latest Stable Version](https://poser.pugx.org/lesstif/jira-smart-commiter/v/stable)](https://packagist.org/packages/lesstif/jira-smart-commiter)
[![Latest Unstable Version](https://poser.pugx.org/lesstif/jira-smart-commiter/v/unstable)](https://packagist.org/packages/lesstif/jira-smart-commiter)
[![StyleCI](https://styleci.io/repos/112442170/shield?branch=master&style=flat)](https://styleci.io/repos/112442170)
[![License](https://poser.pugx.org/lesstif/jira-smart-commiter/license)](https://packagist.org/packages/lesstif/jira-smart-commiter)

External JIRA smart commiter built with [Laravel Zero](https://github.com/laravel-zero/laravel-zero).

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

1. clone repository

    ```sh
    git clone https://github.com/lesstif/jira-smart-commiter.git && cd jira-smart-commiter
    ```

1. install composer dependency

    ```sh
    composer install
    ```

1. Perform an application build

    ```sh
    php jira-smart-commiter app:build jira-smart-commiter.phar    
    ```
    
    you can find built binary in builds directory.

## download

Recommended method 

1. download pre-built binary

    using wget 
    ```sh
    wget https://github.com/lesstif/jira-smart-commiter/releases/download/0.1-alpha/jira-smart-commiter.phar
    ```

    using curl
    ```sh
    curl -k -L -O https://github.com/lesstif/jira-smart-commiter/releases/download/0.1-alpha/jira-smart-commiter.phar
    ```
1. change mod

    ```sh
    chmod +x jira-smart-commiter.phar
    ```


# Usage

1. generate initial configuration
    
    ```sh
    php jira-smart-commiter.phar init
    ```

1. change DVCS Info(URL, Type and API Version) field and JIRA Server Info in the *$HOME/.smartcommit/settings.json* file.

    ```sh
    vim ~/.smartcommit/settings.json
    ```

1. generate dvcs project list.* $HOME/.smartcommit/projects.json*:

    ```sh
     php jira-smart-commiter.phar project:create-list
    ```

1. fetch commit & sync to JIRA.
    
    ```sh
     php jira-smart-commiter.phar fetch:commit --since=DATETIMEString --until=DATETIMEString
    ```


# See also
* https://confluence.atlassian.com/adminjiraserver/enable-smart-commits-938846894.html
* https://docs.gitlab.com/ee/user/project/integrations/jira.html
