<?php
/**
 * Payright Payment Status
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Payright\api;

class Response
{
    const RESPONSE_STATUS_COMPLETE= 'COMPLETE';
    const RESPONSE_STATUS_CANCELLED = 'CANCELLED';
    const RESPONSE_STATUS_REVIEW = 'REVIEW';
    const RESPONSE_STATUS_DECLINE = 'DECLINE';

    //Customer clicks on cancel during checkout process
    const RESPONSE_STATUS_CANCEL = 'CANCEL';
    const RESPONSE_APPROVED_PENDINGID = 'APPROVED_PENDING_ID';
}
