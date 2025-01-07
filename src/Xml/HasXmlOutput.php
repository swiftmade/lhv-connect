<?php

namespace Swiftmade\LhvConnect\Xml;

interface HasXmlOutput
{
    public function toXml(): string;
}
