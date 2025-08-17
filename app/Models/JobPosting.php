<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\JobPosting
 *
 * @property int $id
 * @property string $title
 * @property string $department
 * @property string $description
 * @property string $requirements
 * @property float|null $salary_min
 * @property float|null $salary_max
 * @property string $employment_type
 * @property string $location
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $application_deadline
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobApplication> $applications
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting query()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting published()
 * @method static \Illuminate\Database\Eloquent\Builder|JobPosting active()
 * @method static \Database\Factories\JobPostingFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class JobPosting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'department',
        'description',
        'requirements',
        'salary_min',
        'salary_max',
        'employment_type',
        'location',
        'status',
        'application_deadline',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'application_deadline' => 'date',
    ];

    /**
     * Get the user who created this job posting.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the applications for this job posting.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Scope a query to only include published job postings.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include active job postings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                    ->where(function ($q) {
                        $q->whereNull('application_deadline')
                          ->orWhere('application_deadline', '>=', today());
                    });
    }
}