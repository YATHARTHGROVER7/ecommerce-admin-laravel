/* *****************LOGIN AND FUNCTION******************** */

function goToUrlUpdate(url){
    goToUrl = url;
}

$(".otpfocus").on('keyup',function () {
    $(this).next().focus();
});

function sendotptoregistereduser() {
    var user_mobile = $("#user_mobile").val();
    if (user_mobile == "") {
        $(".alert-danger").show();
        $(".alert-danger").html("Please Enter Mobile Number.");
        return false;
    } else if (user_mobile.length != 10) {
        $(".alert-danger").show();
        $(".alert-danger").html("Please Enter Valid Mobile Number.");
        return false;
    } else {
        $(".alert-danger").hide();
    }
    $('#step1button').html('Loading . . .');
    var datastring = "mobile=" + user_mobile + "&_token=" + _token;
    $.post(base_url+"sendotp", datastring, function (response) {
        $('#step1button').html('Continue');
        if (response.status == "success") {
            $("#step1").hide();
            $("#step2").show();
            $('#mobileData').html(user_mobile);
            $("#timer").html(' ');
            timer();
        } else {
            $(".alert-danger").show();
            $(".alert-danger").html(response.message);
        }
    });
}
var timeleft = 0;
function timer() {
    $("#timer").show();
    timeleft = 60;
    var downloadTimer = setInterval(function () {
        if (timeleft == 0) {
            clearInterval(downloadTimer);
            $("#resend_otp").show();
            $("#timer").hide();
        } else {
            $("#resend_otp").hide();
            var timer = timeleft - 1;
            $("#timer").html("Resend OTP in " + timer + " Sec");
            timeleft -= 1;
        }
    }, 1000);
}

function checkotp() {
    $(".alert-danger").hide();
    $(".alert-success").hide();
    var otp1 = $("#otp1").val();
    var otp2 = $("#otp2").val();
    var otp3 = $("#otp3").val();
    var otp4 = $("#otp4").val();
    var otp = otp1 + otp2 + otp3 + otp4;

    var datastring = "otp=" + otp + "&_token=" + _token;
    if (otp1 == "" || (otp2 == "") | (otp3 == "") || otp4 == "") {
        $(".alert-danger").show();
        $(".alert-danger").html("Please enter the OTP");
        return false;
    } else {
        $('#verifybtn').html('Loading . . .');
        $.post(base_url+"verifyotp", datastring, function (response) {
            $('#verifybtn').html('Verify');
            if (response.status == "success" && response.message == "already_register") {
                window.location.href = goToUrl;
            } else if (response.status == "success" && response.message == "new_register") {
                $("#step3").show();
                $("#step2").hide();
                $("#step1").hide();
            } else {
                $(".alert-danger").html(response.message);
                $(".alert-danger").show();
            }
        });
    }
}

function registrationform() {
    $(".alert-danger").hide();
    $(".alert-success").hide();
    var user_name = $("#user_name").val();
    var user_email = $("#user_email").val();
    if (user_name == "") {
        $(".alert-danger").show().html('Name field is required');
        return false;
    }  
    if (user_email == "") {
        $(".alert-danger").show().html('Email Address field is required');
        return false;
    } else if (IsEmail(user_email) == false) {
        $(".alert-danger").show().html('Enter valid Email Address');
        return false;
    } 
    $('#step3button').html('Loading . . .');
    var datastring = $("#registerformprocess").serialize();
    $.post(base_url+"register", datastring, function (response) {
        $('#step3button').html('Register');
        if (response.status == "success") {
            window.location.href = goToUrl;
        } else {
            $(".alert-danger").html(response.message);
            $(".alert-danger").show();
        }
    });
}

function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}

function changemobile(){
    $('#step1').show();
    $('#step2').hide();
    $('#sendotp-error').hide();
    $('#verifyotp-error').html('');
    $("#otp1").val('');
    $("#otp2").val('');
    $("#otp3").val('');
    $("#otp4").val('');
    timeleft=0;
}
/* **********************************NEWSLETTER SUBSCRIPTION********************************************* */

function subscribeProcess(){
    var newsletter_email = $('#newsletter_email').val();
    if(newsletter_email==''){
        $('#newsletter_email').attr('style','border: 1px solid red;');
        return false;
    }else if (IsEmail(newsletter_email) == false){
        $('#newsletter_email').attr('style','border: 1px solid red;');
        $('#newslettererror').show();
        $('#newslettererror').html('Please enter valid email address');
        setTimeout(function(){ $('#newslettererror').fadeOut() }, 1000);
        return false;
    }else{
        $('#newsletter_email').attr('style','');
    }
    var datastring = "newsletter_email=" + newsletter_email + "&_token=" + _token;
    $.post(base_url + "newsletterProccess", datastring, function (response) {
        if(response.status=='ok'){
            $('#newsletter_email').val('');
            $('#newslettersuccess').show();
            $('#newslettersuccess').html(response.message);
            setTimeout(function(){ $('#newslettersuccess').fadeOut() }, 1000);
        }else{
            $('#newsletter_email').val('');
            $('#newslettererror').show();
            $('#newslettererror').html(response.message);
            setTimeout(function(){ $('#newslettererror').fadeOut() }, 1000);
        }
    });
}

/* ************************************QUICKVIEW FUNCTION********************************************** */
function quickViewModal(url){
    $('#spinnerLoader').show();
    $.get(base_url+'product/product-detail-modal-html/'+url, function (response) {
        $('#appendViewHtml').html(response);
        $('#spinnerLoader').hide();
    }); 
}
/* **********************************************MINI CART FUNCTION************************************************** */
$( document ).ready(function() {
    $.get(base_url+'cart/getcarttotal',function(response){
        $('.cart-price').html('₹'+response.cartTotal);
        $('.cart-count').html(response.cartCount);
    });
});

function getCartTotal(){
    $.get(base_url+'cart/getcarttotal',function(response){
        $('.cart-price').html('₹'+response.cartTotal);
        $('.cart-count').html(response.cartCount);
    });
}

function removeitemfromcart(obj,id){
    $('#spinnerLoader').show();
    var datastring = 'product_id='+id+'&_token='+_token;
    $.post(base_url+'cart/removeitemfromcart',datastring,function(response){
        getCartTotal();
        getminicart();
        shoppingcarthtml();
        $('#spinnerLoader').hide();
        if(response.data.cartcount==0){
            location.reload();
        }
    });
}

function shoppingcarthtml()
{
    $.get(base_url+'cart/shoppingcarthtml',function(response){
        $('#shoppingcarthtml').html(response);
    });
}

function getminicart()
{
    $.get(base_url+'cart/minicart',function(response){
        $('#minicartSidebar').html(response);
    });
}
/* *************************************************************CART PAGE JAVASCRIPT FUNTIONS**************************************************** */

function plustocart(productid,variationid) 
{
    $('#spinnerLoader').show();
    var datastring = {'product_id':productid,'variationid': variationid,'qty': 1,'_token': _token};
    $.post(base_url+"plustocart",datastring,function(response){
        if(response.data.message=='ok'){
            getminicart();
            shoppingcarthtml();
            getCartTotal();
            $('#spinnerLoader').hide();
            $.growl.notice({
                title: "Success!",
                message: 'Quantity updated.' 
            });
        }else{
            $('#spinnerLoader').hide();
            $.growl.error({
                title: "Error!",
                message: response.data.notification 
            });
        }
    }); 
}

function minustocart(productid,variationid) {
    $('#spinnerLoader').show(); 
    var datastring = {'product_id':productid,'variationid': variationid,'qty': 1,'_token': _token};
    $.post(base_url+"minustocart",datastring,function(response){
        if(response.data.message=='ok'){
            getminicart();
            shoppingcarthtml();
            getCartTotal();
            $('#spinnerLoader').hide();
            $.growl.notice({
                title: "Success!",
                message: 'Quantity updated.' 
            });
        }else{
            $('#spinnerLoader').hide();
            $.growl.error({
                title: "Error!",
                message: response.data.notification 
            });
        }
    });
}  

/*******************************Wishlist Proccess************************/
function addToFav(pid,obj)
{
    if(pid>0){
        obj.find('i').removeClass('d-icon-heart').removeClass('d-icon-heart-full').addClass('fa fa-spinner fa-spin');
        var datastring = {'product_id':pid,'_token': _token};
        $.post(base_url+'addtofav',datastring,function(response){
            if(response.data.message == 'ok'){
                obj.find('i').removeClass('fa fa-spinner fa-spin').removeClass('d-icon-heart').removeClass('d-icon-heart-full').addClass(response.data.notification);
            }
        });
    } 
}
 
/*******************************Search Proccess************************/
//function searchdata(obj,urlid)
//{
//    var datastring = {'searchstring':obj.val(),'_token': _token};
//    $.post(base_url+'product/searchproduct',datastring,function(response){
//        $('#'+urlid).html(response);
//    });
//}

/* *********************************COUPON SECTION************************************ */

function applypromocode(obj) {
    var couponcode = $("#couponcode").val();
    if (couponcode.trim() == "") {
        $("#couponcode").attr("style", "border:1px solid red");
        return false;
    } else {
        $("#couponcode").attr("style", "");
    }
    $("#spinnerLoader").show();
    var datastring = { promocode: couponcode, totalAmount: finaltotal, _token: _token };
    $.post(base_url + "cart/updatetocartcouponbyname", datastring, function (response) {
        if (response.data.message == "ok") {
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            $(".alert-title").html(response.data.notification);
            $(".alertmessage").show();
            $("#spinnerLoader").hide();
        }
    });
}

function applypromocodemodal(obj) {
    var couponcode = $("#couponcodemodal").val();
    if (couponcode.trim() == "") {
        $("#couponcodemodal").attr("style", "border:1px solid red");
        return false;
    } else {
        $("#couponcodemodal").attr("style", "");
    }
    $("#spinnerLoader").show();
    var datastring = { promocode: couponcode, totalAmount: finaltotal, _token: _token };
    $.post(base_url + "cart/updatetocartcouponbyname", datastring, function (response) {
        if (response.data.message == "ok") {
            setTimeout(() => {
                $('.mfp-close').trigger('click');
                setTimeout(() => {
                    $('#couponcode').html(response.data.promo_code);
                    $('#couponamt').html(response.data.discountAmount);
                    $("#spinnerLoader").hide();
                    $('#couponsuccessmodal').trigger('click');
                    $('.mfp-close').hide();
                    $('#successcoupondiv').show();
                    $('#errorcoupondiv').hide();
                }, 1000);
                //location.reload();
            }, 1000);
        } else {
            setTimeout(() => {
                $('.mfp-close').trigger('click');
                setTimeout(() => {
                    $('#errornotification').html(response.data.notification);
                    $("#spinnerLoader").hide();
                    $('#couponsuccessmodal').trigger('click');
                    $('.mfp-close').hide();
                    $('#successcoupondiv').hide();
                    $('#errorcoupondiv').show();
                }, 1000);
                //location.reload();
            }, 1000);
        }
    });
}

function applyOffer(couponcode) {
    $("#spinnerLoader").show();
    var datastring = { promocode: couponcode, totalAmount: finaltotal, _token: _token };
    $.post(base_url + "cart/updatetocartcouponbyname", datastring, function (response) {
        if (response.data.message == "ok") {
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            $(".alert-title").html(response.data.notification);
            $(".alertmessage").show();
            $("#spinnerLoader").hide();
        }
    });
}

function removepromocode() {
    $("#spinnerLoader").show();
    $.get(base_url + "cart/removepromocode", function (response) {
        setTimeout(() => {
            location.reload();
        }, 1000);
    });
}

function movetowishlist(product_id,variationid){
    $("#spinnerLoader").show();
    var datastring = { product_id: product_id,variationid: variationid, _token: _token };
    $.post(base_url + "movetowishlist", datastring, function (response) {
        setTimeout(() => {
            location.reload();
        }, 1000);
    });
}

function copyCoupon(copyText) { 
    $('#copyAlert').show();
    navigator.clipboard.writeText(copyText);
    setTimeout(function(){ $('#copyAlert').fadeOut() }, 800);
}

