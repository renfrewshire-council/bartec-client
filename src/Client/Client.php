<?php

namespace Renfrewshire\Bartec\Client;

use Renfrewshire\Bartec\Exception\SoapException;
use Renfrewshire\Bartec\Response\Response;

class Client
{
    public const WSDL_AUTH = 'https://collapi.bartec-systems.com/CollAuth/Authenticate.asmx?WSDL';

    /**
     * @var SoapClient
     */
    private $authSoapClient;

    /**
     * @var SoapClient
     */
    private $collectiveSoapClient;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * Overrides SOAP options in both SoapClients
     *
     * @var array
     */
    private $soapOptions = [];

    /**
     * @param SoapClient $authSoapClient
     * @param SoapClient $collectiveSoapClient
     * @param string $username
     * @param string $password
     * @param array $soapOptions
     */
    public function __construct(
        SoapClient $authSoapClient,
        SoapClient $collectiveSoapClient,
        string $username,
        string $password,
        array $soapOptions = []
    ) {
        $this->authSoapClient = $authSoapClient;
        $this->collectiveSoapClient = $collectiveSoapClient;
        $this->username = $username;
        $this->password = $password;
        $this->setSoapOptions($soapOptions);
    }

    /**
     * @return SoapClient
     */
    public function getAuthSoapClient()
    {
        if ($this->getSoapOptions()) {
            $this->authSoapClient
                ->setOptions($this->getSoapOptions());
        }
        return $this->authSoapClient;
    }

    /**
     * @return SoapClient
     */
    public function getCollectiveSoapClient()
    {
        if ($this->getSoapOptions()) {
            $this->collectiveSoapClient
                ->setOptions($this->getSoapOptions());
        }
        return $this->collectiveSoapClient;
    }

    /**
     * @return array
     */
    public function getSoapOptions()
    {
        return $this->soapOptions;
    }

    /**
     * @param array $soapOptions
     */
    public function setSoapOptions(array $soapOptions)
    {
        $this->soapOptions = $soapOptions;
    }

    /**
     * @return string|null
     * @throws SoapException
     */
    public function getAuthToken()
    {
        $response = (new Response())->hydrate(
            $this->getAuthSoapClient()->callOperation('Authenticate', [
                'user' => $this->username,
                'password' => $this->password
            ])
        );

        if ($response->hasErrors()) {
            throw new SoapException($response);
        }

        return $response->getToken();
    }

    /**
     * @return string|null
     * @throws SoapException
     */
    public function getIFrameAuthToken()
    {
        $response = (new Response())->hydrate(
            $this->getAuthSoapClient()->callOperation('AuthenticateIFrame', [
                'user' => $this->username,
                'password' => $this->password
            ])
        );

        if ($response->hasErrors()) {
            throw new SoapException($response);
        }

        return $response->getToken();
    }

    /**
     * @param string $operation
     * @param array $data
     * @return Response
     * @throws SoapException
     */
    public function call(string $operation, array $data = [])
    {
        $data = array_merge($data, ['token' => $this->getAuthToken()]);

        $response = (new Response())->hydrate(
            $this->getCollectiveSoapClient()->callOperation($operation, $data)
        );

        if ($response->hasErrors()) {
            throw new SoapException($response);
        }

        return $response;
    }
}
