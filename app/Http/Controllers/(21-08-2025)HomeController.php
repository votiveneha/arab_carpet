<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Session;

use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use App\Models\Business;

class HomeController extends Controller
{
    public function index()
    {
        $locale = App::getLocale();
        $brands=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $brand = $brands->map(function ($item) use ($locale) {
            $item->brand_name = $locale == 'ar' ? $item->ar_brand_name : $item->brand_name;
            return $item;
        });

        $categorys=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $category = $categorys->map(function ($item) use ($locale) {
            $item->category_name = $locale == 'ar' ? $item->ar_category_name : $item->category_name;
            return $item;
        });

        $make_year=DB::table('make_year')->where('is_active',1)->where('is_deleted',0)->orderby('id','desc')->get();
        $country=DB::table('master_country')->where('country_status',1)->get();
        
        $citys=DB::table('master_city')->join('master_country','master_country.country_id','=','master_city.country_id')->where('master_country.country_status',1)->where('master_city.city_status',1)->get();
        $city = $citys->map(function ($item) use ($locale) {
            $item->city_name = $locale == 'ar' ? $item->city_name_ar : $item->city_name;
            return $item;
        });
        return view('web.index',compact('brand','category','make_year','country','city'));
    }
    public function productLists(Request $request)
    {
        // Initial filter data
        $brand = DB::table('brand')->where('is_active', 1)->where('is_deleted', 0)->get();
        $category = DB::table('category')->where('is_active', 1)->where('is_deleted', 0)->get();
        $make_year = DB::table('make_year')->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        $country = DB::table('master_country')->get();

        $models = DB::table('make_model')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->when($request->brand_id, fn($q) => $q->where('brand_id', $request->brand_id))
            ->get();

        $generation = DB::table('generation_year')
            ->where('is_deleted', 0)
            ->when($request->model_id, fn($q) => $q->where('model_id', $request->model_id))
            ->get();

        $subcategory = DB::table('subcategory')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->get();

        // Filters
        $filterYear = $request->year ? (int)$request->year : null;
        $useLocation = false;

            if ($request->city_id) {
                $city = DB::table('master_city')->where('city_id', $request->city_id)->first();
                if ($city && $city->latitude && $city->longitude) {
                    $latitude = $city->latitude;
                    $longitude = $city->longitude;
                    $useLocation = true;
                }
            } elseif ($request->country_id) {
                $countryRow = DB::table('master_country')->where('country_id', $request->country_id)->first();
                if ($countryRow && $countryRow->latitude && $countryRow->longitude) {
                    $latitude = $countryRow->latitude;
                    $longitude = $countryRow->longitude;
                    $useLocation = true;
                }
            } elseif ($request->user_latitude && $request->user_longitude) {
                $latitude = $request->user_latitude;
                $longitude = $request->user_longitude;
                $useLocation = true;
            }
        $radius = 100;

        $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(users.latitude))))";

        $hasFilters = $request->brand_id || $request->model_id || $request->category_id || $request->subcategory_id || $request->variant_id || $filterYear || $request->city_id || $request->country_id || $useLocation;

        // STEP 1: Get matching group_ids
        $groupQuery = DB::table('interchange_product')
            ->when($request->brand_id, fn($q) => $q->where('interchange_product.brand_id', $request->brand_id))
            ->when($request->model_id, fn($q) => $q->where('interchange_product.model_id', $request->model_id))
            ->when($request->category_id, fn($q) => $q->where('interchange_product.category_id', $request->category_id))
            ->when($request->subcategory_id, fn($q) => $q->where('interchange_product.subcategory_id', $request->subcategory_id))
            ->when($request->variant_id, fn($q) => $q->where('interchange_product.variant_id', $request->variant_id));

        if (!is_null($filterYear)) {
            $groupQuery->join('generation_year', 'generation_year.id', '=', 'interchange_product.generation_id')
                ->where('generation_year.start_year', '<=', $filterYear)
                ->where('generation_year.end_year', '>=', $filterYear);
        }

        $matchedGroupIds = $groupQuery->pluck('interchange_product.group_id')->unique()->toArray();

        $groupCombinations = [];
        if (!empty($matchedGroupIds)) {
            $groupCombinations = DB::table('interchange_product')
                ->whereIn('group_id', $matchedGroupIds)
                ->get(['brand_id', 'model_id', 'generation_id', 'category_id', 'subcategory_id', 'variant_id']);
        }

        // STEP 2: Build product query
        $productQuery = Product::query()
            ->join('users', 'users.id', '=', 'product.seller_id')
            ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
            ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
            ->leftJoin('product_img', 'product_img.product_id', '=', 'product.id')
            ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
            ->leftJoin('master_country', 'master_country.country_id', '=', 'users.country_id')
            ->leftJoin('master_state', 'master_state.state_id', '=', 'users.state_id')
            ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
            ->leftJoin('part_type', 'part_type.id', '=', 'product.part_type_id')
            ->where('product.is_active', 1)
            ->where('product.is_deleted', 0)
            ->where('users.is_deleted', 0)
            ->select(
                'product.id', 'product_img.product_image',
                'brand.brand_name', 'make_model.model_name',
                'category.category_name', 'subcategory.subcat_name',
                'product.is_active', 'product.product_type', 'product.product_price',
                'product.seller_id', 'users.first_name', 'users.last_name',
                'master_city.city_name', 'master_country.country_name', 'master_state.state_name',
                'generation_year.start_year', 'generation_year.end_year', 'users.mobile',
                'part_type.part_type_label', 'product.admin_product_id',
                DB::raw("$haversine as distance_km")
            );

        // STEP 3: Filter by combinations or direct match
        if (!empty($groupCombinations)) {
            $productQuery->where(function ($q) use ($groupCombinations, $filterYear, $request) {
                foreach ($groupCombinations as $combo) {
                    $q->orWhere(function ($sub) use ($combo, $filterYear) {
                        $sub->where('product.brand_id', $combo->brand_id)
                            ->where('product.model_id', $combo->model_id)
                            ->where('product.category_id', $combo->category_id)
                            ->where('product.subcategory_id', $combo->subcategory_id);

                        if (!empty($combo->variant_id)) {
                            $sub->where('product.part_type_id', $combo->variant_id);
                        }

                        if (!is_null($filterYear) && !empty($combo->generation_id)) {
                            $sub->where('product.generation_id', $combo->generation_id)
                                ->where('generation_year.start_year', '<=', $filterYear)
                                ->where('generation_year.end_year', '>=', $filterYear);
                        }
                    });
                }

                $q->orWhere(function ($direct) use ($request, $filterYear) {
                    if ($request->brand_id)       $direct->where('product.brand_id', $request->brand_id);
                    if ($request->model_id)       $direct->where('product.model_id', $request->model_id);
                    if ($request->category_id)    $direct->where('product.category_id', $request->category_id);
                    if ($request->subcategory_id) $direct->where('product.subcategory_id', $request->subcategory_id);
                    if ($request->variant_id)     $direct->where('product.part_type_id', $request->variant_id);
                    if (!is_null($filterYear)) {
                        $direct->where('generation_year.start_year', '<=', $filterYear)
                            ->where('generation_year.end_year', '>=', $filterYear);
                    }
                });
            });
        }

        // STEP 4: Location filtering
        $productQuery
        ->when($useLocation, function ($q) use ($haversine, $radius) {
            $q->whereRaw("$haversine <= ?", [$radius])
            ->orderByRaw("$haversine ASC");
        });


        // STEP 5: Order by distance or fallback
        if ($useLocation) {
            $productQuery->orderBy('distance_km', 'asc');
        } else {
            $productQuery->inRandomOrder();
        }

        // STEP 6: Final result
        $products = $productQuery->distinct()->paginate(9);
        //echo "<pre>";print_r($products);die();
        
        return view('web.listing', compact('products', 'brand', 'category', 'models', 'subcategory', 'generation', 'make_year', 'country'));
    }




    public function productList(Request $request)
    {
        
        $locale = App::getLocale();
        $brands=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $brand = $brands->map(function ($item) use ($locale) {
            $item->brand_name = $locale == 'ar' ? $item->ar_brand_name : $item->brand_name;
            return $item;
        });

        $categorys=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $category = $categorys->map(function ($item) use ($locale) {
            $item->category_name = $locale == 'ar' ? $item->ar_category_name : $item->category_name;
            return $item;
        });

        $make_year=DB::table('make_year')->where('is_active',1)->where('is_deleted',0)->orderby('id','desc')->get();
        $country=DB::table('master_country')->get();
        
        $cityalls=DB::table('master_city')->join('master_country','master_country.country_id','=','master_city.country_id')->where('master_country.country_status',1)->where('master_city.city_status',1)->get();
        $cityall = $cityalls->map(function ($item) use ($locale) {
            $item->city_name = $locale == 'ar' ? $item->city_name_ar : $item->city_name;
            return $item;
        });

        $model = DB::table('make_model')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->brand_id, fn($q) => $q->where('brand_id', $request->brand_id))
                ->get();
        $models = $model->map(function ($item) use ($locale) {
            $item->model_name = $locale == 'ar' ? $item->ar_model_name : $item->model_name;
            return $item;
        });

        $generation = DB::table('generation_year')
                ->where('is_deleted', 0)
                ->when($request->model_id, fn($q) => $q->where('model_id', $request->model_id))
                ->get();        

        $subcategorys = DB::table('subcategory')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->get();
        $subcategory = $subcategorys->map(function ($item) use ($locale) {
            $item->subcat_name = $locale == 'ar' ? $item->ar_subcat_name : $item->subcat_name;
            return $item;
        });

        $filters = [
                'brand_id' => $request->brand_id,
                'model_id' => $request->model_id,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
            ];
        $buyerLat = '22.28330000';
        $buyerLng = '46.73330000';

        if ($request->city_id) {
            $city = DB::table('master_city')->where('city_id', $request->city_id)->first();
            if ($city) {
                $buyerLat = $city->latitude;
                $buyerLng = $city->longitude;
            }
        } elseif ($request->user_latitude && $request->user_longitude) {
            $buyerLat = $request->user_latitude;
            $buyerLng = $request->user_longitude;
        }    
        
        // STEP 0: Parse year
        $filterYear = $request->year;

        // STEP 1: Find group_id(s) from interchange_product
        $groupQuery = DB::table('interchange_product')
            ->when($request->brand_id, fn($q) => $q->where('interchange_product.brand_id', $request->brand_id))
            ->when($request->model_id, fn($q) => $q->where('interchange_product.model_id', $request->model_id))
            ->when($request->category_id, fn($q) => $q->where('interchange_product.category_id', $request->category_id))
            ->when($request->subcategory_id, fn($q) => $q->where('interchange_product.subcategory_id', $request->subcategory_id))
            ->when($request->variant_id, fn($q) => $q->where('interchange_product.variant_id', $request->variant_id));

        if ($request->year) {
            $groupQuery->join('generation_year', 'generation_year.id', '=', 'interchange_product.generation_id')
                ->where('generation_year.start_year', '<=', $request->year)
                ->where('generation_year.end_year', '>=', $request->year);
        }

        $matchedGroupIds = $groupQuery->pluck('interchange_product.group_id')->unique()->toArray();

        // STEP 2: Get all combinations from those groups
        $groupCombinations = [];

        if (!empty($matchedGroupIds)) {
            $groupCombinations = DB::table('interchange_product')
                ->whereIn('group_id', $matchedGroupIds)
                ->get([
                    'brand_id',
                    'model_id',
                    'generation_id',
                    'category_id',
                    'subcategory_id',
                    'variant_id',
                ]);
        }
    //echo "<pre>";print_r($groupCombinations);die();
        // STEP 3: Base product query with joins
        $productQuery = Product::query()
                    ->join('users', 'users.id', '=', 'product.seller_id')
                    ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                    ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
                    ->leftJoin('category', 'category.id', '=', 'product.category_id')
                    ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
                    ->leftJoin('product_img', 'product_img.product_id', '=', 'product.id')
                    ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id') // THIS IS REQUIRED
                    ->leftJoin('master_country', 'master_country.country_id', '=', 'users.country_id')
                    //->leftJoin('master_state', 'master_state.state_id', '=', 'users.state_id')
                    ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
                    ->leftJoin('part_type', 'part_type.id', '=', 'product.part_type_id')
                    ->leftJoin('shop_detail', 'shop_detail.user_id', '=', 'users.id')
                    ->where('product.is_active', 1)
                    ->where('product.is_deleted', 0)
                    ->where('users.is_deleted', 0)

            ->select(
                'product.id','product.stock_number', 'product_img.product_image',
                'brand.brand_name as brand_name',
                'make_model.model_name as model_name',
                'category.category_name as category_name',
                'subcategory.subcat_name as subcategory_name',
                'product.is_active', 'product.product_type', 'product.product_price', 'product.seller_id','product.product_note',
                'users.first_name', 'users.last_name','shop_detail.shop_name','shop_detail.shop_logo',
                'master_city.city_name', 'master_country.country_name', 
                'generation_year.start_year', 'generation_year.end_year', 'users.mobile','part_type.part_type_label','product.admin_product_id'
            );
        if ($buyerLat && $buyerLng) {
            $haversine = "(6371 * acos(cos(radians($buyerLat)) * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians($buyerLng)) + sin(radians($buyerLat)) * sin(radians(users.latitude))))";

            $productQuery->addSelect(DB::raw("$haversine AS distance"));
        }
        if ($buyerLat && $buyerLng) {
            $productQuery->orderBy('distance', 'asc');
        }
        // STEP 4: Search by group combinations first + direct matches
        if (!empty($groupCombinations)) {
            $productQuery->where(function ($q) use ($groupCombinations, $request) {
                // Match group combinations (interchange)
                foreach ($groupCombinations as $combo) {
                    $q->orWhere(function ($sub) use ($combo, $request) {
                        $sub->where('product.brand_id', $combo->brand_id)
                            ->where('product.model_id', $combo->model_id)
                            ->where('product.category_id', $combo->category_id)
                            ->where('product.subcategory_id', $combo->subcategory_id);

                        // Optional: match variant only if present
                        if (!empty($combo->variant_id)) {
                            $sub->where('product.part_type_id', $combo->variant_id);
                        }

                        // Year matching based on generation range
                        if ($request->year && !empty($combo->generation_id)) {
                            $sub->where('product.generation_id', $combo->generation_id)
                                ->where('generation_year.start_year', '<=', $request->year)
                                ->where('generation_year.end_year', '>=', $request->year);
                        }
                    });
                }

                // Direct match fallback (non-interchange)
                $q->orWhere(function ($direct) use ($request) {
                    if ($request->brand_id)       $direct->where('product.brand_id', $request->brand_id);
                    if ($request->model_id)       $direct->where('product.model_id', $request->model_id);
                    if ($request->category_id)    $direct->where('product.category_id', $request->category_id);
                    if ($request->subcategory_id) $direct->where('product.subcategory_id', $request->subcategory_id);
                    if ($request->variant_id)     $direct->where('product.part_type_id', $request->variant_id);

                    if ($request->year) {
                        $direct->where('generation_year.start_year', '<=', $request->year)
                            ->where('generation_year.end_year', '>=', $request->year);
                    }
                });
            });

            $products = $productQuery->distinct()->inRandomOrder()->paginate(9);
        }
    
        // STEP 5: Fallback to direct product table if nothing found
        if (empty($products) || $products->isEmpty()) {
            $fallbackQuery = clone $productQuery;

            $fallbackQuery->where(function ($q) use ($request, $filterYear) {
                if ($request->brand_id)         $q->where('product.brand_id', $request->brand_id);
                if ($request->model_id)         $q->where('product.model_id', $request->model_id);
                if ($request->category_id)      $q->where('product.category_id', $request->category_id);
                if ($request->subcategory_id)   $q->where('product.subcategory_id', $request->subcategory_id);
                if ($request->variant_id)       $q->where('product.part_type_id', $request->variant_id);

                // ✅ Ensure this year condition is included
                if ($filterYear) {
                    $q->where('generation_year.start_year', '<=', $filterYear)
                    ->where('generation_year.end_year', '>=', $filterYear);
                }
            });

            $products = $fallbackQuery->distinct()->inRandomOrder()->paginate(9);
        }
        //echo "<pre>";print_r($products);die();
        //Now get the filter information
        $bdetail = DB::table('brand')
                    ->where('id', $request->brand_id)
                    ->first();
        $mdetail = DB::table('make_model')
                    ->where('id', $request->model_id)
                    ->first();
        $cdetail = DB::table('category')
                    ->where('id', $request->category_id)
                    ->first();
        $sdetail = DB::table('subcategory')
                    ->where('id', $request->subcategory_id)
                    ->first();
        $header=array(
                        'year'=>$request->year,
                        'brand'=>$bdetail?($locale == 'ar'?$bdetail->ar_brand_name:$bdetail->brand_name):'',
                        'model'=>$mdetail?($locale == 'ar'?$mdetail->ar_model_name:$mdetail->model_name):'',
                        'category'=>$cdetail?($locale == 'ar'?$cdetail->ar_category_name:$cdetail->category_name):'',
                        'subcatagory'=>$sdetail?($locale == 'ar'?$sdetail->ar_subcat_name:$sdetail->subcat_name):'',
                        );
        return view('web.listing',compact('products','brand','category','models','subcategory','generation','make_year','country','header','cityall'));
    }

    public function trackClick(Request $request)
    {
        DB::table('search_product')->insert([
            'product_id' => $request->product_id,
            'seller_id'=>$request->seller_id,
            'admin_product_id'=>$request->admin_product_id,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success']);
    }
    public function productDetail(Request $request,$id)
    {
        //This function is for product detail
        $locale = App::getLocale();
        $brands=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $brand = $brands->map(function ($item) use ($locale) {
            $item->brand_name = $locale == 'ar' ? $item->ar_brand_name : $item->brand_name;
            return $item;
        });

        $categorys=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $category = $categorys->map(function ($item) use ($locale) {
            $item->category_name = $locale == 'ar' ? $item->ar_category_name : $item->category_name;
            return $item;
        });

        $models = DB::table('make_model')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->brand_id, fn($q) => $q->where('brand_id', $request->brand_id))
                ->get();
        $model = $models->map(function ($item) use ($locale) {
            $item->model_name = $locale == 'ar' ? $item->ar_model_name : $item->model_name;
            return $item;
        });

        $subcategorys = DB::table('subcategory')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->get();
        $subcategory = $subcategorys->map(function ($item) use ($locale) {
            $item->subcat_name = $locale == 'ar' ? $item->ar_subcat_name : $item->subcat_name;
            return $item;
        });

        $make_year=DB::table('make_year')->where('is_active',1)->where('is_deleted',0)->orderby('id','desc')->get();
        $country=DB::table('master_country')->get();

        $citys=DB::table('master_city')->join('master_country','master_country.country_id','=','master_city.country_id')->where('master_country.country_status',1)->where('master_city.city_status',1)->get();
        $city = $citys->map(function ($item) use ($locale) {
            $item->city_name = $locale == 'ar' ? $item->city_name_ar : $item->city_name;
            return $item;
        });

        $product = DB::table('product as p')
                        ->leftJoin('brand as b', 'b.id', '=', 'p.brand_id')
                        ->leftJoin('make_model as m', 'm.id', '=', 'p.model_id')
                        ->leftJoin('category as c', 'c.id', '=', 'p.category_id')
                        ->leftJoin('subcategory as s', 's.id', '=', 'p.subcategory_id')
                        ->leftJoin('users as u', 'u.id', '=', 'p.seller_id')
                        ->leftJoin('master_country as co', 'co.country_id', '=', 'u.country_id')
                        //->leftJoin('master_state as st', 'st.state_id', '=', 'u.state_id')
                        ->leftJoin('product_img as pm', 'pm.product_id', '=', 'p.id')
                        ->leftJoin('generation_year as gy', 'gy.id', '=', 'p.generation_id')
                        ->leftJoin('part_type as py', 'py.id', '=', 'p.part_type_id')
                        ->leftJoin('shop_detail as sd', 'sd.user_id', '=', 'u.id')
                        ->select(
                            'p.*',
                            DB::raw($locale == 'ar' ? 'b.ar_brand_name as brand_name' : 'b.brand_name as brand_name'),
                            DB::raw($locale == 'ar' ? 'm.ar_model_name as model_name' : 'm.model_name as model_name'),
                            DB::raw($locale == 'ar' ? 'c.ar_category_name as category_name' : 'c.category_name as category_name'),
                            DB::raw($locale == 'ar' ? 's.ar_subcat_name as subcategory_name' : 's.subcat_name as subcategory_name'),
                            'u.first_name',
                            'u.last_name',
                            'u.mobile','sd.shop_name','u.id as seller_id',
                            'co.country_name',
                            'pm.product_image','gy.start_year','gy.end_year','py.part_type_label'
                        )
                        ->where('p.id', $id)
                        ->first();
        $user  = DB::table('users')->where('id', '=' , $product->seller_id)
                        ->leftJoin('master_country', 'master_country.country_id', '=', 'users.country_id')
                        //->leftJoin('master_state', 'master_state.state_id', '=', 'users.state_id')
                        ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
                        ->select(
                                'users.*',
                                'master_city.city_name','master_country.country_name'
                            )
                        ->first();
        $shop  = DB::table('shop_detail')->where('user_id', '=' , $product->seller_id)->first();
        
        $service = DB::table('seller_service')
                        ->where('seller_id', $product->seller_id)
                        ->join('services','services.id','=','seller_service.service_id')->get();

        /*                
        // Now fetch the group_id from interchange_product for this product combination
        $group = DB::table('interchange_product')
                    ->where('brand_id', $product->brand_id)
                    ->where('model_id', $product->model_id)
                    ->where('generation_id', $product->generation_id)
                    ->where('category_id', $product->category_id)
                    ->where('subcategory_id', $product->subcategory_id)
                    ->where('variant_id', $product->part_type_id)
                    ->value('group_id');

        // Get other products matching same group_id from product table (with same joins)
        $similarProducts = collect();

        if ($group) {
            $combinations = DB::table('interchange_product')
                ->where('group_id', $group)
                ->get();

            if ($combinations->isNotEmpty()) 
            {
                $similarProducts = DB::table('product as p')
                    ->leftJoin('brand as b', 'b.id', '=', 'p.brand_id')
                    ->leftJoin('make_model as m', 'm.id', '=', 'p.model_id')
                    ->leftJoin('category as c', 'c.id', '=', 'p.category_id')
                    ->leftJoin('subcategory as s', 's.id', '=', 'p.subcategory_id')
                    ->leftJoin('users as u', 'u.id', '=', 'p.seller_id')
                    ->leftJoin('master_country as co', 'co.country_id', '=', 'u.country_id')
                    ->leftJoin('master_state as st', 'st.state_id', '=', 'u.state_id')
                    ->leftJoin('product_img as pm', 'pm.product_id', '=', 'p.id')
                    ->leftJoin('generation_year as gy', 'gy.id', '=', 'p.generation_id')
                    ->leftJoin('part_type as py', 'py.id', '=', 'p.part_type_id')
                    ->leftJoin('shop_detail as sd', 'sd.user_id', '=', 'u.id')
                    ->select(
                        'p.*',
                        'b.brand_name',
                        'm.model_name',
                        'c.category_name',
                        's.subcat_name',
                        'u.first_name',
                        'u.last_name',
                        'u.mobile',
                        'sd.shop_name',
                        'u.id as seller_id',
                        'co.country_name',
                        'st.state_name',
                        'pm.product_image',
                        'gy.start_year',
                        'gy.end_year',
                        'py.part_type_label'
                    )
                    ->where(function ($query) use ($combinations) {
                        foreach ($combinations as $combo) {
                            $query->orWhere(function ($q) use ($combo) {
                                $q->where('p.brand_id', $combo->brand_id)
                                ->where('p.model_id', $combo->model_id)
                                ->where('p.generation_id', $combo->generation_id)
                                ->where('p.category_id', $combo->category_id)
                                ->where('p.subcategory_id', $combo->subcategory_id)
                                ->where('p.variant_id', $combo->variant_id);
                            });
                        }
                    })
                    ->where('p.id', '!=', $product->id) // Exclude current product
                    ->get();
            }
        }

        //Fallback: If no group or similar found, use loose match
        if ($similarProducts->isEmpty()) 
        {
            $similarProducts = DB::table('product as p')
                ->leftJoin('brand as b', 'b.id', '=', 'p.brand_id')
                ->leftJoin('make_model as m', 'm.id', '=', 'p.model_id')
                ->leftJoin('category as c', 'c.id', '=', 'p.category_id')
                ->leftJoin('subcategory as s', 's.id', '=', 'p.subcategory_id')
                ->leftJoin('users as u', 'u.id', '=', 'p.seller_id')
                ->leftJoin('master_country as co', 'co.country_id', '=', 'u.country_id')
                ->leftJoin('master_state as st', 'st.state_id', '=', 'u.state_id')
                ->leftJoin('product_img as pm', 'pm.product_id', '=', 'p.id')
                ->leftJoin('generation_year as gy', 'gy.id', '=', 'p.generation_id')
                ->leftJoin('part_type as py', 'py.id', '=', 'p.part_type_id')
                ->leftJoin('shop_detail as sd', 'sd.user_id', '=', 'u.id')
                ->select(
                    'p.*',
                    'b.brand_name',
                    'm.model_name',
                    'c.category_name',
                    's.subcat_name',
                    'u.first_name',
                    'u.last_name',
                    'u.mobile',
                    'sd.shop_name',
                    'u.id as seller_id',
                    'co.country_name',
                    'st.state_name',
                    'pm.product_image',
                    'gy.start_year',
                    'gy.end_year',
                    'py.part_type_label'
                )
                ->where('p.id', '!=', $product->id)
                ->where('p.brand_id', $product->brand_id)
                ->where('p.model_id', $product->model_id)
                ->where('p.category_id', $product->category_id)
                ->limit(6)
                ->get();
        }
        */
        return view('web.product_detail',compact('product','user','shop','brand','model','category','subcategory','country','service','city'));
    }
    public function productListOld(Request $request)
    {
        //This is for ajax function to show product list as per filters

        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();

        $models = DB::table('make_model')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->brand_id, fn($q) => $q->where('brand_id', $request->brand_id))
                ->get();
        
        $generation = DB::table('generation_year')
                ->where('is_deleted', 0)
                ->when($request->model_id, fn($q) => $q->where('model_id', $request->model_id))
                ->get();        

        $subcategory = DB::table('subcategory')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->get();


        $query = Product::query()
                        ->Join('users', 'users.id', '=', 'product.seller_id')
                        ->leftJoin('master_country', 'master_country.country_id', '=', 'users.country_id')
                        ->leftJoin('master_state', 'master_state.state_id', '=', 'users.state_id')
                        ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')

                        ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                        ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
                        ->leftJoin('category', 'category.id', '=', 'product.category_id')
                        ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
                        ->leftJoin('product_img', 'product_img.product_id', '=', 'product.id')
                        ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
                        ->where('product.is_deleted', 0)
                        ->where('product.is_active', 1);

        // Filters
        if ($request->brand_id!='') {
            $query->where('product.brand_id', $request->brand_id);
        }
        if ($request->model_id !='') {
            $query->where('product.model_id', $request->model_id);
        }
        if ($request->category_id!='') {
            $query->where('product.category_id', $request->category_id);
        }
        if ($request->subcategory_id!='') {
            $query->where('product.subcategory_id', $request->subcategory_id);
        }
        if($request->sort!='')
        {
             $query->where('product.product_type', $request->sort);
        }
        $query->distinct('product.id');
        
        // Important: select necessary columns only, avoid ambiguous column names
        $query->select(
                    'product.id','product_img.product_image',
                    'brand.brand_name as brand_name',
                    'make_model.model_name as model_name',
                    'category.category_name as category_name',
                    'subcategory.subcat_name as subcategory_name',
                    'product.is_active','product.product_type','product.product_price','product.seller_id',
                    'users.first_name','users.last_name','master_city.city_name','master_country.country_name','master_state.state_name',
                    'generation_year.start_year','generation_year.end_year','users.mobile'
                );
        $products = $query->inRandomOrder()->paginate(9);

        if ($request->ajax()) {
            return view('web.partial_product_list', compact('products'))->render();
        }        
        return view('web.listing',compact('products','brand','category','models','subcategory','generation'));
    }
    public function shopDetail(Request $request,$city, $shop)
    {
        //This function is for show shop detail
       $locale = App::getLocale();

        $detail  = DB::table('users')
                        ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
                        ->leftJoin('shop_detail', 'shop_detail.user_id', '=', 'users.id')
                        ->where('shop_detail.shop_name', '=' , $shop)
                        ->where('master_city.city_name', '=' , $city)
                        ->select('users.*')
                        ->first();
        if(!$detail)
        {
             return redirect()->back()->with('error', 'Shop not found.'); 
        }
        $id=$detail->id;

        $brands=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $brand = $brands->map(function ($item) use ($locale) {
            $item->brand_name = $locale == 'ar' ? $item->ar_brand_name : $item->brand_name;
            return $item;
        });

        $categorys=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $category = $categorys->map(function ($item) use ($locale) {
            $item->category_name = $locale == 'ar' ? $item->ar_category_name : $item->category_name;
            return $item;
        });

        $models = DB::table('make_model')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->brand_id, fn($q) => $q->where('brand_id', $request->brand_id))
                ->get();
        $model = $models->map(function ($item) use ($locale) {
            $item->model_name = $locale == 'ar' ? $item->ar_model_name : $item->model_name;
            return $item;
        });

        $subcategorys = DB::table('subcategory')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->get();
        $subcategory = $subcategorys->map(function ($item) use ($locale) {
            $item->subcat_name = $locale == 'ar' ? $item->ar_subcat_name : $item->subcat_name;
            return $item;
        });

        $make_year=DB::table('make_year')->where('is_active',1)->where('is_deleted',0)->orderby('id','desc')->get();
        $country=DB::table('master_country')->get();

        $citys=DB::table('master_city')->join('master_country','master_country.country_id','=','master_city.country_id')->where('master_country.country_status',1)->where('master_city.city_status',1)->get();
        $city = $citys->map(function ($item) use ($locale) {
            $item->city_name = $locale == 'ar' ? $item->city_name_ar : $item->city_name;
            return $item;
        });

        $user  = DB::table('users')->where('id', '=' , $id)
                        ->leftJoin('master_country', 'master_country.country_id', '=', 'users.country_id')
                        //->leftJoin('master_state', 'master_state.state_id', '=', 'users.state_id')
                        ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
                        ->select(
                                'users.*',
                                'master_city.city_name','master_country.country_name'
                            )
                        ->first();
        $shop  = DB::table('shop_detail')->where('user_id', '=' , $id)->first();
        $service = DB::table('seller_service')
                        ->where('seller_id', $id)
                        ->join('services','services.id','=','seller_service.service_id')->get();
        $shop_url = url('/shop/').'/'.$user->city_name.'/'. $shop->shop_name;     
                               
        // $query = Product::query()
        //                 ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
        //                 ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
        //                 ->leftJoin('category', 'category.id', '=', 'product.category_id')
        //                 ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
        //                 ->leftJoin('product_img', 'product_img.product_id', '=', 'product.id')
        //                 ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
        //                 ->where('product.seller_id', $id)
        //                 ->where('product.is_deleted', 0)
        //                 ->where('product.is_active', 1);

        
        // $query->distinct('product.id');
        
        // // Important: select necessary columns only, avoid ambiguous column names
        // $query->select(
        //             'product.id','product_img.product_image',
        //             'brand.brand_name as brand_name',
        //             'make_model.model_name as model_name',
        //             'category.category_name as category_name',
        //             'subcategory.subcat_name as subcategory_name',
        //             'product.is_active','product.product_type','product.product_price','product.seller_id',
        //             'generation_year.start_year','generation_year.end_year'
        //         );
        // $products = $query->inRandomOrder()->paginate(9);

        return view('web.shop_detail',compact('brand','model','category','subcategory','make_year','country','user','shop','city','service','shop_url'));
    }
    public function digitalCard($id)
    {
        $user  = DB::table('users')->where('id', '=' , $id)
                        ->leftJoin('master_country', 'master_country.country_id', '=', 'users.country_id')
                        //->leftJoin('master_state', 'master_state.state_id', '=', 'users.state_id')
                        ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
                        ->select(
                                'users.*',
                                'master_city.city_name','master_country.country_name'
                            )
                        ->first();
        $shop  = DB::table('shop_detail')->where('user_id', '=' , $id)->first();

        $qrFile = public_path('uploads/qr_code/' . $shop->qr_code); 
        $qrBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($qrFile));

        $html = view('admin.seller_card', [
            'shop' => $shop,
            'user'  => $user,
            'qrPath' => $qrBase64,
        ])->render();
        
        $fileName = 'card_' . $shop->id . '_' . Str::random(8) . '.png';
        $destinationPath = public_path('uploads/business_card');

        $fullPath = $destinationPath . '/' . $fileName;

        Browsershot::html($html)
            ->windowSize(530, 300)
            ->waitUntilNetworkIdle()
            ->save($fullPath);

        // 6. Update business table with filename
        // $business->card_image = $fileName;
        // $business->save();

        // 7. Return success or download (optional)
        return response()->json([
            'message' => 'Card generated and saved successfully.',
            'image_url' => asset('uploads/business_card/' . $fileName),
        ]);

        //return view('admin.seller_card',compact('user','shop'));
    }

    public function sellerMiniPage(Request $request, $id)
    {
        //This function is for seller minipage
        $locale = App::getLocale();
        $brands=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $brand = $brands->map(function ($item) use ($locale) {
            $item->brand_name = $locale == 'ar' ? $item->ar_brand_name : $item->brand_name;
            return $item;
        });

        $categorys=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $category = $categorys->map(function ($item) use ($locale) {
            $item->category_name = $locale == 'ar' ? $item->ar_category_name : $item->category_name;
            return $item;
        });

        $models = DB::table('make_model')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->brand_id, fn($q) => $q->where('brand_id', $request->brand_id))
                ->get();
        $model = $models->map(function ($item) use ($locale) {
            $item->model_name = $locale == 'ar' ? $item->ar_model_name : $item->model_name;
            return $item;
        });

        $subcategorys = DB::table('subcategory')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->get();
        $subcategory = $subcategorys->map(function ($item) use ($locale) {
            $item->subcat_name = $locale == 'ar' ? $item->ar_subcat_name : $item->subcat_name;
            return $item;
        });

        $make_year=DB::table('make_year')->where('is_active',1)->where('is_deleted',0)->orderby('id','desc')->get();
        $country=DB::table('master_country')->get();

        $citys=DB::table('master_city')->join('master_country','master_country.country_id','=','master_city.country_id')->where('master_country.country_status',1)->where('master_city.city_status',1)->get();
        $city = $citys->map(function ($item) use ($locale) {
            $item->city_name = $locale == 'ar' ? $item->city_name_ar : $item->city_name;
            return $item;
        });

        $user  = DB::table('users')->where('id', '=' , $id)
                        ->leftJoin('master_country', 'master_country.country_id', '=', 'users.country_id')
                        //->leftJoin('master_state', 'master_state.state_id', '=', 'users.state_id')
                        ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
                        ->select(
                                'users.*',
                                'master_city.city_name','master_country.country_name'
                            )
                        ->first();
        $shop  = DB::table('shop_detail')->where('user_id', '=' , $id)->first();

        $query = Product::query()
                        ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                        ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
                        ->leftJoin('category', 'category.id', '=', 'product.category_id')
                        ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
                        ->leftJoin('product_img', 'product_img.product_id', '=', 'product.id')
                        ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
                        ->where('product.seller_id', $id)
                        ->where('product.is_deleted', 0)
                        ->where('product.is_active', 1);

        
        //$query->distinct('product.id');
        if ($request->brand_id) {
            $query->where('product.brand_id', $request->brand_id);
        }
        if ($request->model_id) {
            $query->where('product.model_id', $request->model_id);
        }
        if ($request->category_id) {
            $query->where('product.category_id', $request->category_id);
        }
        if ($request->subcategory_id) {
            $query->where('product.subcategory_id', $request->subcategory_id);
        }
        // Important: select necessary columns only, avoid ambiguous column names
        $query->select(
                    'product.id','product.stock_number','product_img.product_image',
                    DB::raw($locale == 'ar' ? 'brand.ar_brand_name as brand_name' : 'brand.brand_name as brand_name'),
                    DB::raw($locale == 'ar' ? 'make_model.ar_model_name as model_name' : 'make_model.model_name as model_name'),
                    DB::raw($locale == 'ar' ? 'category.ar_category_name as category_name' : 'category.category_name as category_name'),
                    DB::raw($locale == 'ar' ? 'subcategory.ar_subcat_name as subcategory_name' : 'subcategory.subcat_name as subcategory_name'),
                    'product.product_note',
                    'product.is_active','product.product_type','product.product_price','product.seller_id','product.admin_product_id',
                    'generation_year.start_year','generation_year.end_year'
        );
                
        $products = $query->inRandomOrder()->paginate(9);
        $products->appends($request->except('page'));
        //echo "<pre>";print_r($products);die(); 
        $service = DB::table('seller_service')
                        ->where('seller_id', $id)
                        ->join('services','services.id','=','seller_service.service_id')->get();

        return view('web.seller_mini_page',compact('brand','model','category','subcategory','make_year','country','user','shop','products','service','city'));
    }
    
    public function register(Request $request)
    {   
        $request->validate([
            //'user_name' => 'required',
            //'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required',
            'role' => 'required',
        ]);
/*
        $useremail  = DB::table('users')->where('user_name', '=' , $request->user_name)->where('is_deleted', '=' , 0)->first();

        if(!empty($useremail))
        {
            return redirect()->back()->withErrors(["email" => "The user name has already been taken."])->withInput();
        }
*/
        $user = User::create([
           // 'user_name' => $request->user_name,
           // 'password' => Hash::make($request->password),
            'first_name' => $request->last_name,
            //'last_name' => $request->last_name,
            'country_code'=>$request->country_code,
            'mobile' => $request->mobile,
            'user_type' => $request->role === 'user' ? 1 : ($request->role === 'seller' ? 2 : null),
        ]);
        if ($user) {
            $userId = $user->id;

            DB::table('shop_detail')->insert([
                'user_id' => $userId,
                'shop_name' => $request->first_name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with(['registration_success' => 'Registration successfull']);

        } else {
         return redirect()->back()->with(['error' => 'Something went wrong']);
        }
    }

    public function sendVerificationLink($userId)
    {
        $user = User::findOrFail($userId);
        $token = Str::random(64);
        
        // Save token
        $user->verification_token = $token;
        $user->save();
    
        $link = route('verify.email', ['id' => $user->id, 'token' => $token]);
    
        $data = ['link' => $link,'name'=>$name];
        $blade = 'web.emails.registration_email'; 
        $subject = 'Verify Your Email';
    
        Mail::to($user->email)->send(new CommonMail($data, $blade, $subject));
    
        return 'Verification email sent!';
    }
    
    public function verifyEmail($id, $token)
    {
        $user = User::where('id', $id)
                    ->where('verification_token', $token)
                    ->first();
    
        if (!$user) {
            return redirect()->route('signIn')->with('error', 'Invalid verification link.');
        }
    
        // Mark as verified
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->email_verified = 1;
        $user->save();
    
        return redirect()->route('signIn')->with('success', 'Email verified! You can now log in.');
    }

    public function login(Request $request)
    {
        $request->validate([
           'user_name' => 'required',
           'password' => 'required',
        ]);
        $credentials = $request->only('user_name', 'password');
        $credentials['user_status'] = 1;
        $credentials['is_deleted'] = 0;
        if (Auth::attempt($credentials)) {
            Session::regenerate();
            $user = Auth::user();
            
            if($user->user_status===1)
            {   
                if ($user->user_type === 1) {
                    return redirect()->route('user.dashboard');
                } elseif ($user->user_type === 2) {
                    return redirect()->route('seller.dashboard');
                }else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
                }
            }
            else{
                return redirect()->back()->with(['error' => 'Account not activated']);
            }
        }else {
            return redirect()->back()->with(['error' => 'Invalid credentials or inactive account']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
    public function getIndex()
    {
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 7)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
    public function getMatrix()
    {
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 8)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
    public function getLayer()
    {
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 9)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
    public function getPrivacyPolicy()
    {
        //This funcion is for getting the privacy policy
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 1)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
    public function getTermsConditions()
    {
        //This function is for getting the terms conditions
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 2)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
    public function aboutUs()
    {
        //This function is for about us content
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 3)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
    public function contact()
    {
        //This function is for contact information
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 5)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
    public function howWorks()
    {
        //This function is for how it works
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 6)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
    public function getReference()
    {
        //This function is for how it works
        $locale = App::getLocale();
        $policya = DB::table('policy')
                ->where('policy_type', 10)
                ->where('is_deleted', 0)
                ->first();

        if ($policya) {
            $policya->policy_content = $locale === 'ar' ? $policya->policy_content_ar : $policya->policy_content;
        }
        return view('web.privacy_policy',compact('policya'));
    }
}
