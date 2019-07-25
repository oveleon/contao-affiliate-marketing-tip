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

use Contao\CoreBundle\Exception\PageNotFoundException;
use Patchwork\Utf8;

/**
 * Front end module "marketing tip create".
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class ModuleMarketingTipCreate extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_marketingTipCreate';

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['marketingTipList'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if there is no logged in user
		if (!FE_USER_LOGGED_IN)
        {
            return '';
        }

        if ($this->customTpl != '')
        {
            $this->strTemplate = $this->customTpl;
        }

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
        $doNotSubmit = false;
        $strFormId = 'tl_marketing_tip_create_' . $this->id;

        // Create new marketing tip if there are no errors
        if (\Input::post('FORM_SUBMIT') == $strFormId && !$doNotSubmit)
        {
            $this->createNewMarketingTip();
        }
	}
}
