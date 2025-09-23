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
use App\Models\Brand;
use Yajra\DataTables\Facades\DataTables;


class ParentController extends Controller
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
    public function addMakeParent(Request $request, $id=null)
    {
        //This function is for adding the make (brand) parent
        $brand=DB::table('brand')->where('is_active',1)->where('is_deleted',0)->get();
        $parent_detail='';$parent_brand[]='';
        if ($id != null) 
        {
            $parent_detail = DB::table('mparents')->where('id', $id)->first(); 
            $parent_brand = DB::table('mparent_brand')
                ->where('mparents_id', $id)
                ->pluck('brand_id')
                ->toArray();   
        }
        
        if ($request->isMethod('post')) 
        {
            $request->validate([
                'mparents_name' => 'required|string|max:255',
                'mparents_text' => 'required|string|max:500',
                'ids' => 'nullable|array',
                'ids.*' => 'integer|exists:brand,id',
            ]);

            $mparents_id = $request->input('mparents_id');
            $brandIds = $request->input('ids', []);

            if ($mparents_id) {
                // --- UPDATE ---
                $group = DB::table('mparents')->where('id', $mparents_id)->first();

                if (!$group) {
                    return redirect()->back()->with('error', 'Parent not found.');
                }

                // Update group details
                DB::table('mparents')
                    ->where('id', $mparents_id)
                    ->update([
                        'mparents_name' => $request->mparents_name,
                        'mparents_text' => $request->mparents_text,
                        'updated_at' => now(),
                    ]);

                $groupId = $mparents_id;

            } else {
                // --- CREATE ---
                $groupId = DB::table('mparents')->insertGetId([
                    'mparents_name' => $request->mparents_name,
                    'mparents_text' => $request->mparents_text,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Sync brand associations
            $existingBrands = DB::table('mparent_brand')
                ->where('mparents_id', $groupId)
                ->pluck('brand_id')
                ->toArray();

            $newBrands = $brandIds ?? [];

            $toDelete = array_diff($existingBrands, $newBrands);
            $toInsert = array_diff($newBrands, $existingBrands);

            if (!empty($toDelete)) {
                DB::table('mparent_brand')
                    ->where('mparents_id', $groupId)
                    ->whereIn('brand_id', $toDelete)
                    ->delete();
            }

            foreach ($toInsert as $brandId) {
                DB::table('mparent_brand')->insert([
                    'mparents_id' => $groupId,
                    'brand_id' => $brandId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->route('admin.parentList')->with('success', 'Parent saved successfully.');
        }

        return view('admin.create_make_parent',compact('brand','parent_detail','parent_brand'));
    }
    public function parentList()
    {
        //This function is show the parent list
        $parents = DB::table('mparents')->get(); 
        return view('admin.parent_list',compact('parents'));
    }
    public function showParent($id)
    {
        $parent_detail = DB::table('mparents')->where('id', $id)->first(); 
        $parent_brand = DB::table('mparent_brand')
            ->join('brand','brand.id','=','mparent_brand.brand_id')
            ->where('mparents_id', $id)
            ->select('mparent_brand.*','brand.brand_name')
            ->get(); 

        return view('admin.mparent_brand_list',compact('parent_detail','parent_brand'));
    }
    public function deletemBrand($id)
    {
        $deleted = DB::table('mparent_brand')->where('id', $id)->delete();

        if ($deleted) {
            return redirect()->route('admin.parentList')->with('success', 'Brand Removed successfully.');
        }
        return redirect()->route('admin.parentList')->with('error', 'Brand can not remove.');
    }
}