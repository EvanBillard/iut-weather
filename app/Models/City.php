<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'is_favorite'];  // Ajouter 'is_favorite'

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthode pour définir la ville comme favorite
    public function setAsFavorite()
    {
        // S'assurer qu'une seule ville est favorite à la fois pour cet utilisateur
        City::where('user_id', $this->user_id)->update(['is_favorite' => false]);

        $this->is_favorite = true;
        $this->save();
    }
}


