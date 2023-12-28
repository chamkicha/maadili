<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

 /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'file_number',
        'first_name',
        'middle_name',
        'last_name',
        'nida' ,
        'phone_number',
        'email',
        'otp' ,
        'nationality',
        'po_box',
        'councils_id',
        'ward_id',
        'marital_status_id',
        'date_of_birth',
        'verified_at',
        'is_active',
        'is_password_changed',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'undefined',
        'aka',
        'zone_id',
        'title_id',
        'tin_number' ,
        'house_no' ,
        'sex' ,
        'hadhi_id',
        'phone_number2' ,
        'passport' ,
        'profile_picture',
        'residence_village',
        'residence_street',
        'ward_nida',
        'region_nida',
        'district_nida',
        'village_nida',
        'signature_image',
        'village',
        'sex_id',
        'country_birth',
        'country_current',
        'check_number',
        'ward_current' ,
        'district_current' ,
        'region_current' ,
        'po_box_current' ,
        'physical_address_current',
        'kijiji_mtaa_shehia',
        'kijiji_mtaa_shehia_current',
        'village_id',
        'village_string',
	   
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];


    

    public function councils()
    {

        return $this->BelongsTo(Council::class,'councils_id','id');
    }

    public function village()
    {

        return $this->BelongsTo(Village::class,'village_id','id');
    }

    public function family_members(): HasMany
    {

        return $this->hasMany(Family_member::class,'user_id','id');
    }

    public function declarations(): HasMany
    {

        return $this->hasMany(User_declaration::class,'user_id','id');
    }

	  public function zone()
    {
        return $this->BelongsTo(Zone::class,'zone_id','id');
    }
    public function staff()
    {
        return $this->BelongsTo(Staff::class,'staff_id','id');
    }
    public function marital()
    {
        return $this->BelongsTo(Marital_status::class,'marital_status_id','id');
    }
    public function sex()
    {
        return $this->BelongsTo(Sex::class,'sex_id','id');
    }

    public function countryBirthInfo()
    {
        return $this->BelongsTo(Country::class,'country_birth','id');
    }

    public function countryCurrentInfo()
    {
        return $this->BelongsTo(Country::class,'country_current','id');
    }

    public function wardCurrentInfo()
    {
        return $this->BelongsTo(Ward::class,'ward_current','id');
    }

    public function districtCurrentInfo()
    {
        return $this->BelongsTo(District::class,'district_current','id');
    }

    public function regionCurrentInfo()
    {
        return $this->BelongsTo(Region::class,'region_current','id');
    }

    public function hadhi()
    {
        return $this->BelongsTo(Hadhi::class,'hadhi_id','id');
    }
    public function title()
    {
        return $this->BelongsTo(Title::class,'title_id','id');
    }
}
