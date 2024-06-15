<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
    use HasFactory;
    protected $guarded=[];
    public function enrollments()
    {
        return $this->hasMany(enrollment::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(subcategorie::class);
    }
    public function contents()
    {
        return $this->hasMany(content::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }
}
