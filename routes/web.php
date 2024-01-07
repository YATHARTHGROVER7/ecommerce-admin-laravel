<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\csadmin\LoginController;
use App\Http\Controllers\csadmin\DashboardController;
use App\Http\Controllers\csadmin\SettingsController;
use App\Http\Controllers\csadmin\CategoryController;
use App\Http\Controllers\csadmin\CustomerController;
use App\Http\Controllers\csadmin\TestimonialController;
use App\Http\Controllers\csadmin\AppearenceController;
use App\Http\Controllers\csadmin\NewsBlogsController;
use App\Http\Controllers\csadmin\OfferPromoController;
use App\Http\Controllers\csadmin\TransactionController;
use App\Http\Controllers\csadmin\ReviewsController;
use App\Http\Controllers\csadmin\OrderController;
use App\Http\Controllers\csadmin\ProductController;
use App\Http\Controllers\csadmin\PageController;
use App\Http\Controllers\csadmin\ShippingController;
use App\Http\Controllers\csadmin\PaymentsController;
use App\Http\Controllers\csadmin\LocationController;
use App\Http\Controllers\csadmin\NewsletterController;
use App\Http\Controllers\csadmin\MediaController;
use App\Http\Controllers\csadmin\ContactController;
use App\Http\Controllers\csadmin\ThankController;
use App\Http\Controllers\csadmin\PartnerController;
use App\Http\Controllers\csadmin\TaxController;
use App\Http\Controllers\csadmin\SalesController;
use App\Http\Controllers\csadmin\EmailController;
use App\Http\Controllers\csadmin\CareerController;
use App\Http\Controllers\frontend\CCAvenueController;
use App\Http\Controllers\csadmin\SellerController;
use App\Http\Controllers\csadmin\GiftOrderController;
use App\Http\Controllers\csadmin\MeetMakersController;


/*
|--------------------------------------------------------------------------
| Web Routes
| Developed by: Harsh Lakhera
| Date: 14-07-2022
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'csadmin'],function(){
	Route::get('/', [LoginController::class, 'adminLogin'])->name('adminLogin');
	
	Route::any('/pay-u-money-view/{orderId}',[App\Http\Controllers\frontend\PayUMoneyController::class,'payUMoneyView'])->name('payUMoneyView');
    Route::any('/pay-u-response',[App\Http\Controllers\frontend\PayUMoneyController::class,'payUResponse'])->name('pay.u.response');
    Route::any('/pay-u-cancel',[App\Http\Controllers\frontend\PayUMoneyController::class,'payUCancel'])->name('pay.u.cancel');
	
	
	Route::get('/email/{filename?}', [App\Http\Controllers\frontend\EmailController::class, 'index'])->name('emailPreview');
	Route::any('/ccpayment/{orderid}', [CCAvenueController::class, 'index'])->name('payment');
	Route::any('/ccavenue-payment-response', [CCAvenueController::class, 'paymentresponse'])->name('paymentresponse');
	Route::any('/ccavenue-payment-cancel', [CCAvenueController::class, 'paymentcancel'])->name('paymentcancel');
	Route::post('/login/loginAdminProcess', [LoginController::class, 'adminlogincheck'])->name('adminlogincheck');
	
    Route::group(['middleware'=>'AdminSession'], function(){

    	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    	Route::get('/logout', [LoginController::class, 'logout'])->name('csadmin.adminLogout');

		/* Settings Section */
		Route::get('setting/contact-setting', [SettingsController::class, 'contactsetting'])->name('csadmin.setting.contactsetting');
    	Route::post('setting/contact-process', [SettingsController::class, 'contactprocess'])->name('csadmin.setting.contactprocess');
    	Route::get('/setting/site-setting', [SettingsController::class, 'siteSetting'])->name('csadmin.setting.sitesetting');
    	Route::post('/site-settings-process', [SettingsController::class, 'sitesettingsprocess'])->name('csadmin.sitesettingsprocess');
		Route::any('change-password', [SettingsController::class, 'changepassword'])->name('backend.changepassword');
	    Route::any('changepasswordprocess', [SettingsController::class, 'changepasswordprocess'])->name('backend.changepasswordprocess');
	    Route::get('socialsettings', [SettingsController::class, 'socialsettings'])->name('csadmin.socialsettings');
		Route::post('socialsettingprocess', [SettingsController::class, 'socialsettingprocess'])->name('csadmin.socialsettingprocess');

		/* Customer Section */
    	Route::any('/customer/{id?}', [CustomerController::class, 'customer'])->name('csadmin.customer');
		Route::get('/customer-view/{id?}', [CustomerController::class, 'customerview'])->name('csadmin.customer.view');
		Route::get('/customer-status/{id?}', [CustomerController::class, 'customerstatus'])->name('csadmin.customer.status');
		Route::get('/customer-delete/{id?}', [CustomerController::class, 'customerdelete'])->name('csadmin.customer.delete');
		Route::any('/customer-filter', [CustomerController::class, 'customerfilter'])->name('csadmin.customerfilter');
		Route::post('/customer-bulk-action', [CustomerController::class, 'customerbulkaction'])->name('csadmin.customerbulkaction');
		Route::any('customer-report-export', [CustomerController::class, 'customerReportExport'])->name('csadmin.customer.customerReportExport');
		
		//Product section
		Route::any('/allproduct/{id?}', [ProductController::class, 'allproduct'])->name('allproduct');
		Route::get('/addproduct/{id?}', [ProductController::class, 'addproduct'])->name('addproduct');
		Route::post('/products/productProcess', [ProductController::class, 'productProcess'])->name('productProcess');
		Route::post('/product/uploadgalleryimage', [ProductController::class, 'uploadgalleryimage'])->name('csadmin.product.uploadgalleryimage');
		Route::get('/products/checkfeatured/{id?}', [ProductController::class, 'checkfeatured'])->name('product.checkfeatured');
		Route::get('/products/checkrecommended/{id?}', [ProductController::class, 'checkrecommended'])->name('product.checkrecommended');
		Route::get('/products/statusupdate/{id}', [ProductController::class, 'statusupdate'])->name('product.statusupdate');
		Route::get('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
		//Route::any('/searching-product', [ProductController::class, 'allproduct'])->name('csadmin.searching');
 		Route::any('/products/getterms', [ProductController::class, 'getterms'])->name('csadmin.products.getterms');
 		Route::any('/products/reset-filter', [ProductController::class, 'resetfilter'])->name('csadmin.products.resetfilter');
 		Route::any('/products/productbulkaction', [ProductController::class, 'productbulkaction'])->name('csadmin.productbulkaction');
		Route::any('/products/delete-product-video/{id?}', [ProductController::class, 'deleteproductvideo'])->name('csadmin.deleteproductvideo');
		Route::any('/products/remove-variation-image/{id?}', [ProductController::class, 'removevariationimage'])->name('csadmin.removevariationimage');

 		Route::get('/products/productCopy/{productid}', [ProductController::class, 'productCopy'])->name('csadmin.products.productCopy');
 		
 		//Gift Section
 		Route::get('/products/giftproduct', [ProductController::class, 'giftproduct'])->name('csadmin.products.giftproduct');
		Route::post('/products/giftproductProcess', [ProductController::class, 'giftproductProcess'])->name('giftproductProcess');
		Route::get('/products/giftdestroy/{id}', [ProductController::class, 'giftdestroy'])->name('product.giftdestroy');
		Route::get('/products/giftstatusupdate/{id}', [ProductController::class, 'giftstatusupdate'])->name('product.giftstatusupdate');
 		
		Route::any('/get-product-term', [ProductController::class, 'getProductTerms'])->name('csadmin.getProductTerms');
		//Product->Category section
		Route::get('/category/{id?}', [CategoryController::class, 'index'])->name('csadmin.category');
		Route::get('/category-featured-change/{id?}', [CategoryController::class, 'categoryFeaturedChange'])->name('csadmin.category.changefeatured');
    	Route::get('/category-status-change/{id?}', [CategoryController::class, 'categoryStatusChange'])->name('csadmin.category.changestatus');
    	Route::get('/category-delete/{id?}', [CategoryController::class, 'categoryDelete'])->name('csadmin.category.delete');
 		Route::post('/category-proccess',[CategoryController::class, 'categoryProccess'])->name('csadmin.categoryProccess');
 		Route::post('update-category-order',[CategoryController::class, 'updateCategoryOrder'])->name('csadmin.category.updatecategoryorder');

 		//Product->Tags section
		Route::get('products/tags/{id?}', [ProductController::class, 'tags'])->name('csadmin.tags');
    	Route::post('products/tag-process/{id?}',[ProductController::class, 'tagprocess'])->name('csadmin.tagprocess');
    	Route::get('products/tag-status/{id?}',[ProductController::class, 'tagstatus'])->name('csadmin.tagstatus');
    	Route::get('products/delete-tag/{id?}',[ProductController::class, 'deletetag'])->name('csadmin.deletetag');
		Route::get('products/featured-tag/{id?}', [ProductController::class, 'tagfeatured'])->name('csadmin.tagfeatured');

		//Product->Brand section
		Route::get('products/brand/{id?}', [ProductController::class, 'brand'])->name('csadmin.brand');
		Route::post('products/brand-process/{id?}',[ProductController::class, 'brandprocess'])->name('csadmin.brandprocess');
    	Route::get('products/brand-status/{id?}',[ProductController::class, 'brandstatus'])->name('csadmin.brandstatus');
    	Route::get('products/delete-brand/{id?}',[ProductController::class, 'deletebrand'])->name('csadmin.deletebrand');

		//Tax Rates->Tax section
		Route::get('tax-rates/{id?}', [TaxController::class, 'tax'])->name('csadmin.tax'); 
		Route::post('tax-rates/{id?}',[TaxController::class, 'taxprocess'])->name('csadmin.taxprocess');
    	Route::get('tax-rates-status/{id?}',[TaxController::class, 'taxstatus'])->name('csadmin.taxstatus');
    	Route::get('delete-tax-rates/{id?}',[TaxController::class, 'deletetax'])->name('csadmin.deletetax');
		
		//Sales Report->Sales Report section
		Route::any('sales-report', [SalesController::class, 'salesReport'])->name('csadmin.sales.salesReport'); 
		Route::get('sales-report-reset', [SalesController::class, 'salesReportReset'])->name('csadmin.sales.salesReportReset'); 

		//Product->Label section
    	Route::get('label/{id?}', [ProductController::class, 'label'])->name('csadmin.label');
    	Route::post('label-process/{id?}', [ProductController::class, 'labelprocess'])->name('csadmin.labelprocess');
    	Route::get('label-status/{id?}', [ProductController::class, 'labelstatus'])->name('csadmin.labelstatus');
    	Route::get('delete-label/{id?}', [ProductController::class, 'deletelabel'])->name('csadmin.deletelabel');

    	//Product->Attributes section
    	Route::get('products/product-attributes/{id?}', [ProductController::class, 'productAttributes'])->name('csadmin.product.attributes');
    	Route::post('products/attribute-process/{id?}', [ProductController::class, 'attributeProcess'])->name('csadmin.product.attribute_process');
    	Route::get('products/delete-attribute/{id}', [ProductController::class, 'deleteattribute'])->name('csadmin.product.deleteattribute');
    	Route::get('products/attribute-status/{id}', [ProductController::class, 'attributeStatus'])->name('csadmin.product.attribute_status');
    	Route::get('products/attribute-terms/{slug}/{id?}', [ProductController::class, 'attributeTerms'])->name('csadmin.product.attribute_terms');
    	Route::post('products/terms-process/{id?}', [ProductController::class, 'termsProcess'])->name('csadmin.product.terms_process');
    	Route::get('products/attribute-slug', [ProductController::class, 'attributeSlug'])->name('csadmin.product.attribute_slug');
    	Route::get('products/terms-status/{id}', [ProductController::class, 'termsStatus'])->name('csadmin.product.terms_status');
    	Route::get('products/delete-terms/{id}', [ProductController::class, 'deleteTerms'])->name('csadmin.product.delete_terms');
    	Route::get('products/product-reviews/{id}', [ProductController::class, 'productReview'])->name('csadmin.product.productReview');
    	// Route::get('products/product-reviews-status/{id}', [ProductController::class, 'productStatus'])->name('csadmin.product.reviewstatusupdate');
    	Route::get('products/product-reviews-delete/{id}', [ProductController::class, 'productDelete'])->name('csadmin.product.reviewdestroy');

    	/** MediaController **/
    	Route::any('/media', [MediaController::class, 'media'])->name('csadmin.media');
    	Route::any('media/addmedia/{id?}', [MediaController::class, 'addmedia'])->name('csadmin.addmedia');
    	Route::any('media/mediaProcess/{id?}', [MediaController::class, 'mediaProcess'])->name('csadmin.mediaProcess');
    	Route::any('media/deletemedia/{id}', [MediaController::class, 'deletemedia'])->name('csadmin.deletemedia');
		
		/** PartnerController **/
    	Route::get('partner/{id?}', [PartnerController::class, 'partner'])->name('csadmin.partner');
    	Route::post('partner-process/{id?}', [PartnerController::class, 'partnerprocess'])->name('csadmin.partnerprocess');
    	Route::get('partner-status/{id?}', [PartnerController::class, 'partnerstatus'])->name('csadmin.partnerstatus');
    	Route::get('delete-partner/{id?}', [PartnerController::class, 'deletepartner'])->name('csadmin.deletepartner');  
		Route::get('partner-featured/{id?}', [PartnerController::class, 'partnerfeatured'])->name('csadmin.partnerfeatured');

		//OfferPromoController
    	Route::any('all-offers/{id?}', [OfferPromoController::class, 'alloffers'])->name('csadmin.alloffers');
    	Route::any('offers/addoffers/{id?}', [OfferPromoController::class, 'addoffers'])->name('csadmin.addoffers');
		Route::any('offers/offersprocess', [OfferPromoController::class, 'offersprocess'])->name('csadmin.offersprocess');
		Route::any('offers/deleteoffers/{id?}', [OfferPromoController::class, 'deleteoffers'])->name('csadmin.deleteoffers');
		Route::any('offers/offersstatus/{id?}',[OfferPromoController::class, 'offersstatus'])->name('csadmin.offersstatus');
		Route::get('offers/offersfeatured/{id?}', [OfferPromoController::class, 'offersfeatured'])->name('csadmin.offersfeatured');
		Route::any('/offers/reset-filter', [OfferPromoController::class, 'resetfilter'])->name('csadmin.offers.resetfilter');
		Route::any('/offers/offersbulkaction', [OfferPromoController::class, 'offersbulkaction'])->name('csadmin.offersbulkaction');
		
		/**TestimonialController**/
    	Route::get('testimonial', [TestimonialController::class, 'testimonial'])->name('csadmin.testimonial');
    	Route::get('testimonial-status/{id?}', [TestimonialController::class, 'testimonialstatus'])->name('csadmin.testimonialstatus');
    	Route::get('delete-testimonial/{id?}', [TestimonialController::class, 'deletetestimonial'])->name('csadmin.deletetestimonial');
    	Route::get('add-testimonial/{id?}', [TestimonialController::class, 'addtestimonial'])->name('csadmin.addtestimonial');
    	Route::post('testimonial-process', [TestimonialController::class, 'testimonialprocess'])->name('csadmin.testimonialprocess');
		Route::get('testimonial-featured/{id?}', [TestimonialController::class, 'testimonialfeatured'])->name('csadmin.testimonialfeatured');

		/**MeetMakersController**/
    	Route::get('meetmaker', [MeetMakersController::class, 'meetmaker'])->name('csadmin.meetmaker');
    	Route::get('meetmaker-status/{id?}', [MeetMakersController::class, 'meetmakertatus'])->name('csadmin.meetmakertatus');
    	Route::get('delete-meetmaker/{id?}', [MeetMakersController::class, 'deletemeetmaker'])->name('csadmin.deletemeetmaker');
    	Route::get('add-meetmaker/{id?}', [MeetMakersController::class, 'addmeetmaker'])->name('csadmin.addmeetmaker');
    	Route::post('meetmaker-process', [MeetMakersController::class, 'meetmakerprocess'])->name('csadmin.meetmakerprocess');
		
		/**SellerController**/
    	Route::any('seller/{type?}', [SellerController::class, 'seller'])->name('csadmin.seller');
    	Route::get('seller-status/{id?}', [SellerController::class, 'sellerstatus'])->name('csadmin.sellerstatus');
    	Route::get('delete-seller/{id?}', [SellerController::class, 'deleteseller'])->name('csadmin.deleteseller');
    	Route::get('add-seller/{id?}', [SellerController::class, 'addseller'])->name('csadmin.addseller');
    	Route::any('/seller-filter', [SellerController::class, 'sellerfilter'])->name('csadmin.sellerfilter');
    	Route::get('seller-view/{id?}', [SellerController::class, 'sellerview'])->name('csadmin.sellerview');
    	Route::post('seller-process', [SellerController::class, 'sellerprocess'])->name('csadmin.sellerprocess');
		
		
		/**EmailController**/
    	Route::get('email-list', [EmailController::class, 'emaillist'])->name('csadmin.emaillist');
    	Route::get('email-status/{id?}', [EmailController::class, 'emailstatus'])->name('csadmin.emailstatus');
    	Route::get('delete-email/{id?}', [EmailController::class, 'deleteemail'])->name('csadmin.deleteemail');
    	Route::get('add-email/{id?}', [EmailController::class, 'addemail'])->name('csadmin.addemail');
    	Route::post('email-process', [EmailController::class, 'emailprocess'])->name('csadmin.emailprocess');

		/**NewsletterController**/
    	Route::get('newsletter', [NewsletterController::class, 'newsletter'])->name('csadmin.newsletter');
    	Route::get('delete-newsletter/{id?}', [NewsletterController::class, 'deletenewsletter'])->name('csadmin.deletenewsletter');
		Route::post('/newsletter-bulk-action', [NewsletterController::class, 'newsletterbulkaction'])->name('csadmin.newsletterbulkaction');

		/**Payment Controller**/
		Route::get('payment', [PaymentsController::class, 'index'])->name('csadmin.payments');
		Route::post('payment/razorpay-process', [PaymentsController::class, 'razorpayprocess'])->name('csadmin.razorpayprocess');
		Route::post('payment/ccavenue-process', [PaymentsController::class, 'ccavenueprocess'])->name('csadmin.ccavenueprocess');
		Route::post('payment/payumoney-process', [PaymentsController::class, 'payumoneyprocess'])->name('csadmin.payumoneyprocess');
		Route::post('payment/isenabled', [PaymentsController::class, 'isenabled'])->name('csadmin.isenabled');
		Route::post('payment/codenabled', [PaymentsController::class, 'codenabled'])->name('csadmin.codenabled');

		/* Multi-settings */	
		Route::any('/multi-currency-settings', [SettingsController::class, 'multicurrencysettings'])->name('csadmin.multicurrencysettings');
		Route::any('/emails-settings', [SettingsController::class, 'emailssettings'])->name('csadmin.emailssettings');
		//Appearence section
		Route::get('/appearance/slider/{id?}', [AppearenceController::class, 'slider'])->name('csadmin.slider');
    	Route::any('/appearance/add-slider/{id?}', [AppearenceController::class, 'addslider'])->name('csadmin.addslider');
		Route::any('/appearance/slider-process/{id?}', [AppearenceController::class, 'sliderprocess'])->name('csadmin.sliderprocess');
		Route::any('/appearance/delete-slider-video/{id?}', [AppearenceController::class, 'deleteslidervideo'])->name('csadmin.deleteslidervideo');
		Route::any('/appearance/sliderstatus/{id}', [AppearenceController::class, 'sliderstatus'])->name('csadmin.sliderstatus');
		Route::any('/appearance/deleteslider/{id}', [AppearenceController::class, 'deleteslider'])->name('csadmin.deleteslider');
		Route::get('/appearance/editor', [AppearenceController::class, 'editor'])->name('csadmin.editor');
		Route::post('/appearance/editor-process', [AppearenceController::class, 'editorProcess'])->name('csadmin.editorProcess');

		Route::get('appearance/menu/{id?}', [AppearenceController::class, 'menu'])->name('csadmin.menu');
		Route::post('appearance/menu-process/{id?}', [AppearenceController::class, 'menuprocess'])->name('csadmin.menuprocess');
	    Route::get('appearance/deletemenu/{id}', [AppearenceController::class, 'deletemenu'])->name('csadmin.deletemenu');
	    Route::post('/updatemenuOrderAjax', [AppearenceController::class, 'updatemenuOrderAjax'])->name('csadmin.updatemenuOrderAjax');

		Route::get('appearance/header/{id?}', [AppearenceController::class, 'header'])->name('csadmin.header');
	    Route::post('appearance/header-process/{id?}', [AppearenceController::class, 'headerprocess'])->name('csadmin.headerprocess');
    	Route::get('appearance/header-status/{id?}', [AppearenceController::class, 'headerstatus'])->name('csadmin.headerstatus');
    	Route::get('appearance/delete-header/{id?}', [AppearenceController::class, 'deleteheader'])->name('csadmin.deleteheader');
		
		Route::get('/footer', [AppearenceController::class, 'footer'])->name('footer');
		Route::any('appearance/footerProcess', [AppearenceController::class, 'footerProcess'])->name('csadmin.footerProcess');
		Route::get('/menu', [AppearenceController::class, 'menu'])->name('menu');
		
		/* Shipping Controller */
		Route::any('shipping-rate', [ShippingController::class, 'shippingrate'])->name('csadmin.shippingrate');
		Route::post('shipping-rate-process', [ShippingController::class, 'shippingrateprocess'])->name('csadmin.shippingrateprocess');
		Route::get('shipping-pincode', [ShippingController::class, 'shippingpincode'])->name('csadmin.shippingpincode');
		Route::get('add-shipping-pincode/{id?}', [ShippingController::class, 'addshippingpincode'])->name('csadmin.addshippingpincode');
		Route::post('add-shipping-pincode-process', [ShippingController::class, 'addshippingpincodeprocess'])->name('csadmin.addshippingpincodeprocess');
		Route::post('shipping-pincode-search', [ShippingController::class, 'shippingpincodesearch'])->name('csadmin.shippingpincodesearch');
		Route::post('shipping-cod-update', [ShippingController::class, 'shippingcodupdate'])->name('csadmin.shippingcodupdate');
		Route::post('shipping-update', [ShippingController::class, 'shippingupdate'])->name('csadmin.shippingupdate');
		Route::post('pincode-delete', [ShippingController::class, 'deletepincode'])->name('csadmin.deletepincode');
		Route::post('shipping/createzone', [ShippingController::class, 'createzone'])->name('csadmin.createzone');
		Route::get('getzoneCities', [ShippingController::class, 'getzoneCities'])->name('getzoneCities');
		Route::get('restofindiastore', [ShippingController::class, 'restofindiastore'])->name('restofindiastore');
		Route::post('shippingrateamount', [ShippingController::class, 'shippingrateamount'])->name('shippingrateamount');
		Route::post('shippingweightamount', [ShippingController::class, 'shippingweightamount'])->name('shippingweightamount');
		Route::get('deletezone/{id}', [ShippingController::class, 'deletezone'])->name('deletezone');
		Route::post('getCities', [ShippingController::class, 'getCities'])->name('getCities');
		Route::post('shipping/select-checked-base-on', [ShippingController::class, 'selectCheckedBaseOn'])->name('shipping.selectCheckedBaseOn');
		Route::post('shipping/select-order-type', [ShippingController::class, 'selectOrderType'])->name('shipping.selectOrderType');
 
		Route::post('shipping/createzonecountry', [ShippingController::class, 'createzonecountry'])->name('csadmin.createzonecountry');
		Route::get('getzoneCitiesCountry', [ShippingController::class, 'getzoneCitiesCountry'])->name('csadmin.getzoneCitiesCountry');
		Route::get('restofcountrystore', [ShippingController::class, 'restofcountrystore'])->name('csadmin.restofcountrystore');
		Route::post('shippingrateamountcountry', [ShippingController::class, 'shippingrateamountcountry'])->name('csadmin.shippingrateamountcountry');
		Route::post('shippingweightamountcountry', [ShippingController::class, 'shippingweightamountcountry'])->name('csadmin.shippingweightamountcountry');
		Route::get('deletezonecountry/{id}', [ShippingController::class, 'deletezonecountry'])->name('csadmin.deletezonecountry');
		Route::post('shipping/add-free-shipping', [ShippingController::class, 'addFreeShipping'])->name('csadmin.shipping.addfreeshipping');

		Route::any('shipping-agency/{id?}', [ShippingController::class, 'allshippingagency'])->name('csadmin.shippingagency.allshippingagency');
		Route::post('shipping-agency-process', [ShippingController::class, 'shippingAgencyProcess'])->name('csadmin.shippingagency.shippingAgencyProcess');
		Route::post('shipping-agency-deactivate', [ShippingController::class, 'shippingAgencyDeactivate'])->name('csadmin.shippingagency.shippingAgencyDeactivate');


    	/* Transaction Section */
    	Route::get('/transactions', [TransactionController::class, 'transaction'])->name('csadmin.transaction');
    	Route::get('/delete-transaction/{id?}', [TransactionController::class, 'deletetransaction'])->name('csadmin.deletetransaction');

    	/* Contact Us Section */ 
    	Route::get('/contact-us', [ContactController::class, 'contactus'])->name('csadmin.contactus');
    	Route::get('/contact-us/delete-contact/{id}', [ContactController::class, 'deletecontact'])->name('csadmin.deletecontact');
		Route::post('/contact-bulk-action', [ContactController::class, 'contactbulkaction'])->name('csadmin.contactbulkaction');
		
		
		/* Thank You Section */ 
    	Route::get('/thank-you', [ThankController::class, 'thankyou'])->name('csadmin.thankyou');
    	Route::get('/thank-you/delete-thank-you/{id}', [ThankController::class, 'deletethankyou'])->name('csadmin.deletethankyou');
		Route::post('/thank-you-bulk-action', [ThankController::class, 'thankyoubulkaction'])->name('csadmin.thankyoubulkaction');
		
		/* Career Section */ 
    	Route::get('/career', [CareerController::class, 'careerlist'])->name('csadmin.careerlist');
    	Route::get('/career/delete-career_resume/{id}', [CareerController::class, 'deletecareer'])->name('csadmin.deletecareer');

		/* Location Section */ 
    	Route::any('/location', [LocationController::class, 'index'])->name('csadmin.location');
    	Route::post('/location/add-state', [LocationController::class, 'addState'])->name('csadmin.location.addState');
    	Route::post('/location/add-city', [LocationController::class, 'addCity'])->name('csadmin.location.addCity');
    	Route::post('/location/add-pincode', [LocationController::class, 'addPincode'])->name('csadmin.location.addPincode');
    	Route::post('/location/get-city-data', [LocationController::class, 'getCityData'])->name('csadmin.location.getCityData');
    	Route::get('/location/delete-location/{id}', [LocationController::class, 'deleteLocation'])->name('csadmin.location.deletelocation');
		Route::any('/location-filter', [LocationController::class, 'locationfilter'])->name('csadmin.locationfilter');
		
    	/* Order Section */
    	Route::any('/orders/{id?}', [OrderController::class, 'orders'])->name('csadmin.orders');
    	Route::any('/orders/invoice/{id?}', [OrderController::class, 'ordersInvoice'])->name('csadmin.ordersInvoice');
		Route::any('/orders-view/{id?}', [OrderController::class, 'ordersview'])->name('csadmin.ordersview');
		Route::any('/change-status', [OrderController::class, 'changestatus'])->name('changestatus');
		Route::any('/orders-filter', [OrderController::class, 'orderfilter'])->name('csadmin.orderfilter');
		Route::post('/addtracking', [OrderController::class, 'addtracking'])->name('csadmin.location.addtracking');
		//Route::any('/acceptOrder/{trans_id}', [OrderController::class, 'acceptOrder'])->name('csadmin.location.acceptOrder');
		Route::post('/acceptOrder', [OrderController::class, 'acceptOrder'])->name('csadmin.location.acceptOrder');
		
		//Product->Gift Box section
		Route::get('products/gift-box/{id?}', [ProductController::class, 'giftbox'])->name('csadmin.giftbox');
		Route::get('/products/giftboxcopy/{productid}', [ProductController::class, 'giftboxcopy'])->name('csadmin.giftboxcopy');
    	Route::post('products/gift-box-process/{id?}',[ProductController::class, 'giftboxprocess'])->name('csadmin.giftboxprocess');
    	Route::get('products/gift-box-status/{id?}',[ProductController::class, 'giftboxstatus'])->name('csadmin.giftboxstatus');
    	Route::get('products/delete-gift-box/{id?}',[ProductController::class, 'deletegiftbox'])->name('csadmin.deletegiftbox');
    	Route::get('add-gift-product/{id?}',[ProductController::class, 'addgiftproduct'])->name('csadmin.addgiftproduct');

		//Product->Gift Card section
		Route::get('products/gift-card/{id?}', [ProductController::class, 'giftcard'])->name('csadmin.giftcard');
    	Route::post('products/gift-card-process/{id?}',[ProductController::class, 'giftcardprocess'])->name('csadmin.giftcardprocess');
    	Route::get('products/gift-card-status/{id?}',[ProductController::class, 'giftcardstatus'])->name('csadmin.giftcardstatus');
    	Route::get('products/delete-gift-card/{id?}',[ProductController::class, 'deletegiftcard'])->name('csadmin.deletegiftcard');
    	
		/*Gift Box Order Section */
    	Route::any('/gift-box-orders/{id?}', [GiftOrderController::class, 'giftorders'])->name('csadmin.giftorders');
		Route::any('/gift-box-orders-view/{id?}', [GiftOrderController::class, 'giftordersview'])->name('csadmin.giftordersview');
		Route::any('/gift-box-change-status', [GiftOrderController::class, 'giftchangestatus'])->name('giftchangestatus');
		Route::any('/gift-box-orders-filter', [GiftOrderController::class, 'giftorderfilter'])->name('csadmin.giftorderfilter');
		Route::post('/gift-box-addtracking', [GiftOrderController::class, 'giftaddtracking'])->name('csadmin.location.giftaddtracking');
		Route::any('/gift-box-acceptOrder/{trans_id}', [GiftOrderController::class, 'giftacceptOrder'])->name('csadmin.location.giftacceptOrder');
		
		/* Reviews Section */
		Route::get('/reviews', [ReviewsController::class, 'reviews'])->name('csadmin.reviews');
		Route::get('reviews/product-reviews-status/{id}', [ReviewsController::class, 'reviewStatus'])->name('csadmin.reviews.reviewStatus');

    	//Pages Section
		Route::any('all-pages/{id?}', [PageController::class, 'pages'])->name('csadmin.pages');
		Route::any('pages/add-page/{id?}', [PageController::class, 'addpage'])->name('csadmin.addpage');
		Route::any('pages/page-process', [PageController::class, 'pageprocess'])->name('csadmin.pageprocess');
		Route::any('pages/check-slug', [PageController::class, 'checkslug'])->name('csadmin.checkslug');
		Route::any('pages/page-status/{id?}', [PageController::class, 'pagestatus'])->name('csadmin.pagestatus');
		Route::any('pages/page-delete/{id?}', [PageController::class, 'pagedelete'])->name('csadmin.pagedelete');
		Route::any('/pages/reset-filter', [PageController::class, 'resetfilter'])->name('csadmin.pages.resetfilter');
		Route::any('/pages/pagesbulkaction', [PageController::class, 'pagesbulkaction'])->name('csadmin.pagesbulkaction');
		
		/** staff & Team **/
		Route::any('staff',[App\Http\Controllers\csadmin\StaffController::class, 'staff'])->name('csadmin.staff');
		Route::any('addstaff/{id?}',[App\Http\Controllers\csadmin\StaffController::class, 'addstaff'])->name('csadmin.addstaff');
		Route::any('staffprocess',[App\Http\Controllers\csadmin\StaffController::class, 'staffprocess'])->name('staffprocess');
		Route::any('staffstatus/{id}',[App\Http\Controllers\csadmin\StaffController::class, 'staffstatus'])->name('staffstatus');
		Route::any('deletestaff/{id}',[App\Http\Controllers\csadmin\StaffController::class, 'deletestaff'])->name('deletestaff');

		/* Role Controller */
		Route::any('role',[App\Http\Controllers\csadmin\RoleController::class, 'rolepermission'])->name('csadmin.rolepermission');
		Route::any('add-role/{id?}',[App\Http\Controllers\csadmin\RoleController::class, 'addrole'])->name('csadmin.addrole');
		Route::any('roleprocess',[App\Http\Controllers\csadmin\RoleController::class, 'roleprocess'])->name('roleprocess');
		Route::any('rolestatus/{id}',[App\Http\Controllers\csadmin\RoleController::class, 'rolestatus'])->name('csadmin.rolestatus');
		Route::any('deleterole/{id}',[App\Http\Controllers\csadmin\RoleController::class, 'deleterole'])->name('csadmin.deleterole');
		Route::any('permissions',[App\Http\Controllers\csadmin\RoleController::class, 'permissions'])->name('csadmin.permissions');
		Route::any('givepermission',[App\Http\Controllers\csadmin\RoleController::class, 'givepermission'])->name('givepermission');
		Route::any('removepermission',[App\Http\Controllers\csadmin\RoleController::class, 'removepermission'])->name('removepermission');
		Route::any('find_role_data',[App\Http\Controllers\csadmin\RoleController::class, 'find_role_data'])->name('find_role_data');

		/** NewsBlogsController **/
		Route::get('news-blogs/add-news-blogs/{id?}', [NewsBlogsController::class, 'addnewsblogs'])->name('csadmin.addnewsblogs');
		Route::post('news-blogs/store', [NewsBlogsController::class, 'store'])->name('csadmin.store');
		Route::get('all-news-blogs', [NewsBlogsController::class, 'newsblogs'])->name('csadmin.newsblogs');
		Route::get('news-blogs/categories/{id?}', [NewsBlogsController::class, 'categories'])->name('csadmin.categories');
		Route::post('news-blogs/category-process/{id?}', [NewsBlogsController::class, 'categoryprocess'])->name('csadmin.categoryprocess');
		Route::get('news-blogs/deletecategory/{id?}', [NewsBlogsController::class, 'deletecategory'])->name('csadmin.deletecategory');
		Route::get('news-blogs/categorystatus/{id}', [NewsBlogsController::class, 'categorystatus'])->name('csadmin.categorystatus');
		Route::get('news-blogs/tags-status/{id}', [NewsBlogsController::class, 'tagsstatus'])->name('csadmin.tagsstatus');
		Route::get('news-blogs/delete-tags/{id}', [NewsBlogsController::class, 'deletetag'])->name('csadmin.deleteblogtag');
		Route::post('news-blogs/tags-process/{id?}', [NewsBlogsController::class, 'tagsprocess'])->name('csadmin.tagsprocess');
		Route::get('news-blogs/tag/{id?}', [NewsBlogsController::class, 'tag'])->name('csadmin.tag');
		Route::post('/updateblogCategoryOrderAjex',[NewsBlogsController::class, 'updateblogCategoryOrderAjex'])->name('backend.updateblogCategoryOrderAjex');
		Route::post('news-blogs/check_slug', [NewsBlogsController::class, 'check_slug'])->name('blogs.check_slug');
		Route::get('news-blogs/news-blogs-status/{id}', [NewsBlogsController::class, 'newsblogsstatus'])->name('csadmin.newsblogsstatus');
		Route::get('news-blogs/news-blogs-featured/{id}', [NewsBlogsController::class, 'newsblogsfeatured'])->name('csadmin.newsblogsfeatured');
		Route::get('news-blogs/delete-news-blogs/{id}', [NewsBlogsController::class, 'deletenewsblogs'])->name('csadmin.deletenewsblogs');
		
	});
});