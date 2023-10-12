<!DOCTYPE html>
<html>
	<head>
		<title>Embed PHP in a .html File</title>
	</head>
	<body>
		<h1><?php echo "Payment Kalto" ?></h1>
		<kalto-pay 
		    show="true" 
		    layout="modal"
		    payment-order-id="81fb114e-5039-4360-8493-1d18de252cef"
		    merchant-key="rbfBgPzL35QJrou2xw6CLwC8LDENqBvw">
		  </kalto-pay>
	</body>

	<script>
      var kaltoPayScript = "https://kalto-scripts-bucket-sandbox.s3.us-west-1.amazonaws.com/kalto-pay.js";
	    var versionUpdate = "?v=" + (new Date()).getTime();  
	    var script = document.createElement("script");  
	    script.type = "text/javascript";  
	    script.src = kaltoPayScript + versionUpdate;  
	    document.head.appendChild(script); 
  </script>
</html>