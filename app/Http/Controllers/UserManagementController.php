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
use App\Models\User;
use App\Models\ShopDetail;
use App\Models\UserService;
use App\Models\UserLanguage;
use Yajra\DataTables\Facades\DataTables;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;


use Spatie\Browsershot\Browsershot;
use App\Models\Business;


class UserManagementController extends Controller
{
    public function __construct()
    {
        if (Auth::guard("admin")->user()) {
            $user = Auth::guard("admin")->user();
        } else {
            Auth::guard("admin")->logout();
            return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
        }
    }
    public function generateQrCode($id, $shop)
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
    public function generateQrCodeLogo()
    {
        $text = route('sellerMiniPage', ['id' => 123]); // Replace with dynamic route
        $fileName = 'qr_with_logo_' . time() . '.png';
        $filePath = public_path('uploads/qr_code/' . $fileName);

        // Ensure directory exists
        File::ensureDirectoryExists(public_path('uploads/qr_code'));

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($text)
            ->size(300)
            ->margin(10)
            ->logoPath('https://votivetechnology.in/autopart/public/web_assets/images/fav_icon.png') // <-- Path to your logo
            ->logoResizeToWidth(50)
            ->logoResizeToHeight(50)
            ->build();

        // Save QR code
        file_put_contents($filePath, $result->getString());

        // Return QR info
        return response()->json([
            'url' => asset('public/uploads/qr_code/' . $fileName),
            'path' => $filePath,
        ]);
    }
    public function userList()
    {
        $users = DB::table('users')
            ->leftjoin('master_country', 'master_country.country_id', '=', 'users.country_id')
            ->where('user_type', 1)
            ->where('is_deleted', 0)
            ->select('users.*', 'master_country.country_name')
            ->orderBy('id', 'DESC')
            ->paginate(20);
        return view('admin.user_list', compact('users'));
    }
    public function updateUserStatus(Request $request)
    {
        $user = User::find($request->user);
        $user->user_status = $request->status;
        $user->save();
    }
    public function deleteUser(Request $request)
    {
        //This function is for ajax to delete the user
        $user = User::find($request->user);
        $user->is_deleted = 1;
        $user->save();
    }
    public function addUser(Request $request, $id = null)
    {
        $country = DB::table('master_country')->where('country_status', 1)->get();
        $user_detail = $state = $city = "";
        if ($id != null) {
            $user_detail = DB::table('users')->where('id', $id)->first();
            $state = DB::table('master_state')->where('state_country_id', $user_detail->country_id)->get();
            $city = DB::table('master_city')->where('city_state_id', $user_detail->state_id)->get();
        }
        if ($request->isMethod('post')) {
            if ($request->user_id) {
                $useremail  = DB::table('users')->where('email', '=', $request->email)->where('id', '!=', $request->user_id)->where('is_deleted', '=', 0)->first();

                if (!empty($useremail)) {
                    return redirect()->back()->with(["error" => "The email has already been taken."])->withInput();
                }
            } else {
                $useremail  = DB::table('users')->where('email', '=', $request->email)->where('is_deleted', '=', 0)->first();

                if (!empty($useremail)) {
                    return redirect()->back()->with(["email" => "The email has already been taken."])->withInput();
                }
            }

            $user = User::find($request->user_id);
            $message = "User profile updated successfully.";
            if (!$user) {
                $user = new User();
                $message = "User profile added successfully.";
            }

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/profile_image'), $imageName);
                $user->profile_image = $imageName;
            }

            $user->first_name       = $request->first_name;
            $user->last_name        = $request->last_name;
            $user->email            = $request->email;
            if ($request->password != '') {
                $user->password         = Hash::make($request->password);
            }

            $user->gender           = $request->gender;
            $user->country_id       = $request->country_id;
            $user->state_id         = $request->state_id;
            $user->city_id          = $request->city_id;
            $user->address1         = $request->address1;
            $user->address2         = $request->address2;
            $user->zip_code         = $request->zip_code;
            $user->user_type        = 1;
            $user->user_status      = 1;
            $user->user_timezone    = $request->user_time;
            $user->email_verified   = 1;
            $user->created_at       = date('Y-m-d H:i:s');
            $user->save();
            return redirect()->route("admin.userList")->with("success", $message);
        }

        return view('admin.add_user', compact('country', 'user_detail', 'state', 'city'));
    }
    public function viewUser($id)
    {
        if ($id != null) {
            $user_detail = DB::table('users')
                ->leftjoin('master_country as mc', 'users.country_id', '=', 'mc.country_id')
                ->leftjoin('master_state as ms', 'users.state_id', '=', 'ms.state_id')
                ->leftjoin('master_city as c', 'users.city_id', '=', 'c.city_id')
                ->select('users.*', 'mc.country_name', 'ms.state_name', 'c.city_name')
                ->where('id', $id)->first();
        }
        return view('admin.view_user_profile', compact('user_detail'));
    }

    public function sellerList()
    {
        //This function is for list the coach
        $users = DB::table('users')
            ->leftjoin('master_country', 'master_country.country_id', '=', 'users.country_id')
            ->leftjoin('master_city', 'master_city.city_id', '=', 'users.city_id')
            ->leftjoin('shop_detail', 'shop_detail.user_id', '=', 'users.id')
            ->where('user_type', 2)
            ->where('is_deleted', 0)
            ->select('users.*', 'master_country.country_name','shop_detail.shop_name','master_city.city_name')
            //->orderBy('id', 'DESC')
            ->paginate(20);

        return view('admin.seller_list', compact('users'));
    }
    public function getAdminSellerList(Request $request)
    {
        $users = DB::table('users')
            ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
            ->leftJoin('shop_detail', 'shop_detail.user_id', '=', 'users.id')
            ->where('user_type', 2)
            ->where('is_deleted', 0)
            ->select(
                'users.id',
                'users.user_name',
                'users.last_name',
                'users.mobile',
                'users.user_status',
                'shop_detail.shop_name',
                'master_city.city_name'
            );

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="selectBox" name="ids[]" value="' . $row->id . '">';
            })
            ->addColumn('user_name', function ($row) {
                return $row->user_name ;
            })
            ->addColumn('business_name', function ($row) {
                return $row->shop_name ?? '-';
            })
            ->addColumn('phone_number1', function ($row) {
                return $row->mobile ?? '-';
            })
            ->addColumn('city', function ($row) {
                return $row->city_name ?? '-';
            })
            ->addColumn('status', function ($row) {
                $status = '<select class="user_status form-select form-select-sm" user="' . $row->id . '">';
                $status .= '<option value="0" ' . ($row->user_status == 0 ? 'selected' : '') . '>Pending</option>';
                $status .= '<option value="1" ' . ($row->user_status == 1 ? 'selected' : '') . '>Approved</option>';
                $status .= '<option value="2" ' . ($row->user_status == 2 ? 'selected' : '') . '>Suspended</option>';
                $status .= '</select>';
                return $status;
            })

            ->addColumn('action', function ($row) {
                $deleteBtn = '<a href="javascript:void(0)" class="del_user" user_id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                  </a>';

                $editBtn = '<a href="' . route('admin.sellerProfile', $row->id) . '">
                    <i class="mdi mdi-lead-pencil"></i>
                </a>';

                $viewBtn = '<a href="' . route('admin.viewSeller', ['id' => $row->id]) . '">
                    <i class="mdi mdi-eye"></i>
                </a>';

                return $deleteBtn . ' | ' . $editBtn . ' | ' . $viewBtn;
            })

            ->rawColumns(['checkbox', 'status', 'action'])
            ->make(true);
    }
    public function sellerProfile(Request $request, $id = null)
    {
        $country = DB::table('master_country')->where('country_status', 1)->get();
        $services = DB::table('services')->get();

        $user_detail = $state = $city = $shop_detail = "";
        $seller_service_ids = array();
        if ($id != null) {
            $user_detail = DB::table('users')->where('id', $id)->first();
            //$state=DB::table('master_state')->where('state_country_id',$user_detail->country_id)->get();
            $city = DB::table('master_city')->where('country_id', $user_detail->country_id)->get();
            $seller_service_ids = DB::table('seller_service')
                ->where('seller_id', $id)
                ->pluck('service_id') // get only service IDs
                ->toArray();
            if ($user_detail->user_type == 2) {
                $shop_detail = DB::table('shop_detail')->where('user_id', $user_detail->id)->first();
            }
        }

        return view('admin.seller_profile', compact('country', 'user_detail', 'state', 'city', 'shop_detail', 'services', 'seller_service_ids'));
    }
    public function addSeller(Request $request, $id = null)
    {

        if ($request->isMethod('post')) {
            if ($request->email != '') {
                if ($request->user_id) {
                    $useremail  = DB::table('users')->where('email', '=', $request->email)->where('id', '!=', $request->user_id)->where('is_deleted', '=', 0)->first();

                    if (!empty($useremail)) {
                        return redirect()->back()->with(["error" => "The email has already been taken."])->withInput();
                    }
                } else {
                    $useremail  = DB::table('users')->where('email', '=', $request->email)->where('is_deleted', '=', 0)->first();

                    if (!empty($useremail)) {
                        return redirect()->back()->with(["email" => "The email has already been taken."])->withInput();
                    }
                }
            }

            $user = User::find($request->user_id);
            $message = "Seller profile updated successfully.";
            if (!$user) {
                $user = new User();
                $message = "Seller profile added successfully.";
                $user->user_status = 1;
            }

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/profile_image'), $imageName);
                $user->profile_image = $imageName;
            }

            $user->first_name       = $request->first_name;
            $user->last_name        = $request->last_name;
            $user->email            = $request->email;
            $user->user_name        = $request->user_name;

            if ($request->password != '') {
                $user->password         = Hash::make($request->password);
            }

            $user->gender           = $request->gender;
            $user->country_id       = $request->country_id;
            // $user->state_id         = $request->state_id;
            $user->city_id          = $request->city_id;
            $user->address1         = $request->address1;
            $user->address2         = $request->address2;
            $user->address1_ar      = $request->address1_ar;
            $user->address2_ar      = $request->address2_ar;
            $user->zip_code         = $request->zip_code;
            $user->latitude         = $request->latitude;
            $user->longitude        = $request->longitude;
            $user->mobile           = $request->mobile;
            $user->mobile_2         = $request->mobile_2;
            $user->whatsapp1        = $request->whatsapp1;
            $user->whatsapp2        = $request->whatsapp2;
            $user->user_type        = 2;
            $user->user_timezone    = $request->user_time;
            $user->email_verified   = 1;
            $user->created_at       = date('Y-m-d H:i:s');
            $user->save();
            $user_id = $user->id;

            //$shop = ShopDetail::find($request->user_id);
            $shop = ShopDetail::where('user_id', $request->user_id)->first();
            $isNew = false;
            if (!$shop) {
                $shop = new ShopDetail();
                $isNew = true;
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
            // if($isNew)
            // {
            //     $this->generateQrCode($user_id,$request->shop_name);
            // }

            $submittedServices = $request->input('service_id', []);
            if ($submittedServices) {
                DB::table('seller_service')
                    ->where('seller_id', $user_id)
                    ->whereNotIn('service_id', $submittedServices)
                    ->delete();

                foreach ($submittedServices as $service_id) {
                    DB::table('seller_service')->updateOrInsert(
                        ['seller_id' => $user_id, 'service_id' => $service_id],
                        ['created_at' => now()] // optional if you have timestamps
                    );
                }
            }

            $this->generateQrCode($user_id, $request->shop_name);
            //Now generate the new business card
            //$this->sellerDigitalCard($user_id);

            return redirect()->route("admin.sellerList")->with("success", $message);
        }
    }

    public function sellerDigitalCard($id)
    {
        $user  = DB::table('users')->where('id', '=', $id)
            ->leftJoin('master_country', 'master_country.country_id', '=', 'users.country_id')
            //->leftJoin('master_state', 'master_state.state_id', '=', 'users.state_id')
            ->leftJoin('master_city', 'master_city.city_id', '=', 'users.city_id')
            ->select(
                'users.*',
                'master_city.city_name',
                'master_country.country_name'
            )
            ->first();
        $shop  = DB::table('shop_detail')->where('user_id', '=', $id)->first();

        $service = DB::table('seller_service')
            ->where('seller_id', $id)
            ->join('services', 'services.id', '=', 'seller_service.service_id')->get();


        $qrFile = public_path('uploads/qr_code/' . $shop->qr_code);
        $qrBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($qrFile));

        $shop_url = url('/') . '/' . $user->city_name . '/' . strtolower(str_replace(' ', '', $shop->shop_name));

        $html = view('admin.seller_card', [
            'shop' => $shop,
            'user'  => $user,
            'qrPath' => $qrBase64,
            'service' => $service,
            'shop_url' => $shop_url
        ])->render();

        $fileName = 'card_' . $shop->id . '_' . $shop->shop_name . '.png';
        $destinationPath = public_path('uploads/business_card');

        $fullPath = $destinationPath . '/' . $fileName;

        Browsershot::html($html)
            ->windowSize(530, 300)
            ->waitUntilNetworkIdle()
            ->save($fullPath);

        // 6. Update business table with filename
        // $business->card_image = $fileName;
        // $business->save();

        $shop = ShopDetail::where('user_id', $id)->first();
        $shop->digital_card = $fileName;
        $shop->save();

        // 7. Return success or download (optional)
        // return response()->json([
        //     'message' => 'Card generated and saved successfully.',
        //     'image_url' => asset('uploads/business_card/' . $fileName),
        // ]);

        //return view('admin.seller_card',compact('user','shop'));
    }

    public function viewSeller($id)
    {
        //This function is for view the coach profile
        if ($id != null) {
            $user_detail = DB::table('users')
                ->leftjoin('master_country as mc', 'users.country_id', '=', 'mc.country_id')
                //->leftjoin('master_state as ms','users.state_id','=','ms.state_id')
                ->leftjoin('master_city as c', 'users.city_id', '=', 'c.city_id')
                ->select('users.*', 'mc.country_name', 'c.city_name')
                ->where('id', $id)->first();
            if ($user_detail->user_type == 2) {
                $shop_detail = DB::table('shop_detail')->where('user_id', $user_detail->id)->first();
                $services = DB::table('seller_service')
                    ->leftjoin('services', 'seller_service.service_id', '=', 'services.id')
                    ->where('seller_service.seller_id', $id)->get();
            }
        }
        return view('admin.view_seller_profile', compact('user_detail', 'shop_detail', 'services'));
    }
    public function viewSellerEnquiry($id)
    {
        $seller_id = $id;
        $sellreques = DB::table('seller_request')
            ->join('users', 'users.id', '=', 'seller_request.seller_id')
            ->where('seller_request.seller_id', $seller_id)
            ->select('seller_request.*', 'users.user_name', 'users.first_name', 'users.last_name')
            ->orderBy('seller_request.id', 'DESC')
            ->get();

        return view('admin.view_seller_enquiry', compact('seller_id', 'sellreques'));
    }
    public function viewSellerProduct($id)
    {
        $brand = DB::table('brand')->where('is_active', 1)->where('is_deleted', 0)->get();
        $category = DB::table('category')->where('is_active', 1)->where('is_deleted', 0)->get();
        $seller_id = $id;
        return view('admin.view_seller_product', compact('brand', 'category', 'seller_id'));
    }
    public function getSellerProduct(Request $request)
    {
        $query = DB::table('product')->where('seller_id', $request->seller_id)
            ->leftJoin('brand', 'brand.id', '=', 'product.brand_id')
            ->leftJoin('make_model', 'make_model.id', '=', 'product.model_id')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'product.subcategory_id')
            ->leftJoin('generation_year', 'generation_year.id', '=', 'product.generation_id')
            ->leftJoin('part_type', 'part_type.id', '=', 'product.part_type_id')
            ->leftJoin('product_img', 'product_img.product_id', '=', 'product.id')
            ->where('product.is_deleted', 0);
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
            'brand.brand_name as brand_name',
            'make_model.model_name as model_name',
            'category.category_name as category_name',
            'subcategory.subcat_name as subcategory_name',
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
        if ($request->has('prevent')) {
            return DataTables::of(collect([]))->make(true);
        }

        return DataTables::of($query)
            ->addIndexColumn() // This auto-creates DT_RowIndex for serial number
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="ids[]" value="' . $row->id . '" class="selectBox">';
            })
            ->addColumn('brand', fn($row) => $row->brand_name ?? '')
            ->addColumn('model', fn($row) => $row->model_name ?? '')
            ->addColumn('category', fn($row) => $row->category_name ?? '')
            ->addColumn('subcategory', fn($row) => $row->subcategory_name ?? '')
            ->addColumn('generation', fn($row) => $row->start_year . ' - ' . $row->end_year)
            ->addColumn('variant', fn($row) => $row->part_type_label)
            ->addColumn('price', fn($row) => $row->product_price)
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
    public function bulkDeleteusr(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No user selected.');
        }

        User::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected user deleted successfully.');
    }
    public function bulkDeleteCoach(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No seller selected.');
        }

        User::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Seller deleted successfully.');
    }
    public function allRequest()
    {
        $sellreques = DB::table('seller_request')
            ->join('users', 'users.id', '=', 'seller_request.seller_id')
            ->select('seller_request.*', 'users.user_name', 'users.first_name', 'users.last_name')
            ->orderBy('seller_request.id', 'DESC')
            ->get();
        return view('admin.seller_request_list', compact('sellreques'));
    }
    public function updateRequestStatus(Request $request)
    {
        //This function is for update the request status
        DB::table('seller_request')
            ->where('id', $request->user)
            ->update(['request_status' => $request->status]);
    }
}
