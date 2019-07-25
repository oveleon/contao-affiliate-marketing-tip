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
 * Front end module "marketing tip list".
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class ModuleMarketingTipList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_marketingTipList';

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

        $this->import('FrontendUser', 'User');

		// Delete marketing tip
		if (\Input::get('delete'))
        {
            $objMarketingTip = MarketingTipModel::findById(\Input::get('delete'));

            if ($objMarketingTip->member === $this->User->id)
            {
                $objMarketingTip->delete();
            }
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
        \System::loadLanguageFile('tl_marketing_tip');
        $this->loadDataContainer('tl_marketing_tip');

		$limit = null;
		$offset = 0;

		// Maximum number of items
		if ($this->numberOfItems > 0)
		{
			$limit = $this->numberOfItems;
		}

		$this->Template->marketingTips = array();
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['marketingTipEmptyList'];

		// Get the total number of items
		$intTotal = $this->countItems();

		if ($intTotal < 1)
		{
			return;
		}

		$total = $intTotal;

		// Split the results
		if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
		{
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			// Get the current page
			$id = 'page_n' . $this->id;
			$page = (\Input::get($id) !== null) ? \Input::get($id) : 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{
				throw new PageNotFoundException('Page not found: ' . \Environment::get('uri'));
			}

			// Set limit and offset
			$limit = $this->perPage;
			$offset += (max($page, 1) - 1) * $this->perPage;
			$skip = (int) $this->skipFirst;

			// Overall limit
			if ($offset + $limit > $total + $skip)
			{
				$limit = $total + $skip - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->perPage, \Config::get('maxPaginationLinks'), $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$objMarketingTips = $this->fetchItems(($limit ?: 0), $offset);

		// Add marketing tips
		if ($objMarketingTips !== null)
		{
			$this->Template->marketingTips = $this->parseMarketingTips($objMarketingTips);
		}

        $this->Template->labelDate   = $GLOBALS['TL_LANG']['tl_marketing_tip']['date'][0];
        $this->Template->labelStreet = $GLOBALS['TL_LANG']['tl_marketing_tip']['street'][0];
        $this->Template->labelPostal = $GLOBALS['TL_LANG']['tl_marketing_tip']['postal'][0];
        $this->Template->labelCity   = $GLOBALS['TL_LANG']['tl_marketing_tip']['city'][0];
        $this->Template->labelStatus = $GLOBALS['TL_LANG']['tl_marketing_tip']['status'][0];
	}

	/**
	 * Count the total matching items
	 *
	 * @return integer
	 */
	protected function countItems()
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['marketingTipListCountItems']) && \is_array($GLOBALS['TL_HOOKS']['marketingTipListCountItems']))
		{
			foreach ($GLOBALS['TL_HOOKS']['marketingTipListCountItems'] as $callback)
			{
				if (($intResult = \System::importStatic($callback[0])->{$callback[1]}($this)) === false)
				{
					continue;
				}

				if (\is_int($intResult))
				{
					return $intResult;
				}
			}
		}

		return MarketingTipModel::countByMember($this->User->id);
	}

	/**
	 * Fetch the matching items
	 *
	 * @param integer $limit
	 * @param integer $offset
	 *
	 * @return \Model\Collection|MarketingTipModel|null
	 */
	protected function fetchItems($limit, $offset)
	{
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['marketingTipListFetchItems']) && \is_array($GLOBALS['TL_HOOKS']['marketingTipListFetchItems']))
		{
			foreach ($GLOBALS['TL_HOOKS']['marketingTipListFetchItems'] as $callback)
			{
				if (($objCollection = \System::importStatic($callback[0])->{$callback[1]}($limit, $offset, $this)) === false)
				{
					continue;
				}

				if ($objCollection === null || $objCollection instanceof \Model\Collection)
				{
					return $objCollection;
				}
			}
		}

		$arrOptions = array
        (
            'limit'  => $limit,
            'offset' => $offset
        );

		return MarketingTipModel::findByMember($this->User->id, $arrOptions);
	}

    /**
     * Parse an item and return it as string
     *
     * @param MarketingTipModel $objMarketingTip
     * @param string    $strClass
     * @param integer   $intCount
     *
     * @return string
     */
    protected function parseMarketingTip($objMarketingTip, $strClass='', $intCount=0)
    {
        /** @var \PageModel $objPage */
        global $objPage;

        /** @var \FrontendTemplate|object $objTemplate */
        $objTemplate = new \FrontendTemplate('marketingtip_row');
        $objTemplate->setData($objMarketingTip->row());

        if ($objMarketingTip->cssClass != '')
        {
            $strClass = ' ' . $objMarketingTip->cssClass . $strClass;
        }

        $objTemplate->class = $strClass;
        $objTemplate->tstamp = \Date::parse($objPage->datimFormat, $objMarketingTip->tstamp);
        $objTemplate->statusValue = $objMarketingTip->status;
        $objTemplate->status = $GLOBALS['TL_LANG']['tl_marketing_tip'][$objMarketingTip->status];
        $objTemplate->linkDelete = '<a href="'.$objPage->getFrontendUrl().'?delete='.$objMarketingTip->id.'">'.$GLOBALS['TL_LANG']['tl_marketing_tip']['deleteMarketingTip'].'</a>';

        // HOOK: add custom logic
        if (isset($GLOBALS['TL_HOOKS']['parseMarketingTip']) && \is_array($GLOBALS['TL_HOOKS']['parseMarketingTip']))
        {
            foreach ($GLOBALS['TL_HOOKS']['parseMarketingTip'] as $callback)
            {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($objTemplate, $objMarketingTip->row(), $this);
            }
        }

        return $objTemplate->parse();
    }

    /**
     * Parse one or more items and return them as array
     *
     * @param \Model\Collection $objMarketingTips
     *
     * @return array
     */
    protected function parseMarketingTips($objMarketingTips)
    {
        $limit = $objMarketingTips->count();

        if ($limit < 1)
        {
            return array();
        }

        $count = 0;
        $arrMarketingTips = array();

        while ($objMarketingTips->next())
        {
            /** @var MarketingTipModel $objMarketingTip */
            $objMarketingTip = $objMarketingTips->current();

            $arrMarketingTips[] = $this->parseMarketingTip($objMarketingTip, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'), $count);
        }

        return $arrMarketingTips;
    }
}
