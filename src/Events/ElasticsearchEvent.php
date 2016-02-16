<?php

namespace Eilander\Repository\Events;

/**
 * Description of Event
 *
 * @author Eilander
 */
abstract class ElasticsearchEvent {

    /**
     * @var string
     */
    protected $tag;

    /**
     * @param Search $repository
     */
    public function __construct($tag)
    {
        $this->tag   = $tag;
    }

    /**
     * @return RepositoryInterface
     */
    public function getTag()
    {
        return $this->tag;
    }
}
