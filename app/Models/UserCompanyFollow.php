<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompanyFollow extends Model
{
    use HasFactory;
       protected $guarded = [];

              /**
        * Get the user that owns the UserCompanyFavourite
        *
        * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
        */
        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }
}
