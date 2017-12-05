<?php
namespace App\Models;


/**
 * DVCS User Dto
 *
 * Class UserDto
 * @package App\Models
 */
class UserDto
{
    /** @var  integer */
    public $id;

    /** @var  string */
    public $name;

    /** @var  string */
    public $username;

    /** @var  string */
    public $state;

    /** @var  string|null */
    public $avatar_url;

    /** @var  string|null */
    public $web_url;
}