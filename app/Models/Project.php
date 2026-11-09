<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Project extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'title', 'slug', 'short_description', 'problem', 'solution', 'architecture', 'challenges', 'what_i_learned', 'live_demo_url', 'github_url', 'cover_image', 'video_url', 'featured', 'is_published', 'sort_order'];
    public function category() { return $this->belongsTo(ProjectCategory::class); }
    public function features() { return $this->hasMany(ProjectFeature::class)->orderBy('sort_order'); }
    public function technologies() { return $this->belongsToMany(Technology::class); }
    public function images() { return $this->hasMany(ProjectImage::class)->orderBy('sort_order'); }
}
