<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImageGeneration extends Model
{
    protected $fillable = [
        'user_id',
        'image_path',
        'generated_prompt',
        'file_size',
        'orginal_filename',
        'mime_type',
    ];
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
