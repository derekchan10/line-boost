<?php

namespace T8891\LineBoost\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Request;

class Compaign implements Scope
{
    /**
     * 把约束加到 Eloquent 查询构造中.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $compaign = Request::route('compaign');
        if ($compaign) {
            $builder->where('compaign', '=', $compaign);
        }
    }
}