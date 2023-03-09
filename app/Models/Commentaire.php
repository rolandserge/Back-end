<?php

namespace App\Models;

use App\Models\User;
use App\Models\Livre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commentaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'evaluation',
        'user_id',
        'livre_id'
    ];

    public function user() {

        return $this->belongsTo(User::class);

    }
    public function livre() {

        return $this->belongsTo(Livre::class);

    }
}