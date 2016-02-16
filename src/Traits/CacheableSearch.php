<?php

namespace Eilander\Repository\Traits;

use Eilander\Repository\Traits\Cacheable;

/**
 * Class CacheableRepository
 * @package Eilander\Repository\Traits
 */
trait CacheableSearch {
    use Cacheable;

    /**
     * Search query in elasticsearch
     *
     * @param array|string $query
     * @return mixed
     */
    public function search($request = '')
    {
        if ( !$this->allowedCache('search', 'search') || $this->shouldCache() ){
            return parent::search($request);
        }

        $key     = $this->getCacheKey('search', func_get_args(), $this->type);
        $minutes = $this->getCacheMinutes('search');

        $value   = $this->getCacheRepository()->remember($key, $minutes, function() use($request) {
            return parent::search($request);
        });
        return $value;
    }
}