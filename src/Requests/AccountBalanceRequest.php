<?php

namespace Swiftmade\LhvConnect\Requests;

use DateTime;
use Spatie\ArrayToXml\ArrayToXml;
use Swiftmade\LhvConnect\Xml\Tag;

/**
 * Account Balance provides actual and free balance information for any existing currencies on Customer account.
 * It can be requested for one Customer Account at a time.
 * Balances are provided real-time on the moment of generating the response.
 */
class AccountBalanceRequest extends AbstractRequest
{
    protected string $requestedMessageNameIdentification = 'camt.052.001.06';

    protected DateTime $fromDate;

    protected DateTime $toDate;

    public function __construct(protected string $accountIban)
    {
        parent::__construct();

        $this->fromDate = new DateTime('-1y');
        $this->toDate = new DateTime('now');
    }

    public function endpoint(): string
    {
        return '/account-balance';
    }

    public function responseType(): string
    {
        return 'ACCOUNT_STATEMENT';
    }

    public function toXml(): string
    {
        return ArrayToXml::convert(
            [
                '_attributes' => [
                    'xmlns' => 'urn:iso:std:iso:20022:tech:xsd:camt.060.001.03',
                ],
                Tag::ACCOUNT_STATEMENT_REQUEST => [
                    Tag::GROUP_HEADER => [
                        Tag::MESSAGE_IDENTIFICATION => $this->messageId,
                        Tag::CREATION_DATETIME => $this->creationDateTime,
                    ],
                    Tag::REPORTING_REQUEST => [
                        Tag::REQUESTED_MESSAGE_NAME_IDENTIFICATION => $this->requestedMessageNameIdentification,
                        Tag::ACCOUNT => [
                            Tag::IDENTIFICATION => [
                                Tag::IBAN => $this->accountIban
                            ]
                        ],
                        Tag::ACCOUNT_OWNER => [
                            Tag::PARTY => []
                        ],
                        Tag::REPORTING_PERIOD => [
                            Tag::FROM_TO_DATE => [
                                Tag::FROM_DATE => $this->fromDate->format('Y-m-d'),
                                Tag::TO_DATE => $this->toDate->format('Y-m-d')
                            ],
                            Tag::FROM_TO_TIME => [
                                Tag::FROM_TIME => $this->fromDate->format('H:i:sP'),
                                Tag::TO_TIME => $this->toDate->format('H:i:sP')
                            ],
                            Tag::TYPE => 'ALLL'
                        ],
                        Tag::REQUESTED_BALANCE_TYPE => [
                            Tag::CODE_OR_PROPRIETARY => [
                                Tag::PROPRIETARY => 'DATE'
                            ]
                        ]
                    ],
                ]
            ],
            rootElement: 'Document',
            xmlVersion: '1.0',
            xmlEncoding: 'UTF-8'
        );
    }
}
