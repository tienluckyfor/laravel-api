<?php

/**
 * Class Restful
 */
class Restful
{
    private $content;
    private $api;
    private $token;

    function __construct($api, $token)
    {
        if (empty($api) || empty($token)) {
            return response()->json([
                'body' => 'API and TOKEN are required! Please register Codeby API at: https://api.codeby.com',
                'url'  => 'https://api.codeby.com',
                'code' => 400,

            ])->send();
        }
        $this->api = $api;
        $this->token = $token;
    }

    protected function _errorHandle($e)
    {
        $code = $e->getResponse()->getStatusCode();
        switch (true) {
            case ($e instanceof \GuzzleHttp\Exception\ClientException):
            case ($e instanceof \GuzzleHttp\Exception\ServerException):
                $body = $e->getMessage();
                if (preg_match('#configs\/1.+?Resource not found#mis', $body)) {
                    $this->content = json_encode([]);
                    return $this;
                }
                response()->json([
                    'body' => $body,
                    'code' => $code,
                ])->send();
                break;
            case ($e instanceof \GuzzleHttp\Exception\BadResponseException):
                $jsonBody = json_decode($e->getResponse()->getBody()->getContents(), true);
                return response()->json([
                    'body' => $jsonBody['message'],
                    'code' => $code,
                ])->send();
                break;
        }
    }

    public function get($path, $query = [])
    {
        try {
            $client = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET', $this->api . $path);
            $response = $client->send($request, [
                'query'   => $query,
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => "Bearer {$this->token}"
                ]
            ]);
            $this->content = $response->getBody()->getContents();
            return $this;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $this->_errorHandle($e);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->_errorHandle($e);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $this->_errorHandle($e);
        }
    }


    public function data()
    {
        try {
            return json_decode($this->content, true)['data'];
        } catch (Exception $e) {
            return null;
        }
    }


    public function system()
    {
        try {
            return json_decode($this->content, true)['_system'];
        } catch (Exception $e) {
            return null;
        }
    }

    public function json()
    {
        return json_decode($this->content, true);
    }

}
