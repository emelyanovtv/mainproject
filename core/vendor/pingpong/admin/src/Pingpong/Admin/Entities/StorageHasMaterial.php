<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class StorageHasMaterial extends Model
{
    protected $fillable = ['storage_id', 'material_id', 'total'];

    protected $rules = [
        'storage_id'	=>	'required',
        'material_id'	=>	'required',
    ];

    public function storage()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\Storages', 'storage_id', 'id');
    }

    public function materials()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\Materials', 'material_id','id');
    }

    public function events()
    {
        return $this->hasMany('Pingpong\Admin\Entities\StorageHasEventsMaterials', 'storage_to_material_id' , 'id');
    }
}