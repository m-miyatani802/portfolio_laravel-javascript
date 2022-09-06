<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typing extends Model
{
    use HasFactory;

    protected $table = 'typings';
    public $timestamps =false;
    protected $fillable = [
        'word_id', 'typing_character'
    ];

    public function word()
    {
        return $this->hasOne(\App\Models\Word::class);

    }

    public function favorites_word()
    {
        return $this->hasOne(\APP\Models\FavoritesWord::class);
    }
}
