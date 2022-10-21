<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = "Favorites";

    protected $fillable = [
        'id_usuario',
        'ref_api',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
