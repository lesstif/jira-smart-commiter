<?php

namespace App\Models;

/**
 * DVCS User Dto.
 *
 * Class UserDto
 */
class UserDto
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $username;

    /** @var string */
    public $state;

    /** @var string|null */
    public $avatar_url;

    /** @var string|null */
    public $web_url;
}
