<?php namespace Pingpong\Admin\Entities;
/**
 * Created by PhpStorm.
 * User: timofey
 * Date: 15.08.14
 * Time: 10:07
 */


class Measures extends Model
{
    protected $fillable = ['name', 'code'];

    protected $rules = [
        'name'	=>	'required',
        'code'	=>	'required',
    ];
}