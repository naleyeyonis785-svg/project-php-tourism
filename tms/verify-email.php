<?php
session_start();
error_reporting(0);
include('includes/config.php');

$message = "";
$messageType = ""; // success or error

if(isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    
    // Find user with this token
    $sql = "SELECT id, EmailId, FullName, IsEmailVerified FROM tblusers WHERE EmailVerificationToken=:token";
    $query = $dbh->prepare($sql);
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        if($result->IsEmailVerified == 1) {
            $message = "Your email has already been verified. You can now login.";
            $messageType = "info";
        } else {
            // Update user to verified
            $updateSql = "UPDATE tblusers SET IsEmailVerified = 1, EmailVerificationToken = NULL WHERE EmailVerificationToken=:token";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':token', $token, PDO::PARAM_STR);
            $updateQuery->execute();
            
            if($updateQuery->rowCount() > 0) {
                $message = "Email verified successfully! You can now login to your account.";
                $messageType = "success";
            } else {
                $message = "Failed to verify email. Please try again or contact support.";
                $messageType = "error";
            }
        }
    } else {
        $message = "Invalid verification token. Please check your email link or request a new verification email.";
        $messageType = "error";
    }
} else {
    $message = "Invalid verification link. Please check your email.";
    $messageType = "error";
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>TMS | Email Verification</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href="css/font-awesome.css" rel="stylesheet">
<!-- Custom Theme files -->
<script src="js/jquery-1.12.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
	<script>
		 new WOW().init();
	</script>
<!--//end-animate-->
<style>
.verification-message {
    padding: 20px;
    margin: 20px 0;
    border-radius: 5px;
    text-align: center;
}
.verification-message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.verification-message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.verification-message.info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}
</style>
</head>
<body>
<?php include('includes/header.php');?>
<div class="banner-1 ">
	<div class="container">
		<h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;"> Email Verification</h1>
	</div>
</div>
<!--- /banner-1 ---->
<!--- contact ---->
<div class="contact">
	<div class="container">
	<h3> Email Verification Status</h3>
		<div class="col-md-10 contact-left">
			<div class="con-top animated wow fadeInUp animated" data-wow-duration="1200ms" data-wow-delay="500ms" style="visibility: visible; animation-duration: 1200ms; animation-delay: 500ms; animation-name: fadeInUp;">
				<div class="verification-message <?php echo $messageType; ?>">
					<h4><?php echo htmlspecialchars($message); ?></h4>
					<?php if($messageType == "success" || $messageType == "info") { ?>
						<p><a href="index.php" style="margin-top: 20px; display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Go to Login</a></p>
					<?php } else { ?>
						<p><a href="index.php" style="margin-top: 20px; display: inline-block; padding: 10px 20px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 5px;">Return to Home</a></p>
					<?php } ?>
				</div>
			</div>
			<div class="clearfix"></div>
	</div>
</div>
<!--- /contact ---->
<?php include('includes/footer.php');?>
<!-- sign -->
<?php include('includes/signup.php');?>	
<!-- signin -->
<?php include('includes/signin.php');?>	
<!-- //signin -->
<!-- write us -->
<?php include('includes/write-us.php');?>	
<!-- //write us -->
</body>
</html>

