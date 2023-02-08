<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class LovedNews extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_news_loved';

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
        'id_user', 
        'id_news',
    ];

}