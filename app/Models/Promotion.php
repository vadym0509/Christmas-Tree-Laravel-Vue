<?php

namespace App\Models;

use Purcell\Hone\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Promotion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'image',
        'cta_link',
        'cta_text',
        'kilted-trees',
        'festive-floristry',
    ];

    public function getImagePathAttribute()
    {
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image) . '/' . File::find($this->image)->filename;
        }
    }
}
