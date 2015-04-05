<?php

namespace FioApi;

class FioDownloaderTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $a = new FioDownloader();
        $this->assertTrue($a instanceof FioDownloader);
    }

}
