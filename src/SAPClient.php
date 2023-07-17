<?php

namespace BereanK\Coupler;



use BereanK\Coupler\Helpers\Query;
use BereanK\Coupler\Helpers\Config;
use BereanK\Coupler\Helpers\Request;
use BereanK\Coupler\Helpers\Service;
use BereanK\Coupler\Exception\SAPException;

/**
 * SAPClient manages access to SAP B1 Service Layer and provides methods to 
 * perform CRUD operations.
 */
class SAPClient
{

    private $config = [];
    private $session = [];

    /**
     * Initializes SAPClient with configuration and session data.
     */
    public function __construct(array $configOptions, array $session)
    {
        $this->config = new Config($configOptions);
        $this->session = $session;
    }

    /**
     * Returns a new instance of SAPb1\Service.
     */
    public function getService(string $serviceName): Service
    {
        return new Service($this->config, $this->session, $serviceName);
    }

    /**
     * Returns the current SAP B1 session data.
     */
    public function getSession(): array
    {
        return $this->session;
    }

    /**
     * Returns a new instance of BereanK\Coupler\Filters\Query, which allows for cross joins.
     */
    public function query($join, $headers = []): Query
    {
        return new Query($this->config, $this->session, '$crossjoin(' . str_replace(' ', '', $join) . ')', $headers);
    }

    /**
     * Creates a new SAP B1 session and returns a new instance of  BereanK\Coupler\Client.
     * Throws BereanK\Coupler\Exception\SAPException if an error occurred.
     */
    public static function createSession(array $configOptions, string $username, string $password, string $company): SAPClient
    {

        $config = new Config($configOptions);

        $request = new Request($config->getServiceUrl('Login'), $config->getSSLOptions());

        $request->setMethod('POST');
        $request->setPost(['UserName' => $username, 'Password' => $password, 'CompanyDB' => $company]);
        $response = $request->getResponse();

        if ($response->getStatusCode() === 200) {
            return new SAPClient($config->toArray(), $response->getCookies());
        }

        throw new SAPException($response);
    }
}
