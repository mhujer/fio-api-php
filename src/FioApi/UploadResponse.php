<?php
declare(strict_types = 1);

namespace FioApi;

use SimpleXMLElement;

class UploadResponse
{
    protected const SUCCESS = 'ok';

    protected SimpleXMLElement $xml;

    public function __construct(
        string $xml
    ) {
        $this->xml = new SimpleXMLElement($xml);
    }

    public function getXml(): SimpleXMLElement
    {
        return $this->xml;
    }

    public function isOk(): bool
    {
        return $this->getStatus() === self::SUCCESS;
    }

    public function getStatus(): string
    {
        return (string) $this->getResult()->status;
    }

    public function getCode(): int
    {
        return (int) $this->getResult()->errorCode;
    }

    public function getIdInstruction(): int
    {
        return (int) $this->getResult()->idInstruction;
    }

    protected function getResult(): SimpleXMLElement
    {
        return $this->xml->result;
    }
}
