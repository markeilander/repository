<?php

namespace Eilander\Repository\Contracts;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

/**
 * Interface CacheableInterface.
 */
interface CacheableInterface
{
    /**
     * Return instance of Cache Repository.
     *
     * @return CacheRepository
     */
    public function getCacheRepository();

    /**
     * Get Cache key for the method.
     *
     * @param $method
     * @param $args
     *
     * @return string
     */
    public function getCacheKey($method, $args = null);

    /**
     * Return instance of CacheKeys.
     *
     * @return CacheKeys
     */
    public function getCacheKeys();

    /**
     * Get cache minutes.
     *
     * @return int
     */
    public function getCacheMinutes();

    /**
     * No Cache.
     *
     * @param bool $status
     *
     * @return $this
     */
    public function noCache($status = true);
}
