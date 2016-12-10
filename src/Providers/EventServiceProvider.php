<?php

namespace Eilander\Repository\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Description of EventServiceProvider.
 *
 * @author Eilander
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Eilander\Repository\Events\Elasticsearch\Created' => [
            'Eilander\Repository\Listeners\ElasticsearchClearCache',
        ],
        'Eilander\Repository\Events\Eloquent\Created' => [
            'Eilander\Repository\Listeners\EloquentClearCache',
        ],
        'Eilander\Repository\Events\Eloquent\Creating' => [
            'Eilander\Repository\Listeners\EloquentSlugify',
        ],
        'Eilander\Repository\Events\Eloquent\Updated' => [
            'Eilander\Repository\Listeners\EloquentClearCache',
        ],
        'Eilander\Repository\Events\Eloquent\Deleted' => [
            'Eilander\Repository\Listeners\EloquentClearCache',
        ],
    ];
}
