<?php
/**
 * Created by PhpStorm.
 * User: lesstif
 * Date: 2017-12-08
 * Time: 오후 7:42.
 */

namespace App\Models;

class CommitDto implements \JsonSerializable
{
    //{ gitlab commit info

    /** @var string commit hash */
    public $id;

    /** @var string short commit hash */
    public $short_id;

    /** @var string */
    public $title;

    /** @var string */
    public $author_name;

    /** @var string */
    public $author_email;

    /** @var \DateTime */
    public $created_at;

    /** @var string commit message */
    public $message;

    //}} end of gitlab commit info

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
