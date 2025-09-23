<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use DB;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\MakeModel;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\MakeYear;
use App\Models\ProductYear;
use App\Models\AdminProduct;
use App\Models\ProductTemplate;
use App\Models\ShopDetail;
use App\Models\User;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\App;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use Auth;

class SellerDashboardController extends Controller
{
    public function createQrCode($id, $shop)
    {
        $text = route('sellerMiniPage', ['id' => $id]);

        $fileName = 'qr_' . $shop . '_' . time() . '.png';
        $filePath = public_path('uploads/qr_code/' . $fileName);

        // Ensure directory exists
        File::ensureDirectoryExists(public_path('uploads/qr_code'));

        // Build QR Code
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($text)
            ->size(300)
            ->margin(10)
            ->build();

        // Save to file
        file_put_contents($filePath, $result->getString());

        $shop = ShopDetail::where('user_id', $id)->first();
        $shop->qr_code = $fileName;
        $shop->save();

        return response()->json([
            'url' => asset('public/uploads/qr_code/' . $fileName),
            'path' => $filePath,
        ]);
    }
    
    public function index()
    {
        $locale = App::getLocale();
        $user_detail = DB::table('users')->where('id', Auth::id())->first();

        $brandNameCol = $locale === 'ar' ? 'brand.ar_brand_name' : 'brand.brand_name';
        $modelNameCol = $locale === 'ar' ? 'make_model.ar_model_name' : 'make_model.model_name';
        $categoryNameCol = $locale === 'ar' ? 'category.ar_category_name' : 'category.category_name';
        $subcategoryNameCol = $locale === 'ar' ? 'subcategory.ar_subcat_name' : 'subcategory.subcat_name';
        //$partTypeLabelCol = $locale === 'ar' ? 'part_type.ar_part_type_label' : 'part_type.part_type_label';


        $topProducts = DB::table('search_product')
            ->select(
                'search_product.product_id',
                DB::raw('COUNT(*) as total_searches'),
                DB::raw("$brandNameCol as brand_name"),
                DB::raw("$modelNameCol as model_name"),
                DB::raw("$categoryNameCol as category_name"),
                DB::raw("$subcategoryNameCol as subcategory_name"),
                'generation_year.start_year',
                'generation_year.end_year',
                'part_type.part_type_label'
            )
            ->join('product', 'search_product.product_id', '=', 'product.id')
            ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
            ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
            ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
            ->leftJoin('part_type', 'part_type.id', '=', 'product.part_type_id')
            ->where('search_product.seller_id', Auth::id())
            ->groupBy(
                'search_product.product_id',
                $brandNameCol,
                $modelNameCol,
                $categoryNameCol,
                $subcategoryNameCol,
                'generation_year.start_year',
                'generation_year.end_year',
                'part_type.part_type_label'
            )
            ->orderByDesc('total_searches')
            ->limit(10)
            ->get();

        return view('web.seller.dashboard', compact('user_detail', 'topProducts'));
    }




    public function myProfile(Request $request)
    {
        $country = DB::table('master_country')->where('country_status', 1)->get();
        $services = DB::table('services')->get();

        $user_detail = $state = $city = $shop_detail = "";
        $seller_service_ids = array();

        $user_detail = DB::table('users')->where('id', Auth::id())->first();
        //$state=DB::table('master_state')->where('state_country_id',$user_detail->country_id)->get();
        $city = DB::table('master_city')->where('country_id', $user_detail->country_id)->get();
        // $seller_service_ids = DB::table('seller_service')
        //                             ->where('seller_id', Auth::id())
        //                             ->pluck('service_id') // get only service IDs
        //                             ->toArray();
        $seller_service_ids = DB::table('seller_service')
            ->leftjoin('services', 'services.id', '=', 'seller_service.service_id')
            ->where('seller_service.seller_id', Auth::id())
            ->get();
        if ($user_detail->user_type == 2) {
            $shop_detail = DB::table('shop_detail')->where('user_id', $user_detail->id)->first();
        }

        if ($request->isMethod('post')) {
            // $useremail  = DB::table('users')->where('email', '=' , $request->email)->where('id', '!=' , Auth::id())->where('is_deleted', '=' , 0)->first();

            // if(!empty($useremail))
            // {
            //     return redirect()->back()->with(["error" => "The email has already been taken."])->withInput();
            // }



            $user = User::find(Auth::id());
            $message = "Seller profile updated successfully.";

            // if ($request->hasFile('profile_image')) {
            //     $image = $request->file('profile_image');
            //     $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
            //     $image->move(public_path('/uploads/profile_image'), $imageName);
            //     $user->profile_image = $imageName;
            // }

            $user->first_name       = $request->first_name;
            $user->mobile           = $request->mobile;
            $user->mobile_2         = $request->mobile_2;
            $user->whatsapp1        = $request->whatsapp1;
            $user->whatsapp2        = $request->whatsapp2;
            $user->latitude         = $request->latitude;
            $user->longitude        = $request->longitude;
            // $user->last_name        = $request->last_name;
            // $user->email            = $request->email;
            $user->user_name        = $request->user_name;

            if ($request->password != '') {
                $user->password         = Hash::make($request->password);
            }

            // $user->gender           = $request->gender;
            $user->country_id       = $request->country_id;
            // $user->state_id         = $request->state_id;
            $user->city_id          = $request->city_id;
            $user->address1         = $request->address1;
            $user->address2         = $request->address2;
            $user->address1_ar      = $request->address1_ar;
            $user->address2_ar      = $request->address2_ar;
            // $user->zip_code         = $request->zip_code;
            $user->user_timezone    = $request->user_time;
            $user->save();
            $user_id = $user->id;

            //$shop = ShopDetail::find(Auth::id());
            $shop = ShopDetail::where('user_id', Auth::id())->first();

            if (!$shop) {
                $shop = new ShopDetail();
            }
            $shop->user_id = $user_id;
            $shop->shop_name = $request->shop_name;
            $shop->shop_name_ar = $request->shop_name_ar;
            $shop->about_shop = $request->about_shop;
            $shop->about_shop_ar = $request->about_shop_ar;
            $shop->about_shop_fr = $request->about_shop_fr;
            $shop->about_shop_ru = $request->about_shop_ru;
            $shop->about_shop_fa = $request->about_shop_fa;
            $shop->about_shop_ur = $request->about_shop_ur;

            if ($request->hasFile('shop_logo')) {
                $image = $request->file('shop_logo');
                $imageName = random_int(1000, 9999) . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/shop_image'), $imageName);
                $shop->shop_logo = $imageName;
            }

            if ($request->hasFile('shop_banner')) {
                $image = $request->file('shop_banner');
                $imageName = random_int(1000, 9999) . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/shop_image'), $imageName);
                $shop->shop_banner = $imageName;
            }
            $shop->save();

            // $submittedServices = $request->input('service_id', []);
            // if($submittedServices)
            // {
            //     DB::table('seller_service')
            //             ->where('seller_id', Auth::id())
            //             ->whereNotIn('service_id', $submittedServices)
            //             ->delete();

            //     foreach ($submittedServices as $service_id) {
            //         DB::table('seller_service')->updateOrInsert(
            //             ['seller_id' => Auth::id(), 'service_id' => $service_id],
            //             ['created_at' => now()] // optional if you have timestamps
            //         );
            //     }
            // }
            
            $this->createQrCode($user_id, $request->shop_name);
            return redirect()->route("seller.myProfile")->with("success", $message);
        }

        return view('web.seller.my_profile', compact('country', 'user_detail', 'state', 'city', 'shop_detail', 'services', 'seller_service_ids'));
    }



    // public function myProfile(Request $request)
    // {
    //     $country=DB::table('master_country')->where('country_status',1)->get();
    //     $services=DB::table('services')->get();

    //     $user_detail= $state=$city=$shop_detail="";
    //     $seller_service_ids=array();

    //     $user_detail=DB::table('users')->where('id',Auth::id())->first();
    //     //$state=DB::table('master_state')->where('state_country_id',$user_detail->country_id)->get();
    //     $city=DB::table('master_city')->where('country_id',$user_detail->country_id)->get();
    //     // $seller_service_ids = DB::table('seller_service')
    //     //                             ->where('seller_id', Auth::id())
    //     //                             ->pluck('service_id') // get only service IDs
    //     //                             ->toArray();
    //     $seller_service_ids = DB::table('seller_service')
    //                                 ->leftjoin('services','services.id','=','seller_service.service_id')
    //                                 ->where('seller_service.seller_id', Auth::id())
    //                                 ->get();
    //     if($user_detail->user_type==2)
    //     {
    //         $shop_detail=DB::table('shop_detail')->where('user_id',$user_detail->id)->first();
    //     }

    //     if ($request->isMethod('post'))
    //     {
    //         // $useremail  = DB::table('users')->where('email', '=' , $request->email)->where('id', '!=' , Auth::id())->where('is_deleted', '=' , 0)->first();

    //         // if(!empty($useremail))
    //         // {
    //         //     return redirect()->back()->with(["error" => "The email has already been taken."])->withInput();
    //         // }

    //         $user = User::find(Auth::id());
    //         $message="Seller profile updated successfully.";

    //         // if ($request->hasFile('profile_image')) {
    //         //     $image = $request->file('profile_image');
    //         //     $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
    //         //     $image->move(public_path('/uploads/profile_image'), $imageName);
    //         //     $user->profile_image = $imageName;
    //         // }

    //         $user->first_name       = $request->first_name;
    //         $user->mobile           = $request->mobile;
    //         $user->mobile_2         = $request->mobile_2;
    //         $user->whatsapp1        = $request->whatsapp1;
    //         $user->whatsapp2        = $request->whatsapp2;
    //         // $user->latitude         = $request->latitude;
    //         // $user->longitude        = $request->longitude;
    //         // $user->last_name        = $request->last_name;
    //         // $user->email            = $request->email;
    //         $user->user_name        = $request->user_name;

    //         if($request->password!='')
    //         {
    //             $user->password         = Hash::make($request->password);
    //         }

    //         // $user->gender           = $request->gender;
    //         $user->country_id       = $request->country_id;
    //         // $user->state_id         = $request->state_id;
    //         $user->city_id          = $request->city_id;
    //         $user->address1         = $request->address1;
    //         $user->address2         = $request->address2;
    //         // $user->address1_ar      = $request->address1_ar;
    //         // $user->address2_ar      = $request->address2_ar;
    //         // $user->zip_code         = $request->zip_code;
    //         $user->user_timezone    = $request->user_time;
    //         $user->save();
    //         $user_id=$user->id;

    //         //$shop = ShopDetail::find(Auth::id());
    //         $shop = ShopDetail::where('user_id',Auth::id())->first();

    //         if (!$shop)
    //         {
    //             $shop = new ShopDetail();
    //         }
    //         $shop->user_id=$user_id;
    //         $shop->shop_name=$request->shop_name;
    //         $shop->shop_name_ar=$request->shop_name_ar;
    //         $shop->about_shop=$request->about_shop;
    //         // $shop->about_shop_ar=$request->about_shop_ar :??'';
    //         // $shop->about_shop_fr=$request->about_shop_fr;
    //         // $shop->about_shop_ru=$request->about_shop_ru;
    //         // $shop->about_shop_fa=$request->about_shop_fa;
    //         // $shop->about_shop_ur=$request->about_shop_ur;

    //         if ($request->hasFile('shop_logo')) {
    //             $image = $request->file('shop_logo');
    //             $imageName = random_int(1000, 9999). time() . '.' . $image->getClientOriginalExtension();
    //             $image->move(public_path('/uploads/shop_image'), $imageName);
    //             $shop->shop_logo = $imageName;
    //         }

    //         if ($request->hasFile('shop_banner')) {
    //             $image = $request->file('shop_banner');
    //             $imageName = random_int(1000, 9999). time() . '.' . $image->getClientOriginalExtension();
    //             $image->move(public_path('/uploads/shop_image'), $imageName);
    //             $shop->shop_banner = $imageName;
    //         }
    //         $shop->save();

    //         // $submittedServices = $request->input('service_id', []);
    //         // if($submittedServices)
    //         // {
    //         //     DB::table('seller_service')
    //         //             ->where('seller_id', Auth::id())
    //         //             ->whereNotIn('service_id', $submittedServices)
    //         //             ->delete();

    //         //     foreach ($submittedServices as $service_id) {
    //         //         DB::table('seller_service')->updateOrInsert(
    //         //             ['seller_id' => Auth::id(), 'service_id' => $service_id],
    //         //             ['created_at' => now()] // optional if you have timestamps
    //         //         );
    //         //     }
    //         // }
    //         return redirect()->route("seller.myProfile")->with("success", $message);
    //     }

    //     return view('web.seller.my_profile',compact('country','user_detail','state','city','shop_detail','services','seller_service_ids'));
    // }
    public function productList()
    {
        $locale = App::getLocale();
        $brands = DB::table('brand')->where('is_active', 1)->where('is_deleted', 0)->get();
        $brand = $brands->map(function ($item) use ($locale) {
            $item->brand_name = $locale == 'ar' ? $item->ar_brand_name : $item->brand_name;
            return $item;
        });

        //$firstBrandId = $brand->first()->id ?? null;
        $models = DB::table('make_model')->where('is_active', 1)->where('is_deleted', 0)->get();
        $model = $models->map(function ($item) use ($locale) {
            $item->model_name = $locale == 'ar' ? $item->ar_model_name : $item->model_name;
            return $item;
        });


        $categorys = DB::table('category')->where('is_active', 1)->where('is_deleted', 0)->get();
        $category = $categorys->map(function ($item) use ($locale) {
            $item->category_name = $locale == 'ar' ? $item->ar_category_name : $item->category_name;
            return $item;
        });

        $subcategorys = DB::table('subcategory')->where('is_active', 1)->where('is_deleted', 0)->get();
        $subcategory = $subcategorys->map(function ($item) use ($locale) {
            $item->subcat_name = $locale == 'ar' ? $item->ar_subcat_name : $item->subcat_name;
            return $item;
        });

        $make_year = DB::table('make_year')->where('is_active', 1)->where('is_deleted', 0)->get();

        return view('web.seller.productList', compact('brand', 'category', 'make_year', 'model', 'subcategory'));
    }
    public function getMyProduct(Request $request)
    {
        $locale = App::getLocale();
        $query = Product::query()
            ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
            ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
            ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
            ->leftJoin('part_type', 'part_type.id', '=', 'product.part_type_id')
            ->leftJoin('product_img', 'product_img.product_id', '=', 'product.id')
            ->where('product.seller_id', Auth::id())
            ->where('product.is_deleted', 0);
        // Join for year filtering if years are selected
        // if ($request->years) {
        //     $query->join('admin_product_year', 'admin_product.id', '=', 'admin_product_year.admin_product_id')
        //         ->whereIn('admin_product_year.make_year_id', $request->years);
        // }

        // Filters
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
        $query->distinct('product.id');

        // Important: select necessary columns only, avoid ambiguous column names
        $query->select(
            'product.id',
            'product.stock_number',
            DB::raw($locale == 'ar' ? 'brand.ar_brand_name as brand_name' : 'brand.brand_name as brand_name'),
            DB::raw($locale == 'ar' ? 'make_model.ar_model_name as model_name' : 'make_model.model_name as model_name'),
            DB::raw($locale == 'ar' ? 'category.ar_category_name as category_name' : 'category.category_name as category_name'),
            DB::raw($locale == 'ar' ? 'subcategory.ar_subcat_name as subcategory_name' : 'subcategory.subcat_name as subcategory_name'),
            'product.product_price',
            'product.quantity',
            'product.is_active',
            'product.product_type',
            'product_img.product_image',
            'generation_year.start_year',
            'generation_year.end_year',
            'part_type.part_type_label',
            'product.product_description'
        );
        $query->orderBy('generation_year.start_year', 'desc');


        if ($request->has('prevent')) {
            return DataTables::of(collect([]))->make(true);
        }
        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="ids[]" value="' . $row->id . '" class="selectBox">';
            })
            ->addColumn('stock', function ($row) {
                return $row->stock_number;
            })
            ->addColumn('brand', function ($row) {
                $brand = $row->brand_name ?? '';
                $model = $row->model_name ?? '';
                $generation = ($row->start_year && $row->end_year) ? "{$row->start_year} - {$row->end_year}" : '';

                return "{$brand}<br>{$model}<br>{$generation}";
            })
            ->addColumn('subcategory', function ($row) {
                $cat = $row->category_name ?? '';
                $subcat = $row->subcategory_name ?? '';
                $plab = $row->part_type_label ?? '';

                return "{$cat}<br>{$subcat}<br>{$plab}";
            })

            ->addColumn('image', function ($row) {
                $imgUrl = !empty($row->product_image)
                    ? asset('/public/uploads/product_image/' . $row->product_image)
                    : asset('/public/images/no-image.png'); // Dummy image path

                return '<div class="image-upload" data-id="' . $row->id . '">
                                                <img src="' . $imgUrl . '" class="img-thumbnail product-img" style="max-height:60px; cursor:pointer;">
                                                <input type="file" class="d-none product-img-input" accept="image/*">
                                                </div>';
            })
            ->addColumn('description', function ($row) {
                return '<input type="text" class="form-control form-control-sm update-field"
                                                    data-id="' . $row->id . '" data-field="product_description"
                                                    value="' . $row->product_description . '">';
            })
            ->addColumn('price', function ($row) {
                return '<input type="number" class="form-control form-control-sm update-field"
                                                    data-id="' . $row->id . '" data-field="product_price"
                                                    value="' . $row->product_price . '" min="0">';
            })
            ->addColumn('copyp', function ($row) {
                return '<a href="javascript:void(0)" class="copy-product" data-id="' . $row->id . '">' . __('messages.copy') . '</a>';
            })

            // ->addColumn('quantity', function($row) {
            //             return '<input type="number" class="form-control form-control-sm update-field"
            //                         data-id="'.$row->id.'" data-field="quantity"
            //                         value="'.$row->quantity.'" min="0">';
            //         })
            // ->addColumn('type', function($row) {
            //     $selected0 = $row->product_type == 0 ? 'selected' : '';
            //     $selected1 = $row->product_type == 1 ? 'selected' : '';
            //     $selected2 = $row->product_type == 2 ? 'selected' : '';
            //     $selected3 = $row->product_type == 3 ? 'selected' : '';
            //     return '<select class="product_type form-select form-select-sm" user="'.$row->id.'">
            //                 <option value="0" '.$selected0.'>Select</option>
            //                 <option value="1" '.$selected1.'>New</option>
            //                 <option value="2" '.$selected2.'>Old</option>
            //                 <option value="3" '.$selected3.'>Refurbished</option>
            //             </select>';
            // })
            // ->addColumn('status', function($row) {
            //     $selected0 = $row->is_active == 0 ? 'selected' : '';
            //     $selected1 = $row->is_active == 1 ? 'selected' : '';
            //     return '<select class="product_status form-select form-select-sm" user="'.$row->id.'">
            //                 <option value="0" '.$selected0.'>Inactive</option>
            //                 <option value="1" '.$selected1.'>Active</option>
            //             </select>';
            // })
            ->addColumn('status', function ($row) {
                $checked = $row->is_active == 1 ? 'checked' : '';
                return '<input type="checkbox" class="product_status" data-user="' . $row->id . '" ' . $checked . '>';
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('seller.viewMyProduct', ['id' => $row->id]);
                return '<a href="javascript:void(0)" class="del_product" user_id="' . $row->id . '"><i class="mdi mdi-delete"></i></a> |
                                        <a href="' . route('seller.updateMyProduct', $row->id) . '"><i class="mdi mdi-lead-pencil"></i></a> |
                                        <a href="' . $viewUrl . '"><i class="mdi mdi-eye"></i></a>';
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('brand.brand_name', 'like', "%{$search}%")
                            ->orWhere('brand.ar_brand_name', 'like', "%{$search}%")
                            ->orWhere('make_model.model_name', 'like', "%{$search}%")
                            ->orWhere('make_model.ar_model_name', 'like', "%{$search}%")
                            ->orWhere('category.category_name', 'like', "%{$search}%")
                            ->orWhere('category.ar_category_name', 'like', "%{$search}%")
                            ->orWhere('subcategory.subcat_name', 'like', "%{$search}%")
                            ->orWhere('subcategory.ar_subcat_name', 'like', "%{$search}%")
                            ->orWhere('product.product_price', 'like', "%{$search}%")
                            ->orWhere('product.quantity', 'like', "%{$search}%");
                    });
                }
            })
            ->rawColumns(['copyp', 'brand', 'subcategory', 'checkbox', 'status', 'action', 'price', 'quantity', 'type', 'description', 'image'])
            ->make(true);
    }
    public function copyProduct(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $newProduct = $product->replicate(); // clone attributes
        $newProduct->is_active = 0; // optional: keep it inactive initially
        $newProduct->copy_of_product_id = $product->id; // link to original
        $newProduct->is_active = $product->is_active;
        $newProduct->is_deleted = $product->is_deleted;

        $newProduct->created_at = date('Y-m-d H:i:s');

        // Generate unique stock number
        $lastProductWithStockNumber = Product::whereNotNull('stock_number')
            ->orderByDesc('id')
            ->first();

        if ($lastProductWithStockNumber && preg_match('/ACP(\d+)/', $lastProductWithStockNumber->stock_number, $matches)) {
            $lastNumber = (int)$matches[1];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $newProduct->stock_number = 'ACP' . str_pad($nextNumber, 9, '0', STR_PAD_LEFT);

        $newProduct->save();

        $image = ProductImage::where('product_id', $product->id)->first();
        if ($image) {
            $newImage = $image->replicate();
            $newImage->product_id = $newProduct->id;
            $newImage->save();
        }


        return response()->json(['success' => true]);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProductExport($request->all()), 'products.xlsx');
    }
    public function viewMyProduct($id)
    {
        $product_detail = DB::table('product')
            ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
            ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
            ->leftjoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
            ->leftJoin('part_type', 'part_type.id', '=', 'product.part_type_id')
            ->where('product.id', $id)
            ->select(
                'product.*',
                'brand.brand_name as brand_name',
                'make_model.model_name as model_name',
                'category.category_name as category_name',
                'part_type.part_type_label',
                'subcategory.subcat_name as subcategory_name',
                'generation_year.start_year',
                'generation_year.end_year'
            )
            ->first();


        $image = DB::table('product_img')->where('product_id', $id)->first();

        $groupIds = DB::table('interchange_product')
            ->where('brand_id', $product_detail->brand_id)
            ->where('model_id', $product_detail->model_id)
            ->where('generation_id', $product_detail->generation_id)
            ->where('category_id', $product_detail->category_id)
            ->where('subcategory_id', $product_detail->subcategory_id)
            ->pluck('group_id');

        // Step 2: Get all products in those groups, excluding the original product
        $product = DB::table('interchange_product')
            ->whereIn('interchange_product.group_id', $groupIds)
            //->where('group_product.product_id', '!=', $id)
            ->join('interchange_group', 'interchange_group.id', '=', 'interchange_product.group_id')
            //->join('product_template', 'product_template.id', '=', 'group_product.product_id')
            ->Join('brand', 'brand.id', '=', 'interchange_product.brand_id')
            ->Join('make_model', 'make_model.id', '=', 'interchange_product.model_id')
            ->Join('category', 'category.id', '=', 'interchange_product.category_id')
            ->Join('subcategory', 'subcategory.id', '=', 'interchange_product.subcategory_id')
            ->Join('generation_year', 'generation_year.id', '=', 'interchange_product.generation_id')
            ->select(
                'interchange_product.*',
                'generation_year.start_year',
                'generation_year.end_year',
                'brand.brand_name as brand_name',
                'make_model.model_name as model_name',
                'category.category_name as category_name',
                'subcategory.subcat_name as subcat_name',
                'interchange_group.group_name'
            )
            ->orderBy('interchange_product.group_id', 'asc')
            ->get();
        return view('web.seller.view_product', compact('product_detail', 'image', 'product'));
    }
    public function saveImage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'product_image' => 'required|file|mimes:jpeg,png,jpg,webp,heic,heif|max:5120',
        ]);

        $file = $request->file('product_image');
        //$filename = time().'_'.$file->getClientOriginalName();

        $filename = "pro" . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/product_image'), $filename);


        \DB::table('product_img')->updateOrInsert(
            ['product_id' => $request->product_id],     // Match condition
            ['product_image' => $filename, 'created_at' => date('Y-m-d H:i:s')],           // Data to insert/update

        );

        return response()->json([
            'message' => 'Image updated successfully',
            'image_url' => url('public/uploads/product_image/' . $filename)
        ]);
    }
    public function updateProductField(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'field' => 'required|in:product_price,quantity,product_description',
            'value' => 'required'
        ]);
        if (in_array($request->field, ['product_price', 'quantity'])) {
            $request->validate(['value' => 'numeric|min:0']);
        }

        \DB::table('product')
            ->where('id', $request->product_id)
            ->update([$request->field => $request->value]);

        return response()->json(['message' => 'Updated successfully']);
    }


    public function addProduct(Request $request, $id = null)
    {
        //This function is for add/update admin product

        $product_detail = $subcategory = $model = $image = "";
        $selectedYearIds = array();

        $brand = DB::table('brand')->where('is_active', 1)->where('is_deleted', 0)->get();
        $category = DB::table('category')->where('is_active', 1)->where('is_deleted', 0)->get();
        $make_year = DB::table('make_year')->where('is_active', 1)->where('is_deleted', 0)->get();

        if ($id != null) {
            $product_detail = DB::table('product')->where('id', $id)->first();
            $subcategory = DB::table('subcategory')->where('id', $product_detail->subcategory_id)->get();
            $model = DB::table('make_model')->where('id', $product_detail->model_id)->get();
            $image = DB::table('product_img')->where('product_id', $id)->first();
            $selectedYearIds = ProductYear::where('product_id', $id)->pluck('make_year_id')->toArray();
        }
        if ($request->isMethod('post')) {
            if ($request->product_id) {
                $chkproduct  = DB::table('product')->where('brand_id', '=', $request->brand_id)
                    ->where('model_id', '=', $request->model_id)
                    ->where('category_id', '=', $request->category_id)
                    ->where('subcategory_id', '=', $request->subcategory_id)
                    ->where('seller_id', '=', Auth::id())
                    ->where('id', '!=', $request->product_id)->where('is_deleted', '=', 0)->first();

                if (!empty($chkproduct)) {
                    return redirect()->back()->with(["error" => "This product has already been added."])->withInput();
                }
            } else {
                $chkproduct  = DB::table('product')->where('brand_id', '=', $request->brand_id)
                    ->where('model_id', '=', $request->model_id)
                    ->where('category_id', '=', $request->category_id)
                    ->where('subcategory_id', '=', $request->subcategory_id)
                    ->where('seller_id', '=', Auth::id())
                    ->where('is_deleted', '=', 0)->first();

                if (!empty($chkproduct)) {
                    return redirect()->back()->with(["error" => "This product has already been added."])->withInput();
                }
            }

            $product = Product::find($request->product_id);
            $message = "Product updated successfully.";
            if (!$product) {
                $product = new Product();

                $product->brand_id         = $request->brand_id;
                $product->model_id         = $request->model_id;
                $product->category_id      = $request->category_id;
                $product->subcategory_id   = $request->subcategory_id;
                $product->created_at       = date('Y-m-d H:i:s');
                $product->seller_id        = Auth::id();

                $message = "Product added successfully.";
            }

            $product->product_note          = $request->product_note;
            $product->quantity              = $request->quantity;
            $product->product_price         = $request->product_price;
            $product->product_type          = $request->product_type;
            $product->product_description   = $request->product_description;
            $product->product_milage        = $request->product_milage;
            $product->save();
            $product_id = $product->id;

            if ($request->hasFile('product_image')) {
                $image = $request->file('product_image');
                $imageName = "prd" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/product_image'), $imageName);


                $productimg = ProductImage::find($request->product_image_id);

                if (!$productimg) {
                    $productimg = new ProductImage();
                    $productimg->product_id = $product_id;
                }
                $productimg->product_image = $imageName;
                $productimg->save();
            }

            return redirect()->route("seller.productList")->with("success", $message);
        }

        return view('web.seller.addProduct', compact('brand', 'category', 'product_detail', 'make_year', 'subcategory', 'model', 'selectedYearIds', 'image'));
    }

    public function updateMyProduct(Request $request, $id)
    {
        //This function is for update the seller product
        $product_detail = $subcategory = $model = $image = "";
        $selectedYearIds = array();

        $brand = DB::table('brand')->where('is_active', 1)->where('is_deleted', 0)->get();
        $category = DB::table('category')->where('is_active', 1)->where('is_deleted', 0)->get();
        $make_year = DB::table('make_year')->where('is_active', 1)->where('is_deleted', 0)->get();

        if ($id != null) {
            $product_detail = Product::query()
                ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
                ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
                ->leftJoin('category', 'category.id', '=', 'product.category_id')
                ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
                ->leftjoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
                ->leftjoin('part_type', 'part_type.id', '=', 'product.part_type_id')
                ->where('product.id', $id)
                ->select(
                    'product.*',
                    'brand.brand_name as brand_name',
                    'make_model.model_name as model_name',
                    'category.category_name as category_name',
                    'subcategory.subcat_name as subcategory_name',
                    'generation_year.start_year',
                    'generation_year.end_year',
                    'part_type.part_type_label'
                )->first();

            $image = DB::table('product_img')->where('product_id', $id)->first();
        }

        return view('web.seller.editProduct', compact('product_detail', 'image'));
    }

    public function deleteProduct(Request $request)
    {
        //This function is for delete the policy
        $product = Product::find($request->product_id);
        $product->is_deleted = 1;
        $product->save();
    }
    public function updateProductStatus(Request $request)
    {
        $user = Product::find($request->product_id);
        $user->is_active = $request->status;
        $user->save();
    }
    public function updateProductType(Request $request)
    {
        $user = Product::find($request->product_id);
        $user->product_type = $request->product_type;
        $user->save();
    }
    public function bulkDeleteproduct(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No product selected.');
        }

        Product::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected product deleted successfully.');
    }
    public function productMaster()
    {
        $product = '';
        $brand = DB::table('brand')->where('is_active', 1)->where('is_deleted', 0)->get();
        //$firstBrandId = $brand->first()->id ?? null;
        $model = DB::table('make_model')->where('is_active', 1)->where('is_deleted', 0)->get();
        $category = DB::table('category')->where('is_active', 1)->where('is_deleted', 0)->get();
        $subcategory = DB::table('subcategory')->where('is_active', 1)->where('is_deleted', 0)->get();
        $mparents = DB::table('mparents')->get();

        return view('web.seller.bulk_product', compact('product', 'brand', 'category', 'model', 'subcategory', 'mparents'));
    }

    public function getProduct(Request $request)
    {
        $query = ProductTemplate::query()
            ->leftJoin('brand', 'brand.id', '=', 'product_template.brand_id')
            ->leftJoin('make_model', 'make_model.id', '=', 'product_template.make_model_id')
            ->leftJoin('category', 'category.id', '=', 'product_template.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'product_template.subcategory_id')
            ->leftJoin('generation_year', 'generation_year.id', '=', 'product_template.generation_id')
            ->leftJoin('part_type', 'part_type.product_temp_id', '=', 'product_template.id')
            ->leftJoin('mparent_brand', 'mparent_brand.brand_id', '=', 'brand.id');

        // Filters
        if ($request->parent_id) {
            $query->where('mparent_brand.mparents_id', $request->parent_id);
        }
        if ($request->brand_id) {
            $query->where('product_template.brand_id', $request->brand_id);
        }
        if ($request->model_id) {
            $query->where('product_template.make_model_id', $request->model_id);
        }
        if ($request->category_id) {
            $query->where('product_template.category_id', $request->category_id);
        }
        if ($request->subcategory_id) {
            $query->where('product_template.subcategory_id', $request->subcategory_id);
        }
        if ($request->generation) {
            $query->where('product_template.generation_id', $request->generation);
        }
        if ($request->part) {
            $query->where('part_type.id', $request->part);
        }

        $query->distinct('product_template.id');

        // Important: select necessary columns only, avoid ambiguous column names
        $query->select(
            'product_template.id',
            'brand.brand_name as brand_name',
            'make_model.model_name as model_name',
            'category.category_name as category_name',
            'subcategory.subcat_name as subcategory_name',
            'generation_year.start_year',
            'generation_year.end_year',
            'part_type.part_type_label',
            'part_type.id as part_type_id'
        );

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="product-check" data-product-id="' . $row->id . '" data-part-type-id="' . $row->part_type_id . '">';
            })
            ->addColumn('brand', fn($row) => $row->brand_name ?? '')
            ->addColumn('model', fn($row) => $row->model_name ?? '')
            ->addColumn('generation', fn($row) => $row->start_year . ' - ' . $row->end_year ?? '')
            ->addColumn('product', fn($row) => $row->category_name ?? '')
            ->addColumn('subcategory', fn($row) => $row->subcategory_name ?? '')
            ->addColumn('variation', fn($row) => $row->part_type_label ?? '')
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('brand.brand_name', 'like', "%{$search}%")
                            ->orWhere('make_model.model_name', 'like', "%{$search}%")
                            ->orWhere('category.category_name', 'like', "%{$search}%")
                            ->orWhere('subcategory.subcat_name', 'like', "%{$search}%")
                            ->orWhere('part_type.part_type_label', 'like', "%{$search}%");
                    });
                }
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    public function saveSelection(Request $request)
    {
        // Allow longer execution time (5 minutes)
        set_time_limit(3000);

        $request->validate(['products' => 'required|array']);

        // Process in chunks of 50 products to reduce memory/time load
        $chunks = array_chunk($request->products, 500);

        foreach ($chunks as $chunk) {
            foreach ($chunk as $item) {

                $adminProductId   = $item['product_id'];
                $part_type_id     = $item['part_type_id'];

                // Get the admin product template
                $adminProduct = ProductTemplate::find($adminProductId);
                if (!$adminProduct) {
                    //admin product not found
                    continue;
                }

                // Skip if already copied for this seller
                $exists = Product::where('admin_product_id', $adminProductId)
                    ->where('generation_id', $adminProduct->generation_id)
                    ->where('part_type_id', $part_type_id)
                    ->where('is_deleted', 0)
                    ->where('seller_id', auth()->id())
                    ->exists();

                if ($exists) {
                    //check if product is already exist
                    continue;
                }




                // Insert new product record
                $product = Product::create([
                    'seller_id'         => auth()->id(),
                    'admin_product_id'  => $adminProduct->id,
                    'brand_id'          => $adminProduct->brand_id,
                    'model_id'          => $adminProduct->make_model_id,
                    'category_id'       => $adminProduct->category_id,
                    'subcategory_id'    => $adminProduct->subcategory_id,
                    'generation_id'     => $adminProduct->generation_id,
                    'part_type_id'      => $part_type_id,
                    'product_note'          => $request->product_note,
                    'product_description'   => $request->product_description,
                    'product_milage'        => $request->product_milage,
                    'is_active'         => 1,
                    'is_deleted'        => 0,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                // Batch insert product years
                /*
                $yearIds = DB::table('admin_product_year')
                    ->where('admin_product_id', $adminProduct->id)
                    ->pluck('make_year_id');

                $yearsData = [];
                foreach ($yearIds as $yearId) {
                    $yearsData[] = [
                        'product_id' => $product->id,
                        'make_year_id' => $yearId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($yearsData)) {
                    DB::table('product_year')->insert($yearsData);
                }
    */
                /*
                $generationYears = DB::table('generation_year')
                                    ->where('brand_id', $adminProduct->brand_id)
                                    ->where('model_id', $adminProduct->make_model_id)
                                    ->get();

                $generationInsertData = [];

                foreach ($generationYears as $gy) {
                    $generationInsertData[] = [
                        'seller_id' => auth()->id(),
                        'brand_id'   => $gy->brand_id,
                        'model_id'   => $gy->model_id,
                        'start_year' => $gy->start_year,
                        'end_year'   => $gy->end_year,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($generationInsertData)) {
                    DB::table('generation_product')->insertOrIgnore($generationInsertData);
                }
                */
                // Batch insert product images
                $images = DB::table('admin_product_img')
                    ->where('admin_product_id', $adminProduct->id)
                    ->get();

                $imagesData = [];
                foreach ($images as $image) {
                    $imagesData[] = [
                        'product_id' => $product->id,
                        'product_image' => $image->product_image,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($imagesData)) {
                    DB::table('product_img')->insert($imagesData);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Products saved successfully']);
    }

    //show all catalogue to seller and then it will add to his product list
    public function getAllProduct()
    {
        $locale = App::getLocale();
        $product = '';

        $brands = DB::table('brand')->where('is_active', 1)->where('is_deleted', 0)->get();
        $brand = $brands->map(function ($item) use ($locale) {
            $item->brand_name = $locale == 'ar' ? $item->ar_brand_name : $item->brand_name;
            return $item;
        });

        //$firstBrandId = $brand->first()->id ?? null;
        $models = DB::table('make_model')->where('is_active', 1)->where('is_deleted', 0)->get();
        $model = $models->map(function ($item) use ($locale) {
            $item->model_name = $locale == 'ar' ? $item->ar_model_name : $item->model_name;
            return $item;
        });

        $categorys = DB::table('category')->where('is_active', 1)->where('is_deleted', 0)->get();
        $category = $categorys->map(function ($item) use ($locale) {
            $item->category_name = $locale == 'ar' ? $item->ar_category_name : $item->category_name;
            return $item;
        });

        $subcategorys = DB::table('subcategory')->where('is_active', 1)->where('is_deleted', 0)->get();
        $subcategory = $subcategorys->map(function ($item) use ($locale) {
            $item->subcat_name = $locale == 'ar' ? $item->ar_subcat_name : $item->subcat_name;
            return $item;
        });

        $mparents = DB::table('mparents')->get();

        return view('web.seller.all_product', compact('product', 'brand', 'category', 'model', 'subcategory', 'mparents'));
    }



    public function getCatalogueProduct(Request $request)
    {
        $locale = App::getLocale();
        $sellerId = Auth::id();

        // Step 1: Get all products seller already selected
        $sellerProducts = DB::table('product')
            ->select(
                'product.admin_product_id',
                'product.part_type_id',
                'product.id as seller_product_id',
                'product.product_price',
                'product.is_active',
                'product.product_description',
                'product_img.product_image'
            )
            ->leftJoin('product_img', 'product_img.product_id', '=', 'product.id')
            ->where('product.seller_id', $sellerId)
            ->where('product.is_deleted', 0)
            ->get()
            ->keyBy(function ($item) {
                return $item->admin_product_id . '-' . $item->part_type_id;
            });


        // Step 2: Prepare product template query
        $query = ProductTemplate::query()
            ->leftJoin('brand', 'brand.id', '=', 'product_template.brand_id')
            ->leftJoin('make_model', 'make_model.id', '=', 'product_template.make_model_id')
            ->leftJoin('category', 'category.id', '=', 'product_template.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'product_template.subcategory_id')
            ->leftJoin('generation_year', 'generation_year.id', '=', 'product_template.generation_id')
            ->leftJoin('part_type', 'part_type.product_temp_id', '=', 'product_template.id')
            ->leftJoin('mparent_brand', 'mparent_brand.brand_id', '=', 'brand.id')
            ->leftJoin('admin_product_img', 'admin_product_img.admin_product_id', '=', 'product_template.id');

        // Step 3: Apply filters
        if ($request->parent_id) {
            $query->where('mparent_brand.mparents_id', $request->parent_id);
        }
        if ($request->brand_id) {
            $query->where('product_template.brand_id', $request->brand_id);
        }
        if ($request->model_id) {
            $query->where('product_template.make_model_id', $request->model_id);
        }
        if ($request->category_id) {
            $query->where('product_template.category_id', $request->category_id);
        }
        if ($request->subcategory_id) {
            $query->where('product_template.subcategory_id', $request->subcategory_id);
        }
        if ($request->generation) {
            $query->where('product_template.generation_id', $request->generation);
        }
        if ($request->part) {
            $query->where('part_type.id', $request->part);
        }

        //$query->distinct('product_template.id');

        // Step 4: Select required columns
        $query->select(
            'product_template.id',
            'product_template.product_description',
            DB::raw($locale == 'ar' ? 'brand.ar_brand_name as brand_name' : 'brand.brand_name as brand_name'),
            DB::raw($locale == 'ar' ? 'make_model.ar_model_name as model_name' : 'make_model.model_name as model_name'),
            DB::raw($locale == 'ar' ? 'category.ar_category_name as category_name' : 'category.category_name as category_name'),
            DB::raw($locale == 'ar' ? 'subcategory.ar_subcat_name as subcategory_name' : 'subcategory.subcat_name as subcategory_name'),
            'generation_year.start_year',
            'generation_year.end_year',
            'part_type.part_type_label',
            'part_type.id as part_type_id',
            'admin_product_img.product_image'
        );
        $query->orderBy('generation_year.start_year', 'desc');

        // Step 5: DataTable response
        return DataTables::of($query)
            ->addIndexColumn()
            // Brand, Model, etc.
            ->addColumn('brand', function ($row) {
                $brand = $row->brand_name ?? '';
                $model = $row->model_name ?? '';
                $generation = ($row->start_year && $row->end_year) ? "{$row->start_year} - {$row->end_year}" : '';

                return trim("{$brand} <br>{$model} <br>{$generation}");
            })
            ->addColumn('subcategory', function ($row) {
                $cat = $row->category_name ?? '';
                $subcat = $row->subcategory_name ?? '';
                $plab = $row->part_type_label ?? '';

                return trim("{$cat} <br>{$subcat} <br>{$plab}");
            })

            // Image column
            ->addColumn('image', function ($row) use ($sellerProducts) {
                $key = $row->id . '-' . $row->part_type_id;
                $image = $sellerProducts[$key]->product_image ?? null;
                $imgUrl = $image
                    ? asset('/public/uploads/product_image/' . $image) : ($row->product_image ? asset('/public/uploads/product_image/' . $row->product_image) : asset('/public/web_assets/images/no_image.png'));

                $disabledClass = !isset($sellerProducts[$key]) ? 'disabled-image' : '';
                $sellerProductId = $sellerProducts[$key]->seller_product_id ?? '';

                return '<div class="image-upload ' . $disabledClass . '" data-id="' . $sellerProductId . '">
                            <img src="' . $imgUrl . '" class="img-thumbnail product-img" style="max-height:60px; cursor:pointer;">
                            <input type="file" class="d-none product-img-input" accept="image/*">
                        </div>';
            })
            ->addColumn('description', function ($row) use ($sellerProducts) {
                $key = $row->id . '-' . $row->part_type_id;
                $disabled = !isset($sellerProducts[$key]) ? 'disabled' : '';
                $value = $sellerProducts[$key]->product_description ?? '';
                $sellerProductId = $sellerProducts[$key]->seller_product_id ?? '';

                return '<input type="text" class="form-control form-control-sm update-field"
                            data-id="' . $sellerProductId . '"
                            data-field="product_description"
                            value="' . $value . '"
                            min="0"
                            ' . $disabled . '>';
            })
            // Price input column
            ->addColumn('price', function ($row) use ($sellerProducts) {
                $key = $row->id . '-' . $row->part_type_id;
                $disabled = !isset($sellerProducts[$key]) ? 'disabled' : '';
                $value = $sellerProducts[$key]->product_price ?? 0;
                $sellerProductId = $sellerProducts[$key]->seller_product_id ?? '';

                return '<input type="number" class="form-control form-control-sm update-field"
                            data-id="' . $sellerProductId . '"
                            data-field="product_price"
                            value="' . $value . '"
                            min="0"
                            ' . $disabled . '>';
            })
            ->addColumn('available', function ($row) use ($sellerProducts) {
                $key = $row->id . '-' . $row->part_type_id;
                $checked = isset($sellerProducts[$key]) ? 'checked' : '';
                $sellerProductId = $sellerProducts[$key]->seller_product_id ?? '';

                return '<input type="checkbox" class="product-check"
                            data-product-id="' . $row->id . '"
                            data-part-type-id="' . $row->part_type_id . '"
                            data-seller-product-id="' . $sellerProductId . '"
                            ' . $checked . '>';
            })

            // Global search filter
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('brand.brand_name', 'like', "%{$search}%")
                            ->orWhere('brand.ar_brand_name', 'like', "%{$search}%")
                            ->orWhere('make_model.model_name', 'like', "%{$search}%")
                            ->orWhere('make_model.ar_model_name', 'like', "%{$search}%")
                            ->orWhere('category.category_name', 'like', "%{$search}%")
                            ->orWhere('category.ar_category_name', 'like', "%{$search}%")
                            ->orWhere('subcategory.subcat_name', 'like', "%{$search}%")
                            ->orWhere('subcategory.ar_subcat_name', 'like', "%{$search}%")
                            ->orWhere('part_type.part_type_label', 'like', "%{$search}%");
                    });
                }
            })

            ->rawColumns(['brand', 'subcategory', 'available', 'image', 'price', 'description'])
            ->make(true);
    }


    public function addSellerProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',       // admin_product_id
            'part_type_id' => 'nullable|integer',
        ]);

        $sellerId = Auth::id();

        // Prevent duplicate insert
        $existing = Product::where('admin_product_id', $request->product_id)
            ->where('seller_id', $sellerId)
            ->where('is_deleted', 0)
            ->where('part_type_id', $request->part_type_id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already exists.']);
        }

        // Fetch product_template details
        $template = ProductTemplate::find($request->product_id);

        if (!$template) {
            return response()->json(['error' => 'Product template not found'], 404);
        }


        // Insert new seller product
        $product = new Product();
        $product->seller_id = $sellerId;
        $product->admin_product_id = $template->id;
        $product->part_type_id = $request->part_type_id;
        $product->brand_id = $template->brand_id;
        $product->model_id = $template->make_model_id;
        $product->category_id = $template->category_id;
        $product->subcategory_id = $template->subcategory_id;
        $product->generation_id = $template->generation_id;
        $product->product_price = 0;
        $product->is_active = 1;
        $product->created_at = now();

        //assign the stock number
        $lastProduct = Product::whereNotNull('stock_number')
            ->orderByDesc('id')
            ->first();

        if ($lastProduct && preg_match('/ACP(\d+)/', $lastProduct->stock_number, $matches)) {
            $lastNumber = (int)$matches[1];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $product->stock_number = 'ACP' . str_pad($nextNumber, 9, '0', STR_PAD_LEFT);

        $product->save();

        //now save template image
        $images = DB::table('admin_product_img')
            ->where('admin_product_id', $template->id)
            ->first();

        $imagesData = [];
        if ($images) {
            $imagesData = [
                'product_id' => $product->id,
                'product_image' => $images->product_image,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (!empty($imagesData)) {
                DB::table('product_img')->insert($imagesData);
            }
        }

        return response()->json([
            'message' => 'Product added successfully',
            'seller_product_id' => $product->id,
        ]);
    }
    public function removeSellerProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'part_type_id' => 'nullable|integer',
        ]);

        $sellerId = Auth::id();

        $deleted = Product::where('admin_product_id', $request->product_id)
            ->where('seller_id', $sellerId)
            ->where('part_type_id', $request->part_type_id)
            ->delete();

        return response()->json([
            'message' => $deleted ? 'Product removed' : 'Not found',
        ]);
    }

    public function updateSellerProductField(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'field' => 'required|string',
            'value' => 'required',
        ]);

        $sellerId = Auth::id();

        $product = Product::where('id', $request->product_id)
            ->where('seller_id', $sellerId)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $allowedFields = ['product_description', 'product_price', 'is_active', 'product_image'];

        if (!in_array($request->field, $allowedFields)) {
            return response()->json(['error' => 'Invalid field'], 400);
        }

        $product->{$request->field} = $request->value;
        $product->save();

        return response()->json(['message' => 'Field updated']);
    }
    public function savesPImage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'product_image' => 'required|file|mimes:jpeg,png,jpg,webp,heic,heif|max:5120',
        ]);

        $sellerId = Auth::id();

        // Get the seller product ID
        $product = Product::where('id', $request->product_id)
            ->where('seller_id', $sellerId)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Seller product not found'], 404);
        }

        $imageName = uniqid() . '.' . $request->product_image->extension();
        $request->product_image->move(public_path('uploads/product_image'), $imageName);

        // Store or update product_img entry
        ProductImage::updateOrCreate(
            ['product_id' => $product->id],
            ['product_image' => $imageName]
        );

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image_url' => asset('public/uploads/product_image/' . $imageName),
        ]);
    }

    public function addSellerRequest(Request $request)
    {
        if ($request->isMethod('post')) {
            $count = DB::table('seller_request')->count();
            $new_request = sprintf('%010d', $count + 1);

            if ($request->request_for == 'car') {
                DB::table('seller_request')->insert([
                    'seller_id'     => $request->user_id,
                    'request_id'    => $new_request,
                    'make'          => $request->brand,
                    'model'         => $request->model,
                    'generation'    => $request->generation,
                    'is_car'        => $request->request_for == 'car' ? 1 : 0,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);
                $message = "Car Request added ";
            }

            if ($request->request_for == 'part') {
                DB::table('seller_request')->insert([
                    'seller_id'     => $request->user_id,
                    'request_id'    => $new_request,
                    'category'      => $request->category,
                    'subcategory'   => $request->subcategory,
                    'is_car'        => $request->request_for == 'car' ? 1 : 0,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);
                $message = "Part Request added ";
            }
            return redirect()->route("seller.myRequest")->with("success", $message);
        }
        return view('web.seller.seller_request');
    }
    public function myRequest()
    {
        //This function is for list of seller request
        $sellreques = DB::table('seller_request')
            ->join('users', 'users.id', '=', 'seller_request.seller_id')
            ->where('seller_request.seller_id', Auth::id())
            ->select('seller_request.*', 'users.user_name', 'users.first_name', 'users.last_name')
            ->orderBy('seller_request.id', 'DESC')
            ->get();
        return view('web.seller.request_list', compact('sellreques'));
    }
}
