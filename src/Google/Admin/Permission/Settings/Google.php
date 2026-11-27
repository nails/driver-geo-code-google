<?php

namespace Nails\GeoCode\Driver\Google\Admin\Permission\Settings;

use Nails\Admin\Interfaces\Permission;

class Google implements Permission
{
    public function label(): string
    {
        return 'Can manage Geo-Code: Google Geocoding API settings';
    }

    public function group(): string
    {
        return 'Settings';
    }
}
