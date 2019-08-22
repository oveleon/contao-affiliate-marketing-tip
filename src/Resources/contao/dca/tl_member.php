<?php
/**
 * This file is part of Contao Affiliate Marketing Tip Bundle.
 *
 * @link      https://www.oveleon.de/
 * @source    https://github.com/oveleon/contao-affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 */

// Add fields to tl_member
$GLOBALS['TL_DCA']['tl_member']['fields']['marketingTip_partner'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['marketingTip_partner'],
    'exclude'                 => true,
    'search'                  => true,
    'sorting'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>32, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'contact', 'tl_class'=>'w50'),
    'sql'                     => "varchar(32) NOT NULL default ''"
);

// Extend default palette
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addField(array('marketingTip_partner'), 'contact_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member')
;
