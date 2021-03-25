<?php

namespace Nails\GeoCode\Driver\Settings;

use Nails\Common\Helper\Form;
use Nails\Common\Interfaces;
use Nails\Common\Service\FormValidation;
use Nails\Components\Setting;
use Nails\Factory;

/**
 * Class Google
 *
 * @package Nails\GeoCode\Driver\Settings
 */
class Google implements Interfaces\Component\Settings
{
    const KEY_API_KEY = 'apiKey';

    // --------------------------------------------------------------------------

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return 'Geo-Code: Google Geocoding API';
    }

    // --------------------------------------------------------------------------

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        /** @var Setting $oApiKey */
        $oApiKey = Factory::factory('ComponentSetting');
        $oApiKey
            ->setKey(static::KEY_API_KEY)
            ->setType(Form::FIELD_PASSWORD)
            ->setLabel('API Key')
            ->setEncrypted(true)
            ->setValidation([
                FormValidation::RULE_REQUIRED,
            ]);

        return [
            $oApiKey,
        ];
    }
}
