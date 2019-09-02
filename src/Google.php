<?php

namespace Nails\GeoCode\Driver;

use Nails\Common\Driver\Base;
use Nails\Common\Traits\Caching;
use Nails\Factory;
use Nails\GeoCode\Exception\GeoCodeDriverException;
use Nails\GeoCode\Result\LatLng;

/**
 * Class Google
 *
 * @package Nails\GeoCode\Driver
 */
class Google extends Base implements \Nails\GeoCode\Interfaces\Driver
{
    use Caching;

    // --------------------------------------------------------------------------

    /**
     * The URL to make the request
     */
    const REQUEST_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    // --------------------------------------------------------------------------

    /**
     * The API Key to use.
     * Generate one here: https://developers.google.com/maps/documentation/geocoding/get-api-key
     *
     * @var string
     */
    protected $sApiKey;

    // --------------------------------------------------------------------------

    /**
     * The HTTP client to use
     *
     * @var object
     */
    protected $oHttpClient;

    // --------------------------------------------------------------------------

    /**
     * Set up the driver.
     */
    public function __construct()
    {
        $this->sApiKey = appSetting('apiKey', 'nails/driver-geo-code-google');

        if (empty($this->sApiKey)) {
            throw new GeoCodeDriverException('A Google API key must be provided.');
        }

        // --------------------------------------------------------------------------

        $this->oHttpClient = Factory::factory('HttpClient');
    }

    // --------------------------------------------------------------------------

    /**
     * @param string $sAddress The address to look up
     *
     * @return \Nails\GeoCode\Result\LatLng
     */
    public function lookup($sAddress)
    {
        $sCacheKey = md5($sAddress);
        $oCache    = $this->getCache($sCacheKey);

        if (!empty($oCache)) {
            return $oCache;
        }

        /** @var LatLng $oLatLng */
        $oLatLng = Factory::factory('LatLng', 'nails/module-geo-code');
        $oLatLng->setAddress($sAddress);

        try {

            $oResponse = $this->oHttpClient->request(
                'GET',
                $this::REQUEST_URL,
                [
                    'query' => [
                        'key'     => $this->sApiKey,
                        'address' => $sAddress,
                    ],
                ]
            );

            if ($oResponse->getStatusCode() === 200) {

                $oJson = json_decode($oResponse->getBody());

                if ($oJson->status === 'OK') {
                    if (!empty($oJson->results[0]->geometry->location->lat)) {
                        $oLatLng->setLat($oJson->results[0]->geometry->location->lat);
                    }

                    if (!empty($oJson->results[0]->geometry->location->lng)) {
                        $oLatLng->setLng($oJson->results[0]->geometry->location->lng);
                    }
                }
            }

        } catch (\Exception $e) {
            //  @todo (Pablo - 2019-09-02) - log the exception somewhere?
        }

        $this->setCache($sCacheKey, $oLatLng);
        return $oLatLng;
    }
}
