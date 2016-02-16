<?php
namespace Eilander\Repository\Elasticsearch;

use Event;
use Eilander\Repository\RepositoryException;
use Elastica\Client as ElasticsearchClient;
/**
 * Class BaseSearch
 * @package Eilander\Repository\Elasticsearch
 */
abstract class Elastica
{
    /**
     * @var Client
     */
    protected $client;
    /**
     * Which database should be used, defaults to config setting
     * @var Index
     */
    protected $index;
    /**
     * Which table should me used
     * @var Type
     */
    protected $type;
    /**
     * @param Application $app
     */
    public function __construct()
    {
        list($host, $port) = explode(':', env('ELASTIC_HOST', 'localhost:9200'));
        $elasticaClient = new ElasticsearchClient(array(
            'host' => $host,
            'port' => $port
        ));
        $this->type();
        // set default index and type
        // Load index
        $this->index = $elasticaClient->getIndex(config("database.connections.elasticsearch.index"));
        //Create a type
        $elasticaType = $this->index->getType($this->type);
        $this->client = $elasticaType;
    }

    /**
     * Specify Table
     *
     * @return string
     */
    abstract public function type();
}
