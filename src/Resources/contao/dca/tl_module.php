<?php
/**
 * This file is part of Contao Affiliate Marketing Tip Bundle.
 *
 * @link      https://www.oveleon.de/
 * @source    https://github.com/oveleon/contao-affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 */

// Add selector to tl_module
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'marketingTip_activate';

// Add module palette for marketing lists
array_insert($GLOBALS['TL_DCA']['tl_module']['palettes'], 0, array
(
    'marketingTipList'       => '{title_legend},name,headline,type;{config_legend},numberOfItems,perPage;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID',
    'marketingTipCreate'     => '{title_legend},name,headline,type;{redirect_legend},jumpTo;{email_legend},marketingTip_activate;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID',
    'marketingTipStatistics' => '{title_legend},name,headline,type;{config_legend},marketingTip_addSorting,marketingTip_addChart;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID',
));

// Add subpalette to tl_module
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['marketingTip_activate'] = 'marketingTip_recipient';

// Add fields to tl_module
$GLOBALS['TL_DCA']['tl_module']['fields']['marketingTip_activate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['marketingTip_activate'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['marketingTip_recipient'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['marketingTip_recipient'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('mandatory'=>true, 'maxlength'=>1022, 'rgxp'=>'emails', 'tl_class'=>'w50'),
    'sql'                     => "varchar(1022) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['marketingTip_addSorting'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['marketingTip_addSorting'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['marketingTip_addChart'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['marketingTip_addChart'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class tl_module_marketing_tip extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }
}
