<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_at',
        'created_name',
        'updated_at',
        'updated_name',
        'updated_no',
        'deleted_at',
        'name',
        'email',
        'password',
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * すべて取得
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function getAll(array $data)
    {
        /** @var \Illuminate\Database\Eloquent\Builder */
        $builder = $this->select('users.*');

        if (isset($data['name'])) {
            $builder->where('users.name', 'like', '%' . $data['name'] . '%');
        }

        if (isset($data['order']) && str_contains($data['order'], '_')) {
            [$column, $order] = explode('_', $data['order']);
            $builder->orderBy('users.' . $column, $order);
        }

        return $builder->get();
    }
}
