<?php

namespace Eilander\Repository\Events;

use Illuminate\Database\Eloquent\Model;
use Eilander\Repository\Contracts\Eloquent as Repository;
use Illuminate\Queue\SerializesModels;
/**
 * Description of Event
 *
 * @author Eilander
 */
abstract class EloquentEvent {
    use SerializesModels;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    protected $action;

    /**
     * @param RepositoryInterface $repository
     * @param Model $model
     */
    public function __construct(Repository $repository, Model $model)
    {
        $this->repository   = $repository;
        $this->model        = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
