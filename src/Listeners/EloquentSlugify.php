<?php

namespace Eilander\Repository\Listeners;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Eilander\Repository\Contracts\Eloquent as Repository;
use Eilander\Repository\Events\EloquentEvent;

/**
 * Class EloquentListener
 */
class EloquentSlugify {

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * Handle the event.
     *
     * @param  SomeEvent  $event
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
	        	$slugCount = count( $this->model->whereRaw("$to REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
	    		$this->model->$to = ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
	    	}
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }
}