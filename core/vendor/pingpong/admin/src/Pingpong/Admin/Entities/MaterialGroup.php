<?php
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 11.08.14
 * Time: 12:58
 */

namespace Pingpong\Admin\Entities;

class MaterialGroup extends Model
{
    protected $fillable = ['name', 'parent_id'];

    protected $rules = [
        'name'	=>	'required',
    ];

    public function properties()
    {
        return $this->hasMany('Pingpong\Admin\Entities\MaterialGroupHasProperties', 'material_group_id','id');
    }

    public function parent()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\MaterialGroup', 'parent_id','id');
    }

    public function child()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\MaterialGroup','id','parent_id');
    }
}