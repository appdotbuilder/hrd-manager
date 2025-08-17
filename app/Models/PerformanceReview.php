<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PerformanceReview
 *
 * @property int $id
 * @property int $employee_id
 * @property int $reviewer_id
 * @property \Illuminate\Support\Carbon $review_period_start
 * @property \Illuminate\Support\Carbon $review_period_end
 * @property int $performance_score
 * @property string|null $goals_achieved
 * @property string|null $areas_for_improvement
 * @property string|null $manager_comments
 * @property string|null $employee_comments
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read User $employee
 * @property-read User $reviewer
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceReview whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceReview whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceReview whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceReview completed()
 * @method static \Database\Factories\PerformanceReviewFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class PerformanceReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_period_start',
        'review_period_end',
        'performance_score',
        'goals_achieved',
        'areas_for_improvement',
        'manager_comments',
        'employee_comments',
        'status',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'review_period_start' => 'date',
        'review_period_end' => 'date',
        'performance_score' => 'integer',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the employee being reviewed.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the reviewer conducting the review.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Scope a query to only include completed reviews.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}