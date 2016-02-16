<?php

namespace Eilander\Repository\Events\Eloquent;

use Eilander\Repository\Events\EloquentEvent;

/**
 * Clean cache when new entity/model is deleted
 *
 * @author Eilander
 */
class Deleted extends EloquentEvent {
    /**
     * @var string
     */
    protected $action = "deleted";
}