<?php

namespace App\Models;

use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory,HasApiTokens;
    protected $fillable = [
        'title','description','image','user_id'
    ];
    // protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
