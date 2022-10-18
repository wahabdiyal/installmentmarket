<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Company extends Model
{
    use HasFactory;
       protected $guarded = [];

       /**
        * Get all of the products for the Company
        *
        * @return \Illuminate\Database\Eloquent\Relations\HasMany
        */
       public function products(): HasMany
       {
           return $this->hasMany(Product::class);
       }

       /**
        * Get the user that owns the Company
        *
        * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
        */
       public function user(): BelongsTo
       {
           return $this->belongsTo(User::class);
       }

       public function images()
       {
           return $this->morphOne(Image::class, 'imageable');
       }


}
