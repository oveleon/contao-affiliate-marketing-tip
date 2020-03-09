<?php
/**
 * This file is part of Contao Affiliate Marketing Tip Bundle.
 *
 * @link      https://www.oveleon.de/
 * @source    https://github.com/oveleon/contao-affiliate-marketing-tip
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 */

namespace Oveleon\ContaoAffiliateMarketingTip;

use Patchwork\Utf8;

/**
 * Front end module "marketing tip create".
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class ModuleMarketingTipStatistics extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_marketingTipStatistics';

    /**
     * Array with valid years
     * @var array
     */
	protected $validYears = [];

    /**
     * Current selected year
     * @var string
     */
	protected $currentYear = '';

    /**
     * Chart has data
     * @var bool
     */
	protected $isEmpty = true;

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['mod_marketingTipStatistics'][0]) . ' ###';
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
        $this->import('FrontendUser', 'User');

        \System::loadLanguageFile('tl_marketing_tip');
        $this->loadDataContainer('tl_marketing_tip');

        $arrColumns = [
            'member=?',
            'status=?'
        ];

        $arrValues = [
            $this->User->id,
            'success'
        ];

        $arrOptions = [
            'order' => 'tstamp'
        ];

        $this->currentYear = date('Y');

        $objData = MarketingTipModel::findBy($arrColumns, $arrValues, $arrOptions);

        $this->Template->addSorting      = !!$this->marketingTip_addSorting;
        $this->Template->sortingFields   = $this->createSorting($objData);

        $this->Template->statTable       = $this->createStatTable($objData);

        $this->Template->addChart        = !!$this->marketingTip_addChart;
        $this->Template->chartData       = $this->createChartData($objData);
        $this->Template->isEmpty         = $this->isEmpty;
        $this->Template->messageEmpty    = sprintf($GLOBALS['TL_LANG']['tl_marketing_tip']['messageEmpty'], $this->currentYear);
    }

    /**
     * Create statistic table
     */
    public function createStatTable($objData)
    {
        if($objData !== null)
        {
            $arrData = [];
            $objData->reset();

            while($objData->next())
            {
                $intYear  = date('Y', $objData->tstamp);
                $intMonth = date('n', $objData->tstamp) - 1;

                if($intYear === $this->currentYear)
                {
                    $arrData[ $intMonth ][] = $objData->current();
                }
            }

            $sum = [];
            $rows = [
                'header'  => [$this->currentYear],
                0         => [],
                1         => [],
                2         => [],
                3         => [],
                4         => [],
                5         => [],
                6         => [],
                7         => [],
                8         => [],
                9         => [],
                10        => [],
                11        => [],
                'footer'  => [$GLOBALS['TL_LANG']['tl_marketing_tip']['total_amount']]
            ];

            if(count($arrData))
            {
                foreach (range(0, 11) as $n)
                {
                    $rows[ $n ][] = $GLOBALS['TL_LANG']['MONTHS'][$n];
                    $index = 1;

                    if($arrData[ $n ])
                    {
                        foreach ($arrData[ $n ] as $objTip)
                        {
                            $intYear  = date('Y', $objTip->tstamp);

                            if($intYear === $this->currentYear)
                            {
                                $rows['header'][$index] = sprintf($GLOBALS['TL_LANG']['tl_marketing_tip']['col'], $index);
                                $rows[$n][] = $this->parsePrice($objTip->commission);

                                $sum[$n] += $objTip->commission;

                                $index++;
                            }
                        }
                    }
                }

                $maxCol = count($rows['header']);
                $sumTotal = 0;

                foreach ($rows as $name => &$row)
                {
                    if($name === 'header'){
                        $row[] = 'Summe';
                        continue;
                    }

                    $int = count($row);
                    $row = $row + array_fill($int, $maxCol - $int, $name === 'footer' ? '' : 0);

                    if($name === 'footer'){
                        $row[] = $this->parsePrice($sumTotal);
                        continue;
                    }

                    $sumTotal += $sum[ $name ];
                    $row[] = $sum[ $name ] ? $this->parsePrice($sum[ $name ]) : 0;
                }
            }
        }

        return $rows;
    }

    /**
     * Create and returns chart data
     *
     * @param $objData
     *
     * @return string
     */
	public function createChartData($objData)
    {
        if(!$this->marketingTip_addChart)
        {
            return '';
        }

        if($objData !== null)
        {
            $objData->reset();
            $arrData = [];

            while($objData->next())
            {
                $intYear  = date('Y', $objData->tstamp);
                $intMonth = date('n', $objData->tstamp) - 1;

                if($intYear === $this->currentYear)
                {
                    $arrData[ $intMonth ][] = $objData->commission;
                }
            }

            $arrMonths = [];

            if(count($arrData))
            {
                foreach (range(0,11) as $n)
                {
                    $key = $GLOBALS['TL_LANG']['MONTHS_SHORT'][ $n ];

                    foreach (range(0,11) as $m)
                    {
                        $value = $arrData[ $m ] ? intval(array_shift($arrData[ $m ])) : 0;

                        if($value)
                        {
                            $this->isEmpty = false;
                        }

                        $arrMonths[ $key ][] = [
                            'name'  => $GLOBALS['TL_LANG']['MONTHS_SHORT'][ $m ],
                            'value' => $value
                        ];
                    }
                }
            }

            return json_encode([
                'categories' => array_keys($arrMonths),
                'dataset'    => $arrMonths
            ]);
        }

        return '';
    }

    /**
     * Create and returns sorting fields
     *
     * @param $objData
     *
     * @return string
     */
    public function createSorting($objData)
    {
        $this->Template->formId     = 'form_' . $this->id;
        $this->Template->formAction = \Environment::get('requestUri');

        $this->validYears = [];

        if($objData !== null)
        {
            while($objData->next())
            {
                $year = date('Y', $objData->tstamp);

                if(!in_array($year, $this->validYears))
                {
                    $this->validYears[] = $year;
                }
            }

            $this->validYears  = array_reverse($this->validYears);
            $this->currentYear = $this->getSortingFlag() ?: $this->validYears[0];

            if(!in_array($this->currentYear, $this->validYears) && count($this->validYears))
            {
                $this->currentYear = $this->validYears[0];
            }
        }

        $arrFieldData = array(
            'label' => $GLOBALS['TL_LANG']['tl_marketing_tip']['sorting'],
            'inputType' => 'select',
            'options' => $this->validYears
        );

        /** @var \Widget $strClass */
        $strClass = $GLOBALS['TL_FFL']['select'];

        // Continue if the class is not defined
        if (!class_exists($strClass))
        {
            return '';
        }

        $objWidget = new $strClass($strClass::getAttributesFromDca($arrFieldData, 'marketing_tip_sorting', $this->getSortingFlag(), '', '', $this));
        $objWidget->storeValues = true;
        $objWidget->onchange = 'this.form.submit()';

        // Validate input
        if (\Input::post('FORM_SUBMIT') === $this->Template->formId)
        {
            $objWidget->validate();
        }

        return $objWidget->parse();
    }

    /**
     * Returns the current year considering the sorting
     * @return string
     */
    public function getSortingFlag()
    {
        return \Input::post('marketing_tip_sorting') ?: $this->currentYear;
    }

    /**
     * Parse and return price with currency
     *
     * @param $intPrice
     *
     * @return string
     */
    public function parsePrice($intPrice)
    {
        return '<span class="price">' . number_format($intPrice, 0, ',', '.') . $GLOBALS['TL_LANG']['tl_marketing_tip']['currency'] . '</span>';
    }
}
