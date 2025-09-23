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
use App\Models\Mgroups;
use App\Models\UniqueProduct;
use Yajra\DataTables\Facades\DataTables;


class GroupController extends Controller
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
    public function groupList()
    {
        //This function is for show group list
        $groups=DB::table('mgroups')->where('is_deleted',0)->orderby('id','desc')->get();
        return view('admin.group_list',compact('groups'));
    }
    public function createGroup(Request $request,$id=null)
    {
        $group_detail="";
        $group_model_combinations[]='';
        $make_model=DB::table('make_model')
                    ->join('brand','brand.id','=','make_model.brand_id')
                    ->join('generation_year','generation_year.model_id','=','make_model.id')
                    ->where('brand.is_deleted',0)
                    ->where('make_model.is_deleted',0)
                    ->select('make_model.*','brand.brand_name','brand.ar_brand_name','generation_year.start_year','generation_year.end_year','generation_year.id as gen_id')
                    ->orderBy('make_model.id', 'DESC')
                    ->get();

        if($id!=null)
        {
            $group_detail=DB::table('mgroups')->where('id',$id)->first();
            $group_model_combinations = DB::table('group_model')
                                ->where('group_id', $id)
                                ->get(['brand_id', 'model_id', 'generation_id'])
                                ->map(fn($item) => $item->brand_id . '-' . $item->model_id . '-' . $item->generation_id)
                                ->toArray();
        }

        if ($request->isMethod('post'))
        {
            if($request->group_id)
            {
                $cat_check=DB::table('mgroups')->where('group_name',$request->group_name)->where('id', '!=', $request->group_id)->where('is_deleted', '!=', 0)->first();
                if($cat_check)
                {
                    return redirect()->route("admin.groupList")->with("error", "Group Already Exist.");
                }
            }
            else
            {
                $cat_check=DB::table('mgroups')->where('group_name',$request->group_name)->where('is_deleted', '!=', 0)->first();
                if($cat_check)
                {
                    return redirect()->route("admin.groupList")->with("error", "Group Already Exist.");
                }
            }

            $category = Mgroups::find($request->group_id);
            $message="Group updated successfully.";
            if (!$category) 
            {
                $category = new Mgroups(); 
                $message="Group added successfully.";
            }                       

            $category->group_name       = $request->group_name;
            $category->group_note       = $request->group_note;
            
            $category->created_at     = date('Y-m-d H:i:s');
            $category->save();
            $group_id=$category->id;

            if ($request->ids && $request->brand_ids && $request->gen_ids) 
            {
                $brand_id = $request->brand_ids;
                $model_id = $request->ids;
                $generation_ids = $request->gen_ids;
                

                // Step 1: Build submitted combinations (all 4 keys)
                $submitted = [];
                foreach ($generation_ids as $index => $gen_id) {
                    $submitted[] = [
                        'group_id' => (int) $group_id,
                        'brand_id' => (int) $brand_id[$index],
                        'model_id' => (int) $model_id[$index],
                        'generation_id' => (int) $gen_id,
                    ];
                }

                // Step 2: Fetch existing combinations from DB
                $existing = \DB::table('group_model')
                    ->where('group_id', $group_id)
                    ->get(['group_id', 'brand_id', 'model_id', 'generation_id'])
                    ->map(fn($item) => [
                        'group_id' => (int) $item->group_id,
                        'brand_id' => (int) $item->brand_id,
                        'model_id' => (int) $item->model_id,
                        'generation_id' => (int) $item->generation_id,
                    ])
                    ->toArray();

                // Step 3: Compare as JSON for simple diffing
                $submittedJson = collect($submitted)->map(fn($row) => json_encode($row))->toArray();
                $existingJson = collect($existing)->map(fn($row) => json_encode($row))->toArray();

                $toInsertJson = array_diff($submittedJson, $existingJson);
                $toDeleteJson = array_diff($existingJson, $submittedJson);

                // Step 4: Delete removed combinations
                foreach ($toDeleteJson as $json) {
                    $row = json_decode($json, true);
                    \DB::table('group_model')
                        ->where('group_id', $row['group_id'])    
                        ->where('brand_id', $row['brand_id'])
                        ->where('model_id', $row['model_id'])
                        ->where('generation_id', $row['generation_id'])
                        ->delete();
                }

                // Step 5: Insert new combinations
                $insertData = [];
                foreach ($toInsertJson as $json) {
                    $row = json_decode($json, true);
                    $insertData[] = array_merge($row, ['created_at' => now()]);
                }

                if (!empty($insertData)) {
                    \DB::table('group_model')->insert($insertData);
                }
            }

            return redirect()->route("admin.groupList")->with("success", $message);
        }
        return view('admin.create_group',compact('group_detail','make_model','group_model_combinations'));
    }
    public function updateGroupStatus(Request $request)
    {
        $user = Mgroups::find($request->user);
        $user->is_active=$request->status;
        $user->save();
    }

    public function addUniversalProduct(Request $request,$id)
    {
        $group_detail=DB::table('mgroups')->where('id',$id)->first();

        $parts=DB::table('category')
                        ->Join('subcategory', 'subcategory.category_id', '=', 'category.id')
                        ->where('category.is_active',1)->where('category.is_deleted',0)
                        ->get();
        $group_part_ids = DB::table('mgroup_product')
                            ->where('group_id', $id)
                            ->pluck('subcategory_id')
                            ->toArray();
        if ($request->isMethod('post'))
        {
            $group_id=$request->group_id;
            if ($request->ids && $request->brand_ids) 
            {
                $newModelIds = $request->ids;
                $newBrandIds = $request->brand_ids;
                
                // Step 1: Get existing model IDs for this group
                $existingModelIds = DB::table('mgroup_product')
                    ->where('group_id', $group_id)
                    ->pluck('subcategory_id')
                    ->toArray();

                // Step 2: Delete removed models (unchecked)
                $modelsToDelete = array_diff($existingModelIds, $newModelIds);

                if (!empty($modelsToDelete)) {
                    DB::table('mgroup_product')
                        ->where('group_id', $group_id)
                        ->whereIn('subcategory_id', $modelsToDelete)
                        ->delete();
                }

                // Step 3: Insert only new checked models (that didn't exist before)
                $modelsToInsert = array_diff($newModelIds, $existingModelIds);
                $dataToInsert = [];

                foreach ($request->ids as $index => $modelId) {
                    if (in_array($modelId, $modelsToInsert)) {
                        $brandId = $request->brand_ids[$index] ?? null;

                        if ($brandId) {
                            $dataToInsert[] = [
                                'group_id'   => $group_id,
                                'category_id'   => $brandId,
                                'subcategory_id'   => $modelId,
                                'created_at' => now(),
                            ];
                        }
                    }
                }

                if (!empty($dataToInsert)) {
                    DB::table('mgroup_product')->insert($dataToInsert);
                }
            }

            return redirect()->route("admin.addUniversalProduct",['id'=>$group_id])->with("success", "Universal Product added to group");
        }
        return view('admin.add_group_product',compact('group_detail','parts','group_part_ids'));
    }
    public function viewGroupDetail($id)
    {
        //This function is for view group detail
        $group_detail=DB::table('mgroups')->where('id',$id)->first();

        $make_model=DB::table('group_model')
                    ->join('brand','brand.id','=','group_model.brand_id')
                    ->join('make_model','make_model.id','=','group_model.model_id')
                    ->join('generation_year','generation_year.id','=','group_model.generation_id')
                    ->select('make_model.*','brand.brand_name','make_model.model_name','generation_year.start_year','generation_year.end_year')
                    ->where('group_model.group_id', $id)
                    ->orderBy('make_model.id', 'DESC')
                    ->get();

        $parts=DB::table('mgroup_product')
                    ->join('category','category.id','=','mgroup_product.category_id')
                    ->join('subcategory','subcategory.id','=','mgroup_product.subcategory_id')
                    ->select('subcategory.*','category.category_name','subcategory.subcat_name')
                    ->where('mgroup_product.group_id', $id)
                    ->orderBy('subcategory.id', 'DESC')
                    ->get();

        return view('admin.view_group_detail',compact('make_model','group_detail','parts'));
    }
    public function uniqueProductList()
    {
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->orderby('brand_name','asc')->get();
        //$firstBrandId = $brand->first()->id ?? null;
        $model=DB::table('make_model')->where('is_active',1)->where('is_deleted',0)->orderby('model_name','asc')->get();
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->orderby('category_name','asc')->get();
        $subcategory=DB::table('subcategory')->where('is_active',1)->where('is_deleted',0)->orderby('subcat_name','asc')->get();        
        
        return view('admin.unique_product_list',compact('brand','category','model','subcategory'));
    }
    public function getUniqueProduct(Request $request)
    {
        $query = UniqueProduct::query()
                            ->leftJoin('brand', 'brand.id', '=', 'unique_product.brand_id')
                            ->leftJoin('make_model', 'make_model.id', '=', 'unique_product.model_id')
                            ->leftJoin('category', 'category.id', '=', 'unique_product.category_id')
                            ->leftJoin('subcategory', 'subcategory.id', '=', 'unique_product.subcategory_id');
        // Filters
        if ($request->brand_id) {
            $query->where('unique_product.brand_id', $request->brand_id);
        }
        if ($request->model_id) {
            $query->where('unique_product.model_id', $request->model_id);
        }
        if ($request->category_id) {
            $query->where('unique_product.category_id', $request->category_id);
        }
        if ($request->subcategory_id) {
            $query->where('unique_product.subcategory_id', $request->subcategory_id);
        }
        $query->distinct('unique_product.id');
        
        // Important: select necessary columns only, avoid ambiguous column names
        $query->select(
                    'unique_product.id',
                    'brand.brand_name as brand_name',
                    'make_model.model_name as model_name',
                    'category.category_name as category_name',
                    'subcategory.subcat_name as subcategory_name',
                    
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
                            ->addColumn('action', function($row) {
                                $editUrl = route('admin.addUniqueProduct', $row->id);
                                $viewUrl = route('admin.viewAdminProduct', ['id' => $row->id]);
                                return '<a href="javascript:void(0)" class="del_product" user_id="'.$row->id.'"><i class="mdi mdi-delete"></i></a>';
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
    public function addUniqueProduct(Request $request)
    {
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->orderby('brand_name','asc')->get();
        $parts=DB::table('category')
                        ->Join('subcategory', 'subcategory.category_id', '=', 'category.id')
                        ->where('category.is_active',1)->where('category.is_deleted',0)
                        ->get();

        if ($request->isMethod('post'))
        {
            $brand_id = $request->brand_id;
            $model_id = $request->model_id;
            $generation_id= $request->generation_id;
            $subcategory_ids = $request->ids;
            $category_ids = $request->category_ids;

            // Step 1: Build submitted combinations (all 4 keys)
            $submitted = [];
            foreach ($subcategory_ids as $index => $subcat_id) {
                $submitted[] = [
                    'brand_id' => (int) $brand_id,
                    'model_id' => (int) $model_id,
                    'category_id' => (int) $category_ids[$index],
                    'subcategory_id' => (int) $subcat_id,
                    'generation_id'=>(int) $generation_id,
                ];
            }

            // Step 2: Fetch existing combinations from DB
            $existing = \DB::table('unique_product')
                ->where('brand_id', $brand_id)
                ->where('model_id', $model_id)
                ->get(['brand_id', 'model_id', 'category_id', 'subcategory_id','generation_id'])
                ->map(fn($item) => [
                    'brand_id' => (int) $item->brand_id,
                    'model_id' => (int) $item->model_id,
                    'category_id' => (int) $item->category_id,
                    'subcategory_id' => (int) $item->subcategory_id,
                    'generation_id'=>(int) $item->generation_id
                ])
                ->toArray();

            // Step 3: Compare as JSON for simple diffing
            $submittedJson = collect($submitted)->map(fn($row) => json_encode($row))->toArray();
            $existingJson = collect($existing)->map(fn($row) => json_encode($row))->toArray();

            $toInsertJson = array_diff($submittedJson, $existingJson);
            $toDeleteJson = array_diff($existingJson, $submittedJson);

            // Step 4: Delete removed combinations
            foreach ($toDeleteJson as $json) {
                $row = json_decode($json, true);
                \DB::table('unique_product')
                    ->where('brand_id', $row['brand_id'])
                    ->where('model_id', $row['model_id'])
                    ->where('category_id', $row['category_id'])
                    ->where('subcategory_id', $row['subcategory_id'])
                    ->where('generation_id', $row['generation_id'])
                    ->delete();
            }

            // Step 5: Insert new combinations
            $insertData = [];
            foreach ($toInsertJson as $json) {
                $row = json_decode($json, true);
                $insertData[] = array_merge($row, ['created_at' => now()]);
            }

            if (!empty($insertData)) {
                \DB::table('unique_product')->insert($insertData);
            }

            return redirect()->back()->with('success', 'Unique products updated successfully.');
        }
        return view('admin.add_unique_product',compact('brand','parts'));
    }
    public function deleteUniqueProduct(Request $request)
    {
        DB::table('unique_product')->where('id', $request->product_id)->delete();

        return response()->json(['success' => true]);
    }
    public function bulkDeleteunique(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No product selected.');
        }

        DB::table('unique_product')->whereIn('id', $ids)->delete();
        
        return redirect()->back()->with('success', 'Selected product deleted successfully.');
    }
    public function commonProductList()
    {
        //This function is for common product list

        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->orderby('brand_name','asc')->get();
        //$firstBrandId = $brand->first()->id ?? null;
        $model=DB::table('make_model')->where('is_active',1)->where('is_deleted',0)->orderby('model_name','asc')->get();
        $category=DB::table('category')->where('is_active',1)->where('is_deleted',0)->orderby('category_name','asc')->get();
        $subcategory=DB::table('subcategory')->where('is_active',1)->where('is_deleted',0)->orderby('subcat_name','asc')->get();

        $products = DB::table('mgroup_product as mp')
                        ->leftJoin('mgroups as g', 'mp.group_id', '=', 'g.id')
                        ->leftJoin('group_model as gm', 'mp.group_id', '=', 'gm.group_id')
                        ->leftJoin('category as c', 'mp.category_id', '=', 'c.id')
                        ->leftJoin('subcategory as sc', 'mp.subcategory_id', '=', 'sc.id')
                        ->leftJoin('generation_year as gy', 'gm.generation_id', '=', 'gy.id')
                        ->select([
                            'g.group_name as model_name',
                            DB::raw('"" as brand_name'), // brand name empty
                            'c.category_name',
                            'sc.subcat_name',
                            'gy.start_year',
                            'gy.end_year',
                            'mp.product_note',
                            'mp.product_description',
                            'mp.product_image',
                            'mp.created_at',
                        ])
                        ->get();
        
        $products2 = DB::table('unique_product')
                            ->leftJoin('brand', 'brand.id', '=', 'unique_product.brand_id')
                            ->leftJoin('make_model', 'make_model.id', '=', 'unique_product.model_id')
                            ->leftJoin('category', 'category.id', '=', 'unique_product.category_id')
                            ->leftJoin('subcategory', 'subcategory.id', '=', 'unique_product.subcategory_id')
                            ->leftjoin('generation_year','generation_year.id','=','unique_product.generation_id')
                            ->select([
                                    'unique_product.id',
                                    'brand.brand_name as brand_name',
                                    'make_model.model_name as model_name',
                                    'category.category_name as category_name',
                                    'subcategory.subcat_name as subcategory_name',
                                    'generation_year.start_year','generation_year.end_year'
                            ])
                            ->get();
        return view('admin.all_product_list',compact('brand','model','category','subcategory','products'));
    }
    public function getCommonProduct(Request $request)
    {
        $query = ProductTemplate::query()
                            ->leftJoin('brand', 'brand.id', '=', 'product_template.brand_id')
                            ->leftJoin('make_model', 'make_model.id', '=', 'product_template.make_model_id')
                            ->leftJoin('category', 'category.id', '=', 'product_template.category_id')
                            ->leftJoin('subcategory', 'subcategory.id', '=', 'product_template.subcategory_id')
                            ->leftjoin('generation_year','generation_year.id','=','product_template.generation_id');
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
}
?>