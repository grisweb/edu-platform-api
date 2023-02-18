<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\TemporaryUpload
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryUpload query()
 * @mixin \Eloquent
 */
class TemporaryUpload extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
}
