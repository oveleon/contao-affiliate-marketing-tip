<?php
/**
 * This file is part of Contao Affiliate Marketing Tip Bundle.
 *
 * @link      https://www.oveleon.de/
 * @source    https://github.com/oveleon/contao-affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 */

// Add module palette for marketing lists
array_insert($GLOBALS['TL_DCA']['tl_module']['palettes'], 0, array
(
    'marketingTipList'    => '{title_legend},name,headline,type;{config_legend},numberOfItems,perPage;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID',
    'marketingTipCreate'  => '{title_legend},name,headline,type;{redirect_legend},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID',
));
