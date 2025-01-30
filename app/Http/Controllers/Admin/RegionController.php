<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class RegionController extends Controller
{
    public function index(){
        $page_title = 'Region Detail';
        $users = User::selectRaw("JSON_UNQUOTE(JSON_EXTRACT(address, '$.country')) as country, COUNT(*) as total")
        ->groupBy('country')
        ->pluck('total', 'country');
       
        return view('admin.regions.list',compact('page_title','users'));
    }
}
