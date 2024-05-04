<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function enrollments()
    {
        return $this->hasMany(enrollments::class);
    }
    public function category()
    {
        return $this->belongsTo(categories::class);
    }
    public function contents()
    {
        return $this->hasMany(contents::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
