<?php
declare(strict_types = 1);

namespace FioApi\Upload;

use FioApi\Exceptions\MissingPaymentOrderException;
use FioApi\Exceptions\UnexpectedPaymentOrderValueException;
use FioApi\Upload\Entity\PaymentOrderList;
use FioApi\Transferrer;
use FioApi\Upload\FileBuilder\FileBuilder;
use FioApi\Upload\FileBuilder\XmlFileBuilder;
use FioApi\Upload\Entity\PaymentOrder;
use FioApi\Upload\Entity\UploadResponse;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class Uploader extends Transferrer
{
    protected const ACCOUNT_FROM_MAX_LENGTH = 16;

    protected string $accountFrom;
    protected ?FileBuilder $fileBuilder;
    protected PaymentOrderList $paymentOrderList;

    public function __construct(
        string $token,
        string $accountFrom,
        ?ClientInterface $client = null,
        ?FileBuilder $fileBuilder = null
    ) {
        parent::__construct($token, $client);
        $this->accountFrom = static::validateAccountFrom($accountFrom);
        $this->fileBuilder = $fileBuilder;
    }

    public function addPaymentOrder(PaymentOrder $paymentOrder): void
    {
        $this->getPaymentOrderList()->addPaymentOrder($paymentOrder);
    }

    public function uploadPaymentOrders(): Entity\UploadResponse
    {
        if ($this->getPaymentOrderList()->isEmpty()) {
            throw new MissingPaymentOrderException('You have to add at least one payment order before uploading.');
        }
        $response = $this->sendRequest();
        $this->getPaymentOrderList()->clear();

        return new UploadResponse($response->getBody()->getContents());
    }

    public function getPaymentOrderList(): PaymentOrderList
    {
        if (isset($this->paymentOrderList) === false) {
            $this->paymentOrderList = new PaymentOrderList();
        }
        return $this->paymentOrderList;
    }

    public function getFileBuilder(): FileBuilder
    {
        if ($this->fileBuilder === null) {
            $this->fileBuilder = new XmlFileBuilder();
        }
        return $this->fileBuilder;
    }

    protected function sendRequest(): ResponseInterface
    {
        $url = $this->urlBuilder->buildUploadUrl();
        $client = $this->getClient();

        return $client->request('post', $url, [
            'verify' => $this->getCertificatePath(),
            'multipart' => [
                [
                    'name'     => 'token',
                    'contents' => $this->urlBuilder->getToken()
                ],
                [
                    'name'     => 'type',
                    'contents' => $this->getFileBuilder()->getFileType()
                ],
                [
                    'name'     => 'file',
                    'contents' => $this->getFileBuilder()->createFromPaymentOrderList($this->getPaymentOrderList(), $this->accountFrom),
                    'filename' => 'request.' . $this->getFileBuilder()->getFileType()
                ],
                [
                    'name'     => 'lng',
                    'contents' => 'en'
                ],
            ]
        ]);
    }

    protected static function validateAccountFrom(string $account): string
    {
        if (ctype_digit($account) === false) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Account "%s" has to contain digits only.', $account)
            );
        }
        if (strlen($account) > self::ACCOUNT_FROM_MAX_LENGTH) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Account "%s" has to contain %s digits at maximum.', $account, self::ACCOUNT_FROM_MAX_LENGTH)
            );
        }
        return $account;
    }
}
