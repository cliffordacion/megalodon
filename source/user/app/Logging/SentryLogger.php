<?php

namespace App\Logging;

use \Monolog\Handler\RavenHandler;
use Monolog\Logger;

class SentryLogger
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $client = new \Raven_Client(env('SENTRY_DSN', null));
        $logger->pushHandler(
            new RavenHandler($client, Logger::DEBUG)
        );
        $logger->pushHandler(
            new RavenHandler($client, Logger::INFO)
        );
        $logger->pushHandler(
            new RavenHandler($client, Logger::NOTICE)
        );
        $logger->pushHandler(
            new RavenHandler($client, Logger::WARNING)
        );
        $logger->pushHandler(
            new RavenHandler($client, Logger::ERROR)
        );
        $logger->pushHandler(
            new RavenHandler($client, Logger::CRITICAL)
        );
    }
}