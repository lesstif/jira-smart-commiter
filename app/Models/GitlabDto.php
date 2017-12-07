<?php

namespace App\Models;

class GitlabDto extends ProjectDto
{
    public function __construct()
    {
        $this->dvcsType = 'gitlab';
        $this->apiVersion = 'V4';
    }
}
