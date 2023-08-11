<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sectiontaarafa478 extends Model
{
    use HasFactory;
  protected $table='section_taarafa_478';

    protected $fillable = [
	'id',
        'user_id',
        'title_id',
        'date_employment',
        'type_employment',
        'salary',
        'posh',
        'other_revenue',
        'employer',
        'last_title',
        'last_title_date',
        'last_date_employment',
        'last_end_title_date',
        'user_declaration_id',
        'is_active',
        'selected_date',
        'last_employer',
        'kuthibitishwa_date',
        'mkoa_sasa',
        'wilaya_sasa',
        'kata_sasa',
        'village_id',
        'councils_id',
        'secure_token',
        'maelezo_ya_cheo_wadhifa',
        'country_id',
        'physical_address'
       
    ];

    public function councils()
    {

        return $this->BelongsTo(Council::class,'councils_id','id');
    }

    public function village()
    {

        return $this->BelongsTo(Village::class,'village_id','id');
    }

    public function kata_sasa_name(){
        return $this->belongsTo(Ward::class,'kata_sasa','id');

    }

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');

    }

    public function title_name(){
        return $this->belongsTo(Title::class,'title_id','id');

    }

    

    public function wilaya_sasa_name(){
        return $this->belongsTo(District::class,'wilaya_sasa','id');

    }

    public function mkoa_sasa_name(){
        return $this->belongsTo(Region::class,'mkoa_sasa','id');

    }

    public function userDetails(){
        return $this->belongsTo(User::class,'user_id','id');

    }

    public function userDeclaration(){
        return $this->belongsTo(User_declaration::class,'user_declaration_id','id');

    }

    public function mwaajiri()
    {
        return $this->belongsTo(Office::class,'employer','id');
    }

    public function ainaya_ajira()
    {
        return $this->belongsTo(Employment_type::class,'type_employment','id');
    }

    public function marital_status(){
        return $this->belongsTo(Marital_status::class,'marital_status_id','id');

    }
}
