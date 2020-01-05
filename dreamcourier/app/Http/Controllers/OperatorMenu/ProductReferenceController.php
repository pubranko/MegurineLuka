<?php

namespace App\Http\Controllers\OperatorMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ProductSearchRequest; #追加
use App\ProductMasters;                     #追加

class ProductReferenceController extends Controller
{
    /**
     * 
     */
    public function search(ProductSearchRequest $request){
        return view('operator.menu.product_search');
    }

    /**
     * 
     */
    public function show(Request $request){
        return view('operator.menu.product_show');
    }

}
