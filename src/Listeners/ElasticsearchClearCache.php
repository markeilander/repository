<?php

namespace Eilander\Repository\Listeners;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Eilander\Repository\Contracts\Search;
use Eilander\Repository\Events\ElasticsearchEvent;
use Eilander\Repository\CacheKeys;

/**
 * Class EloquentListener
 */
class ElasticsearchClearCache {

    /**
     * @var Illuminate\Contracts\Cache\Repository
     */
    protected $cache = null;
    /**
     * @var Eilander\Repository\CacheKeys
     */
    protected $cacheKeys = null;

    /**
     * @var Search
     */
    protected $repository = null;

    /**
     * @var Client
     */
    protected $client = null;

    /**
     * @var string
     */
    protected $action = null;

    /**
     *
     */
    public function __construct()
    {
        $this->cache = app('cache');
        $this->cacheKeys = new CacheKeys();
    }

    /**
     * Handle the event.
     *
     * @param  SomeEvent  $event
     * @return void
     */
    public function handle(ElasticsearchEvent $event)
    {
        try {
            $cleanEnabled = config("repository.cache.search.clean.enabled",true);

            if ( $cleanEnabled ) {
                $tag = $event->getTag();

                if ( config("repository.cache.search.clean.on.{$this->action}",true) ) {
                    $cachedKeys = $this->cacheKeys->getKeysByTag($tag);
                    if ( is_array($cachedKeys) ) {
                        foreach ($cachedKeys as $key) {
                            $this->cache->forget($key);
                        }
                    }
                    // cleanup cachekeys
                    $this->cacheKeys->tag($tag)->forget();
                }
            }
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }
}