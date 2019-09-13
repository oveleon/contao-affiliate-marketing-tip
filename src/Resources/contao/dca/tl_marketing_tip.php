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
            'fields'                  => array('member', 'street', 'postal', 'city', 'status'),
            'showColumns'             => true,
            'label_callback'          => array('tl_marketing_tip', 'determineUsername')
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
        'status_success'              => 'priceSale,brokerCommission,commission',
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
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['tstamp'],
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
            'reference'               => &$GLOBALS['TL_LANG']['tl_marketing_tip'],
            'eval'                    => array('submitOnChange'=>true, 'mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(8) NOT NULL default ''",
        ),
        'reason' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['reason'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'priceSale' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['priceSale'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'brokerCommission' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['brokerCommission'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>10, 'tl_class'=>'w50'),
            'sql'                     => "decimal(10,2) NULL default '3.57'"
        ),
        'commission' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_marketing_tip']['commission'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>10, 'tl_class'=>'w50'),
            'save_callback' => array
            (
                array('tl_marketing_tip', 'calculateCommission')
            ),
            'sql'                     => "decimal(10,2) NULL default NULL"
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

    /**
     * Determine and add username
     *
     * @param array         $row
     * @param string        $label
     * @param DataContainer $dc
     * @param array         $args
     *
     * @return array
     */
    public function determineUsername($row, $label, DataContainer $dc, $args)
    {
        $objMember = \MemberModel::findByPk($row['member']);

        if ($objMember === null)
        {
            $args[0] = '---';

            return $args;
        }

        $args[0] = $objMember->firstname . ' ' . $objMember->lastname . ' (' . $objMember->username . ')';

        // translate date
        $args[5] = date(\Config::get('datimFormat'), $args[5]);

        return $args;
    }

    /**
     * Calculates commission
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return mixed
     */
    public function calculateCommission($varValue, DataContainer $dc)
    {
        if ($varValue && $varValue != 0)
        {
            return $varValue;
        }

        $commission = intval($dc->activeRecord->priceSale) / 100 * floatval($dc->activeRecord->brokerCommission) * 0.1;

        return $commission;
    }
}
