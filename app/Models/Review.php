<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Review extends Model
{
    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'review_school');
    }
}