<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Category extends Model
{
    use HasFactory;
       protected $guarded = [];
            /**
             * Get all of the parent for the Category
             *
             * @return \Illuminate\Database\Eloquent\Relations\HasMany
             */
            public function parent()
            {
                return $this->belongsTo(Category::class, 'parent_id');
            }
            /**
             * Get all of the children for the Category
             *
             * @return \Illuminate\Database\Eloquent\Relations\HasMany
             */
            public function children()
            {
                return $this->hasMany(Category::class, 'parent_id');
            }
            /**
             * Get all of the products for the Category
             *
             * @return \Illuminate\Database\Eloquent\Relations\HasMany
             */
            public function products()
            {
                return $this->hasMany(Category::class,'id');
            }

            public function images()
            {
                return $this->morphOne(Image::class, 'imageable');
            }
}
