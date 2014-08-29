<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class MaterialHasProperties extends Model
{
    protected $fillable = ['material_id', 'properties_id', 'value', 'is_cnt'];

    public function entetyprop()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\MaterialGroupHasProperties', 'properties_id');
    }

    protected $rules = [
        'material_id'	=>	'required',
        'properties_id'	=>	'required',
    ];
}