<?php

namespace Lmate\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Customer extends Model
{
    use HasFactory;
    use Sortable;
    protected $table = "customer";
    public $timestamps = true;
     protected  $fillable = [
        'firstname', 'lastname','phonenumber','email','phone_number','dob','created_date','updated_date','address','gender','devices','status','subscription','customer_image','product_images','filename','obsolete'
    ];
	public $sortable = ['id',
                        'firstname',
                        'lastname',
                        'email',
                        'dob',
                        'status'];
}
