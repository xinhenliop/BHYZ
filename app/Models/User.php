<?php

namespace App\Models;

use App\Libs\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "bh_users";
    protected $fillable = [
        'uid',
        'user',
        'name',
        'password',
        "Inviter_uid",
        'system',
        'app_list',
        'crad_list',
        'remark',
        'data',
        'remember_token',
        'qq',
        'email',
        'spread_number',
        'Inviter',
        'user_status',
        'discount',
        'reg_ip',
        'last_login_ip',
        'last_login_time',
        'login_count',
        'balance',
        'level',
        'email_verified_at'
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
    const UPDATED_AT = "updated_at";
    const CREATED_AT = "created_at";

    protected $primaryKey = "Id";

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function show()
    {
        $show = [];
        $label = $this->fillable;
        array_push($label, "created_at", "updated_at");
        foreach (array_diff($label, $this->hidden) as $column => $value) $show[$value] = $this->$value;
        return $show;
    }

    public static function updateds(array $array_pop_key)
    {
        $App = strpos($array_pop_key['uid'], "]") > 0 ? User::whereIn("uid", json_decode($array_pop_key['uid'], true)) : User::where("uid", "=", $array_pop_key['uid']);
        return $App->update(Utils::array_pop_key($array_pop_key, "uid"));
    }

    public static function createds($arrays)
    {
        $thiss = new User();
        $thiss->uid = rand(111111, 99999999);
        $arrays['password'] = Hash::make($arrays['password']);
        foreach ($arrays as $key => $value) {
            $thiss->{$key} = $value;
        }
        return $thiss->save();
    }
}




