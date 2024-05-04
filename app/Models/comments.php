<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    use HasFactory;
    protected $guarded=[];
    // protected $appends=[

    //     'created_at_relable'
    // ];
    
    // public function created_at_relable(){
    //     return $this->created_at()->differhuman();
    // }
}
