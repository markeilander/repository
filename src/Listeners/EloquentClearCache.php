<?php

namespace Eilander\Repository\Listeners;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Eilander\Repository\Contracts\Eloquent as Repository;
use Eilander\Repository\Events\EloquentEvent;
use Eilander\Repository\CacheKeys;

/**
 * Class EloquentListener
 */
class EloquentClearCache {

    /**
     * @var Illuminate\Contracts\Cache\Repository
     */
    protected $cache = null;
    /**
     * @var Eilander\Repository\CacheKeys
     */
    protected $cacheKeys = null;

    /**
     * @var RepositoryInterface
     */
    protected $repository = null;

    /**
     * @var Model
     */
    protected $model = null;

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
    public function handle(EloquentEvent $event)
    {
        try {
            $cleanEnabled = config("repository.cache.repository.clean.enabled",true);

            if ( $cleanEnabled ) {
                $this->repository = $event->getRepository();
                $this->model      = $event->getModel();
                $this->action     = $event->getAction();
                if ( config("repository.cache.repository.clean.on.{$this->action}",true) ) {
                    $this->cleanByRepository(get_class($this->repository));

                    if (count($this->repository->isUsedBy())) {
                        foreach ($this->repository->isUsedBy() as $implementation) {
                            $this->cleanByRepository(get_class(app($implementation)));
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    private function cleanByRepository($repository)
    {
        $cachedKeys = $this->cacheKeys->getKeysByTag($repository);
        if ( is_array($cachedKeys) ) {
            foreach ($cachedKeys as $key) {
                $this->cache->forget($key);
            }
        }
        // cleanup cachekeys
        $this->cacheKeys->tag($repository)->forget();
    }
}