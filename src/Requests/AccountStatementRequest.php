<?php

namespace Swiftmade\LhvConnect\Requests;

use DateTime;

/**
 * Account Statement provides a list of incoming and outgoing transactions and payments with additional details.
 * It can be requested for one Customer Account for the specified period.
 * 
 * This request shape is identical to the AccountReportRequest, except for the requestedMessageNameIdentification.
 * @see AccountReportRequest.php
 */
final class AccountStatementRequest extends AccountReportRequest
{
    protected string $requestedMessageNameIdentification = 'camt.053.001.02';

    public function __construct(string $accountIban, DateTime $fromDate, DateTime $toDate)
    {
        parent::__construct($accountIban);

        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function endpoint(): string
    {
        return '/account-statement';
    }
}
