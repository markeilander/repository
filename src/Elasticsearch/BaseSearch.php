<?php

namespace Eilander\Repository\Elasticsearch;

use Eilander\Repository\Contracts\Search;
use Eilander\Repository\RepositoryException;
use Eilander\Repository\Traits\Elasticsearch\Filterable as Filter;
use Eilander\Repository\Traits\Elasticsearch\Parser;
use Elasticsearch\ClientBuilder as ElasticsearchClient;
use Event;
use Illuminate\Container\Container as Application;
use Input;

/**
 * Class BaseSearch.
 */
abstract class BaseSearch implements Search
{
    use Filter, Parser;
    /**
     * @var Application
     */
    protected $app;
    /**
     * @var Client
     */
    protected $client;
    /**
     * Which database should be used, defaults to config setting.
     *
     * @var Index
     */
    protected $index;
    /**
     * Which table should me used.
     *
     * @var Type
     */
    protected $type;
    /**
     * Which model should we use.
     *
     * @var Model
     */
    protected $model;
    /**
     * Query filters.
     *
     * @var Model
     */
    protected $filters = '';

    protected $extendedBounds = '';

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $hosts = [
            env('ELASTIC_HOST', 'localhost:9200'),
        ];
        $this->client = ElasticsearchClient::create()->setHosts($hosts)->build();
        $this->index = config('database.connections.elasticsearch.index');
        $this->app = $app;
        $this->filter(Input::get('filter'));
    }

    /**
     * Specify Table.
     *
     * @return string
     */
    abstract public function type();

    /**
     * Specify Table.
     *
     * @return string
     */
    abstract public function model();

    /**
     * @throws RepositoryException
     */
    protected function resetModel()
    {
        $this->makeModel();
    }

    /**
     * @throws RepositoryException
     *
     * @return Model
     */
    protected function makeModel($data)
    {
        $model = $this->app->make($this->model(), [$data]);

        return $this->model = $model;
    }

    /**
     * Search query in elasticsearch.
     *
     * @param array|string $query
     *
     * @return mixed
     */
    protected function query($selection = '')
    {
        $this->type();
        if (!isset($this->index) || $this->index == 'localhost' || !isset($this->type)) {
            throw new RepositoryException("Index {$this->index} and Type {$this->type} are required.");
        }
        $request = [
          'index' => $this->index,
          'type'  => $this->type,
          'body'  => $this->body($selection),
        ];

        return $this->search($request);
    }

    /**
     * Search query in elasticsearch.
     *
     * @param array|string $request
     *
     * @return mixed
     */
    public function search($request = '')
    {
        // Clear cache on new search
        //Event::fire(new EventUpdated($this, $model));
        $results = $this->client->search($request);

        return $this->makeModel($results);
    }
}
