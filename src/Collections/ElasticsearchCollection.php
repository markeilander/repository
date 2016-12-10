<?php

namespace Eilander\Repository\Collections;

use Illuminate\Support\Collection;

class ElasticsearchCollection extends Collection
{
    protected $took;
    protected $timed_out;
    protected $shards;
    protected $hits;
    protected $aggregations = null;

    /**
     * _construct.
     *
     * @param   $results elasticsearch results
     * @param $instance
     *
     * @return \Eilander\Repository\Collections\ElasticsearchCollection
     */
    public function __construct($results)
    {
        // Take our result data and map it
        // to some class properties.
        $this->took = $results['took'];
        $this->timed_out = $results['timed_out'];
        $this->shards = $results['_shards'];
        $this->hits = $results['hits'];
        $this->aggregations = isset($results['aggregations']) ? $results['aggregations'] : [];
    }

    /**
     * Total Hits.
     *
     * @return int
     */
    public function totalHits()
    {
        return $this->hits['total'];
    }

    /**
     * Max Score.
     *
     * @return float
     */
    public function maxScore()
    {
        return $this->hits['max_score'];
    }

    /**
     * Get Shards.
     *
     * @return array
     */
    public function getShards()
    {
        return $this->shards;
    }

    /**
     * Took.
     *
     * @return string
     */
    public function took()
    {
        return $this->took;
    }

    /**
     * Timed Out.
     *
     * @return bool
     */
    public function timedOut()
    {
        return (bool) $this->timed_out;
    }

    /**
     * Get Hits.
     *
     * Get the raw hits array from
     * Elasticsearch results.
     *
     * @return array
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Get aggregations.
     *
     * Get the raw hits array from
     * Elasticsearch results.
     *
     * @return array
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * Get aggregations.
     *
     * Get the raw hits array from
     * Elasticsearch results.
     *
     * @return array
     */
    public function getAggregation($name = '')
    {
        if (array_key_exists($name, $this->aggregations)) {
            return $this->aggregations[$name];
        }
    }
}
