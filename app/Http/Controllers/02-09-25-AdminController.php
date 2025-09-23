<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;


class AdminController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post'))
        {
            //print_r(Auth::guard("admin")->attempt(["email" => $request->email,"password" => $request->password,'user_type'=>'1']));die();
            if (Auth::guard("admin")->attempt(["email_id" => $request->email,"password" => $request->password])) {

                $user = Auth::guard("admin")->user();

                return redirect()->route("admin.dashboard");
            }else{
                echo "Credentails do not matches our record.";
                 Session::flash('message', "Credentails do not matches our record");
                return redirect()->back()->withErros(["email" => "Credentails do not matches our record."]);
            }
        }
        if(Auth::guard("admin")->user())
        {
            $user = Auth::guard("admin")->user();
        
            return redirect()->route("admin.dashboard");
        }
        else
        {
            return view('admin.login');
        }
        
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
    public function dashboard()
    {
        
        if(Auth::guard("admin")->user())
        {
            $user = Auth::guard("admin")->user();
        
            $totalSellers = DB::table('users')->where('user_type', '2')->where('is_deleted', '0')->count();
            $totalProduct = DB::table('product_template')->where('is_deleted', '0')->count();
            return view('admin.dashboard',compact('totalSellers','totalProduct'));
        }
        else
        {
            return view('admin.login');
        }
    }
    public function getstate(Request $request) 
    {
        $state = DB::table('master_state')->where('state_country_id', '=', $request->country_id)->orderBY('state_name', 'asc')->get();
        $data = compact('state');
        return response()->json($data);
    }

    public function getcity(Request $request) 
    {
        $city = DB::table('master_city')->where('city_state_id', '=', $request->state_id)->where('city_status','=',1)->orderBY('city_name', 'asc')->get();
        $data = compact('city');
        return response()->json($data);
    }
    public function getcityByCountry(Request $request) 
    {
        $city = DB::table('master_city')->where('country_id', '=', $request->country_id)->where('city_status','=',1)->orderBY('city_name', 'asc')->get();
        $data = compact('city');
        return response()->json($data);
    }

    public function getModel(Request $request) 
    {
        $locale = App::getLocale();
        $referrer = $request->headers->get('referer');

        if ($referrer && str_contains($referrer, '/admin/')) {
            $locale = 'en';
        }
        if($request->brand_id)
        {
            $city = DB::table('make_model')->where('brand_id', '=', $request->brand_id)->where('is_active', '=', 1)->where('is_deleted', '=', 0)->get();
        }
        else
        {
            $city=DB::table('make_model')->where('is_active',1)->where('is_deleted',0)->get();
        }
        $models = $city->map(function ($item) use ($locale) {
            $item->model_name = $locale == 'ar' ? $item->ar_model_name : $item->model_name;
            return $item;
        });

        // $data = compact('city');
        // return response()->json($data);
        return response()->json([
            'city' => $models
        ]);
    }
    public function getSubcategory(Request $request) 
    {
        $locale = App::getLocale();
        $referrer = $request->headers->get('referer');

        if ($referrer && str_contains($referrer, '/admin/')) {
            $locale = 'en';
        }

        if($request->category_id)
        {
            $subcat = DB::table('subcategory')->where('category_id', '=', $request->category_id)->where('is_active', '=', 1)->where('is_deleted', '=', 0)->get();
        }
        else{
            $subcat = DB::table('subcategory')->where('is_active', '=', 1)->where('is_deleted', '=', 0)->get();
        }
        $models = $subcat->map(function ($item) use ($locale) {
            $item->subcat_name = $locale == 'ar' ? $item->ar_subcat_name : $item->subcat_name;
            return $item;
        });

        return response()->json([
            'subcat' => $models
        ]);

        // $data = compact('subcat');
        // return response()->json($data);
    }
    public function getgeneration(Request $request) 
    {
        $subcat = DB::table('generation_year')->where('model_id', '=', $request->model_id)->where('is_active', '=', 1)->where('is_deleted', '=', 0)->orderby('start_year','DESC')->get();
        $data = compact('subcat');
        return response()->json($data);
    }
    public function getBrand(Request $request)
    {
        $locale = App::getLocale();
        $referrer = $request->headers->get('referer');

        if ($referrer && str_contains($referrer, '/admin/')) {
            $locale = 'en';
        }
        if($request->mparents_id)
        {
            $subcat = DB::table('mparent_brand')
                        ->leftJoin('brand', 'brand.id', '=', 'mparent_brand.brand_id')
                        ->where('mparents_id', '=', $request->mparents_id)
                        ->where('brand.is_active', '=', 1)
                        ->where('brand.is_deleted', '=', 0)
                        ->select('brand.*')
                        ->get();
        }
        else
        {
            $subcat=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        }

        $models = $subcat->map(function ($item) use ($locale) {
            $item->brand_name = $locale == 'ar' ? $item->ar_brand_name : $item->brand_name;
            return $item;
        });

        return response()->json([
            'subcat' => $models
        ]);

        // $data = compact('subcat');
        // return response()->json($data);
    }

    public function getPartType(Request $request)
    {
        $brand_id       = $request->brand_id;
        $model_id       = $request->model_id;
        $generation_id  = $request->generation_id;
        $category_id    = $request->category_id;
        $subcategory_id = $request->subcategory_id;

        $product = DB::table('product_template')
                        ->where('brand_id', '=', $brand_id)
                        ->where('make_model_id', '=', $model_id)
                        ->where('category_id', '=', $category_id)
                        ->where('subcategory_id', '=', $subcategory_id)
                        ->where('generation_id', '=', $generation_id)
                        ->first();
        if($product)
        {
            $part_type = DB::table('part_type')->where('product_temp_id', '=', $product->id)->get();
            $data = compact('part_type');
        }        
        else{
            $data="";
        }        
        
        return response()->json($data);
    }
}


?>