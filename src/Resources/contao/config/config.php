<?php
/**
 * This file is part of Contao Affiliate Marketing Tip Bundle.
 *
 * @link      https://www.oveleon.de/
 * @source    https://github.com/oveleon/contao-affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 */

// Back end modules
array_insert($GLOBALS['BE_MOD']['content'], 5, array
(
    'marketingTip' => array
    (
        'tables'      => array('tl_marketing_tip')
    )
));

// Front end modules
array_insert($GLOBALS['FE_MOD']['user'], 0, array
(
    'marketingTipList'      => '\\Oveleon\\ContaoAffiliateMarketingTip\\ModuleMarketingTipList',
    'marketingTipCreate'    => '\\Oveleon\\ContaoAffiliateMarketingTip\\ModuleMarketingTipCreate',
    'marketingTipStatistics'=> '\\Oveleon\\ContaoAffiliateMarketingTip\\ModuleMarketingTipStatistics',
));

// Models
$GLOBALS['TL_MODELS']['tl_marketing_tip'] = '\\Oveleon\\ContaoAffiliateMarketingTip\\MarketingTipModel';
