<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseModule
 *
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CourseModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseModule query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseModule whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseModule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseModule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseModule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseModule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CourseModule extends Model
{
    use HasFactory;
}
