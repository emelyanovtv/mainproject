<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class Properties extends Model
{
    protected $fillable = ['name', 'measures_id', 'is_required'];

    public function measure()
    {
        return $this->belongsTo('Pingpong\Admin\Entities\Measures', 'measures_id');
    }
    protected $rules = [
        'name'	=>	'required',
        'measures_id'	=>	'required',
    ];
}