<?php
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 11.08.14
 * Time: 12:58
 */

namespace Pingpong\Admin\Entities;

class Storages extends Model
{
    protected $fillable = ['name', 'parent_id'];

    protected $rules = [
        'name'	=>	'required',
    ];

    public function parent()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\Storages', 'parent_id','id');
    }

    public function child()
    {
        return $this->hasMany('Pingpong\Admin\Entities\Storages','parent_id', 'id');
    }

    public function hasMaterials()
    {
        return $this->hasMany('Pingpong\Admin\Entities\StorageHasMaterial','storage_id', 'id');
    }


    public function events()
    {
        return $this->hasMany('Pingpong\Admin\Entities\StorageEventsMaterials', 'storage_id', 'id');
    }
}