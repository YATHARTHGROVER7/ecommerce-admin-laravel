@php
$user = Session::get('CS_USER');
$settingData = App\Models\CsThemeAdmin::where('id', 1)->first();
$urls = $settingData->site_address;
$userid = Session::get('CS_ADMIN');
if($userid->type==1){
    $user = App\Models\CsThemeAdmin::where('id',$userid->id)->first();
}else{
    $user = App\Models\CsStaff::where('staff_id',$userid->id)->first();
}
$todayOrders = App\Models\CsTransactions::whereDate('created_at',date('Y-m-d'))->count();
$newUserToday = App\Models\CsUsers::whereDate('created_at',date('Y-m-d'))->where('user_status',1)->count();
$newOrderToday = App\Models\CsTransactions::whereDate('created_at',date('Y-m-d'))->orderBy('trans_id','DESC')->limit(5)->get();
foreach($newOrderToday as $value){
$orderDetails = App\Models\CsTransactionDetails::where('td_trans_id',$value->trans_id)->pluck('td_item_title')->toArray();
$value['order_details'] = implode(',',$orderDetails);
}
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{env('APP_NAME')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <link rel="shortcut icon" href="@if(isset($settingData->favicon) && $settingData->favicon!=''){{env('SETTING_IMAGE')}}/{{$settingData->favicon}}@else{{url('/')}}/public{{env('NO_IMAGE')}}@endif" />
        <link href="{{asset('public/backend_assets/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/css/dragula.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/backend_assets/assets/css/ext-component-drag-drop.css')}}" rel="stylesheet" type="text/css" />
        <script src="{{asset('public/backend_assets/assets/libs/jquery/jquery.min.js')}}"></script>
        <script>
            var site_url = "{{env('ADMIN_URL')}}";
            var _token = "{{ csrf_token() }}";
        </script>
        <!-- NAZOX -->
    </head>
    <body data-sidebar="dark">
        <div id="layout-wrapper">
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            <a href="{{env('APP_URL')}}" class="logo">
                                <img src="@if(isset($settingData->logo) && $settingData->logo!=''){{env('SETTING_IMAGE')}}{{$settingData->logo}}@endif" alt="logo-sm-dark" height="42" />
                            </a>
                        </div>
                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>
                        <form class="app-search d-none d-lg-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search..." />
                                <span class="ri-search-line"></span>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex">
                        <div class="dropdown d-inline-block d-lg-none ms-2">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-search-line"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                                <form class="p-3">
                                    <div class="mb-3 m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ..." />
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="dropdown d-none d-lg-inline-block ms-1">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                <i class="ri-fullscreen-line"></i>
                            </button>
                        </div>
                         @if($userid->type==1)
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-notification-3-line"></i>
                                <span class="noti-dot"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0">Notifications</h6>
                                        </div>
                                        <!-- <div class="col-auto">
<a href="javascript:void(0);" class="small"> View All</a>
</div> -->
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    @if(count($newOrderToday)>0) @foreach($newOrderToday as $value)
                                    <a href="{{route('csadmin.ordersview',$value->trans_order_number)}}" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <h6 class="mb-1">New Order</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">{{$value->order_details}}</p>
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i>{{$value->created_at}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach @else
                                    <a href="javascript:void(0);" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3"></div>
                                            <div class="flex-1">
                                                <h6 class="mb-1" style="text-align: center;">No Order found</h6>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                </div>
                                <div class="p-2 border-top">
                                    <div class="d-grid">
                                        <a class="btn btn-sm btn-link font-size-14 text-center" href="{{route('csadmin.orders')}}"> <i class="mdi mdi-arrow-right-circle me-1"></i> View All </a>
                                    </div>
                                </div>
                            </div>
                        </div>@endif
                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img
                                    class="rounded-circle header-profile-user"
                                    src="@if(isset($settingData->favicon) && $settingData->favicon!=''){{env('SETTING_IMAGE')}}{{$settingData->favicon}}@else{{env('NO_IMAGE')}}@endif"
                                    alt="Header Avatar"
                                />
                                <span class="d-none d-xl-inline-block ms-1">
                                @if($userid->type==1)
                                @if(isset($user->site_title) && $user->site_title !=''){{$user->site_title}}@else{{'Admin'}}@endif</span>
                                @else
                                {{$user->staff_name}}
                                @endif
                                </span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <!-- <a class="dropdown-item" href="javascript:void(0);"><i class="ri-user-line align-middle me-1"></i> Profile</a> -->
                                <!-- <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle me-1"></i> My Wallet</a>-->
                                @if($userid->type==1)
                                <a class="dropdown-item d-block" href="{{route('backend.changepassword')}}"><span class="badge bg-success float-end mt-1"></span><i class="fas fa-key"></i> Change Password</a>
                                
                                <a class="dropdown-item d-block" href="{{route('csadmin.setting.sitesetting')}}"><span class="badge bg-success float-end mt-1"></span><i class="ri-settings-2-line align-middle me-1"></i> Settings</a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{route('csadmin.adminLogout')}}" onclick="return confirm('Are you sure want to logout?');">
                                    <i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">
                <div data-simplebar class="h-100">
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            @if(isset($permissionData) && in_array('DASHLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Dashboard'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('dashboard')}}" class="waves-effect">
                                    <i class="ri-home-line"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('CUSTLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Customer' || $title == 'Customer View'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.customer')}}" class="waves-effect">
                                    <i class="ri-user-line"></i>
                                    @if(isset($newUserToday) && $newUserToday>0)
                                    <span class="badge rounded-pill bg-danger float-end">{{$newUserToday}}</span>
                                    @endif
                                    <span>Customers</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('SELLERLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Add Seller' || $title == 'Seller'){{'active mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.seller')}}" class="waves-effect"><i class="ri-store-2-line"></i><span>Seller</span></a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('ORDERLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Orders' || $title == 'Orders View'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.orders')}}" class="waves-effect">
                                    <i class="ri-file-text-line"></i>
                                    @if(isset($todayOrders) && $todayOrders>0)
                                    <span class="badge rounded-pill bg-danger float-end">{{$todayOrders}}</span>
                                    @endif
                                    <span>Orders</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('PROALLPRODUCT',$permissionData) || in_array('PROADDNEW',$permissionData) || in_array('PROCATELIST',$permissionData) || in_array('PROTAGLIST',$permissionData) || in_array('PROLABELLIST',$permissionData) || in_array('PROATTRLIST',$permissionData) || in_array('PROBRANDLIST',$permissionData))
                            <li
                                class="@php echo (isset($title) && $title == 'Product' || $title == 'Add Product' || $title == 'Brand' || $title == 'Category' || $title == 'Tags' || $title == 'Attributes' || $title == 'Attributes Terms' || $title == 'Label')?'mm-active':'';@endphp"
                            >
                                <a class="has-arrow waves-effect">
                                    <i class="ri-store-2-line"></i>
                                    <span>Products</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if(isset($permissionData) && in_array('PROALLPRODUCT',$permissionData))
                                    <li><a href="{{route('allproduct')}}">All Product</a></li>@endif
                                    @if(isset($permissionData) && in_array('PROADDNEW',$permissionData))
                                    <li><a href="{{route('addproduct')}}" class="@if(isset($title) && $title == 'Add Product'){{'mm-active'}}@else{{''}}@endif">Add New</a></li>@endif
                                    @if(isset($permissionData) && in_array('PROCATELIST',$permissionData))
                                    <li><a href="{{route('csadmin.category')}}" class="@if(isset($title) && $title == 'Category'){{'mm-active'}}@else{{''}}@endif">Categories</a></li>@endif
                                    @if(isset($permissionData) && in_array('PROTAGLIST',$permissionData))
                                    <li><a href="{{route('csadmin.tags')}}" class="@if(isset($title) && $title == 'Tags'){{'mm-active'}}@else{{''}}@endif">Tags</a></li>@endif
                                    @if(isset($permissionData) && in_array('PROLABELLIST',$permissionData))
                                    <li><a href="{{route('csadmin.label')}}" class="@if(isset($title) && $title == 'Label'){{'mm-active'}}@else{{''}}@endif">Label</a></li>@endif
                                    @if(isset($permissionData) && in_array('PROATTRLIST',$permissionData))
                                    <li><a href="{{route('csadmin.product.attributes')}}" class="@if(isset($title) && $title == 'Attributes' || $title == 'Attributes Terms'){{'active'}}@else{{''}}@endif">Attributes</a></li>@endif
                                    @if(isset($permissionData) && in_array('PROBRANDLIST',$permissionData))
                                    <li><a href="{{route('csadmin.brand')}}" class="@if(isset($title) && $title == 'Brand'){{'mm-active'}}@else{{''}}@endif">Brand</a></li>@endif
                                </ul>
                            </li>@endif
                            <!--<li class="@php echo (isset($title) && $title == 'Gift Product' || $title == 'Gift Box' || $title == 'Gift Card')?'mm-active':'';@endphp">-->
                            <!--    <a class="has-arrow waves-effect">-->
                            <!--    <i class="ri-dashboard-line"></i>-->
                            <!--    <span>Gift Catalogue</span>-->
                            <!--    </a>-->
                            <!--    <ul class="sub-menu" aria-expanded="false">-->
                            <!--    <li><a href="{{route('csadmin.giftbox')}}" class="@if(isset($title) && $title == 'Gift Box'){{'mm-active'}}@else{{''}}@endif">Gift Box</a></li>-->
                            <!--    <li><a href="{{route('csadmin.giftcard')}}" class="@if(isset($title) && $title == 'Gift Card'){{'mm-active'}}@else{{''}}@endif">Gift Card</a></li>-->
                            <!--    <li><a href="{{route('csadmin.products.giftproduct')}}" class="@if(isset($title) && $title == 'Gift Product'){{'mm-active'}}@else{{''}}@endif">Gift Product</a></li>-->
                            <!--    </ul>-->
                            <!--</li>-->
                            @if(isset($permissionData) && in_array('PAYPRATLIST',$permissionData) || in_array('TAXLIST',$permissionData))
                            <li>
                                <a class="has-arrow waves-effect">
                                    <i class="ri-store-2-line"></i>
                                    <span>ecommerce</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if(isset($permissionData) && in_array('PAYPRATLIST',$permissionData))
                                    <li><a href="{{route('csadmin.payments')}}">Payments</a></li>@endif
                                    @if(isset($permissionData) && in_array('TAXLIST',$permissionData))
                                    <li><a href="{{route('csadmin.tax')}}">Tax</a></li>@endif
                                </ul>
                            </li>@endif
                            @if(isset($permissionData) && in_array('REVIEWLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Reviews'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.reviews')}}" class="waves-effect">
                                    <i class="ri-file-text-line"></i>
                                    <span>Reviews</span>
                                </a>
                            </li>@endif
                            
                            <!--<li class="@if(isset($title) && $title == 'Gift Box Orders' || $title == 'Gift Box Orders View'){{'mm-active'}}@else{{''}}@endif">-->
                            <!--    <a href="{{route('csadmin.giftorders')}}" class="waves-effect">-->
                            <!--    <i class="ri-file-text-line"></i>-->
                            <!--    @if(isset($todayGiftBoxOrders) && $todayGiftBoxOrders>0)-->
                            <!--    <span class="badge rounded-pill bg-danger float-end">{{$todayGiftBoxOrders}}</span>-->
                            <!--    @endif-->
                            <!--    <span>Gift Box Orders</span>-->
                            <!--    </a>-->
                            <!--</li>-->
                            @if(isset($permissionData) && in_array('OFFPROLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'All Offers' || $title == 'Add Offers'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.alloffers')}}" class="waves-effect">
                                    <i class="ri-gift-line"></i>
                                    <span class="badge rounded-pill bg-danger float-end"></span>
                                    <span>Offers & Promos</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('MAKERLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'MeetMakers' || $title == 'Add MeetMakers'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.meetmaker')}}" class="waves-effect">
                                    <i class="ri-file-text-line"></i>
                                    <span class="badge rounded-pill bg-danger float-end"></span>
                                    <span>Meet The Makers</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('TESTLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Testimonials' || $title == 'Add Testimonials'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.testimonial')}}" class="waves-effect">
                                    <i class="ri-file-text-line"></i>
                                    <span class="badge rounded-pill bg-danger float-end"></span>
                                    <span>Testimonials</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('MEDIALIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Media' || $title == 'Add Media'){{'active mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.media')}}" class="waves-effect"><i class="ri-image-2-line"></i><span>Media</span></a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('SILDBANLIST',$permissionData) || in_array('MENULIST',$permissionData) || in_array('HEADER',$permissionData) || in_array('FOOTER',$permissionData) || in_array('CERTIFICATESLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Slider' || $title == 'Add Slider' || $title == 'Editor' || $title == 'Header' || $title == 'Footer'){{'mm-active'}}@else{{''}}@endif">
                                <a class="has-arrow waves-effect">
                                    <i class="ri-dashboard-line"></i>
                                    <span>Appearence</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if(isset($permissionData) && in_array('SILDBANLIST',$permissionData))
                                    <li><a href="{{route('csadmin.slider')}}" class="@if(isset($title) && $title == 'Slider' || $title == 'Add Slider'){{'mm-active'}}@else{{''}}@endif">Slider & Banner</a></li>@endif
                                    @if(isset($permissionData) && in_array('MENULIST',$permissionData))
                                    <li class="@if(isset($title) && $title == 'Menu'){{'mm-active'}}@else{{''}}@endif"><a href="{{route('csadmin.menu')}}">Menu</a></li>@endif
                                    @if(isset($permissionData) && in_array('HEADER',$permissionData))
                                    <li class="@if(isset($title) && $title == 'Header'){{'mm-active'}}@else{{''}}@endif"><a href="{{route('csadmin.header')}}">Header</a></li>@endif
                                    @if(isset($permissionData) && in_array('FOOTER',$permissionData))
                                    <li class="@if(isset($title) && $title == 'Footer'){{'mm-active'}}@else{{''}}@endif"><a href="{{route('footer')}}">Footer</a></li>@endif
                                    @if(isset($permissionData) && in_array('EDITOR',$permissionData))
                                    <li class="@if(isset($title) && $title == 'Editor'){{'mm-active'}}@else{{''}}@endif"><a href="{{route('csadmin.editor')}}">Editor</a></li>@endif
                                    @if(isset($permissionData) && in_array('CERTIFICATESLIST',$permissionData))
                                    <li class="@if(isset($title) && $title == 'Partner'){{'active mm-active'}}@else{{''}}@endif"><a href="{{route('csadmin.partner')}}" class="waves-effect">Certificates</a></li>@endif
                                </ul>
                            </li>@endif
                            @if(isset($permissionData) && in_array('SHIPPINGRATELIST',$permissionData) || in_array('SHIPPINGVIEW',$permissionData))
                            <li class="@php echo (isset($title) && $title == 'Shipping Agency' || $title == 'Shipping Rate')?'mm-active':'';@endphp">
                                <a class="has-arrow waves-effect">
                                    <i class="ri-shield-user-line"></i>
                                    <span>Shipping</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if(isset($permissionData) && in_array('SHIPPINGRATELIST',$permissionData))
                                    <li><a href="{{route('csadmin.shippingrate')}}">Shipping Rate</a></li>@endif
                                    @if(isset($permissionData) && in_array('SHIPPINGVIEW',$permissionData))
                                    <li><a href="{{route('csadmin.shippingagency.allshippingagency')}}">Shipping Agency</a></li>@endif
                                </ul>
                            </li>
                            @endif
                            @if(isset($permissionData) && in_array('REPORTLIST',$permissionData) || in_array('TRANLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Sales Report' || $title == 'Transaction'){{'mm-active'}}@else{{''}}@endif">
                                <a class="has-arrow waves-effect">
                                    <i class="ri-file-text-line"></i>
                                    <span>Reports</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if(isset($permissionData) && in_array('REPORTLIST',$permissionData))
                                    <li><a href="{{route('csadmin.sales.salesReport')}}" class="@if(isset($title) && $title == 'Sales Report'){{'mm-active'}}@else{{''}}@endif">Sales Report</a></li>@endif
                                    @if(isset($permissionData) && in_array('TRANLIST',$permissionData))
                                    <li><a href="{{route('csadmin.transaction')}}" class="@if(isset($title) && $title == 'Transaction'){{'mm-active'}}@else{{''}}@endif">Transactions Reports</a></li>@endif
                                </ul>
                            </li>@endif
                            @if(isset($permissionData) && in_array('NEWSBLOGLIST',$permissionData) || in_array('NEWSBLOGADDNEW',$permissionData) || in_array('NEWSBLOGCATELIST',$permissionData) || in_array('NEWSBLOGTAGLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'News & Blogs' || $title == 'Add News & Blogs' || $title == 'Blogs Category' || $title == 'Blogs Tags'){{'mm-active'}}@else{{''}}@endif">
                                <a class="has-arrow waves-effect">
                                    <i class="ri-newspaper-line"></i>
                                    <span>News & Blogs</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if(isset($permissionData) && in_array('NEWSBLOGLIST',$permissionData))
                                    <li><a href="{{route('csadmin.newsblogs')}}" class="@if(isset($title) && $title == 'News & Blogs'){{'mm-active'}}@else{{''}}@endif">All News & Blogs</a></li>@endif
                                    @if(isset($permissionData) && in_array('NEWSBLOGADDNEW',$permissionData))
                                    <li><a href="{{route('csadmin.addnewsblogs')}}" class="@if(isset($title) && $title == 'Add News & Blogs'){{'mm-active'}}@else{{''}}@endif">Add New</a></li>@endif
                                    @if(isset($permissionData) && in_array('NEWSBLOGCATELIST',$permissionData))
                                    <li><a href="{{route('csadmin.categories')}}" class="@if(isset($title) && $title == 'Blogs Category'){{'mm-active'}}@else{{''}}@endif">Categories</a></li>@endif
                                    @if(isset($permissionData) && in_array('NEWSBLOGTAGLIST',$permissionData))
                                    <li><a href="{{route('csadmin.tag')}}" class="@if(isset($title) && $title == 'Blogs Tags'){{'mm-active'}}@else{{''}}@endif">Tags</a></li>@endif
                                </ul>
                            </li>@endif
                            @if(isset($permissionData) && in_array('NEWSLETTERLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Newsletter'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.newsletter')}}" class="waves-effect">
                                    <i class="ri-file-text-line"></i>
                                    <span class="badge rounded-pill bg-danger float-end"></span>
                                    <span>Newsletters</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('CENQLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Career Enquiry'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.careerlist')}}" class="waves-effect">
                                    <i class="ri-user-line"></i>
                                    <span>Career Enquiry</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('CONUSENQLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Contact Us'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.contactus')}}" class="waves-effect">
                                    <i class="ri-user-line"></i>
                                    <span>Enquiry</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('THENQLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Thank You'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.thankyou')}}" class="waves-effect">
                                    <i class="ri-user-line"></i>
                                    <span> Thank You Enquiry</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('LOCATIONLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Location'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.location')}}" class="waves-effect">
                                    <i class="ri-map-pin-line"></i>
                                    <span>Location</span>
                                </a>
                            </li>@endif
                            @if(isset($permissionData) && in_array('PAGLIST',$permissionData))
                            <li class="@if(isset($title) && $title == 'Pages' || $title == 'Add Pages'){{'mm-active'}}@else{{''}}@endif">
                                <a href="{{route('csadmin.pages')}}" class="waves-effect">
                                    <i class="ri-book-open-line"></i>
                                    <span>Pages</span>
                                </a>
                            </li>@endif
                            @if($userid->type==1)
                            <li class="@if(isset($title) && $title == 'Staff' || $title == 'Add Staff' || $title == 'Role' || $title == 'Add Role' || $title == 'Permission'){{'mm-active'}}@else{{''}}@endif">
                                <a class="has-arrow waves-effect">
                                    <i class="ri-team-line"></i>
                                    <span>Staff & Team</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{route('csadmin.staff')}}" class="@if(isset($title) && $title == 'Staff' || $title == 'Add Staff'){{'mm-active'}}@else{{''}}@endif">Manage Staff & Teams</a></li>
                                    <li><a href="{{route('csadmin.rolepermission')}}" class="@if(isset($title) && $title == 'Role' || $title == 'Add Role'){{'mm-active'}}@else{{''}}@endif">Roles</a></li>
                                    <li><a href="{{route('csadmin.permissions')}}" class="@if(isset($title) && $title == 'Permission'){{'mm-active'}}@else{{''}}@endif">Permissions</a></li>
                                </ul>
                            </li>
                            @endif
                             @if(isset($permissionData) && in_array('CONSET',$permissionData) || in_array('SITSET',$permissionData) || in_array('SOCSET',$permissionData))
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-settings-2-line"></i>
                                    <span>Settings</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if(isset($permissionData) && in_array('CONSET',$permissionData))
                                    <li><a href="{{route('csadmin.setting.contactsetting')}}">Contact Setting</a></li>@endif
                                    @if(isset($permissionData) && in_array('SITSET',$permissionData))
                                    <li><a href="{{route('csadmin.setting.sitesetting')}}">Site Setting</a></li>@endif
                                    @if(isset($permissionData) && in_array('SOCSET',$permissionData))
                                    <li><a href="{{route('csadmin.socialsettings')}}">Social Setting</a></li>@endif
                                </ul>
                            </li>@endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="main-content">
                @yield('content')
            </div>
        </div>
        <script src="{{asset('public/backend_assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/select2/js/select2.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/spectrum-colorpicker2/spectrum.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/js/pages/form-advanced.init.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/js/app.js')}}"></script>
        <script src="https://cdn.ckeditor.com/4.12.1/full-all/ckeditor.js"></script>
        <script src="{{asset('public/backend_assets/assets/js/pages/ext-component-drag-drop.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/js/pages/dragula.min.js')}}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="{{asset('public/backend_assets/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/apexcharts/apexcharts.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js')}}"></script>
        <script src="{{asset('public/backend_assets/assets/js/pages/dashboard.init.js')}}"></script>
        <script type="text/javascript">
            CKEDITOR.replace(".ckeditor");
            CKEDITOR.config.allowedContent = true;
        </script>
    </body>
</html>
