<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'email',
        'role',
        'job_title',
        'date_of_birth',
        'join_date',
        'salary',
        'nid',
        'blood_group',
        'image',
    ];

    // Define the relationship back to the user
    public function user()
	{
	    return $this->belongsTo(User::class, 'phone', 'phone');
	}
	public function store()
    {
        return $this->belongsTo(Store::class, 'store_id'); // foreign key: 'store_id'
    }

}
