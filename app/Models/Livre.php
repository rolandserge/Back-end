<?php

namespace App\Models;

use App\Models\Categorie;
use App\Models\Commentaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'auteur',
        'cover',
        'description',
        'user_id',
        "categorie_id"
    ];

    public function categorie() {

        return $this->belongsTo(Categorie::class);

    }
    public function user() {

        return $this->belongsTo(User::class);

    }
    public function commentaires() {

        return $this->hasMany(Commentaire::class);

    }

}