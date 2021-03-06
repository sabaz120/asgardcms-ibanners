<?php

namespace Modules\Ibanners\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Bcrud\Support\Traits\CrudTrait;

class Category extends Model
{
    use CrudTrait;

    protected $table = 'ibanners__categories';

    protected $fillable = ['title','description','slug','parent_id','options'];

    protected $fakeColumns = ['options'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array'
    ];



    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function parent()
    {
        return $this->belongsTo('Modules\Ibanners\Entities\Category', 'parent_id');
    }
    public function children()
    {
        return $this->hasMany('Modules\Ibanners\Entities\Category', 'parent_id');
    }

    public function banners()
    {
        return $this->belongsToMany('Modules\Ibanners\Entities\Banner','ibanners__banner__category');
    }
    protected function setSlugAttribute($value){

        if(!empty($value)){
            $this->attributes['slug'] = str_slug($value,'-');
        }else{$this->attributes['slug'] = str_slug($this->title,'-');}

    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeFirstLevelItems($query)
    {
        return $query->where('depth', '1')
            ->orWhere('depth', null)
            ->orderBy('lft', 'ASC');
    }
}
