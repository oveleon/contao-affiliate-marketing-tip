<?php
/**
 * This file is part of Contao Affiliate Marketing Tip Bundle.
 *
 * @link      https://www.oveleon.de/
 * @source    https://github.com/oveleon/contao-affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 */

namespace Oveleon\ContaoAffiliateMarketingTip;

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
        $this->loadDataContainer('tl_marketing_tip');

        $this->Template->fields = '';

        $doNotSubmit = false;
        $strFormId = 'tl_marketing_tip_create_' . $this->id;

        $arrMarketingTip = array();
        $i = 0;
        $editable = $this->getEditableFields();

        // Build form
        foreach ($editable as $field)
        {
            $arrData = $GLOBALS['TL_DCA']['tl_marketing_tip']['fields'][$field];

            /** @var \Widget $strClass */
            $strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];

            // Continue if the class is not defined
            if (!class_exists($strClass))
            {
                continue;
            }

            $arrData['eval']['required'] = $arrData['eval']['mandatory'];

            $objWidget = new $strClass($strClass::getAttributesFromDca($arrData, $field, $arrData['default'], '', '', $this));

            $objWidget->rowClass = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

            // Validate input
            if (\Input::post('FORM_SUBMIT') == $strFormId)
            {
                $objWidget->validate();
                $varValue = $objWidget->value;

                // Store the current value
                if ($objWidget->hasErrors())
                {
                    $doNotSubmit = true;
                }
                elseif ($objWidget->submitInput())
                {
                    $arrMarketingTip[$field] = $varValue;
                }
            }

            $this->Template->fields .= $objWidget->parse();

            ++$i;
        }

        // Create new marketing tip if there are no errors
        if (\Input::post('FORM_SUBMIT') == $strFormId && !$doNotSubmit)
        {
            $this->createNewMarketingTip($arrMarketingTip);
        }

        $this->Template->formId = $strFormId;
        $this->Template->slabel = \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['createMarketingTip']);
        $this->Template->action = \Environment::get('indexFreeRequest');
	}

    /**
     * Create a new marketing tip and redirect
     *
     * @param array $arrData
     */
    protected function createNewMarketingTip($arrData)
    {
        $arrData['tstamp'] = time();
        $arrData['member'] = $this->User->id;

        //$this->sendInfoMail($arrData);

        // Create the user
        $objNewMarketingTip = new MarketingTipModel();
        $objNewMarketingTip->setRow($arrData);
        $objNewMarketingTip->save();

        // HOOK: send insert ID and user data
        if (isset($GLOBALS['TL_HOOKS']['createNewMarketingTip']) && \is_array($GLOBALS['TL_HOOKS']['createNewMarketingTip']))
        {
            foreach ($GLOBALS['TL_HOOKS']['createNewMarketingTip'] as $callback)
            {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($objNewMarketingTip->id, $arrData, $this);
            }
        }

        // Check whether there is a jumpTo page
        if (($objJumpTo = $this->objModel->getRelated('jumpTo')) instanceof \PageModel)
        {
            $this->jumpToOrReload($objJumpTo->row());
        }

        $this->reload();
    }

    /**
     * Send an info mail to
     *
     * @param array $arrData
     */
    protected function sendInfoMail($arrData)
    {
        // Prepare the simple token data
        $arrTokenData = $arrData;
        $arrTokenData['domain'] = \Idna::decode(\Environment::get('host'));
        $arrTokenData['link'] = \Idna::decode(\Environment::get('base')) . \Environment::get('request') . ((strpos(\Environment::get('request'), '?') !== false) ? '&' : '?') . 'token=' . $arrData['activation'];
        $arrTokenData['channels'] = '';

        $bundles = \System::getContainer()->getParameter('kernel.bundles');

        // Deprecated since Contao 4.0, to be removed in Contao 5.0
        $arrTokenData['channel'] = $arrTokenData['channels'];

        $objEmail = new \Email();

        $objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
        $objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
        $objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['emailSubject'], \Idna::decode(\Environment::get('host')));
        $objEmail->text = \StringUtil::parseSimpleTokens($this->reg_text, $arrTokenData);
        $objEmail->sendTo($arrData['email']);
    }

    /**
     * Get a list of editable marketing tip fields
     *
     * @return array
     */
	protected function getEditableFields()
    {
        $return = array();

        foreach ($GLOBALS['TL_DCA']['tl_marketing_tip']['fields'] as $k=>$v)
        {
            if ($v['eval']['feEditable'])
            {
                $return[] = $k;
            }
        }

        return $return;
    }
}
