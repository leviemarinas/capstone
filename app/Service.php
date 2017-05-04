<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service';
    public $timestamps = false;
    protected $fillable = [
    	'name',
        'price',
        'size',
    	'categoryId',
    	'isActive'  	
    ];

    public function category(){
        return $this->belongsTo('App\ServiceCategory','categoryId')->where('isActive',1);
    }
}
