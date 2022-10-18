<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Product extends Model
{
    use HasFactory;
       protected $guarded = [];

       /**
        * Get the company that owns the Product
        *
        * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
        */
       public function company(): BelongsTo
       {
           return $this->belongsTo(Company::class);
       }
       public function sub_category(): BelongsTo
       {
           return $this->belongsTo(Category::class);
       }


       /**
        * Get the price associated with the Product
        *
        * @return \Illuminate\Database\Eloquent\Relations\HasOne
        */
       public function price(): HasOne
       {
           return $this->hasOne(Price::class);
       }

       public function images()
       {
           return $this->morphMany(Image::class, 'imageable');
       }

}
