<?php

declare(strict_types=1);

/**
 * @param $invoice
 */
function PaidAdslightHook($invoice): bool
{
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('adslight_listing') . ' set `status` = 2 where `lid`= "' . $invoice->getVar('key') . '"';
    $GLOBALS['xoopsDB']->queryF($sql);
    require_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
    return PaidXPaymentHook($invoice);
}

/**
 * @param $invoice
 */
function UnpaidAdslightHook($invoice): bool
{
    require_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
    return UnpaidXPaymentHook($invoice);
}

/**
 * @param $invoice
 */
function CancelAdslightHook($invoice): bool
{
    require_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
    return CancelXPaymentHook($invoice);
}
