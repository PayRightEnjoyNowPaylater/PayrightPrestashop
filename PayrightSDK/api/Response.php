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
    const RESPONSE_STATUS_SUCCESS = 'APPROVED';
    const RESPONSE_STATUS_CANCELLED = 'CANCELLED';
    const RESPONSE_STATUS_FAILURE = 'FAILURE';
    const RESPONSE_STATUS_REVIEW = 'REVIEW';

    /* Order payment statuses */
    const RESPONSE_STATUS_APPROVED = 'APPROVED';
    const RESPONSE_STATUS_PENDING  = 'PENDING';
    const RESPONSE_STATUS_FAILED   = 'FAILED';
    const RESPONSE_STATUS_DECLINED = 'DECLINED';

    const RESPONSE_APPROVED_PENDINGID = 'APPROVED_PENDING_ID';
}
