<?php

namespace Eilander\Repository\Events\Eloquent;

use Eilander\Repository\Events\EloquentEvent;

/**
 * Clean cache when new entity/model is created
 *
 * @author Eilander
 */
class Created extends EloquentEvent {
    /**
     * @var string
     */
    protected $action = "created";
}
