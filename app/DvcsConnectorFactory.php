<?php
namespace App;

use App\Exceptions\NotImplmentationException;
use InvalidArgumentException;
use App\Exceptions\SmartCommitException;

class DvcsConnectorFactory
{
    public static function create() : DvcsContract
    {
        $config = new SmartCommitConfig();

        $config->load();

        $dvcsType = $config->getSettings()->dvcsType;

        if (empty($dvcsType)) {
            throw new SmartCommitException("DVCS Type not found");
        }

        switch($dvcsType) {
            case 'gitlab':
                $ver = mb_strtoupper ($config->getSettings()->gitlabApiVersion);
                if ($ver === 'V3') {
                    return new GitLabV3Handler($config);
                }
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