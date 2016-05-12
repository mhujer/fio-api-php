<?php

namespace FioApi;

abstract class AbstractPaymentBuilder
{
    /**
     * Create root element of XML document.
     *
     * @return \SimpleXMLElement
     */
    protected function createRoot()
    {
        $root = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Import />');
        $root->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-Instance');

        return $root;
    }
}
