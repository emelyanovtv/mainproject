<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class Materials extends Model
{
    protected $fillable = ['name', 'material_group_id'];

    protected $rules = [
        'name'	=>	'required',
        'material_group_id'	=>	'required',
    ];

    public function values()
    {
        return $this->hasMany('Pingpong\Admin\Entities\MaterialHasProperties', 'material_id','id');
    }

    public function materialsgroup()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\MaterialGroup', 'material_group_id');
    }

    public function hasStorage()
    {
        return $this->hasMany('Pingpong\Admin\Entities\StorageHasMaterial', 'material_id', 'id');
    }
}