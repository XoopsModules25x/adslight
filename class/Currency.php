<?php

declare(strict_types=1);

namespace XoopsModules\Adslight;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      Hervé Thouzard (http://www.herve-thouzard.com/)
 */

/**
 * Gestion de la Currency
 */
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

class Currency
{
    protected $decimalsCount;
    protected $thousandsSep;
    protected $decimalSep;
    protected $moneyFull;
    protected $moneyShort;
    protected $currencyPosition;

    /**
     * Currency constructor.
     */
    public function __construct()
    {
        $moduleDirName = \basename(__DIR__);
        $helper        = Helper::getHelper($moduleDirName);

        // Get the module's preferences
        $this->decimalsCount    = $helper->getConfig('decimals_count');
        $this->thousandsSep     = $helper->getConfig('thousands_sep');
        $this->decimalSep       = $helper->getConfig('decimal_sep');
        $this->moneyFull        = $helper->getConfig('money_full');
        $this->moneyShort       = $helper->getConfig('money_short');
        $this->currencyPosition = $helper->getConfig('currency_position');
        $this->thousandsSep     = \str_replace('[space]', ' ', $this->thousandsSep);
        $this->decimalSep       = \str_replace('[space]', ' ', $this->decimalSep);
    }

    /**
     * Access the only instance of this class
     *
     * @return object
     *
     * @static
     * @staticvar   object
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Returns an amount according to the currency's preferences (defined in the module's options)
     *
     * @param float|int $amount The amount to work on
     * @return string    The amount formated according to the currency
     */
    public function amountInCurrency($amount = 0): string
    {
        return \number_format($amount, $this->decimalsCount, $this->decimalSep, $this->thousandsSep);
    }

    /**
     * Format an amount for display according to module's preferences
     *
     * @param float  $originalAmount The amount to format
     * @param string $format         Format to use, 's' for Short and 'l' for Long
     * @return string The amount formated
     */
    public function amountForDisplay($originalAmount, $format = 's'): string
    {
        $amount = $this->amountInCurrency($originalAmount);

        $currencyLeft = $currencyRight = $currencyLeftShort = $currencyRightShort = '';
        if (1 == $this->currencyPosition) { // To the right
            $currencyRight      = '' . $this->moneyFull; // Long version
            $currencyRightShort = '' . $this->moneyShort; // Short version
        } else { // To the left
            $currencyLeft      = $this->moneyFull . ''; // Long version
            $currencyLeftShort = $this->moneyShort . ''; // Short version
        }
        if ('s' !== $format) {
            return $currencyLeft . $amount . $currencyRight;
        }

        return $currencyLeftShort . $amount . $currencyRightShort;
    }
}
