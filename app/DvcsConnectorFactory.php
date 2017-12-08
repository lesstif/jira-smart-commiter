<?php

namespace App;

use InvalidArgumentException;
use App\Exceptions\SmartCommitException;
use App\Exceptions\NotImplmentationException;

class DvcsConnectorFactory
{
    public static function create($file = 'settings.json') : DvcsContract
    {
        $config = new SmartCommitConfig();

        $config->loadSettings($file);

        $dvcsType = $config->getSettings()->dvcsType;

        return self::createByType($dvcsType, $config->getSettings()->gitlabApiVersion);
    }

    public static function createByType($dvcsType, $apiVersion = 'V4') : DvcsContract
    {
        if (empty($dvcsType)) {
            throw new SmartCommitException('DVCS Type not supplied');
        }

        switch ($dvcsType) {
            case 'gitlab':
                $ver = mb_strtoupper($apiVersion);
                if ($ver === 'V3') {
                    return new GitLabV3Handler();
                }

                return new GitLabHandler();
            case 'github':
                throw new NotImplmentationException('github handler not implmentation yet.');
            case 'bitbucket':
                throw new NotImplmentationException('bitbucket handler not implmentation yet.');
            default:
                throw new InvalidArgumentException($dvcsType.' unknown dvcs type.');
        }
    }
}
