<?php

namespace FioApi;

/**
 * Uploader for Fio bank.
 *
 * @link https://perlur.cz/
 *
 * @author Petr Kramar <petr.kramar@perlur.cz>
 */
class Uploader extends AbstractClient
{
    const URL = 'https://www.fio.cz/ib_api/rest/import/';

    /**
     * Send request.
     *
     * @param string $request XML formatted request
     *
     * @throws BadResponseException
     *
     * @return ImportResponse
     */
    public function sendRequest($request)
    {
        $client = $this->getClient();
        try {
            $response = $client->request('post', self::URL, [
                'verify'    => $this->getCertificatePath(),
                'multipart' => [
                    ['name' => 'token', 'contents' => $this->getToken()],
                    ['name' => 'type', 'contents' => 'xml'],
                    ['name' => 'file', 'contents' => $request, 'filename' => 'request.xml'],
                    ['name' => 'lng', 'contents' => 'en'],
                ],
            ]);

            return $this->createResponse((string) $response->getBody());
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            throw $e;
        }
    }

    /**
     * Create instance of ImportReponse.
     *
     * @param string $body
     *
     * @return ImportResponse
     */
    protected function createResponse($body)
    {
        return new ImportResponse($body);
    }
}
