<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class StorageEventsHasEventsProperties extends Model
{
    protected $fillable = ['storage_events_id', 'event_prop_id'];
    public $timestamps = false;

    public function property()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\EventsProperties', 'event_prop_id');
    }

    protected $rules = [
        'storage_events_id'	=>	'required',
        'event_prop_id'	=>	'required',
    ];
}