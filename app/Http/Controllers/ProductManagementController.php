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
use App\Models\ProductTemplate;
use App\Models\MakeYear;
use App\Models\MakeYearProduct;
use App\Models\AdminProductImage;
use App\Models\InterchangeMaster;
use App\Models\PartType;
use Yajra\DataTables\Facades\DataTables;


class ProductManagementController extends Controller
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
    public function addAdminProduct(Request $request,$id=null)
    {
        //This function is for add/update admin product
       
        $product_detail=$subcategory=$model=$image=$relatedProducts=$generation=$type="";
        $selectedYearIds=array();

        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $make_year=DB::table('make_year')->where('is_active',1)->where('is_deleted',0)->get();

        if($id!=null)
        {

            $product_detail=DB::table('product_template')->where('id',$id)->first();
            $subcategory=DB::table('subcategory')->where('id',$product_detail->subcategory_id)->get();
            $model=DB::table('make_model')->where('id',$product_detail->make_model_id)->get();
            $image=DB::table('admin_product_img')->where('admin_product_id',$id)->first();
            $generation=DB::table('generation_year')->where('model_id',$product_detail->make_model_id)->get();
            $type=DB::table('part_type')->where('product_temp_id',$id)->get();
            //$selectedYearIds = MakeYearProduct::where('admin_product_id', $id)->pluck('make_year_id')->toArray();
        }
        if ($request->isMethod('post'))
        {
            
            if($request->product_id)
            {
                $chkproduct  = DB::table('product_template')->where('brand_id', '=' , $request->brand_id)
                                                        ->where('make_model_id', '=' , $request->model_id)
                                                        ->where('category_id', '=' , $request->category_id)
                                                        ->where('subcategory_id', '=' , $request->subcategory_id)
                                                        ->where('generation_id', '=' , $request->generation_id)
                                                        ->where('id', '!=' , $request->product_id)->where('is_deleted', '=' , 0)->first();

                if(!empty($chkproduct))
                {
                    return redirect()->back()->with(["error" => "This product has already been added."])->withInput();
                }
            }
            else
            {
                $chkproduct  = DB::table('product_template')->where('brand_id', '=' , $request->brand_id)
                                                        ->where('make_model_id', '=' , $request->model_id)
                                                        ->where('category_id', '=' , $request->category_id)
                                                        ->where('subcategory_id', '=' , $request->subcategory_id)
                                                        ->where('generation_id', '=' , $request->generation_id)
                                                        ->where('is_deleted', '=' , 0)->first();

                if(!empty($chkproduct))
                {
                    return redirect()->back()->with(["error" => "This product has already been added."])->withInput();
                }
            }

            $product = ProductTemplate::find($request->product_id);
            $message="Product updated successfully.";
            if (!$product) 
            {
                $product = new ProductTemplate(); 
                $message="Product added successfully.";
            }
                        
            $product->brand_id         = $request->brand_id;
            $product->make_model_id    = $request->model_id;
            $product->category_id      = $request->category_id;
            $product->subcategory_id   = $request->subcategory_id;
            $product->generation_id    = $request->generation_id; 

            $product->product_note          = $request->product_note;
            $product->product_description   = $request->product_description;
            

            $product->created_at       = date('Y-m-d H:i:s');
            $product->save();
            $product_id=$product->id;

            if ($request->hasFile('product_image')) {
                $image = $request->file('product_image');
                $imageName = "prd" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/product_image'), $imageName);
               

                $productimg = AdminProductImage::find($request->product_image_id);
                
                if (!$productimg) 
                {
                    $productimg = new AdminProductImage(); 
                    $productimg->admin_product_id=$product_id;
                }
                $productimg->product_image = $imageName;
                $productimg->save();
            }
            if($request->part_type)
            {
                foreach($request->part_type as $index =>$label)
                {
                    if (trim($label) != '') {
                        $part_type_id = $request->part_type_id[$index] ?? null;

                        if ($part_type_id) {
                        
                            $type = PartType::find($part_type_id);
                            if ($type) {
                                $type->part_type_label = $label;
                                $type->save();
                            }
                        } else {
                            $type = new PartType();
                            $type->product_temp_id = $product_id;
                            $type->part_type_label = $label;
                            $type->created_at = now();
                            $type->save();
                        }
                    }
                }
            }

            

            return redirect()->route("admin.adminProductList")->with("success", $message);
        }
        
        return view('admin.add_admin_product',compact('brand','category','product_detail','make_year','subcategory','model','generation','image','type'));
    }
    public function deletePartType($id)
    {
        $part = PartType::find($id);
        if ($part) {
            $part->delete(); // or soft delete
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
    public function adminProductList()
    {
        //This function is for admin product list
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        //$firstBrandId = $brand->first()->id ?? null;
        $model=DB::table('make_model')->where('is_active',1)->where('is_deleted',0)->get();
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $subcategory=DB::table('subcategory')->where('is_active',1)->where('is_deleted',0)->get();
        $mparents=DB::table('mparents')->get();
        

        $product=DB::table('product_template')
                    ->join('brand','brand.id','=','product_template.brand_id')
                    ->join('make_model','make_model.id','=','product_template.make_model_id')
                    ->leftjoin('category','category.id','=','product_template.category_id')
                    ->leftjoin('subcategory','subcategory.id','=','product_template.subcategory_id')
                    ->leftjoin('generation_year','generation_year.id','=','product_template.generation_id')
                    ->where('product_template.is_deleted',0)
                    ->select('product_template.*','brand.brand_name','make_model.model_name','category.category_name','subcategory.subcat_name','generation_year.start_year','generation_year.end_year')
                    ->orderBy('id', 'DESC')
                    ->paginate(20);
        return view('admin.admin_product_list',compact('product','brand','category','model','subcategory','mparents'));
    }
    public function getAdminProduct(Request $request)
    {
        $query = ProductTemplate::query()
                            ->leftJoin('brand', 'brand.id', '=', 'product_template.brand_id')
                            ->leftJoin('make_model', 'make_model.id', '=', 'product_template.make_model_id')
                            ->leftJoin('category', 'category.id', '=', 'product_template.category_id')
                            ->leftJoin('subcategory', 'subcategory.id', '=', 'product_template.subcategory_id')
                            ->leftjoin('generation_year','generation_year.id','=','product_template.generation_id')
                            ->leftJoin('mparent_brand', 'mparent_brand.brand_id', '=', 'brand.id')
                            ->where('product_template.is_deleted', 0);
        // Filters
        if($request->parent_id){
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
        $query->distinct('product_template.id');
        
        // Important: select necessary columns only, avoid ambiguous column names
        $query->select(
                    'product_template.id',
                    'brand.brand_name as brand_name',
                    'make_model.model_name as model_name',
                    'category.category_name as category_name',
                    'subcategory.subcat_name as subcategory_name',
                    'product_template.is_active','generation_year.start_year','generation_year.end_year'
                );

       return DataTables::of($query)
                            ->addIndexColumn() // This auto-creates DT_RowIndex for serial number
                            ->addColumn('checkbox', function ($row) {
                                return '<input type="checkbox" name="ids[]" value="' . $row->id . '" class="selectBox">';
                            })
                            ->addColumn('brand', fn($row) => $row->brand_name ?? '')
                            ->addColumn('model', fn($row) => $row->model_name ?? '')
                            ->addColumn('category', fn($row) => $row->category_name ?? '')
                            ->addColumn('subcategory', fn($row) => $row->subcategory_name ?? '')
                            ->addColumn('generation', fn($row) => $row->start_year.' - '.$row->end_year ?? '')
                            ->addColumn('status', function($row) {
                                $selected0 = $row->is_active == 0 ? 'selected' : '';
                                $selected1 = $row->is_active == 1 ? 'selected' : '';
                                return '<select class="product_status form-select form-select-sm" user="'.$row->id.'">
                                            <option value="0" '.$selected0.'>In active</option>
                                            <option value="1" '.$selected1.'>Active</option>
                                        </select>';
                            })
                            ->addColumn('action', function($row) {
                                $editUrl = route('admin.addAdminProduct', $row->id);
                                $viewUrl = route('admin.viewAdminProduct', ['id' => $row->id]);
                                return '<a href="javascript:void(0)" class="del_product" user_id="'.$row->id.'"><i class="mdi mdi-delete"></i></a> |
                                        <a href="'.$editUrl.'"><i class="mdi mdi-lead-pencil"></i></a> |
                                        <a href="'.$viewUrl.'"><i class="mdi mdi-eye"></i></a>';
                            })
                            ->filter(function ($query) use ($request) {
                                if ($search = $request->get('search')['value']) {
                                    $query->where(function ($q) use ($search) {
                                        $q->where('brand.brand_name', 'like', "%{$search}%")
                                        ->orWhere('make_model.model_name', 'like', "%{$search}%")
                                        ->orWhere('category.category_name', 'like', "%{$search}%")
                                        ->orWhere('subcategory.subcat_name', 'like', "%{$search}%");
                                    });
                                }
                            })
                            ->rawColumns(['checkbox', 'status', 'action']) // Include all columns with HTML
                            ->make(true);

    
    }
    public function getProduct(Request $request)
    {
        $query = ProductTemplate::query()
                            ->leftJoin('brand', 'brand.id', '=', 'product_template.brand_id')
                            ->leftJoin('make_model', 'make_model.id', '=', 'product_template.make_model_id')
                            ->leftJoin('category', 'category.id', '=', 'product_template.category_id')
                            ->leftJoin('subcategory', 'subcategory.id', '=', 'product_template.subcategory_id');

        // Filters
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
        $query->distinct('product_template.id');
        
        // Important: select necessary columns only, avoid ambiguous column names
        $query->select(
                    'product_template.id',
                    'brand.brand_name as brand_name',
                    'make_model.model_name as model_name',
                    'category.category_name as category_name',
                    'subcategory.subcat_name as subcategory_name'
                );

        return DataTables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($row) {
                                return '<input type="checkbox" class="product-check" value="' . $row->id . '">';
                            })
                            ->addColumn('brand', fn($row) => $row->brand_name ?? '')
                            ->addColumn('model', fn($row) => $row->model_name ?? '')
                            ->addColumn('product', fn($row) => $row->category_name ?? '')
                            ->addColumn('subcategory', fn($row) => $row->subcategory_name ?? '')
                            ->filter(function ($query) use ($request) {
                                if ($search = $request->get('search')['value']) {
                                    $query->where(function ($q) use ($search) {
                                        $q->where('brand.brand_name', 'like', "%{$search}%")
                                        ->orWhere('make_model.model_name', 'like', "%{$search}%")
                                        ->orWhere('category.category_name', 'like', "%{$search}%")
                                        ->orWhere('subcategory.subcat_name', 'like', "%{$search}%");
                                        
                                    });
                                }
                            })
                            ->rawColumns(['checkbox'])
                            ->make(true);
    }

    public function viewAdminProduct($id)
    {
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        

        $product_detail=DB::table('product_template')
                                ->leftJoin('brand', 'brand.id', '=', 'product_template.brand_id')
                                ->leftJoin('make_model', 'make_model.id', '=', 'product_template.make_model_id')
                                ->leftJoin('category', 'category.id', '=', 'product_template.category_id')
                                ->leftJoin('subcategory', 'subcategory.id', '=', 'product_template.subcategory_id')
                                ->leftjoin('generation_year','generation_year.id','=','product_template.generation_id')
                                ->where('product_template.id', $id)
                                ->select(
                                    'product_template.*',
                                    'brand.brand_name as brand_name',
                                    'make_model.model_name as model_name',
                                    'category.category_name as category_name',
                                    'subcategory.subcat_name as subcategory_name','generation_year.start_year','generation_year.end_year'
                                )
                                ->first();
        

        $image=DB::table('admin_product_img')->where('admin_product_id',$id)->first();


        $groupIds = DB::table('interchange_product')
                        ->where('brand_id', $product_detail->brand_id)
                        ->where('model_id', $product_detail->make_model_id)
                        ->where('generation_id', $product_detail->generation_id)
                        ->where('category_id', $product_detail->category_id)
                        ->where('subcategory_id', $product_detail->subcategory_id)
                        ->pluck('group_id');
                           //         echo "<pre>";print_r($groupIds);die();
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
                        ->leftJoin('part_type', 'part_type.id', '=', 'interchange_product.variant_id')
                        ->select('interchange_product.*', 'generation_year.start_year', 'generation_year.end_year','brand.brand_name as brand_name',
                                    'make_model.model_name as model_name',
                                    'category.category_name as category_name',
                                    'subcategory.subcat_name as subcat_name','interchange_group.group_name','part_type.part_type_label')
                        ->orderBy('interchange_product.group_id', 'asc')
                        ->get();

        
        return view('admin.view_admin_product',compact('product_detail','image','product','brand','category'));
    }
    public function InterchangeProduct()
    {
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
                        
        $parts=DB::table('category')
                        ->Join('subcategory', 'subcategory.category_id', '=', 'category.id')
                        ->where('category.is_active',1)->where('category.is_deleted',0)
                        ->get();
        
        return view('admin.interchange_product',compact('brand','parts'));
    }
    public function getIProducts(Request $request)
    {
        $combinations = $request->combinations;

        if (!is_array($combinations)) {
            return response()->json(['products' => []]); 
        }

        $products = collect();

        foreach ($combinations as $combo) {
            $query = DB::table('product_template')
                ->join('category', 'category.id', '=', 'product_template.category_id')
                ->join('subcategory', 'subcategory.id', '=', 'product_template.subcategory_id')
                ->leftJoin('brand', 'brand.id', '=', 'product_template.brand_id')
                ->leftJoin('make_model', 'make_model.id', '=', 'product_template.make_model_id')
                ->leftJoin('generation_year', 'generation_year.id', '=', 'product_template.generation_id')
                ->leftJoin('part_type', 'part_type.product_temp_id', '=', 'product_template.id');

            if (!empty($combo['brand_id'])) {
                $query->where('product_template.brand_id', $combo['brand_id']);
            }

            if (!empty($combo['model_id'])) {
                $query->where('product_template.make_model_id', $combo['model_id']);
            }

            if (!empty($combo['generation_id'])) {
                $query->where('product_template.generation_id', $combo['generation_id']);
            }

            $matched = $query->select(
                    'product_template.*','category.category_name',
                    'subcategory.subcat_name','subcategory.id as sub_cat_id','brand.brand_name','part_type.part_type_label','part_type.id as variant_id',
                    'make_model.model_name','generation_year.start_year','generation_year.end_year'
                )
                ->get();

            $products = $products->merge($matched);
        }

        return response()->json(['products' => $products]);
    }

    public function addInterchangeProduct(Request $request)
    {
        $products = $request->input('products'); // array of [subcategory_id, variant_id, brand_id, model_id, generation_id]

        if (empty($products)) {
            return response()->json(['status' => 'error', 'message' => 'No product data provided.'], 400);
        }

        $grouped = collect($products)->groupBy('product_id');

        $responseGroups = [];

        foreach ($grouped as $key => $groupItems) {
            $first = $groupItems->first();
            $subcategoryId = $first['product_id'];
            $variantId = $first['variant_id'] ?? 0;

            $subcate = DB::table('subcategory')->where('id', $subcategoryId)->first();
            if (!$subcate) continue;

            $categoryId = $subcate->category_id;

            $existingGroupId = null;

            // Try to find existing group for any of the items in this group
            foreach ($groupItems as $productData) {
                $match = DB::table('interchange_product')
                    ->where('brand_id', $productData['brand_id'])
                    ->where('model_id', $productData['model_id'])
                    ->where('generation_id', $productData['generation_id'])
                    ->where('category_id', $categoryId)
                    ->where('subcategory_id', $subcategoryId)
                    ->where('variant_id', $variantId)
                    ->first();

                if ($match) {
                    $existingGroupId = $match->group_id;
                    break;
                }
            }

            // If not found, create new group
            if (!$existingGroupId) {
                $groupName = 'GRP-' . strtoupper(Str::random(8));
                $existingGroupId = DB::table('interchange_group')->insertGetId([
                    'group_name' => $groupName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Insert entries in interchange_product
            foreach ($groupItems as $productData) {
                $subcategoryId = $productData['product_id'];
                $variantId = $productData['variant_id'] ?? 0;

                $subcate = DB::table('subcategory')->where('id', $subcategoryId)->first();
                if (!$subcate) continue;

                $categoryId = $subcate->category_id;

                $exists = DB::table('interchange_product')
                    ->where('group_id', $existingGroupId)
                    ->where('brand_id', $productData['brand_id'])
                    ->where('model_id', $productData['model_id'])
                    ->where('generation_id', $productData['generation_id'])
                    ->where('category_id', $categoryId)
                    ->where('subcategory_id', $subcategoryId)
                    ->where('variant_id', $variantId)
                    ->exists();

                if (!$exists) {
                    DB::table('interchange_product')->insert([
                        'group_id' => $existingGroupId,
                        'brand_id' => $productData['brand_id'],
                        'model_id' => $productData['model_id'],
                        'generation_id' => $productData['generation_id'],
                        'category_id' => $categoryId,
                        'subcategory_id' => $subcategoryId,
                        'variant_id' => $variantId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $responseGroups[] = $existingGroupId;
        }

        return response()->json(['status' => 'success', 'group_ids' => $responseGroups]);
    }

    public function InterchangeProductList()
    {
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        //$firstBrandId = $brand->first()->id ?? null;
        $model=DB::table('make_model')->where('is_active',1)->where('is_deleted',0)->get();
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        $subcategory=DB::table('subcategory')->where('is_active',1)->where('is_deleted',0)->get();

        return view('admin.interchange_product_list',compact('brand','model','category','subcategory'));    
    }
    public function getInterchangeList(Request $request)
    {
        $query = DB::table('interchange_product')
                        ->join('interchange_group', 'interchange_group.id', '=', 'interchange_product.group_id')
                        //->join('product_template', 'product_template.id', '=', 'group_product.product_id')
                        ->Join('brand', 'brand.id', '=', 'interchange_product.brand_id')
                        ->Join('make_model', 'make_model.id', '=', 'interchange_product.model_id')
                        ->Join('category', 'category.id', '=', 'interchange_product.category_id')
                        ->Join('subcategory', 'subcategory.id', '=', 'interchange_product.subcategory_id')
                        ->Join('generation_year', 'generation_year.id', '=', 'interchange_product.generation_id')
                        ->leftJoin('part_type', 'part_type.id', '=', 'interchange_product.variant_id');

        if ($request->brand_id) {
            $query->where('interchange_product.brand_id', $request->brand_id);
        }
        if ($request->model_id) {
            $query->where('interchange_product.model_id', $request->model_id);
        }
        if ($request->category_id) {
            $query->where('interchange_product.category_id', $request->category_id);
        }
        if ($request->subcategory_id) {
            $query->where('interchange_product.subcategory_id', $request->subcategory_id);
        }

        $query->select('interchange_product.*', 'generation_year.start_year', 'generation_year.end_year','brand.brand_name as brand_name',
                        'make_model.model_name as model_name',
                        'category.category_name as category_name',
                        'subcategory.subcat_name as subcat_name','interchange_group.group_name','part_type.part_type_label')
            ->orderBy('interchange_product.group_id', 'DESC');
        
          
        
        
        return DataTables::of($query)
                        ->addIndexColumn() // This auto-creates DT_RowIndex for serial number
                        ->addColumn('group', fn($row) => $row->group_name ?? '')
                        ->addColumn('brand', fn($row) => $row->brand_name ?? '')
                        ->addColumn('model', fn($row) => $row->model_name ?? '')
                        ->addColumn('generation', fn($row) => $row->start_year .' - '.$row->end_year ?? '')
                        ->addColumn('category', fn($row) => $row->category_name ?? '')
                        ->addColumn('subcategory', fn($row) => $row->subcat_name ?? '')
                        ->addColumn('variant', fn($row) => $row->part_type_label ?? '')
                        ->addColumn('action', function($row) {
                            
                            return '<a href="javascript:void(0)" class="del_product" user_id="'.$row->id.'"><i class="mdi mdi-delete"></i></a>';
                        })
                        ->filter(function ($query) use ($request) {
                            if ($search = $request->get('search')['value']) {
                                $query->where(function ($q) use ($search) {
                                    $q->where('brand.brand_name', 'like', "%{$search}%")
                                    ->orWhere('make_model.model_name', 'like', "%{$search}%")
                                    ->orWhere('category.category_name', 'like', "%{$search}%")
                                    ->orWhere('subcategory.subcat_name', 'like', "%{$search}%")
                                    ->orWhere('interchange_group.group_name', 'like', "%{$search}%")
                                    ->orWhere('part_type.part_type_label', 'like', "%{$search}%");
                                });
                            }
                        })
                        ->rawColumns(['checkbox', 'status', 'action']) // Include all columns with HTML
                        ->make(true);
    }
    public function addInterchangeProduct_old(Request $request)
    {
        $products = $request->input('products'); // array of [subcategory_id, variant_id, brand_id, model_id, generation_id]

        if (empty($products)) {
            return response()->json(['status' => 'error', 'message' => 'No product data provided.'], 400);
        }

        $existingGroupId = null;

        // Step 1: Check if any combination already exists in a group
        foreach ($products as $productData) {
            $subcategoryId = $productData['product_id']; // actually subcategory_id
            $variantId = $productData['variant_id'] ?? 0;
            $brandId = $productData['brand_id'];
            $modelId = $productData['model_id'];
            $generationId = $productData['generation_id'];

            $subcate = DB::table('subcategory')->where('id', $subcategoryId)->first();
            if (!$subcate) continue;

            $categoryId = $subcate->category_id;

            $match = DB::table('interchange_product')
                ->where('brand_id', $brandId)
                ->where('model_id', $modelId)
                ->where('generation_id', $generationId)
                ->where('category_id', $categoryId)
                ->where('subcategory_id', $subcategoryId)
                ->where('variant_id', $variantId)
                ->first();

            if ($match) {
                $existingGroupId = $match->group_id;
                break;
            }
        }

        // Step 2: Create group if not found
        if (!$existingGroupId) {
            $groupName = 'GRP-' . strtoupper(Str::random(8));
            $existingGroupId = DB::table('interchange_group')->insertGetId([
                'group_name' => $groupName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Step 3: Insert each unique (subcategory + variant + vehicle) combo
        foreach ($products as $productData) {
            $subcategoryId = $productData['product_id'];
            $variantId = $productData['variant_id'] ?? 0;
            $brandId = $productData['brand_id'];
            $modelId = $productData['model_id'];
            $generationId = $productData['generation_id'];

            $subcate = DB::table('subcategory')->where('id', $subcategoryId)->first();
            if (!$subcate) continue;

            $categoryId = $subcate->category_id;

            $exists = DB::table('interchange_product')
                ->where('group_id', $existingGroupId)
                ->where('brand_id', $brandId)
                ->where('model_id', $modelId)
                ->where('generation_id', $generationId)
                ->where('category_id', $categoryId)
                ->where('subcategory_id', $subcategoryId)
                ->where('variant_id', $variantId)
                ->exists();

            if (!$exists) {
                DB::table('interchange_product')->insert([
                    'group_id' => $existingGroupId,
                    'brand_id' => $brandId,
                    'model_id' => $modelId,
                    'generation_id' => $generationId,
                    'category_id' => $categoryId,
                    'subcategory_id' => $subcategoryId,
                    'variant_id' => $variantId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'success', 'group_id' => $existingGroupId]);
    }




    public function deleteInter(Request $request)
    {
        //This function is to delete the interchange product
        $inter_id = $request->user;

        $interchange = DB::table('interchange_product')->where('id', $inter_id)->first();

        if (!$interchange) {
            return response()->json(['error' => 'Interchange product not found'], 404);
        }

        $group_id = $interchange->group_id;

        // Count how many products belong to this group
        $count = DB::table('interchange_product')->where('group_id', $group_id)->count();

        if ($count > 2) {
            // If more than 2 products, delete only the requested one
            DB::table('interchange_product')->where('id', $inter_id)->delete();
        } else {
            // If only 2 or fewer, delete both and the group
            DB::table('interchange_product')->where('group_id', $group_id)->delete();
            DB::table('interchange_group')->where('id', $group_id)->delete();
        }

        return response()->json(['success' => true]);
    }
    
    public function bulkDeleteproduct(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No product selected.');
        }

        ProductTemplate::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected product deleted successfully.');
    }
    public function updateProductStatus(Request $request)
    {
        $user = ProductTemplate::find($request->product_id);
        $user->is_active=$request->status;
        $user->save();
    }
    public function deleteProduct(Request $request)
    {
        //This function is for delete the policy
        $product = ProductTemplate::find($request->product_id);
        $product->is_deleted=1;
        $product->save();
    }
    public function addProductCatalogue(Request $request)
    {
        //This function is for add product to catalogue
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        if ($request->isMethod('post'))
        {
            $insertData     = array();
            $brand_id       = $request->brand_id;
            $model_id       = $request->model_id;
            $generation_id  = $request->generation_id;

            $parts = DB::table('subcategory')->get();
            
            foreach ($parts as $part) 
            {
                // Check if combination already exists
                $exists = DB::table('product_template')->where([
                    'brand_id'       => $brand_id,
                    'make_model_id'  => $model_id,
                    'generation_id'  => $generation_id,
                    'category_id'    => $part->category_id,
                    'subcategory_id' => $part->id,
                ])->exists();

                if (!$exists) {
                    $insertData[] = [
                        'brand_id'       => $brand_id,
                        'make_model_id'  => $model_id,
                        'generation_id'  => $generation_id,
                        'category_id'    => $part->category_id,
                        'subcategory_id' => $part->id,
                        'created_at'     => now(),
                    ];
                }
            }

            // Chunk insert for performance
            foreach (array_chunk($insertData, 1000) as $chunk) {
                DB::table('product_template')->insert($chunk);
            }
            if(count($insertData)==0)
            {
                return redirect()->route("admin.addProductCatalogue")->with("error", " Product Template already added.");
            }
            else{
                return redirect()->route("admin.addProductCatalogue")->with("success", count($insertData)." Product Template added successfully.");
            }
        }

        return view('admin.add_catalogue',compact('brand'));
    }
    public function addPartCatalogue(Request $request)
    {
        //This function is for adding the part to all vehicle
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->get();
        if ($request->isMethod('post'))
        {
            $insertData     = array();
            $category_id    = $request->category_id;
            $subcategory_id = $request->subcategory_id;

             $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
             foreach($brand as $brands)
             {
                $models=DB::table('make_model')->where('brand_id',$brands->id)->where('is_active',1)->where('is_deleted',0)->get();
                foreach($models as $model)
                {
                    $generation=DB::table('generation_year')->where('model_id',$model->id)->where('is_active',1)->where('is_deleted',0)->get();
                    foreach($generation as $generations)
                    {
                        // Check if combination already exists
                        $exists = DB::table('product_template')->where([
                            'brand_id'       => $generations->brand_id,
                            'make_model_id'  => $generations->model_id,
                            'generation_id'  => $generations->id,
                            'category_id'    => $category_id,
                            'subcategory_id' => $subcategory_id,
                        ])->exists();

                        if (!$exists) {
                            $insertData[] = [
                                'brand_id'       => $generations->brand_id,
                                'make_model_id'  => $generations->model_id,
                                'generation_id'  => $generations->id,
                                'category_id'    => $category_id,
                                'subcategory_id' => $subcategory_id,
                                'created_at'     => now(),
                            ];
                        }
                    }
                }
             }

             foreach (array_chunk($insertData, 1000) as $chunk) {
                DB::table('product_template')->insert($chunk);
            }

            if(count($insertData)==0)
            {
                return redirect()->route("admin.addPartCatalogue")->with("error", " Product Template already added.");
            }
            else{
                return redirect()->route("admin.addPartCatalogue")->with("success", count($insertData)." Product Template added successfully.");
            }
        }
        return view('admin.add_part_catalogue',compact('category'));   
    }
}