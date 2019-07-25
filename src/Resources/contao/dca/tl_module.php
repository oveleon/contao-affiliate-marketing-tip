<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

if(ContaoEstateManager\AffiliateMarketingTip\AddonManager::valid()) {
    // Add module palette for marketing lists
    array_insert($GLOBALS['TL_DCA']['tl_module']['palettes'], 0, array
    (
        'marketingTipList'  => '{title_legend},name,headline,type;{config_legend},numberOfItems,perPage;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID',
    ));
}
