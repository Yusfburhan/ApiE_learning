<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contents extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function course()
    {
        return $this->belongsTo(Courses::class);
    }
}
