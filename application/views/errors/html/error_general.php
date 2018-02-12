<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentelella Alela! | </title>

    <!-- Bootstrap -->
    <link href="../../../../assets/society/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../../../assets/society/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../../../../assets/society/vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../../../../assets/society/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number"><?php echo $heading; ?></h1>
              <h2><?php echo $message; ?></h2>
              <div class="mid_center">
                
                
                  <div class="col-xs-12 form-group pull-right top_search">
                      <button onclick="goBack()">Go Back</button>
                  </div>
                
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../../../../assets/society/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../../../assets/society/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../../../../assets/society/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../../../assets/society/vendors/nprogress/nprogress.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../../../../assets/society/build/js/custom.min.js"></script>
    <script>
function goBack() {
    window.history.back();
}
</script>
  </body>
</html>
