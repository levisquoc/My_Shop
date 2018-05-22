<?php

namespace App\Modules\Acl\Models;

use App\Helpers\ErrorHelpers;
use App\Modules\Posts\Models\Category;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\URL;
use Laratrust\Traits\LaratrustUserTrait;
use Auth;
use App\Modules\Posts\Models\Post;

class Admin extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait;
    use ErrorHelpers;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'gender',
        'facebook',
        'googleplus',
        'github',
        'phone',
        'secret_password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'secret_password'
    ];

    public function save(array $options = [])
    {
        // If no avatar has been set, set it to the default
        $this->avatar = $this->avatar ?: 'Modules/Acl/images/useravatar.svg';

        parent::save();
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'admin_category', 'admin_id', 'cate_id');
    }


}
