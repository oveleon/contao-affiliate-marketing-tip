<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

namespace ContaoEstateManager\AffiliateMarketingTip;

use ContaoEstateManager\EstateManager;

class AddonManager
{
    /**
     * Addon name
     * @var string
     */
    public static $name = 'AffiliateMarketingTip';

    /**
     * Addon config key
     * @var string
     */
    public static $key  = 'addon_affiliate_marketing_tip_license';

    /**
     * Is initialized
     * @var boolean
     */
    public static $initialized  = false;

    /**
     * Is valid
     * @var boolean
     */
    public static $valid  = false;

    /**
     * Licenses
     * @var array
     */
    private static $licenses = [

    ];

    public static function getLicenses()
    {
        return static::$licenses;
    }

    public static function valid()
    {
        if(strpos(\Environment::get('requestUri'), '/contao/install') !== false)
        {
            return true;
        }

        if (static::$initialized === false)
        {
            static::$valid = EstateManager::checkLicenses(\Config::get(static::$key), static::$licenses, static::$key);
            static::$initialized = true;
        }

        return static::$valid;
    }

}
