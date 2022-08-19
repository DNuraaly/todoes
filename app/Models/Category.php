<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const TABLE = 'categories';
    use HasFactory;

    protected $fillable = ['title'];
//    protected $appends = ['added_at'];
//    protected $hidden = ['created_at','updated_at'];
//    public $timestamps = false;

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



//    public function getAddedAtAttribute()
//    {
//        return $this->created_at;
//    }
}
