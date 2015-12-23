<?php

namespace Nails\GeoCode\Driver;

use Nails\Factory;

class Google implements \Nails\GeoCode\Interfaces\Driver
{
    /**
     * @param string $sAddress  The address to look up
     * @return \Nails\GeoCode\Result\LatLng
     */
    public function lookup($sAddress)
    {
        dumpanddie('@todo');
    }
}
