<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSupport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'query_title',
        'query_description',
        'query_ss_name',
        'assigned_to',
        'assigned_on_date',
        'status',
        'resolved_at',
        'closed_by',
        'remark'
    ];
}
