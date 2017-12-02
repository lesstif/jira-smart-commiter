<?php
namespace App;

use App\Exceptions\NotImplmentationException;
use InvalidArgumentException;

class DvcsConnectorFactory
{
    public static function create(SmartCommitConfig $config) : DvcsContract
    {
        $dvcsType = $config->getConfig()->dvcsType;

        switch($dvcsType) {
            case 'gitlab':
                return new GitLabHandler($config);
            case 'github':
                throw new NotImplmentationException("github handler not implmentation yet.");
            case 'bitbucket':
                throw new NotImplmentationException("bitbucket handler not implmentation yet.");
            default:
                throw new InvalidArgumentException($dvcsType . " unknown dvcs type.");

        }
    }
}