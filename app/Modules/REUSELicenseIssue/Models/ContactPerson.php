<?php
/**
 * Author: Md. Mehedi Hasan Ahad
 * Date: 29 Nov, 2022
 */
namespace App\Modules\REUSELicenseIssue\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    protected $table = 'contact_person';
    protected $guarded = ['id'];
}
