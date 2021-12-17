<?php
declare(strict_types = 1);

namespace FioApi;

use GuzzleHttp\ClientInterface;

abstract class Transferrer
{
    protected UrlBuilder $urlBuilder;
    protected ?ClientInterface $client;
    protected string $certificatePath;

    protected function __construct(
        string $token,
        ClientInterface $client = null
    ) {
        $this->urlBuilder = new UrlBuilder($token);
        $this->client = $client;
    }

    public function setCertificatePath(string $path)
    {
        $this->certificatePath = $path;
    }

    public function getCertificatePath(): string
    {
        if (isset($this->certificatePath)) {
            return $this->certificatePath;
        }

        if (class_exists('\Composer\CaBundle\CaBundle')) {
            return \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath();
        } elseif (class_exists('\Kdyby\CurlCaBundle\CertificateHelper')) {
            return \Kdyby\CurlCaBundle\CertificateHelper::getCaInfoFile();
        }

        //Key downloaded from https://www.geotrust.com/resources/root-certificates/
        return __DIR__ . '/keys/Geotrust_PCA_G3_Root.pem';
    }

    public function getClient(): ClientInterface
    {
        if (isset($this->client) === false) {
            $this->client = new \GuzzleHttp\Client();
        }
        return $this->client;
    }
}
