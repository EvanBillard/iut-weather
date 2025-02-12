<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'is_favorite'];  

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
    public function setAsFavorite()
    {
        
        City::where('user_id', $this->user_id)->update(['is_favorite' => false]);

        $this->is_favorite = true;
        $this->save();
    }
}


