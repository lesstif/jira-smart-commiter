<?php
/**
 * Created by PhpStorm.
 * User: lesstif
 * Date: 2017-12-12
 * Time: 오전 9:22.
 */

namespace App;

/**
 * DVCS Repository(git project) Contract.
 *
 * Class RepositoryContract
 */
abstract class RepositoryContract
{
    /**
     * List Branches.
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function getBranches($options = []) : \Illuminate\Support\Collection;
}
