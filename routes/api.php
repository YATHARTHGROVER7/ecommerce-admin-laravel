<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\AddressController;
use App\Http\Controllers\api\AccountController;
use App\Http\Controllers\api\CallbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});  
Route::get('/recommended-products-list', [DashboardController::class, 'recommendedproductslist']);
Route::get('/menue-list', [DashboardController::class, 'menuelist']);
Route::get('/header-data', [DashboardController::class, 'headerdata']);
Route::get('/product-list', [DashboardController::class, 'productList']);
Route::get('/featured-video-product-list', [DashboardController::class, 'featuredvideoproductlist']);
Route::post('/product-details', [DashboardController::class, 'productdetails']);
Route::post('/variation-wise-price', [DashboardController::class, 'variationwiseprice']);
Route::post('/related-product-list', [DashboardController::class, 'relatedproductlist']);
Route::get('/get-product-list-sidebar', [DashboardController::class, 'getproductlistsidebar']);
Route::get('/top-banner-list', [DashboardController::class, 'topBannerList']);
Route::get('/after-top-banner-list', [DashboardController::class, 'aftertopbannerlist']);
Route::get('/bottom-cat-product-banner-list', [DashboardController::class, 'bottomcatproductbannerlist']);
Route::get('/bottom-tag-product-banner-list', [DashboardController::class, 'bottomtagproductbannerlist']);
Route::get('/featured-testimonial', [DashboardController::class, 'featuredtestimonial']);
Route::get('/featured-category', [DashboardController::class, 'featuredcategory']);
Route::get('/featured-certificate', [DashboardController::class, 'featuredcertificate']);
Route::get('/category-wise-products', [DashboardController::class, 'categorywiseproducts']);
Route::get('/tags-wise-products', [DashboardController::class, 'tagswiseproducts']);
Route::get('/settings', [DashboardController::class, 'settingsdata']);
Route::get('/footer', [DashboardController::class, 'footerdata']);
Route::post('/newsletter-process', [DashboardController::class, 'newsletterprocess']);
Route::post('/page-content', [DashboardController::class, 'pagecontent']);
Route::get('/blog-list', [DashboardController::class, 'blogslist']);
Route::post('/blog-details', [DashboardController::class, 'blogDetails']);
Route::post('/contact-us-process', [DashboardController::class, 'contactusprocess']);
Route::post('/thank-you-process', [DashboardController::class, 'thankyouprocess']);
Route::get('/thank-you-data', [DashboardController::class, 'thankyoudata']);
Route::get('/getAttributeData', [DashboardController::class, 'getAttributeData']);
Route::post('/getfilterproductsdata', [DashboardController::class, 'getfilterproductsdata']);
Route::get('/all-categories', [DashboardController::class, 'allcategory']);
Route::post('/category-detail', [DashboardController::class, 'categorydetail']);
Route::post('/getsearchdata', [DashboardController::class, 'getsearchdata']);
Route::post('/submit-career', [DashboardController::class, 'submitcareer']);
Route::post('/track-order', [DashboardController::class, 'trackorder']);
Route::post('/cancel-order', [DashboardController::class, 'cancelOrder']);

Route::post('/user-login-process', [LoginController::class, 'userloginprocess']);
Route::post('/user-register-process', [LoginController::class, 'userregisterprocess']);
Route::get('/get-user-data', [LoginController::class, 'getuserdata']);
Route::get('/logout-user', [LoginController::class, 'logoutuser']);
Route::get('/get-country', [LoginController::class, 'getcountry']);
Route::post('/forgotpassword', [LoginController::class, 'forgotpassword']);

Route::post('/update-profile', [AccountController::class, 'updateProfile']);
Route::post('/update-password', [AccountController::class, 'updatePassword']);
Route::get('/get-user-address', [AddressController::class, 'getUserAddress']);
Route::post('/user-address-process', [AddressController::class, 'userAddressProcess']);
Route::post('/check-pincode', [AddressController::class, 'checkPincode']);
Route::get('/get-states-data', [AddressController::class, 'getstatesdata']);
Route::post('/get-city-data', [AddressController::class, 'getcitydata']);
Route::get('/getallcitydata', [AddressController::class, 'getallcitydata']);
Route::post('/removeAddress', [AddressController::class, 'removeAddress']);
Route::post('/editAddress', [AddressController::class, 'editAddress']);

Route::post('/plus-to-cart', [CartController::class, 'plustocart']);
Route::post('/minus-to-cart', [CartController::class, 'minustocart']);
Route::post('/select-coupon', [CartController::class, 'selectCoupon']);
Route::get('/coupons-list', [CartController::class, 'couponsList']);
Route::post('/check-shipping-availability', [CartController::class, 'checkShippingAvailability']);
Route::post('/calculate-shipping-amount', [CartController::class, 'calculateShippingAmount']);
Route::post('/makecodorder', [CartController::class, 'makecodorder']);
Route::post('/initiateCCPayment', [CartController::class, 'initiateCCPayment']);
Route::post('/initiatePayUMoney', [CartController::class, 'initiatePayUMoney']);
Route::post('/submit-feedback', [CartController::class, 'submitfeedback']);


Route::get('/get-order-data', [AccountController::class, 'getUserOrders']);
Route::post('/get-order-detail', [AccountController::class, 'getordersdetail']);
Route::post('/add-to-fav', [AccountController::class, 'addtofav']);
Route::post('/remove-fav-wishlist', [AccountController::class, 'removefavwishlist']);
Route::post('/add-to-fav-cart', [AccountController::class, 'addtofavcart']);
Route::get('/user-fav-data', [AccountController::class, 'userfavdata']);
Route::post('/currency-rates', [AccountController::class, 'currencyrates']);
Route::get('/currency', [AccountController::class, 'currency']);

/* CALL BACK URL*/
Route::any('/shiprocket-callback', [CallbackController::class, 'shiprocketCallback']);

/**********************************************************TESTING API***************************************************************************/


Route::get('/testingapi/recommended-products-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'recommendedproductslist']);
Route::get('/testingapi/menue-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'menuelist']);
Route::get('/testingapi/header-data', [App\Http\Controllers\api\testingapi\DashboardController::class, 'headerdata']);
Route::get('/testingapi/product-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'productList']);
Route::get('/testingapi/featured-video-product-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'featuredvideoproductlist']);
Route::post('/testingapi/product-details', [App\Http\Controllers\api\testingapi\DashboardController::class, 'productdetails']);
Route::post('/testingapi/variation-wise-price', [App\Http\Controllers\api\testingapi\DashboardController::class, 'variationwiseprice']);
Route::post('/testingapi/related-product-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'relatedproductlist']);
Route::get('/testingapi/get-product-list-sidebar', [App\Http\Controllers\api\testingapi\DashboardController::class, 'getproductlistsidebar']);
Route::get('/testingapi/top-banner-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'topBannerList']);
Route::get('/testingapi/popup-banner', [App\Http\Controllers\api\testingapi\DashboardController::class, 'popupbanner']);
Route::get('/testingapi/after-top-banner-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'aftertopbannerlist']);
Route::get('/testingapi/bottom-cat-product-banner-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'bottomcatproductbannerlist']);
Route::get('/testingapi/bottom-tag-product-banner-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'bottomtagproductbannerlist']);
Route::get('/testingapi/featured-testimonial', [App\Http\Controllers\api\testingapi\DashboardController::class, 'featuredtestimonial']);
Route::get('/testingapi/featured-category', [App\Http\Controllers\api\testingapi\DashboardController::class, 'featuredcategory']);
Route::get('/testingapi/featured-certificate', [App\Http\Controllers\api\testingapi\DashboardController::class, 'featuredcertificate']);
Route::get('/testingapi/category-wise-products', [App\Http\Controllers\api\testingapi\DashboardController::class, 'categorywiseproducts']);
Route::get('/testingapi/tags-wise-products', [App\Http\Controllers\api\testingapi\DashboardController::class, 'tagswiseproducts']);
Route::get('/testingapi/settings', [App\Http\Controllers\api\testingapi\DashboardController::class, 'settingsdata']);
Route::get('/testingapi/footer', [App\Http\Controllers\api\testingapi\DashboardController::class, 'footerdata']);
Route::post('/testingapi/newsletter-process', [App\Http\Controllers\api\testingapi\DashboardController::class, 'newsletterprocess']);
Route::post('/testingapi/page-content', [App\Http\Controllers\api\testingapi\DashboardController::class, 'pagecontent']);
Route::get('/testingapi/blog-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'blogslist']);
Route::post('/testingapi/blog-details', [App\Http\Controllers\api\testingapi\DashboardController::class, 'blogDetails']);
Route::post('/testingapi/contact-us-process', [App\Http\Controllers\api\testingapi\DashboardController::class, 'contactusprocess']);
Route::post('/testingapi/thank-you-process', [App\Http\Controllers\api\testingapi\DashboardController::class, 'thankyouprocess']);
Route::get('/testingapi/thank-you-data', [App\Http\Controllers\api\testingapi\DashboardController::class, 'thankyoudata']);
Route::get('/testingapi/getAttributeData', [App\Http\Controllers\api\testingapi\DashboardController::class, 'getAttributeData']);
Route::post('/testingapi/getfilterproductsdata', [App\Http\Controllers\api\testingapi\DashboardController::class, 'getfilterproductsdata']);
Route::get('/testingapi/all-categories', [App\Http\Controllers\api\testingapi\DashboardController::class, 'allcategory']);
Route::post('/testingapi/category-detail', [App\Http\Controllers\api\testingapi\DashboardController::class, 'categorydetail']);
Route::post('/testingapi/getsearchdata', [App\Http\Controllers\api\testingapi\DashboardController::class, 'getsearchdata']);
Route::post('/testingapi/submit-career', [App\Http\Controllers\api\testingapi\DashboardController::class, 'submitcareer']);
Route::post('/testingapi/track-order', [App\Http\Controllers\api\testingapi\DashboardController::class, 'trackorder']);
Route::get('/testingapi/meet-maker-list', [App\Http\Controllers\api\testingapi\DashboardController::class, 'meetmakerlist']);
Route::post('/testingapi/meet-maker-details', [App\Http\Controllers\api\testingapi\DashboardController::class, 'meetmakerDetails']);

Route::post('/testingapi/user-login-process', [App\Http\Controllers\api\testingapi\LoginController::class, 'userloginprocess']);
Route::post('/testingapi/user-register-process', [App\Http\Controllers\api\testingapi\LoginController::class, 'userregisterprocess']);
Route::get('/testingapi/get-user-data', [App\Http\Controllers\api\testingapi\LoginController::class, 'getuserdata']);
Route::get('/testingapi/logout-user', [App\Http\Controllers\api\testingapi\LoginController::class, 'logoutuser']);
Route::get('/testingapi/get-country', [App\Http\Controllers\api\testingapi\LoginController::class, 'getcountry']);
Route::post('/testingapi/forgotpassword', [App\Http\Controllers\api\testingapi\LoginController::class, 'forgotpassword']);


Route::get('/testingapi/get-user-address', [App\Http\Controllers\api\testingapi\AddressController::class, 'getUserAddress']);
Route::post('/testingapi/user-address-process', [App\Http\Controllers\api\testingapi\AddressController::class, 'userAddressProcess']);
Route::post('/testingapi/check-pincode', [App\Http\Controllers\api\testingapi\AddressController::class, 'checkPincode']);
Route::get('/testingapi/get-states-data', [App\Http\Controllers\api\testingapi\AddressController::class, 'getstatesdata']);
Route::post('/testingapi/get-city-data', [App\Http\Controllers\api\testingapi\AddressController::class, 'getcitydata']);
Route::get('/testingapi/getallcitydata', [App\Http\Controllers\api\testingapi\AddressController::class, 'getallcitydata']);
Route::post('/testingapi/removeAddress', [App\Http\Controllers\api\testingapi\AddressController::class, 'removeAddress']);
Route::post('/testingapi/editAddress', [App\Http\Controllers\api\testingapi\AddressController::class, 'editAddress']);

Route::post('/testingapi/plus-to-cart', [App\Http\Controllers\api\testingapi\CartController::class, 'plustocart']);
Route::post('/testingapi/minus-to-cart', [App\Http\Controllers\api\testingapi\CartController::class, 'minustocart']);
Route::post('/testingapi/select-coupon', [App\Http\Controllers\api\testingapi\CartController::class, 'selectCoupon']);
Route::get('/testingapi/coupons-list', [App\Http\Controllers\api\testingapi\CartController::class, 'couponsList']);
Route::post('/testingapi/check-shipping-availability', [App\Http\Controllers\api\testingapi\CartController::class, 'checkShippingAvailability']);
Route::post('/testingapi/calculate-shipping-amount', [App\Http\Controllers\api\testingapi\CartController::class, 'calculateShippingAmount']);
Route::post('/testingapi/makecodorder', [App\Http\Controllers\api\testingapi\CartController::class, 'makecodorder']);
Route::post('/testingapi/initiateCCPayment', [App\Http\Controllers\api\testingapi\CartController::class, 'initiateCCPayment']);
Route::post('/testingapi/initiatePayUMoney', [App\Http\Controllers\api\testingapi\CartController::class, 'initiatePayUMoney']);
Route::post('/testingapi/submit-feedback', [App\Http\Controllers\api\testingapi\CartController::class, 'submitfeedback']);

Route::post('/testingapi/update-profile', [App\Http\Controllers\api\testingapi\AccountController::class, 'updateProfile']);
Route::post('/testingapi/update-password', [App\Http\Controllers\api\testingapi\AccountController::class, 'updatePassword']);
Route::get('/testingapi/get-order-data', [App\Http\Controllers\api\testingapi\AccountController::class, 'getUserOrders']);
Route::post('/testingapi/get-order-detail', [App\Http\Controllers\api\testingapi\AccountController::class, 'getordersdetail']);
Route::post('/testingapi/add-to-fav', [App\Http\Controllers\api\testingapi\AccountController::class, 'addtofav']);
Route::post('/testingapi/remove-fav-wishlist', [App\Http\Controllers\api\testingapi\AccountController::class, 'removefavwishlist']);
Route::post('/testingapi/add-to-fav-cart', [App\Http\Controllers\api\testingapi\AccountController::class, 'addtofavcart']);
Route::get('/testingapi/user-fav-data', [App\Http\Controllers\api\testingapi\AccountController::class, 'userfavdata']);
Route::post('/testingapi/currency-rates', [App\Http\Controllers\api\testingapi\AccountController::class, 'currencyrates']);
Route::get('/testingapi/currency', [App\Http\Controllers\api\testingapi\AccountController::class, 'currency']);
Route::post('/testingapi/cancel-order', [App\Http\Controllers\api\testingapi\DashboardController::class, 'cancelOrder']);
Route::post('/testingapi/get-order-status', [App\Http\Controllers\api\testingapi\AccountController::class, 'getorderstatus']);

