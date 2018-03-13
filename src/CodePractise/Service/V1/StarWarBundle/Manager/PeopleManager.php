<?php

namespace CodePractise\Service\V1\StarWarBundle\Manager;

class PeopleManager {

    private $kernel;
    private $logger;

    public function __construct($kernel)
    {
        $this->kernel = $kernel;

        \Logger::configure($this->kernel->getRootDir().'/../log4php.xml');
        $this->logger = \Logger::getLogger("default");
    }

    /**
     * Get all People from third party API
     * @return mixed
     */
    public function getAllPeople($params)
    {

        $this->logger->info("Start to get people");
        $configs  = $this->kernel->getContainer()->getParameter('webservice');
        $url      = $configs['swapi_get_poeple_url'];

        $this->preparePages($params, $url);
        
        $client   = new \GuzzleHttp\Client([ 'headers' => [ 'Content-Type' => 'application/json' ] ]);
        $response = $client->get($url);
        $result   = json_decode($response->getBody()->getContents(), true);
        $this->logger->debug("Star war API Get People Response: ".print_r($result, true));

        $this->logger->info("Return from get people");
        return $result;
    }

    /**
     * @param $params
     * @param $url
     */
    private function preparePages($params, & $url)
    {
        $url .= '/?page='. $params['page'];
    }

}
