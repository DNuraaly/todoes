<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    const TABLE = 'notes';
    protected $table = 'notes';

    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
}
