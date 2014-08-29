<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class StorageEvents extends Model
{
    protected $fillable = ['name', 'description', 'char','is_arifmetic'];

    protected $rules = [
        'name'	=>	'required',
        'description'	=>	'required',
    ];

    public function properties()
    {
        return $this->hasMany('Pingpong\Admin\Entities\StorageEventsHasEventsProperties', 'storage_events_id','id');
    }
}