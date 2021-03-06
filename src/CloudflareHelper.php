<?php

namespace Zoxta\NovaCloudflareCard;

use GuzzleHttp\Client;

class CloudflareHelper
{
    public static function cachePurge()
    {
        $zone_id = config('services.cloudflare.zone_id');

        $client = static::getClient();
        $response = $client->delete("client/v4/zones/{$zone_id}/purge_cache", ['json' => ['purge_everything' => true]]);

        return json_decode($response->getBody(), true)['success'] ?? false;
    }

    protected static function getClient()
    {
        $headers = [
            'X-Auth-Key' => config('services.cloudflare.key'),
            'X-Auth-Email' => config('services.cloudflare.email'),
        ];
        if(config('services.cloudflare.bearer')) {
            $headers['Authorization'] = 'Bearer ' . config('services.cloudflare.bearer');
            unset($headers['X-Auth-Key']);
        }
        $base_uri = 'https://api.cloudflare.com/';
        $client = new Client(compact('base_uri', 'headers'));

        return $client;
    }
}