<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use App\Jobs\GiftJob;
use Log;

class UserController extends Controller
{
    protected $userCache = [
        1 => [
            'name' => 'John',
            'city' => 'Barcelona'
        ],
        2 => [
            'name' => 'Joe',
            'city' => 'Paris'
        ],
        3 => [
            'name' => 'Cliff',
            'city' => 'French'
        ],
    ];

    public function index(Request $request)
    {
        Log::warning('CLIFFORD');
        Log::info('SENTRY PASOK NA!');
        return response()->json(
            $this->userCache
        );
    }

    public function get($id)
    {
        return response()->json(
            $this->userCache[$id]
        );
    }

    public function create(Request $request)
    {
        $this->dispatch(new GiftJob());
        return response()->json(['method' => 'create']);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['method' => 'update', 'id' => $id]);
    }

    public function delete($id)
    {
        return response()->json(['method' => 'delete', 'id' => $id]);
    }

    public function getCurrentLocation($id)
    {
        return response()->json([
            'method' => 'getCurrentLocation',
            'id' => $id
        ]);
    }

    public function setCurrentLocation(Request $request, $id, $latitude, $longitude)
    {
        return response()->json([
            'method' => 'setCurrentLocation',
            'id' => $id, 
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
    }

    public function getWallet($id)
    {
        $client = new Client(['verify' => false]);
        try {
            $remoteCall = $client->get('http://microservice_secret_nginx/api/v1/secret/1');
            return $remoteCall;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
