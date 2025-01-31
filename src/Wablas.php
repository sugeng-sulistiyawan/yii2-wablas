<?php

/**
 * @author Agiel K. Saputra <agielkurniawans@gmail.com>
 * @copyright Copyright (c) 2022 Agiel K. Saputra
 */

namespace diecoding\yii2\wablas;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * Class WablasHelper
 * @author agiel
 *
 * @property Client $client Client object readonly
 * @property Request $request Request object readonly
 * @property Response $response Response object readonly
 */
class Wablas extends Component
{
    /**
     * @var string
     */
    public $endpoint;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $secret;

    /**
     * @var array
     */
    public $versions = [];

    /**
     * @var Client
     */
    private $_client;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Response
     */
    private $_response;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $authorization = $this->token;
        if ($this->secret && strpos($authorization, '.') === false) {
            $authorization .= '.' . $this->secret;
        }

        $this->_client = Yii::createObject([
            'class' => Client::class,
            'baseUrl' => $this->endpoint,
            'requestConfig' => [
                'headers' => ['Authorization' => $authorization],
                'format' => Client::FORMAT_JSON
            ]
        ]);

        $this->versions = array_merge([
            'v1' => versions\V1::class,
            'v2' => versions\V2::class,
        ], $this->versions);
    }

    /**
     * @param string $version The version key, if `null` will use default version
     * @return versions\V1|versions\V2|mixed
     * @throws InvalidConfigException
     */
    public function build($version)
    {
        if (isset($this->versions[$version])) {
            $className = $this->versions[$version];
            if (property_exists($className, 'wablas')) {
                return new $className(['wablas' => $this]);
            }

            throw new InvalidConfigException('The "wablas" property must be set in the class "' . $className . '".');
        }

        throw new InvalidConfigException('The version "' . $version . '" is not in version list.');
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->_client;
    }

    /**
     * @return Request
     */
    public function setRequest($request): void
    {
        $this->_request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->_request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->_response;
    }

    /**
     * @return self
     */
    public function send(): Wablas
    {
        $this->_response = $this->request->send();
        return $this;
    }
}
