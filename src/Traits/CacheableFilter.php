<?php

namespace Eilander\Repository\Traits;

/**
 * Class CacheableFilter.
 */
trait CacheableFilter
{
    use Cacheable;

    /**
     * Retrieve all data of repository.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function filter($model, $fieldsSearchable)
    {
        if (!$this->allowedCache('filter') || $this->shouldCache()) {
            return parent::filter($model, $fieldsSearchable);
        }

        $key = $this->getCacheKey('filter', $fieldsSearchable);
        $minutes = $this->getCacheMinutes();
        $value = $this->getCacheRepository()->remember($key, $minutes, function () use ($model, $fieldsSearchable) {
            return parent::filter($model, $fieldsSearchable);
        });

        return $value;
    }
}
