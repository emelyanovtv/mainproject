<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class MaterialGroupHasProperties extends Model
{
    protected $fillable = ['material_group_id', 'properties_id'];
    public $timestamps = false;

    public function property()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\Properties', 'properties_id');
    }

    protected $rules = [
        'material_group_id'	=>	'required',
        'properties_id'	=>	'required',
    ];
}