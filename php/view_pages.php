<?php
$start_time = microtime(TRUE);
   require_once("config.php");
   $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");
session_start();
if(!isset($_SESSION['user'])){
       header('location: login.php');
   }
 function sendMessageThroughGCM($registration_ids, $message) {
        //Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );
        // Update your Google Cloud Messaging API Key
        define("GOOGLE_API_KEY", "AIzaSyAZkvlEZxGIsuMhhlvCLM750GiloYfEa78");        
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);               
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
            $milliseconds = round(microtime(true) * 1000);
                if(isset($_POST['topic']))
                {
                 $topic=$_POST['topic'];
                 $sqlString = "SELECT topic_id FROM topic WHERE name='$topic'";
                 $result = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
                 $row=$result->fetch_row();
                 $main_id=$row[0];
                }
                $sqlString = "SELECT client_id FROM data WHERE main_id='$main_id'";
                $data = array();
                $gcmRegID = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
                while(($row = mysqli_fetch_array($gcmRegID))) {
                               $data[] = $row['client_id'];
                              }
                $req=array();
                $req['type']="Request";
                $req['main_id']=$main_id;
                $message = array("m" => json_encode($req)); 
        $pushStatus = sendMessageThroughGCM($data, $message);   
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MDS - Mobile Data Server">
    <meta name="author" content="phacsin">
    <meta name="keyword" content="MDS, Phacsin, phacsindevs">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>MDS - Mobile Data Server | CPanel</title>

    <!-- Bootstrap CSS -->    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />    
    <!-- full calendar css-->
    <link href="assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
	<link href="assets/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" />
    <!-- DataTables -->
    <link rel="stylesheet" href="assets/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="assets/datatables/jquery.dataTables.bootstrap.css">
    <!-- easy pie chart-->
    <link href="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <!-- owl carousel -->
    <link rel="stylesheet" href="css/owl.carousel.css" type="text/css">
	<link href="css/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <!-- Custom styles -->
	<link rel="stylesheet" href="css/fullcalendar.css">
	<link href="css/widgets.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
	<link href="css/xcharts.min.css" rel=" stylesheet">	
	<link href="css/jquery-ui-1.10.4.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
      <script src="js/lte-ie7.js"></script>
    <![endif]-->
  </head>

  <body>
  <!-- container section start -->
  <section id="container" class="">
     
      
      <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"></div>
            </div>

            <!--logo start-->
            <a href="index.html" class="logo">Mobile <span class="lite">Data Server</span></a>
            <!--logo end-->

            <div class="nav search-row" id="top_menu">
                <!--  search form start -->
                <ul class="nav top-menu">                    
                    <li>
                        <form class="navbar-form">
                            <input class="form-control" placeholder="Search" type="text">
                        </form>
                    </li>                    
                </ul>
                <!--  search form end -->                
            </div>

            <div class="top-nav notification-row">                
                <!-- notificatoin dropdown start-->
                <ul class="nav pull-right top-menu">
                    
                    <!-- alert notification end-->
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="profile-ava">
                                <!--img alt="" src="img/avatar1_small.jpg"-->
                            </span>
                            <span class="username"><?php echo $_SESSION['user']; ?></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            
                            <li>
                                <a href="logout.php"><i class="icon_lock_alt"></i> Log Out</a>
                            </li>
                            <li>
                                <a href=""><i class="icon_key_alt"></i> Documentation</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                
                <!-- notificatoin dropdown end-->
            </div>
      </header>      
      <!--header end-->

      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu">                
                  <li class="sub-menu">
                      <a class="" href="index.php">
                          <i class="icon_house_alt"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a href="insert_data.php" class="">
                          <i class="icon_document_alt"></i>
                          <span>Insert Data</span>
                      </a>
                  </li>       
                  <li class="active">
                      <a href="test_pages.php" class="">
                          <i class="icon_documents_alt"></i>
                          <span>Test Pages</span>
                      </a>
                  </li>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">            
              <!--overview start-->
			  <div class="row">
  				<div class="col-lg-12">
  					<h3 class="page-header"><i class="fa fa-laptop"></i> Test Pages</h3>
  					<ol class="breadcrumb">
  						<li><i class="fa fa-home"></i><a href="index.php">Home</a></li>
  						<li><i class="fa fa-laptop"></i>Test Pages</li>						  	
  					</ol>
  				</div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                    <header class="panel-heading">
                        
                        <h3 id="main_topic"></h3>
                    </header>
                    <div class="panel-body" id="content">
                        
                    </div>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                    $end_time = microtime(TRUE);
 
$time_taken = $end_time - $start_time;
 
$time_taken = round($time_taken,5);
 
//echo 'Page generated in '.$time_taken.' seconds.';
                ?>
            </div>
        </div>
          </section>
      </section>
      <!--main content end-->
  </section>
  <!-- container section start -->

    <!-- javascripts -->
    <script src="js/jquery.js"></script>
	<script src="js/jquery-ui-1.10.4.min.js"></script>
    <script src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
    <!-- bootstrap -->
    <script src="js/bootstrap.min.js"></script>
    <!-- nice scroll -->
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <!-- charts scripts -->
    <script src="assets/jquery-knob/js/jquery.knob.js"></script>
    <script src="js/jquery.sparkline.js" type="text/javascript"></script>
    <script src="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
    <script src="js/owl.carousel.js" ></script>
    <!-- jQuery full calendar -->
    <<script src="js/fullcalendar.min.js"></script> <!-- Full Google Calendar - Calendar -->
	<script src="assets/fullcalendar/fullcalendar/fullcalendar.js"></script>
    <!--script for this page only-->
    <script src="js/calendar-custom.js"></script>
	<script src="js/jquery.rateit.min.js"></script>
    <!-- custom select -->
    <script src="js/jquery.customSelect.min.js" ></script>
	<script src="assets/chart-master/Chart.js"></script>
    <!-- DataTables -->
    <script src="assets/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/datatables/dataTables.bootstrap.min.js"></script>
   
    <!--custome script for all page-->
    <script src="js/scripts.js"></script>
    <!-- custom script for this page-->
    <script src="js/sparkline-chart.js"></script>
    <script src="js/easy-pie-chart.js"></script>
	<script src="js/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="js/jquery-jvectormap-world-mill-en.js"></script>
	<script src="js/xcharts.min.js"></script>
	<script src="js/jquery.autosize.min.js"></script>
	<script src="js/jquery.placeholder.min.js"></script>
	<script src="js/gdp-data.js"></script>	
	<script src="js/morris.min.js"></script>
	<script src="js/sparklines.js"></script>	
	<script src="js/charts.js"></script>
	<script src="js/jquery.slimscroll.min.js"></script>
  <script>
     

      //knob
      $(function() {
        $(".knob").knob({
          'draw' : function () { 
            $(this.i).val(this.cv + '%')
          }
        })
      });

      //carousel
      $(document).ready(function() {
          $("#owl-slider").owlCarousel({
              navigation : true,
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem : true

          });
      });

      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });
	  
	  var sendDate = <?php echo json_encode($milliseconds); ?>;
              var refreshId=setInterval(function() {
                     $.ajax({
               url: 'test.php',
                type: 'get',
                data: {'main_id': <?php echo $main_id; ?>,'gcm_id[]':<?php echo json_encode($data); ?>},
               success: function(data, status) {
                      if(data!="Error")
                       {
                         var response_string="";
                         var time;
                         var i;
                         var obj=JSON.parse(data);
                         for(i=0;i<obj.length;i++)
                         {        
                                 time=obj[i].response_time-sendDate;
                                 $("#content").append("<h3>"+obj[i].sub_name+" </h3>");
                                 $("#content").append("<h4> Data from IMEI :"+obj[i].imei+" and Cluster : "+obj[i].cluster_id +" with a response time :"+time+" ms</h4>");
                                 $("#content").append("<p>"+obj[i].info+"</p>");
                         }                    
                         clearInterval(refreshId);
                       }
                      
      },
      error: function(xhr, desc, err) {
         clearInterval(refreshId);
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }
    }); // end ajax call
                }, 200);
      document.title =<?php echo json_encode($topic);?>;
      var main_topic = document.getElementById('main_topic');
      main_topic.innerHTML=<?php echo json_encode($topic);?>;

  </script>

  </body>
</html>		