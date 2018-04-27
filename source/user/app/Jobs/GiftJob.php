<?php
namespace App\Jobs;

use GuzzleHttp\Client;
use Log;

class GiftJob extends Job
{
    public function __construct()
    {
        //
    }

    public function handle()
    {
        $client = new Client(['verify' => false]);
        $remoteCall = $client->get('http://microservice_secret_nginx/api/v1/secret/1');

        /* Do stuff with the return from a remote service, for 
        example save it in the wallet */
        $debug = var_export($remoteCall, true);
        Log::info($debug);

    }
}
