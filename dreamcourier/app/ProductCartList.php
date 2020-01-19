<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCartList extends Model
{
    public function productMaster(){
        return $this->hasOne('App\ProductMaster','id','product_id');    #productMasterのidと、カートリストのproduct_idを紐付け
    }

    public function Member(){
        return $this->hasOne('App\Member','member_code','member_code');    #
    }
}
