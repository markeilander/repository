<?php

namespace Eilander\Repository;

use Eilander\Cache\CacheKeys as Base;

/**
 * Description of CacheKeys.
 *
 * @author Eilander
 */
class CacheKeys extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->path = config('repository.cache.store.path');
        $this->file = config('repository.cache.store.file');
        $this->tag = '*';
    }
}
