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
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Policy;
use App\Models\Brand;
use App\Models\MakeModel;
use App\Models\MakeYear;
use App\Models\GenerationYear;
use App\Models\SubGenerationYear;
use Yajra\DataTables\Facades\DataTables;


class MasterController extends Controller
{
    public function __construct()
    {
        if(Auth::guard("admin")->user())
        {
            $user = Auth::guard("admin")->user();
        }
        else{
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
        }
    }
    public function BrandList()
    {
        $brand = DB::table('brand')->where('is_deleted',0)->orderBy('brand_name', 'asc')
                        ->get();
        return view('admin.brand_list',compact('brand'));
    }
    public function addBrand(Request $request,$id=null)
    {

        $brand_detail="";
        if($id!=null)
        {
            $brand_detail=DB::table('brand')->where('id',$id)->first();
        }
        if ($request->isMethod('post'))
        {
            $brand_check=DB::table('brand')->where('brand_name',$request->brand_name)->where('is_deleted',0)->where('id', '!=', $request->id)->first();
            if($brand_check){
                return redirect()->route("admin.BrandList")->with("error", "Brand Already Exist.");
            }
            else{
                $brand = Brand::find($request->id);
                $message="Brand Update Successfully";

                if (!$brand)
                {
                    $brand = new Brand();
                    $message="Brand Added Successfully";
                }

                $brand->brand_name       = $request->brand_name;
                $brand->ar_brand_name    = $request->ar_brand_name;
                $brand->fr_brand_name    = $request->fr_brand_name;
                $brand->ru_brand_name    = $request->ru_brand_name;
                $brand->fa_brand_name    = $request->fa_brand_name;
                $brand->ur_brand_name    = $request->ur_brand_name;
                $brand->created_at       = date('Y-m-d H:i:s');
                $brand->save();
                return redirect()->route("admin.BrandList")->with("success", $message);
            }
        }

        return view('admin.add_brand',compact('brand_detail'));
    }
    public function updateBrandStatus(Request $request)
    {
        $user = Brand::find($request->user);
        $user->is_active=$request->status;
        $user->save();
    }
    public function bulkDeleteBrand(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Brand selected.');
        }

        Brand::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Brands deleted successfully.');
    }

    public function addModel(Request $request,$id=null)
    {
        //This function is for add / update coach type
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $make_model='';
        if($id!=null)
        {
            $make_model=DB::table('make_model')->where('id',$id)->first();
        }

        if ($request->isMethod('post'))
        {
            $model_check=DB::table('make_model')->where('model_name',$request->model_name)->where('id', '!=', $request->id)->first();
            if($model_check)
            {
                return redirect()->route("admin.modelList")->with("error", "Make Model Already Exist.");
            }
            else
            {
                $type = MakeModel::find($request->id);
                $message="Make Model updated successfully.";
                if (!$type)
                {
                    $type = new MakeModel();
                    $message="Make Model added successfully.";
                }
                $type->brand_id         = $request->brand_id;
                $type->model_name       = $request->model_name;
                $type->ar_model_name    = $request->ar_model_name;
                $type->fr_model_name    = $request->fr_model_name;
                $type->ru_model_name    = $request->ru_model_name;
                $type->fa_model_name    = $request->fa_model_name;
                $type->ur_model_name    = $request->ur_model_name;
                $type->created_at       = date('Y-m-d H:i:s');
                $type->save();
                return redirect()->route("admin.modelList")->with("success", $message);
            }
        }
        return view('admin.add_model',compact('brand','make_model'));
    }
    public function modelList($id=null)
    {
        //This function is for show list
        $type=DB::table('make_model')
                    ->join('brand','brand.id','=','make_model.brand_id')
                    ->where('brand.is_deleted',0)
                    ->where('make_model.is_deleted',0)
                    ->select('make_model.*','brand.brand_name','brand.ar_brand_name')
                    ->orderBy('make_model.id', 'DESC')
                    ->get();
        if($id!=null)
        {
            $type=DB::table('make_model')
                    ->where('make_model.brand_id',$id)
                    ->join('brand','brand.id','=','make_model.brand_id')
                    ->where('brand.is_deleted',0)
                    ->where('make_model.is_deleted',0)
                    ->select('make_model.*','brand.brand_name','brand.ar_brand_name')
                    ->orderBy('make_model.id', 'DESC')
                    ->get();
        }

        return view('admin.model_list',compact('type'));
    }
    public function bulkDeleteModel(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Make Model selected.');
        }

        MakeModel::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Make Model deleted successfully.');
    }
    public function updateModelStatus(Request $request)
    {
        $user = MakeModel::find($request->user);
        $user->is_active=$request->status;
        $user->save();
    }

    public function addMakeYear(Request $request,$id=null)
    {
        //This function is for add / update coach type
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $make_year=$model='';
        if($id!=null)
        {
            $make_year=DB::table('generation_year')->where('id',$id)->first();
            $model=DB::table('make_model')->where('brand_id',$make_year->brand_id)->where('is_active',1)->where('is_deleted',0)->get();
        }

        if ($request->isMethod('post'))
        {

            $type = GenerationYear::find($request->id);
            $message="Genration updated successfully.";
            if (!$type)
            {
                $type = new GenerationYear();
                $message="Genration added successfully.";
            }

            $type->brand_id     = $request->brand_id;
            $type->model_id     = $request->model_id;
            $type->start_year   = $request->start_year;
            $type->end_year     = $request->end_year;
            $type->gen_text     = $request->gen_text;
            $type->created_at    = date('Y-m-d H:i:s');
            $type->save();
            return redirect()->route("admin.makeYearList")->with("success", $message);

        }
        return view('admin.add_year',compact('make_year','brand','model'));
    }
    public function makeYearList()
    {
        //This function is for show list
        $type=DB::table('generation_year')->where('generation_year.is_deleted',0)
                    ->join('brand','brand.id','=','generation_year.brand_id')
                    ->join('make_model','make_model.id','=','generation_year.model_id')
                    ->select('generation_year.*','brand.brand_name','make_model.model_name')
                    ->get();
        return view('admin.year_list',compact('type'));
    }
    public function bulkDeleteMakeYear(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No generation selected.');
        }

        GenerationYear::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected generation deleted successfully.');
    }

    public function subGenerationList($id=null)
    {
        $subgen=DB::table('sub_generation')->where('sub_generation.is_deleted',0)
                    ->join('generation_year','generation_year.id','=','sub_generation.generation_id')
                    ->join('brand','brand.id','=','generation_year.brand_id')
                    ->join('make_model','make_model.id','=','generation_year.model_id')
                    ->select('sub_generation.*','brand.brand_name','make_model.model_name','generation_year.start_year as gen_start_year','generation_year.end_year as gen_end_year')
                    ->paginate(20);
        if($id!=null)
        {
            $subgen=DB::table('sub_generation')->where('sub_generation.is_deleted',0)
                    ->where('sub_generation.generation_id',$id)
                    ->join('generation_year','generation_year.id','=','sub_generation.generation_id')
                    ->join('brand','brand.id','=','generation_year.brand_id')
                    ->join('make_model','make_model.id','=','generation_year.model_id')
                    ->select('sub_generation.*','brand.brand_name','make_model.model_name','generation_year.start_year as gen_start_year','generation_year.end_year as gen_end_year')
                    ->paginate(20);
        }
        return view('admin.sub_generation_list',compact('subgen'));
    }

    public function addSubGeneration(Request $request,$id=null)
    {
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $subgen=$model=$generation='';
        if($id!=null)
        {
            $subgen=DB::table('sub_generation')->where('id',$id)->first();
            $gener=DB::table('generation_year')->where('id',$subgen->generation_id)->first();
            $model=DB::table('make_model')->where('id',$gener->model_id)->where('is_active',1)->where('is_deleted',0)->get();
            $generation=DB::table('generation_year')->where('model_id',$model[0]->id)->get();
        }

        if ($request->isMethod('post'))
        {

            $type = SubGenerationYear::find($request->id);
            $message="Sub Genration updated successfully.";
            if (!$type)
            {
                $type = new SubGenerationYear();
                $message="Sub Genration added successfully.";
            }

            $type->generation_id    = $request->generation_id;
            $type->start_year       = $request->start_year;
            $type->end_year         = $request->end_year;
            $type->subgen_text      = $request->subgen_text;
            $type->created_at       = date('Y-m-d H:i:s');
            $type->save();
            return redirect()->route("admin.subGenerationList")->with("success", $message);
        }

        return view('admin.add_subgeneration',compact('brand','model','subgen','generation'));
    }


    public function CategoryList()
    {
        $category = DB::table('category')->where('is_deleted',0)
                    ->get();
        return view('admin.category_list',compact('category'));
    }
    public function updateCategoryStatus(Request $request)
    {
        $user = Category::find($request->user);
        $user->is_active=$request->status;
        $user->save();
    }

    public function addCategory(Request $request,$id=null)
    {

        $category_detail="";
        if($id!=null)
        {
            $category_detail=DB::table('category')->where('id',$id)->first();
        }
        if ($request->isMethod('post'))
        {
            $cat_check=DB::table('category')->where('category_name',$request->category_name)->where('id', '!=', $request->id)->where('is_deleted', '!=', 0)->first();
            if($cat_check)
            {
                return redirect()->route("admin.CategoryList")->with("error", "Language Already Exist.");
            }
            else
            {
                $category = Category::find($request->id);
                $message="Category updated successfully.";
                if (!$category)
                {
                    $category = new Category();
                    $message="Category added successfully.";
                }

                $category->category_name       = $request->category_name;
                $category->ar_category_name    = $request->ar_category_name;
                $category->fr_category_name    = $request->fr_category_name;
                $category->ru_category_name    = $request->ru_category_name;
                $category->fa_category_name    = $request->fa_category_name;
                $category->ur_category_name    = $request->ur_category_name;

                $category->created_at     = date('Y-m-d H:i:s');
                $category->save();
                return redirect()->route("admin.CategoryList")->with("success", $message);
            }
        }

        return view('admin.add_category',compact('category_detail'));
    }
    public function bulkDeleteCategory(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Category selected.');
        }

        Category::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Category deleted successfully.');
    }
    public function addSubCategory(Request $request,$id=null)
    {
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $subcategory='';
        if($id!=null)
        {
            $subcategory=DB::table('subcategory')->where('id',$id)->first();
        }

        if ($request->isMethod('post'))
        {
            $model_check=DB::table('subcategory')->where('subcat_name',$request->subcat_name)->where('id', '!=', $request->id)->first();
            if($model_check)
            {
                return redirect()->route("admin.subCategoryList")->with("error", "Subcategory Already Exist.");
            }
            else
            {
                $type = Subcategory::find($request->id);
                $message="Subcategory updated successfully.";
                if (!$type)
                {
                    $type = new Subcategory();
                    $message="Subcategory added successfully.";
                }
                $type->category_id         = $request->category_id;
                $type->subcat_name       = $request->subcat_name;
                $type->ar_subcat_name    = $request->ar_subcat_name;
                $type->fr_subcat_name    = $request->fr_subcat_name;
                $type->ru_subcat_name    = $request->ru_subcat_name;
                $type->fa_subcat_name    = $request->fa_subcat_name;
                $type->ur_subcat_name    = $request->ur_subcat_name;
                $type->created_at       = date('Y-m-d H:i:s');
                $type->save();
                return redirect()->route("admin.subCategoryList")->with("success", $message);
            }
        }
        return view('admin.add_sub_cate',compact('category','subcategory'));
    }
    public function subCategoryList($id=null)
    {
        //This function is for show list
        $type=DB::table('subcategory')
                    ->join('category','category.id','=','subcategory.category_id')
                    ->where('category.is_deleted',0)
                    ->where('subcategory.is_deleted',0)
                    ->select('subcategory.*','category.category_name','category.ar_category_name')
                    ->get();
        if($id!=null)
        {
            $type=DB::table('subcategory')
                    ->where('subcategory.category_id',$id)
                    ->join('category','category.id','=','subcategory.category_id')
                    ->where('category.is_deleted',0)
                    ->where('subcategory.is_deleted',0)
                    ->select('subcategory.*','brand.category_name','brand.ar_category_name')
                    ->get();
        }

        return view('admin.subcate_list',compact('type'));
    }
    public function bulkDeleteSubCategory(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Category selected.');
        }

        Subcategory::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Category deleted successfully.');
    }
    public function updateSubCategoryStatus(Request $request)
    {
        $user = Subcategory::find($request->user);
        $user->is_active=$request->status;
        $user->save();
    }

    function subscriptionList()
    {
        $subscription_plan=DB::table('subscription_plan')->where('is_deleted',0)->paginate(20);
        return view('admin.subscription_list',compact('subscription_plan'));
    }
    function addSubscription(Request $request,$id=null)
    {

        $subscription_detail="";
        if($id!=null)
        {
            $subscription_detail=DB::table('subscription_plan')->where('id',$id)->first();
        }
        if ($request->isMethod('post'))
        {
            $subscription_check=DB::table('subscription_plan')->where('plan_name', $request->plan_name)->where('is_deleted',0)->where('id', '!=', $request->id)
            ->first();
            if($subscription_check){
                return redirect()->route("admin.subscriptionList")->with("error", "Subscription Plan Name Already Exist.");
            }
            else{
            $Subscription = Subscription::find($request->id);
            if (!$Subscription)
            {
                $Subscription = new Subscription();
            }

            $Subscription->plan_name       = $request->plan_name;

            $Subscription->plan_content    = (!empty($request->plan_content) && $request->plan_content != '' ) ? $request->plan_content : '';
            $Subscription->plan_amount     = $request->plan_amount;
            $Subscription->plan_duration   = $request->plan_duration;
            $Subscription->duration_unit   = $request->duration_unit;

            $Subscription->created_at       = date('Y-m-d H:i:s');
            $Subscription->save();
            return redirect()->route("admin.subscriptionList")->with("success", "Master Subscription Plan Added/updated successfully.");
            }
        }

        return view('admin.add_subscription',compact('subscription_detail'));
    }
    public function bulkDeletePlan(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Subscription Plan selected.');
        }

        Subscription::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Subscription Plan deleted successfully.');
    }

    public function addPolicy(Request $request , $id=null)
    {
        //This function is for add / update policy
        $policies='';
        if($id!=null)
        {
            $policies=DB::table('policy')->where('id',$id)->first();
        }
        if ($request->isMethod('post'))
        {
            $type_check=DB::table('policy')->where('policy_type',$request->policy_type)->where('is_deleted',0)->where('id', '!=', $request->policy_id)->first();
            if($type_check)
            {
                return redirect()->route("admin.policyList")->with("error", "Policy Type Already Exist.");
            }
            else
            {
                $policy_type        = $request->policy_type;
                $policy_content     = $request->policy_content;
                $policy_content_ar  = $request->policy_content_ar;
                $policy_id          = $request->policy_id;

                $policy = Policy::where('id', $policy_id)->first();

                if (!$policy) {
                    $policy = new Policy();
                }
                $policy->policy_type        = $policy_type;
                $policy->policy_content     = $policy_content;
                $policy->policy_content_ar  = $policy_content_ar;
                $policy->policy_name        = $request->policy_name;
                $policy->save();

                return redirect()->route("admin.policyList")->with("success", "Policy content added successfully.");
            }
        }
        return view('admin.add_policy',compact('policies'));
    }

    public function policyList()
    {
        //This function is for show policy list
        $policy=DB::table('policy')->where('is_deleted',0)->paginate(20);
        return view('admin.policy_list',compact('policy'));
    }
    public function viewPolicy($id=null)
    {
        $policy=DB::table('policy')->where('id',$id)->first();
        return view('admin.policy_view',compact('policy'));
    }

    public function deletePolicy(Request $request)
    {
        //This function is for delete the policy
        $policy = Policy::find($request->policy_id);
        $policy->is_deleted=1;
        $policy->save();
    }
    public function bulkDeletePolicy(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No policies selected.');
        }

        Policy::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected policies deleted successfully.');
    }

    public function update_subscri_status(Request $request)
    {
        $subscription_plan = Subscription::find($request->user);
        $subscription_plan->is_active=$request->status;
        $subscription_plan->save();
    }

    public function masterProductList()
    {
        $product=DB::table('product_template')
                    ->join('brand','brand.id','=','product_template.brand_id')
                    ->join('make_model','make_model.id','=','product_template.make_model_id')
                    ->leftjoin('category','category.id','=','product_template.category_id')
                    ->leftjoin('subcategory','subcategory.id','=','product_template.subcategory_id')
                    ->where('product_template.is_deleted',0)
                    ->select('product_template.*','brand.brand_name','make_model.model_name','category.category_name','subcategory.subcat_name')
                    ->orderBy('id', 'DESC')
                    ->paginate(20);

        return view('admin.master_product_list',compact('product'));
    }
    public function addProductTemplate(Request $request,$id=null)
    {
        $product_detail=$subcategory=$model=$image="";
        $selectedYearIds=array();

        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $make_year=DB::table('make_year')->where('is_active',1)->where('is_deleted',0)->get();
        if($id!=null)
        {
            $product_detail=DB::table('product_template')->where('id',$id)->first();
            $subcategory=DB::table('subcategory')->where('id',$product_detail->subcategory_id)->get();
            $model=DB::table('make_model')->where('id',$product_detail->make_model_id)->get();
            $image=DB::table('admin_product_img')->where('admin_product_id',$id)->first();
            $selectedYearIds = MakeYearProduct::where('admin_product_id', $id)->pluck('make_year_id')->toArray();
        }

        return view('admin.add_template_product',compact('brand','product_detail','make_year','subcategory','model','selectedYearIds','image'));
    }
    public function productTemplate()
    {
        //This function is to attach all the product with model and brand
        $brands = DB::table('brand')->where('is_deleted', 0)->get();
        $models = DB::table('make_model')->where('is_deleted', 0)->get()->groupBy('brand_id');
        $categories = DB::table('category')->where('is_deleted', 0)->get();
        $subcategories = DB::table('subcategory')->where('is_deleted', 0)->get()->groupBy('category_id');

        $rows = [];

        foreach ($brands as $brand) {
            if (!isset($models[$brand->id])) continue;

            foreach ($models[$brand->id] as $model) {
                foreach ($categories as $category) {
                    if (!isset($subcategories[$category->id])) continue;

                    foreach ($subcategories[$category->id] as $subcategory) {
                        $rows[] = [
                            'brand_id' => $brand->id,
                            'make_model_id' => $model->id,
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Chunk insert to avoid max_allowed_packet or memory issues
        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('product_template')->insert($chunk);
        }
    }

    /******************[Location Master Start]*********************/
    public function countryList()
    {
        //This function is for show country list
        $country = DB::table('master_country')->orderBy('country_status', 'DESC')->get();
        return view('admin.country_list',compact('country'));
    }
    public function addCountry(Request $request,$id=null)
    {
        //This function is for add/update country
        $country_detail="";
        if($id!=null)
        {
            $country_detail=DB::table('master_country')->where('country_id',$id)->first();
        }

        if ($request->isMethod('post'))
        {
            $brand_check=DB::table('master_country')->where('country_name',$request->country_name)->where('country_id', '!=', $request->id)->first();
            if($brand_check){
                return redirect()->route("admin.countryList")->with("error", "Country Already Exist.");
            }
            else{
                $brandId = $request->id;
                $data = [
                    'country_name'     => $request->country_name,
                    'created_at'     => now(), // or date('Y-m-d H:i:s')
                ];

                if ($brandId) {
                    $existing = DB::table('master_country')->where('country_id', $brandId)->first();

                    if ($existing) {
                        DB::table('master_country')->where('country_id', $brandId)->update($data);
                        $message = "Country Update Successfully";
                    } else {
                        $brandId = DB::table('master_country')->insertGetId($data);
                        $message = "Country Added Successfully";
                    }
                } else {
                    $brandId = DB::table('master_country')->insertGetId($data);
                    $message = "Country Added Successfully";
                }

                return redirect()->route("admin.countryList")->with("success", $message);
            }
        }

        return view('admin.add_country',compact('country_detail'));
    }
    public function updateCountryStatus(Request $request)
    {
        DB::table('master_country')
        ->where('country_id', $request->user)
        ->update(['country_status' => $request->status]);
    }
    public function cityList()
    {
        //This function is for show country list
        $country = DB::table('master_country')->where('country_status',1)->get();
        return view('admin.city_list',compact('country'));
    }
    public function getCityList(Request $request)
    {
        //This is for ajax function

        $query = DB::table('master_city')
                        ->join('master_country', 'master_country.country_id', '=', 'master_city.country_id')
                        ->where('master_country.country_status',1);

        if ($request->country_id) {
            $query->where('master_city.country_id', $request->country_id);
        }

        $query->select('master_city.*', 'master_country.country_name')
            ->orderBy('master_city.city_status', 'DESC')
            ->orderBy('master_city.city_id', 'DESC');

        return DataTables::of($query)
                        ->addIndexColumn() // This auto-creates DT_RowIndex for serial number
                        ->addColumn('checkbox', function ($row) {
                                return '<input type="checkbox" name="ids[]" value="' . $row->city_id . '" class="selectBox">';
                            })
                        ->addColumn('country', fn($row) => $row->country_name ?? '')
                        ->addColumn('city', fn($row) => $row->city_name ?? '')
                        ->addColumn('status', function($row) {
                                $selected0 = $row->city_status == 0 ? 'selected' : '';
                                $selected1 = $row->city_status == 1 ? 'selected' : '';
                                return '<select class="product_status form-select form-select-sm" user="'.$row->city_id.'">
                                            <option value="0" '.$selected0.'>In active</option>
                                            <option value="1" '.$selected1.'>Active</option>
                                        </select>';
                            })
                        ->addColumn('action', function($row) {
                            $editUrl = route('admin.addCity', $row->city_id);
                            return '<a href="'.$editUrl.'"><i class="mdi mdi-lead-pencil"></i></a>';
                        })
                        ->filter(function ($query) use ($request) {
                            if ($search = $request->get('search')['value']) {
                                $query->where(function ($q) use ($search) {
                                    $q->where('master_country.country_name', 'like', "%{$search}%")
                                    ->orWhere('master_city.city_name', 'like', "%{$search}%");
                                });
                            }
                        })
                        ->rawColumns(['status', 'action','checkbox']) // Include all columns with HTML
                        ->make(true);

    }
    public function updateCityStatus(Request $request)
    {
        DB::table('master_city')
            ->where('city_id', $request->product_id)
            ->update(['city_status' => $request->status]);
    }
    public function bulkUpdateCity(Request $request)
    {
        $productIds = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($productIds)) {
            return back()->with('error', 'No City selected.');
        }

        $status = $action === 'active' ? 1 : 0;

         DB::table('master_city')
            ->whereIn('city_id', $productIds)
            ->update(['city_status' => $status]);


        return back()->with('success', ucfirst($action) . 'd selected products successfully.');
    }
    public function addCity(Request $request,$id=null)
    {
        //This function is for add/update city
        $country = DB::table('master_country')->where('country_status',1)->get();
        $city_detail="";
        if($id!=null)
        {
            $city_detail=DB::table('master_city')->where('city_id',$id)->first();
        }

        if ($request->isMethod('post'))
        {
            $brand_check=DB::table('master_city')->where('city_name',$request->city_name)->where('country_id',$request->country_id)->where('city_id', '!=', $request->id)->first();
            if($brand_check){
                return redirect()->route("admin.cityList")->with("error", "City Already Exist.");
            }
            else{
                $city_id = $request->id;

                if ($city_id) {

                    $data = [
                        'city_name'     => $request->city_name,
                        'country_id'    => $request->country_id,
                        'city_name_ar'  => $request->city_name_ar,
                        'latitude'      => $request->latitude,
                        'longitude'     => $request->longitude,
                        'created_at'     => now(), // or date('Y-m-d H:i:s')
                    ];

                    DB::table('master_city')->where('city_id', $city_id)->update($data);
                    $message = "City Update Successfully";
                } else {
                    $data = [
                        'city_name'     => $request->city_name,
                        'country_id'    => $request->country_id,
                        'city_name_ar'  => $request->city_name_ar,
                        'latitude'      => $request->latitude,
                        'longitude'     => $request->longitude,
                        'created_at'     => now(), // or date('Y-m-d H:i:s')
                    ];

                    $city_id = DB::table('master_city')->insertGetId($data);
                    $message = "Country Added Successfully";
                }

                return redirect()->route("admin.cityList")->with("success", $message);
            }
        }
        return view('admin.add_city',compact('country','city_detail'));
    }
    /******************[Location Master End]*********************/


    public function modelImport()
    {
        return view('admin.import_model');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $collection = Excel::toCollection(null, $request->file('file'));

        DB::beginTransaction();

        try {
            foreach ($collection[0] as $index => $row) {
                // Skip header row if column names exist
                if ($index === 0 && strtolower(trim($row[0])) == 'category_id') continue;

                $categoryId     = trim($row[0] ?? '');
                $subcatName     = trim($row[1] ?? '');
                $arSubcatName   = trim($row[2] ?? '');

                if (!$categoryId || !$subcatName) {
                    \Log::warning("Skipping row due to missing data: " . json_encode($row));

                    continue;
                }

                // Avoid duplicate entries
                $exists = Subcategory::where('category_id', $categoryId)
                    ->where('subcat_name', $subcatName)
                    ->first();

                if (!$exists) {
                    Subcategory::create([
                        'category_id'    => $categoryId,
                        'subcat_name'    => $subcatName,
                        'ar_subcat_name' => $arSubcatName,
                    ]);
                    \Log::info("Inserted: $subcatName under Category ID: $categoryId");
                }
            }

            DB::commit();
            return back()->with('success', 'Subcategories imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import failed: " . $e->getMessage());
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function generateCombinations()
    {
        $models = DB::table('make_model')->get();
        $parts = DB::table('subcategory')->get();

        $insertData = [];

        foreach ($models as $model) {
            $generations = DB::table('generation_year')->where('model_id', $model->id)->where('is_deleted', 0)->pluck('id');

            foreach ($generations as $generationId) {
                foreach ($parts as $part) {
                    $insertData[] = [
                        'brand_id'       => $model->brand_id,
                        'make_model_id'  => $model->id,
                        'generation_id'  => $generationId,
                        'category_id'    => $part->category_id,
                        'subcategory_id' => $part->id,
                        'created_at'    => date('Y-m-d H:i:s')
                    ];
                }
            }
        }

        // Optional: Remove duplicates before insert
        $insertData = collect($insertData)->unique();

        // Chunk insert if data is large
        foreach (array_chunk($insertData->toArray(), 1000) as $chunk) {
            DB::table('product_template')->insert($chunk);
        }

        return "Inserted " . count($insertData) . " combinations.";
    }


}
