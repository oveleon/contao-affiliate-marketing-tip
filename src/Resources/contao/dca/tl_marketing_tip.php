<?php
/**
 * This file is part of Contao Affiliate Marketing Tip Bundle.
 *
 * @link      https://www.oveleon.de/
 * @source    https://github.com/oveleon/contao-affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 */

$GLOBALS['TL_DCA']['tl_marketing_tip'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'closed'                      => true,
        'notCopyable'                 => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'member' => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('tstamp'),
            'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('street', 'postal', 'city', 'status'),
            'showColumns'             => true,
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_marketing_tip']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_marketing_tip']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_marketing_tip']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('status'),
        'default'                     => '{member_legend},member;{address_legend},street,postal,city;{status_legend},status',
    ),

    // Subpalettes
    'subpalettes' => array
    (
        'status_rejected'             => 'reason',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'member' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['member'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.username',
            'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ),
        'street' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['street'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'feEditable'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'postal' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['postal'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>32, 'feEditable'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'city' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['city'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'feEditable'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'status' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['status'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'select',
            'options'                 => array('pending', 'open', 'success', 'rejected'),
            'eval'                    => array('submitOnChange'=>true, 'mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
        ),
        'reason' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['reason'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
    )
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class tl_marketing_tip extends Backend
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
