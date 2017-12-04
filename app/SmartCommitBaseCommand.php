<?php
namespace App;

use LaravelZero\Framework\Commands\Command;

class SmartCommitBaseCommand extends Command
{
    protected $handler ;

    protected $config;

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command. Here goes the command
     * code.
     *
     * @return void
     */
    public function handle(): void
    {
        // TODO: Implement handle() method.
        /*
        $this->config = new SmartCommitConfig();

        $this->config->load();

        if (empty($this->config->getConfig()->dvcsType)) {
            throw SmartCommitException("DVCS Type not found");
        }
        */

        $handler = DvcsConnectorFactory::create();


    }
}