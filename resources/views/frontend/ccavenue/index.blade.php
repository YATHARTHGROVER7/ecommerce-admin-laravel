<html>
<head>
<title> CCAvenue Payment Gateway Integration kit</title>
</head>
<body>
<center>
<!-- <iframe src="<?php echo $production_url?>" id="paymentFrame" width="482" height="450" frameborder="0" scrolling="No" ></iframe> -->
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction">
    <input type="hidden" name="encRequest" value="{{$encrypted_data}}">
    <input type="hidden" name="access_code" value="{{$access_code}}"> 
</form>
</center>
<script language='javascript'>document.redirect.submit();</script>
<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.js"></script>
<script type="text/javascript">
       $(document).ready(function(){
           window.addEventListener('message', function(e) {
              $("#paymentFrame").css("height",e.data['newHeight']+'px');     
          }, false);
      });
</script> -->
</body>
</html>