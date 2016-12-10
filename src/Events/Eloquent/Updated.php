<?php

namespace Eilander\Repository\Events\Eloquent;

use Eilander\Repository\Events\EloquentEvent;

/**
 * Clean cache when new entity/model is updated.
 *
 * @author Eilander
 */
class Updated extends EloquentEvent
{
    /**
     * @var string
     */
    protected $action = 'updated';
}
