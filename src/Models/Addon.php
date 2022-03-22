<?php

namespace Lmate\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;
    protected $table = "addon";
    protected $fillable = ['addon_name', 'addon_desc', 'customer_id'];
}
