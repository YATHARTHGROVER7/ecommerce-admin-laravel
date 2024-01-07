<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="x-apple-disable-message-reformatting">
<title>HWF</title>
<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i" rel="stylesheet">
<style>
html,
body {
margin: 0 auto !important;
padding: 0 !important;
height: 100% !important;
width: 100% !important;
background: #f1f1f1;
}

/* What it does: Stops email clients resizing small text. */
* {
-ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%;
}

/* What it does: Centers email on Android 4.4 */
div[style*="margin: 16px 0"] {
margin: 0 !important;
}

/* What it does: Stops Outlook from adding extra spacing to tables. */
table,
td {
mso-table-lspace: 0pt !important;
mso-table-rspace: 0pt !important;
}

/* What it does: Fixes webkit padding issue. */
table {
border-spacing: 0 !important;
border-collapse: collapse !important;
table-layout: fixed !important;
margin: 0 auto !important;
}

/* What it does: Uses a better rendering method when resizing images in IE. */
img {
-ms-interpolation-mode:bicubic;
}

/* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
a {
text-decoration: none;
}

/* What it does: A work-around for email clients meddling in triggered links. */
*[x-apple-data-detectors],  /* iOS */
.unstyle-auto-detected-links *,
.aBn {
border-bottom: 0 !important;
cursor: default !important;
color: inherit !important;
text-decoration: none !important;
font-size: inherit !important;
font-family: inherit !important;
font-weight: inherit !important;
line-height: inherit !important;
}

/* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
.a6S {
display: none !important;
opacity: 0.01 !important;
}

/* What it does: Prevents Gmail from changing the text color in conversation threads. */
.im {
color: inherit !important;
}

/* If the above doesn't work, add a .g-img class to any image in question. */
img.g-img + div {
display: none !important;
}

/* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
/* Create one of these media queries for each additional viewport size you'd like to fix */

/* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
u ~ div .email-container {
min-width: 320px !important;
}
}
/* iPhone 6, 6S, 7, 8, and X */
@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
u ~ div .email-container {
min-width: 375px !important;
}
}
/* iPhone 6+, 7+, and 8+ */
@media only screen and (min-device-width: 414px) {
u ~ div .email-container {
min-width: 414px !important;
}
}

</style>

<!-- CSS Reset : END -->

<!-- Progressive Enhancements : BEGIN -->
<style>
.primary{
background: #f3a333;
}
.bg_white{
background: #ffffff;
}
.bg_light{
background: #fafafa;
}
.bg_black{
background: #000000;
}
.bg_dark{
background: rgba(0,0,0,.8);
}
.email-section{
padding:2.5em;
}
/*BUTTON*/
.btn{
padding: 10px 15px;
}
.btn.btn-primary{
border-radius: 30px;
background: #f3a333;
color: #ffffff;
}
h1,h2,h3,h4,h5,h6{
color: #000000;
margin-top: 0;
margin-bottom:10px;
}
body{
font-family: 'Montserrat', sans-serif;
font-weight: 400;
font-size: 15px;
line-height: 1.8;
color: rgba(0,0,0,.4);
}
a{
color: #f3a333;
}
table{
}
/*LOGO*/
.logo h1{
margin: 0;
}
.logo h1 a{
color: #000;
font-size: 20px;
font-weight: 700;
text-transform: uppercase;
font-family: 'Montserrat', sans-serif;
}
/*HERO*/
.hero{
position: relative;
}
.hero img{
}
.hero .text{
color: rgba(255,255,255,.8);
}
.hero .text h2{
color: #ffffff;
font-size: 30px;
margin-bottom: 0;
}
/*HEADING SECTION*/
.heading-section{
}
.heading-section h2{
color: #000000;
font-size: 28px;
margin-top: 0;
line-height: 1.4;
}
.heading-section .subheading{
margin-bottom: 20px !important;
display: inline-block;
font-size: 13px;
text-transform: uppercase;
letter-spacing: 2px;
color: rgba(0,0,0,.4);
position: relative;
}
.heading-section .subheading::after{
position: absolute;
left: 0;
right: 0;
bottom: -10px;
content: '';
width: 100%;
height: 2px;
background: #f3a333;
margin: 0 auto;
}
.heading-section-white{
color: rgba(255,255,255,.8);
}
.heading-section-white h2{
font-size: 28px;
font-family: 
line-height: 1;
padding-bottom: 0;
}
.heading-section-white h2{
color: #ffffff;
}
.heading-section-white .subheading{
margin-bottom: 0;
display: inline-block;
font-size: 13px;
text-transform: uppercase;
letter-spacing: 2px;
color: rgba(255,255,255,.4);
}
.icon{
text-align: center;
}
.icon img{
}
/*SERVICES*/
.text-services{
padding: 10px 10px 0; 
text-align: center;
}
.text-services h3{
font-size: 20px;
}
/*BLOG*/
.text-services .meta{
text-transform: uppercase;
font-size: 14px;
}
/*TESTIMONY*/
.text-testimony .name{
margin: 0;
}
.text-testimony .position{
color: rgba(0,0,0,.3);
}
/*VIDEO*/
.img{
width: 100%;
height: auto;
position: relative;
}
.img .icon{
position: absolute;
top: 50%;
left: 0;
right: 0;
bottom: 0;
margin-top: -25px;
}
.img .icon a{
display: block;
width: 60px;
position: absolute;
top: 0;
left: 50%;
margin-left: -25px;
}
/*COUNTER*/
.counter-text{
text-align: center;
}
.counter-text .num{
display: block;
color: #ffffff;
font-size: 34px;
font-weight: 700;
}
.counter-text .name{
display: block;
color: rgba(255,255,255,.9);
font-size: 13px;
}
/*FOOTER*/
.footer{
color: rgba(255,255,255,.5);
}
.footer .heading{
color: #ffffff;
font-size: 20px;
}
.footer ul{
margin: 0;
padding: 0;
}
.footer ul li{
list-style: none;
margin-bottom: 10px;
}
.footer ul li a{
color: rgba(255,255,255,1);
}
@media screen and (max-width: 500px) {
.icon{
text-align: left;
}
.text-services{
padding-left: 0;
padding-right: 20px;
text-align: left;
}
}
</style>
</head>
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #222222;">
<center style="width: 100%; background-color: #f1f1f1;">
<div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
</div>
<div style="max-width: 600px; margin: 0 auto;" class="email-container">
<!-- BEGIN BODY -->
<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
<tr>
<td class="logo" style="padding: 1em 2.5em; text-align: center ;background:white">
<a href="https://heartswithfingers.com/"><img src="https://heartswithfingers.com/csadmin/public/img/uploads/settings/169219063164dcc7a7a7a4a.png" width="125" height="52"></a>
</td>
</tr>
<tr>
<td style="background:white">
<a href="https://heartswithfingers.com/"><img src="https://heartswithfingers.com/csadmin/public/img/uploads/appearance/1698431924.jpg" width="100%" height="250"></a>
</td>
</tr>
<tr>
<td style="background:white">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td style="text-align:center; ">
<h2>Dear {{$details['name']}}<h2>
<h4>Welcome to the heartswithfingers parivar!</h4>
<h4>As a valuable member, we offer you an extra discount* this Diwali festival</h4>
</td>
</tr>
<tr>
<td class="logo" style="padding: 1em 2.5em; text-align: center;background:white">
<a href="https://heartswithfingers.com/"<img src="https://heartswithfingers.com/csadmin/public/img/uploads/appearance/1698431924.jpg" width="100%" height="102"></a>
</td>
</tr>
<tr>
</tr>
<tr>
<td class="bg_light email-section">
<div class="heading-section" style="text-align: center; padding: 0 30px;">
<h3>Our Product Categories</h3>
</div>
<table role="presentation" border="0" cellpadding="10" cellspacing="0" width="100%">
<tr>
<td valign="top" width="50%" style="padding-top: 20px;">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td>
<a href="https://heartswithfingers.com/collection/category/grocery-foods"><img src="	https://heartswithfingers.com/csadmin/public/img/uploads/media/1699000861.png" alt="" style="width: 80px; max-width: 600px; height: auto; margin: auto; margin-bottom: 10px; display: block; border-radius: 50%;"></a>
</td>
</tr>
<tr>
<td class="text-testimony" style="text-align: center;">
<h5 class="name">Food</h5>
</td>
</tr>
</table>
</td>
<td valign="top" width="50%" style="padding-top: 20px;">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td>
<a href="https://heartswithfingers.com/collection/category/apparel"><img src="https://heartswithfingers.com/csadmin/public/img/uploads/media/1699000980.png" alt="" style="width: 80px; max-width: 600px; height: auto; margin: auto; margin-bottom: 10px; display: block; border-radius: 50%;"></a>
</td>
</tr>
<tr>
<td class="text-testimony" style="text-align: center;">
<h5 class="name">Apparel</h5>
</td>
</tr>
</table>
</td>
<td valign="top" width="50%" style="padding-top: 20px;">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td>
<a href="https://heartswithfingers.com/collection/category/home-living"><img src="https://heartswithfingers.com/csadmin/public/img/uploads/media/1699001059.png" alt="" style="width: 80px; max-width: 600px; height: auto; margin: auto; margin-bottom: 10px; display: block; border-radius: 50%;"></a>
</td>
</tr>
<tr>
<td class="text-testimony" style="text-align: center;">
<h5 class="name">Home & Living</h5>
</td>
</tr>
</table>
</td>

<td valign="top" width="50%" style="padding-top: 20px;">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td>
<a href="https://heartswithfingers.com/collection/category/accessories"><img src="https://heartswithfingers.com/csadmin/public/img/uploads/media/1699001117.png" alt="" style="width: 80px; max-width: 600px; height: auto; margin: auto; margin-bottom: 10px; display: block; border-radius: 50%;"></a>
</td>
</tr>
<tr>
<td class="text-testimony" style="text-align: center;">
<h5 class="name">Accessories</h5>
</td>
</tr>
</table>
</td>
<td valign="top" width="50%" style="padding-top: 20px;">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td>
<a href="https://heartswithfingers.com/collection/category/handicrafts"><img src="https://heartswithfingers.com/csadmin/public/img/uploads/media/1699001191.png" alt="" style="width: 80px; max-width: 600px; height: auto; margin: auto; margin-bottom: 10px; display: block; border-radius: 50%;"></a>
</td>
</tr>
<tr>
<td class="text-testimony" style="text-align: center;">
<h5 class="name">Handicrafts</h5>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr><!-- end: tr -->
<tr>
<td style="text-align:left;padding:20px; ">
<h2>heartswithfingers<h2>
<h4>Curating the best of artisanal treasures from all over India</h4>
<h4>We’re an exclusive platform for artisans and farmers committed to building brighter futures through 
sustainable micro-enterprise. We invite you to explore our platform where every purchase supports a 
world of creativity and craftsmanship.</h4>
<h4>Buy one support one</h4>
<h4>With every heartswithfingers purchase, you’re directly supporting women artisans and farmers on 
their journey towards self-reliance.</h4>
</td>
</tr>
<tr>
<td class=" logo" style="padding: 1em 2.5em; text-align: center">
<a href="https://heartswithfingers.com/"><img src="https://heartswithfingers.com/csadmin/public/img/uploads/appearance/1698431924.jpg" width="100%" height="102"></a>
</td>
</tr>

<tr>
<td style="text-align:center; padding:20px;">
<h4>In case of any queries,</h4>
<h4>reach out to us support@heartswithfingers.com</h4>
<h5>Follow us on</h5>
</td>
</tr>
<tr>
<td style="text-align:center; padding:20px;background:#f5f5f5">
<p>This email was sent by heartswithfingers.<br>
Please add info@heartswithfingers.com to your address book.<br>
If you want to join our email list, subscribe here.<br>
Privacy Policy – Your Privacy Rights.<br>
Please do not reply to this email. Contact us here.<br>
@2023 HeartsWithFingers and its subsidiaries. All rights reserved.<br>
Office: A-21, behind Of Ramrakhi Hotel, Anand Vihar, Nagar Palika Colony, Chittorgarh, Rajasthan 312001</p>
</td>
</tr>
</table>

</td>
</tr><!-- end:tr -->
<!-- 1 Column Text + Button : END -->
</table>
<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
<tr>
<td valign="middle" class="bg_black footer">
<table>
<tr>
<td>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td style="text-align: center; padding:10px">
<p style="font-size:10px">This message has been sent as you have registered with our website www.heartswithfingers.com.Should you receive this
message by mistake, we ask that you inform us at your earliest possible experience. In this case, we also ask that you delete
this message from your mailbox, and do not forward it or any part of it to anyone else. Thank you for your cooperation
and understanding.</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr><!-- end: tr -->
</table>

</div>
</center>
</body>
</html>