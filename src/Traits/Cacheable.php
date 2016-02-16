<?php

namespace Eilander\Repository\Traits;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Eilander\Repository\CacheKeys;

/**
 * Class CacheableRepository
 * @package Eilander\Repository\Traits
 */
trait Cacheable {

    /**
     * @var Illuminate\Contracts\Cache\Repository
     */
    protected $cacheRepository = null;

    /**
     * @var Eilander\Repository\Helpers\CacheKeys
     */
    protected $cacheKeys = null;

    /**
     * @var noCache
     */
    protected $noCache = false;

    /**
     * @var lifetime
     */
    protected $lifetime = 0;

    /**
     * No Cache
     *
     * @param bool $status
     * @return $this
     */
    public function noCache($status = true)
    {
        $this->noCache = $status;
        return $this;
    }

    /**
     * Minutes
     *
     * @param int $minutes
     * @return $this
     */
    public function minutes($minutes = 1)
    {
        if (is_numeric($minutes)) {
            $this->lifetime = $minutes;
        }
        return $this;
    }

    /**
     * Hours
     *
     * @param int $hours
     * @return $this
     */
    public function hours($hours = 1)
    {
        if (is_numeric($hours)) {
            $this->lifetime = $hours * 60;
        }
        return $this;
    }

    /**
     * Days
     *
     * @param int $days
     * @return $this
     */
    public function days($days = 1)
    {
        if (is_numeric($days)) {
            $this->lifetime = $days * 3600;
        }
        return $this;
    }

    /**
     * Check if cache should be applied
     */
    protected function shouldCache()
    {
        return $this->noCache;
    }

    /**
     * Get Cache key for the method
     *
     * @param $method
     * @param $args
     * @return string
     */
    public function getCacheKey($method, $args = null, $tag = '') {
        $request = app('Illuminate\Http\Request');
        $args    = serialize($args);
        $key     = sprintf('%s@%s-%s',
            get_called_class(),
            $method,
            md5($args.$request->fullUrl())
        );
        // use called class if tag not is defined
        if (trim($tag) == '') {
            $tag = get_called_class();
        }
        $this->getCacheKeys()->tag($tag)->add($key)->store();

        return $key;

    }

    /**
     * Get cache minutes
     *
     * @return int
     */
    public function getCacheMinutes($repository = 'repository')
    {
        if ($this->lifetime > 0) {
            return $this->lifetime;
        }
        $cacheMinutes = isset($this->cacheMinutes) ? $this->cacheMinutes : config('repository.cache.'.$repository.'.minutes',45);
        return $cacheMinutes;
    }

    /**
     * Return instance of Cache Repository
     *
     * @return CacheRepository
     */
    public function getCacheRepository()
    {
        if ( is_null($this->cacheRepository) ) {
            $this->cacheRepository = app('cache');
        }

        return $this->cacheRepository;
    }

    /**
     * Return instance of cacheKeys
     *
     * @return CacheKeys
     */
    public function getCacheKeys()
    {
        if ( is_null($this->cacheKeys) ) {
            $this->cacheKeys = new CacheKeys();
        }

        return $this->cacheKeys;
    }

    /**
     * @param $method
     * @return bool
     */
    protected function allowedCache($method, $repository = 'repository')
    {
        $cacheEnabled = config('repository.cache.enabled',true);

        if ( !$cacheEnabled ){
            return false;
        }

        $cacheOnly    = isset($this->cacheOnly)     ? $this->cacheOnly    : config('repository.cache.'.$repository.'.allowed.only',null);
        $cacheExcept  = isset($this->cacheExcept)   ? $this->cacheExcept  : config('repository.cache.'.$repository.'.allowed.except',null);

        if ( is_array($cacheOnly) ) {
            return isset($cacheOnly[$method]);
        }

        if ( is_array($cacheExcept) ) {
            return !in_array($method, $cacheExcept);
        }

        if ( is_null($cacheOnly) && is_null($cacheExcept) ) {
            return true;
        }

        return false;
    }
}