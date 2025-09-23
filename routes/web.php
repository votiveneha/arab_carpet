<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProductManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ParentController;
use Illuminate\Support\Facades\Session;
// web routes start

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::any('/productList', [HomeController::class, 'productList'])->name('productList');
Route::get('/productDetail/{id}', [HomeController::class, 'productDetail'])->name('productDetail');

Route::get('/shop/{city}/{shop}', [HomeController::class, 'shopDetail'])->name('shopDetail');


Route::get('/sellerMiniPage/{id}', [HomeController::class, 'sellerMiniPage'])->name('sellerMiniPage');
Route::post('/track-product-click', [HomeController::class, 'trackClick'])->name('product.track.click');
Route::get('/digitalCard/{id}', [HomeController::class, 'digitalCard'])->name('digitalCard');

Route::get('lang/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return redirect()->back();
})->name('lang.switch');


Route::get('/generateCombinations', [MasterController::class, 'generateCombinations'])->name('generateCombinations');

Route::post('/sign-up', [HomeController::class, 'register'])->name('web.signup');
Route::any('/logout', [HomeController::class, 'logout'])->name('web.logout');
Route::post('/login', [HomeController::class, 'login'])->name('web.login');
Route::get('/getPrivacyPolicy', [HomeController::class, 'getPrivacyPolicy'])->name('web.getPrivacyPolicy');
Route::get('/getTermsConditions', [HomeController::class, 'getTermsConditions'])->name('web.getTermsConditions');
Route::get('/aboutUs', [HomeController::class, 'aboutUs'])->name('web.aboutUs');
Route::get('/contactUs', [HomeController::class, 'contact'])->name('web.contact');
Route::get('/how-it-works', [HomeController::class, 'howWorks'])->name('web.howWorks');
Route::get('/getIndex', [HomeController::class, 'getIndex'])->name('web.index');
Route::get('/getMatrix', [HomeController::class, 'getMatrix'])->name('web.matrix');
Route::get('/getLayer', [HomeController::class, 'getLayer'])->name('web.layer');
Route::get('/getReference', [HomeController::class, 'getReference'])->name('web.reference');
Route::get('/directory_listing', [HomeController::class, 'directory_listing'])->name('web.directory_listing');
Route::get('/cities', [HomeController::class, 'getCities'])->name('web.cities');

Route::get('/check-username', function (\Illuminate\Http\Request $request) {
    $username = $request->query('username');
    $exists = \App\Models\User::where('user_name', $username)->exists();

    return response()->json(['available' => !$exists]);
});

Route::get('/check-seller-username', function (\Illuminate\Http\Request $request) {
    $username = $request->query('username');
    $userId = $request->query('user_id'); // Get current user's ID from query

    $exists = \App\Models\User::where('user_name', $username)
                ->when($userId, function ($q) use ($userId) {
                    $q->where('id', '!=', $userId); // Exclude current user
                })
                ->exists();

    return response()->json(['available' => !$exists]);
});

// User dashboard routes
Route::middleware(['user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});


// Seller dashboard routes
Route::middleware(['seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    Route::get('/seller/productList', [SellerDashboardController::class, 'productList'])->name('seller.productList');
    Route::any('/seller/addProduct/{id?}', [SellerDashboardController::class, 'addProduct'])->name('seller.addProduct');
    Route::any('/seller/updateMyProduct/{id}', [SellerDashboardController::class, 'updateMyProduct'])->name('seller.updateMyProduct');
    
    Route::post('/seller/deleteProduct', [SellerDashboardController::class, 'deleteProduct'])->name('seller.deleteProduct');
    Route::post('/seller/bulkDeleteproduct', [SellerDashboardController::class, 'bulkDeleteproduct'])->name('seller.bulkDeleteproduct');

    Route::get('/seller/productMaster', [SellerDashboardController::class, 'productMaster'])->name('seller.productMaster');
    Route::post('/seller/getProduct', [SellerDashboardController::class, 'getProduct'])->name('seller.getProduct');
    Route::post('/seller/save-selection', [SellerDashboardController::class, 'saveSelection']);

    Route::get('/seller/getMasterProduct', [SellerDashboardController::class, 'getMasterProduct'])->name('seller.getMasterProduct');
    Route::post('/seller/getMyProduct', [SellerDashboardController::class, 'getMyProduct'])->name('seller.getMyProduct');
    Route::post('/seller/updateProductStatus', [SellerDashboardController::class, 'updateProductStatus'])->name('seller.updateProductStatus');
    Route::post('/seller/updateProductField', [SellerDashboardController::class, 'updateProductField']);

    Route::post('/seller/updateProductType', [SellerDashboardController::class, 'updateProductType'])->name('seller.updateProductType');

    Route::get('/seller/viewMyProduct/{id}', [SellerDashboardController::class, 'viewMyProduct'])->name('seller.viewMyProduct');

    Route::any('/seller/myProfile', [SellerDashboardController::class, 'myProfile'])->name('seller.myProfile');
    Route::post('/seller/saveImage', [SellerDashboardController::class, 'saveImage'])->name('seller.saveImage');
    Route::get('/seller/export-products', [SellerDashboardController::class, 'export'])->name('seller.export');

    //show all the product to seller then can add to his list
    Route::get('/seller/getAllProduct', [SellerDashboardController::class, 'getAllProduct'])->name('seller.getAllProduct');
    Route::post('/seller/getCatalogueProduct', [SellerDashboardController::class, 'getCatalogueProduct'])->name('seller.getCatalogueProduct');
    
    Route::post('/seller/addSellerProduct', [SellerDashboardController::class, 'addSellerProduct']);
    Route::post('/seller/removeSellerProduct', [SellerDashboardController::class, 'removeSellerProduct']);
    Route::post('/seller/updateSellerProductField', [SellerDashboardController::class, 'updateSellerProductField']);
    Route::post('/seller/savesPImage', [SellerDashboardController::class, 'savesPImage']);

    Route::post('/seller/copyProduct', [SellerDashboardController::class, 'copyProduct']);

    //Seller Request start 
    Route::any('/seller/addSellerRequest', [SellerDashboardController::class, 'addSellerRequest'])->name('seller.addSellerRequest');
    Route::get('/seller/myRequest', [SellerDashboardController::class, 'myRequest'])->name('seller.myRequest');

});

// we routes end 

Route::get('/clear-views', function () {
    Artisan::call('view:clear');
    return 'View cache cleared';
});
Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    return '✅ Config cache cleared.';
});

Route::any('/admin', [AdminController::class, 'login'])->name('admin.login');
Route::any('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('/admin/getstate', [AdminController::class, 'getstate']);
Route::post('/admin/getcity', [AdminController::class, 'getcity']);
Route::post('/admin/getcityByCountry', [AdminController::class, 'getcityByCountry']);
Route::post('/admin/getBrand', [AdminController::class, 'getBrand']);
Route::post('/admin/getModel', [AdminController::class, 'getModel']);
Route::post('/admin/getSubcategory', [AdminController::class, 'getSubcategory']);
Route::post('/admin/getgeneration', [AdminController::class, 'getgeneration']);
Route::post('/admin/getPartType', [AdminController::class, 'getPartType']);

Route::get('/admin/modelImport', [MasterController::class, 'modelImport']);
Route::post('/admin/import', [MasterController::class, 'import'])->name('admin.import');
Route::get('/testExcel', [MasterController::class, 'testExcel']);
Route::get('/productTemplate', [MasterController::class, 'productTemplate']);




// Group all protected admin routes under middleware
Route::middleware(['auth:admin', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/generateQrCode', [UserManagementController::class, 'generateQrCode'])->name('admin.generateQrCode');

    Route::get('/admin/BrandList', [MasterController::class, 'BrandList'])->name('admin.BrandList');
    Route::any('/admin/addBrand/{id?}', [MasterController::class, 'addBrand'])->name('admin.addBrand');
    Route::post('/admin/updateBrandStatus', [MasterController::class, 'updateBrandStatus']);
    Route::post('/admin/bulkDeleteBrand', [MasterController::class, 'bulkDeleteBrand'])->name('admin.bulkDeleteBrand');

    Route::get('/admin/modelList/{id?}', [MasterController::class, 'modelList'])->name('admin.modelList');
    Route::any('/admin/addModel/{id?}', [MasterController::class, 'addModel'])->name('admin.addModel');
    Route::post('/admin/updateModelStatus', [MasterController::class, 'updateModelStatus']);
    Route::post('/admin/bulkDeleteModel', [MasterController::class, 'bulkDeleteModel'])->name('admin.bulkDeleteModel');

    Route::get('/admin/makeYearList', [MasterController::class, 'makeYearList'])->name('admin.makeYearList');
    Route::any('/admin/addMakeYear/{id?}', [MasterController::class, 'addMakeYear'])->name('admin.addMakeYear');
    Route::post('/admin/bulkDeleteMakeYear', [MasterController::class, 'bulkDeleteMakeYear'])->name('admin.bulkDeleteMakeYear');

    Route::get('/admin/subGenerationList/{id?}', [MasterController::class, 'subGenerationList'])->name('admin.subGenerationList');
    Route::any('/admin/addSubGeneration/{id?}', [MasterController::class, 'addSubGeneration'])->name('admin.addSubGeneration');

    Route::get('/admin/userList', [UserManagementController::class, 'userList'])->name('admin.userList');
    Route::any('/admin/addUser/{id?}', [UserManagementController::class, 'addUser'])->name('admin.addUser');
    Route::get('/admin/viewUser/{id}', [UserManagementController::class, 'viewUser'])->name('admin.viewUser');
    Route::post('/admin/user/bulk-delete-user', [UserManagementController::class, 'bulkDeleteusr'])->name('admin.bulkDeleteusr');

    Route::get('/admin/sellerList', [UserManagementController::class, 'sellerList'])->name('admin.sellerList');
    Route::get('/admin/sellerProfile/{id?}', [UserManagementController::class, 'sellerProfile'])->name('admin.sellerProfile');
    Route::any('/admin/addSeller/{id?}', [UserManagementController::class, 'addSeller'])->name('admin.addSeller');
    Route::post('/admin/update_status', [UserManagementController::class, 'updateUserStatus']);
    Route::post('/admin/delete_user', [UserManagementController::class, 'deleteUser']);
    Route::get('/admin/viewSeller/{id}', [UserManagementController::class, 'viewSeller'])->name('admin.viewSeller');
    Route::get('/admin/viewSellerEnquiry/{id}', [UserManagementController::class, 'viewSellerEnquiry'])->name('admin.viewSellerEnquiry');
    Route::get('/admin/viewSellerProduct/{id}', [UserManagementController::class, 'viewSellerProduct'])->name('admin.viewSellerProduct');
    Route::post('/admin/getSellerProduct', [UserManagementController::class, 'getSellerProduct'])->name('admin.getSellerProduct');
    Route::post('/admin/getAdminSellerList', [UserManagementController::class, 'getAdminSellerList'])->name('admin.getAdminSellerList');

    Route::post('/admin/coach/bulk-delete-coach', [UserManagementController::class, 'bulkDeleteCoach'])->name('admin.bulkDeleteCoach');


    Route::any('/admin/addPolicy/{id?}', [MasterController::class, 'addPolicy'])->name('admin.addPolicy');
    Route::get('/admin/policyList', [MasterController::class, 'policyList'])->name('admin.policyList');
    //Route::get('/admin/viewPolicy/{id}', [MasterController::class, 'viewPolicy'])->name('admin.viewPolicy');
    Route::get('/admin/viewPolicy/{lang_code}/{id}', [MasterController::class, 'viewPolicy'])->name('admin.viewPolicy');
    Route::post('/admin/deletePolicy', [MasterController::class, 'deletePolicy'])->name('admin.deletePolicy');
    Route::post('/admin/policy/bulk-delete', [MasterController::class, 'bulkDeletePolicy'])->name('admin.bulkDeletePolicy');
    

    Route::get('/admin/CategoryList', [MasterController::class, 'CategoryList'])->name('admin.CategoryList');
    Route::any('/admin/addCategory/{id?}', [MasterController::class, 'addCategory'])->name('admin.addCategory');
    Route::post('/admin/updateCategoryStatus', [MasterController::class, 'updateCategoryStatus']);
    Route::post('/admin/bulkDeleteCategory', [MasterController::class, 'bulkDeleteCategory'])->name('admin.bulkDeleteCategory');


    Route::get('/admin/subCategoryList', [MasterController::class, 'subCategoryList'])->name('admin.subCategoryList');
    Route::any('/admin/addSubCategory/{id?}', [MasterController::class, 'addSubCategory'])->name('admin.addSubCategory');
    Route::post('/admin/updateSubCategoryStatus', [MasterController::class, 'updateSubCategoryStatus']);
    Route::post('/admin/bulkDeleteSubCategory', [MasterController::class, 'bulkDeleteSubCategory'])->name('admin.bulkDeleteSubCategory');
   
    Route::any('/admin/addAdminProduct/{id?}', [ProductManagementController::class, 'addAdminProduct'])->name('admin.addAdminProduct');
    Route::get('/admin/adminProductList', [ProductManagementController::class, 'adminProductList'])->name('admin.adminProductList');
    Route::post('/admin/updateProductStatus', [ProductManagementController::class, 'updateProductStatus'])->name('admin.updateProductStatus');
    Route::post('/admin/bulkDeleteproduct', [ProductManagementController::class, 'bulkDeleteproduct'])->name('admin.bulkDeleteproduct');
    Route::post('/admin/deleteProduct', [ProductManagementController::class, 'deleteProduct'])->name('admin.deleteProduct');
    Route::post('/admin/getAdminProduct', [ProductManagementController::class, 'getAdminProduct'])->name('admin.getAdminProduct');

    //Route::get('/admin/masterProductList', [MasterController::class, 'masterProductList'])->name('admin.masterProductList');
    Route::any('/admin/addProductTemplate/{id?}', [MasterController::class, 'addProductTemplate'])->name('admin.addProductTemplate');

    Route::post('/admin/getProduct', [ProductManagementController::class, 'getProduct'])->name('admin.getProduct');
    Route::get('/admin/viewAdminProduct/{id}', [ProductManagementController::class, 'viewAdminProduct'])->name('admin.viewAdminProduct');

    Route::get('/admin/InterchangeProduct', [ProductManagementController::class, 'InterchangeProduct'])->name('admin.InterchangeProduct');
    Route::post('/admin/get-products-by-combinations', [ProductManagementController::class, 'getIProducts'])->name('admin.get.products.by.combinations');
    
    Route::post('/admin/addInterchangeProduct', [ProductManagementController::class, 'addInterchangeProduct'])->name('admin.addInterchangeProduct');
    Route::post('/admin/delete_inter', [ProductManagementController::class, 'deleteInter']);
    Route::get('/admin/InterchangeProductList', [ProductManagementController::class, 'InterchangeProductList'])->name('admin.InterchangeProductList');
    Route::post('/admin/getInterchangeList', [ProductManagementController::class, 'getInterchangeList'])->name('admin.getInterchangeList');

    Route::delete('/admin/deletePartType/{id}', [ProductManagementController::class, 'deletePartType']);
    Route::any('/admin/addProductCatalogue', [ProductManagementController::class, 'addProductCatalogue'])->name('admin.addProductCatalogue');

    //add part to all vehicle
    Route::any('/admin/addPartCatalogue', [ProductManagementController::class, 'addPartCatalogue'])->name('admin.addPartCatalogue');

    //Location master start from here    
    Route::get('/admin/countryList', [MasterController::class, 'countryList'])->name('admin.countryList');
    Route::any('/admin/addCountry/{id?}', [MasterController::class, 'addCountry'])->name('admin.addCountry');
    Route::post('/admin/updateCountryStatus', [MasterController::class, 'updateCountryStatus']);

    Route::get('/admin/cityList', [MasterController::class, 'cityList'])->name('admin.cityList');
    Route::post('/admin/getCityList', [MasterController::class, 'getCityList'])->name('admin.getCityList');
    Route::post('/admin/updateCityStatus', [MasterController::class, 'updateCityStatus']);
    Route::post('/admin/bulkUpdateCity', [MasterController::class, 'bulkUpdateCity'])->name('admin.bulkUpdateCity');
    Route::any('/admin/addCity/{id?}', [MasterController::class, 'addCity'])->name('admin.addCity');

    //parent controller start from here
    Route::any('/admin/addMakeParent/{id?}', [ParentController::class, 'addMakeParent'])->name('admin.addMakeParent');
    Route::get('/admin/parentList', [ParentController::class, 'parentList'])->name('admin.parentList');
    Route::get('/admin/showParent/{id}', [ParentController::class, 'showParent'])->name('admin.showParent');
    Route::get('/admin/deletemBrand/{id}', [ParentController::class, 'deletemBrand'])->name('admin.deletemBrand');
    
    //seller request
    Route::get('/admin/allRequest', [UserManagementController::class, 'allRequest'])->name('admin.allRequest');
    Route::post('/admin/updateRequestStatus', [UserManagementController::class, 'updateRequestStatus'])->name('admin.updateRequestStatus');

    Route::get('/admin/sellerDigitalCard/{id}', [UserManagementController::class, 'sellerDigitalCard'])->name('admin.sellerDigitalCard');

    //New controller start from here
    // Route::get('/admin/groupList', [GroupController::class, 'groupList'])->name('admin.groupList');
    // Route::any('/admin/createGroup/{id?}', [GroupController::class, 'createGroup'])->name('admin.createGroup');
    // Route::post('/admin/updateGroupStatus', [GroupController::class, 'updateGroupStatus']);
    // Route::any('/admin/addUniversalProduct/{id}', [GroupController::class, 'addUniversalProduct'])->name('admin.addUniversalProduct');
    // Route::get('/admin/viewGroupDetail/{id}', [GroupController::class, 'viewGroupDetail'])->name('admin.viewGroupDetail');

    // Route::any('/admin/addUniqueProduct', [GroupController::class, 'addUniqueProduct'])->name('admin.addUniqueProduct');
    // Route::get('/admin/uniqueProductList', [GroupController::class, 'uniqueProductList'])->name('admin.uniqueProductList');
    // Route::post('/admin/getUniqueProduct', [GroupController::class, 'getUniqueProduct'])->name('admin.getUniqueProduct');
    // Route::post('/admin/deleteUniqueProduct', [GroupController::class, 'deleteUniqueProduct'])->name('admin.deleteUniqueProduct');
    // Route::post('/admin/bulkDeleteunique', [GroupController::class, 'bulkDeleteunique'])->name('admin.bulkDeleteunique');

    // Route::get('/admin/commonProductList', [GroupController::class, 'commonProductList'])->name('admin.commonProductList');
    // Route::post('/admin/getCommonProduct', [GroupController::class, 'getCommonProduct'])->name('admin.getCommonProduct');
});