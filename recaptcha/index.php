<?php
/***
 * Automate Recaptcha
 *
 * Example in PHP, concepts apply to any server-side language: .NET, Node, etc.
 *
 * Hide or show the recaptcha based on server environment variables.
 *
 */

$success = false;
$errors = [];
$is_test_environment = getenv('atp_environment') == 'my_test_environment';

if (!empty($_POST['name'])) {

    //verify recaptcha
    if (!$is_test_environment) {
        /***
         *
         * From Recaptcha Setup documentation:
         * https://www.google.com/recaptcha/admin#site/340861227?setup
         * When your users submit the form where you integrated reCAPTCHA, you'll get as part of the payload a string with the name "g-recaptcha-response".
         * In order to check whether Google has verified that user, send a POST request with these parameters:
         * URL: https://www.google.com/recaptcha/api/siteverify
         *
         */
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => 'YOURRECAPTCHASECRETKEY', 'response' => @$_POST['g-recaptcha-response']);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $http_result = file_get_contents($url, false, $context);
        if ($http_result === false) { /* Handle error */
        }

        $recaptcha_result = json_decode($http_result);

        if (!$recaptcha_result->success) {
            $errors[] = 'Recaptcha response was invalid';
        }
    }

    if (empty($errors)) {
        // Process the form, the recaptcha was successful
        $success = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <div class="starter-template">

        <h1>Bootstrap Form Example</h1>

        <?php
        if (!empty($errors)) {
            ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) {
                    echo $error . '<br />';
                } ?>
            </div>
            <?php
        } else if ($success) {
            ?>
            <div class="alert alert-success">
                You submitted this form!
            </div>
            <?php
        }
        ?>

        <form method="post">
            <div class="row form-group">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-8">
                    <input type="text" name="name" class="form-control">
                </div>
            </div>
            <div class="row form-group">
                <label class="col-md-2 control-label">Email Address</label>
                <div class="col-md-8">
                    <input type="text" name="email" class="form-control">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-2 col-md-offset-4 text-center">
                    <?php
                    // Determine if we're currently operating in a development environment
                    if (!$is_test_environment) {
                        // then display Recaptcha
                        ?>
                        <div class="g-recaptcha" data-sitekey="6LcrIVEUAAAAAH_0RmKS879Lef9pR-r_61IQjX_N"></div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="form-check">

            </div>

            <div class="row form-group">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>

        </form>

    </div>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../../dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
