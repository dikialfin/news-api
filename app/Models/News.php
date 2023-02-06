<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class News extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_news';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'source', 
        'author',
        'title',
        'description',
        'published_at',
        'content',
        'url_image',
        'category',
        'is_headline',
    ];

}