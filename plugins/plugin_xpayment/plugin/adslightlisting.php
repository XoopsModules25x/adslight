<?php

declare(strict_types=1);

/**
 * @param $invoice
 */
function PaidAdslightlistingHook($invoice): bool
{
    $sql = 'update ' . $GLOBALS['xoopsDB']->prefix('adslight_listing') . ' set `valid` = \'Yes\' where `lid`= "' . $invoice->getVar('key') . '"';
    $GLOBALS['xoopsDB']->queryF($sql);
    require_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
    return PaidXPaymentHook($invoice);
}

/**
 * @param $invoice
 */
function UnpaidAdslightlistingHook($invoice): bool
{
    require_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
    return UnpaidXPaymentHook($invoice);
}

/**
 * @param $invoice
 */
function CancelAdslightlistingHook($invoice): bool
{
    require_once $GLOBALS['xoops']->path('/modules/xpayment/plugin/xpayment.php');
    return CancelXPaymentHook($invoice);
}
