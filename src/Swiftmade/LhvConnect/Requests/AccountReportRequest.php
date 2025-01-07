<?php

namespace Swiftmade\LhvConnect\Requests;

use Spatie\ArrayToXml\ArrayToXml;
use Swiftmade\LhvConnect\Xml\HasXmlOutput;

class AccountReportRequest implements HasXmlOutput
{
    use SerializesXml;

    public function toXml(): string
    {
        return ArrayToXml::convert([
            '_attributes' => [
                'xmlns' => 'urn:iso:std:iso:20022:tech:xsd:camt.060.001.03',
                'xmlns:xs' => 'http://www.w3.org/2001/XMLSchema',
                'elementFormDefault' => 'qualified',
                'targetNamespace' => 'urn:iso:std:iso:20022:tech:xsd:camt.060.001.03',
            ]
        ], 'Document');
    }
}
