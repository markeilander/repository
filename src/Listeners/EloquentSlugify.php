<?php

namespace Eilander\Repository\Listeners;

use Eilander\Repository\Events\EloquentEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class EloquentListener.
 */
class EloquentSlugify
{
    /**
     * @var Model
     */
    protected $model = null;

    /**
     * Handle the event.
     *
     * @param SomeEvent $event
     *
     * @return void
     */
    public function handle(EloquentEvent $event)
    {
        try {
            $this->model = $event->getModel();
            if (isset($this->model->slugable)) {
                $to = $this->model->slugable['to'];
                $name = $this->model->slugable['from'];
                $slug = Str::slug($this->model->$name);
                $slugCount = count($this->model->whereRaw("$to REGEXP '^{$slug}(-[0-9]*)?$'")->get());
                $this->model->$to = ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
            }
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }
}
