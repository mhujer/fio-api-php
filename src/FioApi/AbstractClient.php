<?php
namespace FioApi;

abstract class AbstractClient
{
    /**
     * @var string secret token from FIO Internet banking
     */
    protected $token;

    /**
     * @var UrlBuilder
     */
    protected $urlBuilder;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $certificatePath;

    /**
     * @param string $token
     * @return AbstractClient
     */
    public function __construct($token, \GuzzleHttp\ClientInterface $client = null)
    {
        $this->token = $token;
        $this->urlBuilder = new UrlBuilder($token);
        $this->client = $client;
    }

    /**
     * @param string $path
     * @return void
     */
    public function setCertificatePath($path)
    {
        $this->certificatePath = $path;
    }

    /**
     * Get certificate path
     *
     * @return string
     */
    public function getCertificatePath()
    {
        if ($this->certificatePath) {
            return $this->certificatePath;
        }

        if (class_exists('\Kdyby\CurlCaBundle\CertificateHelper')) {
            return \Kdyby\CurlCaBundle\CertificateHelper::getCaInfoFile();
        }

        // Key downloaded from https://www.geotrust.com/resources/root-certificates/
        return __DIR__.'/keys/Geotrust_PCA_G3_Root.pem';
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new \GuzzleHttp\Client();
        }

        return $this->client;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
