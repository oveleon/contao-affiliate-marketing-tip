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


/**
 * Reads and writes marketing tips
 *
 * @property integer $id
 * @property integer $tstamp
 * @property integer $member
 * @property string  $street
 * @property string  $postal
 * @property string  $city
 * @property string  $status
 * @property string  $reason
 *
 * @method static MarketingTipModel|null findById($id, array $opt=array())
 * @method static MarketingTipModel|null findOneBy($col, $val, $opt=array())
 * @method static MarketingTipModel|null findOneByTstamp($val, $opt=array())
 * @method static MarketingTipModel|null findOneByMember($val, $opt=array())
 * @method static MarketingTipModel|null findOneByStreet($val, $opt=array())
 * @method static MarketingTipModel|null findOneByPostal($val, $opt=array())
 * @method static MarketingTipModel|null findOneByCity($val, $opt=array())
 * @method static MarketingTipModel|null findOneByStatus($val, $opt=array())
 * @method static MarketingTipModel|null findOneByReason($val, $opt=array())
 *
 * @method static \Model\Collection|MarketingTipModel[]|MarketingTipModel|null findMultipleByIds($val, array $opt=array())
 * @method static \Model\Collection|MarketingTipModel[]|MarketingTipModel|null findByTstamp($val, array $opt=array())
 * @method static \Model\Collection|MarketingTipModel[]|MarketingTipModel|null findByMember($val, array $opt=array())
 * @method static \Model\Collection|MarketingTipModel[]|MarketingTipModel|null findByStreet($val, array $opt=array())
 * @method static \Model\Collection|MarketingTipModel[]|MarketingTipModel|null findByPostal($val, array $opt=array())
 * @method static \Model\Collection|MarketingTipModel[]|MarketingTipModel|null findByCity($val, array $opt=array())
 * @method static \Model\Collection|MarketingTipModel[]|MarketingTipModel|null findByStatus($val, array $opt=array())
 * @method static \Model\Collection|MarketingTipModel[]|MarketingTipModel|null findByReason($val, array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByMember($val, $opt=array())
 * @method static integer countByStreet($val, $opt=array())
 * @method static integer countByPostal($val, $opt=array())
 * @method static integer countByCity($val, $opt=array())
 * @method static integer countByStatus($val, $opt=array())
 * @method static integer countByReason($val, $opt=array())
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */

class MarketingTipModel extends \Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_marketing_tip';
}
