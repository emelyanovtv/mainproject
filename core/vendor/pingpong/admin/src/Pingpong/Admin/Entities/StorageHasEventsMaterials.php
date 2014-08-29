<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class StorageHasEventsMaterials extends Model
{
    protected $fillable = ['storage_to_material_id', 'storage_events_materials_id', 'total_value'];

    protected $rules = [
        'storage_to_material_id'	=>	'required',
        'storage_events_materials_id'	=>	'required',
        'total_value'	=>	'required',
    ];

    public function event()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\StorageEventsMaterials', 'storage_events_materials_id', 'id');
    }
}