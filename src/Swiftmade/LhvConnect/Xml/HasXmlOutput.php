<?php

namespace Swiftmade\LhvConnect\Xml;

interface HasXmlOutput
{
    public function toXmlArray(): array;

    public function toXml(): string;
}
