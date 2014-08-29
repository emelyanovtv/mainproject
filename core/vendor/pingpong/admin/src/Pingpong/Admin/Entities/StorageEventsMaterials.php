<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class StorageEventsMaterials extends Model
{
    protected $fillable = ['storage_id', 'storage_events_id', 'material_id','user_id', 'value', 'data'];

    protected $rules = [
        'storage_id'	=>	'required',
        'storage_events_id'	=>	'required',
        'material_id'	=>	'required',
        'user_id'	=>	'required',
        'value'	=>	'required',
    ];


    public function eventData()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\StorageEvents', 'storage_events_id', 'id');
    }


}