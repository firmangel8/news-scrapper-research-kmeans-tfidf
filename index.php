<!DOCTYPE html>
<?php
	
	$modul = $_GET["modul"];
	function viewSidebarMenu(){
		$view = "
		<!-- Sidebar user panel -->
          <div class=\"user-panel\">
            <div class=\"pull-left image\">
              <img src=\"img/head-logo.png\" class=\"img-circle\" alt=\"User Image\">
            </div>
            <div class=\"pull-left info\">
              <p>I Gede Mahendra Darmawiguna</p>
              <a href=\"#\"><i class=\"fa fa-circle text-success\"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form action=\"#\" method=\"get\" class=\"sidebar-form\">
            <div class=\"input-group\">
              <input type=\"text\" name=\"q\" class=\"form-control\" placeholder=\"Search...\">
              <span class=\"input-group-btn\">
                <button type=\"submit\" name=\"search\" id=\"search-btn\" class=\"btn btn-flat\"><i class=\"fa fa-search\"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class=\"sidebar-menu\">
            <li class=\"header\">MAIN NAVIGATION</li>
            <li class=\"treeview\">
              <a href=\"#\">
                <i class=\"fa fa-dashboard\"></i> <span>Configuration</span> <i class=\"fa fa-angle-left pull-right\"></i>
              </a>
              <ul class=\"treeview-menu\">
                <li><a href=\"?modul=masterstopwords\"><i class=\"fa fa-circle-o\"></i> Stop Words</a></li>
                <li><a href=\"?modul=mastercluster\"><i class=\"fa fa-circle-o\"></i> Sarkawi Test</a></li>
                <li><a href=\"?modul=masterscrap\"><i class=\"fa fa-circle-o\"></i> Setup Scrap</a></li>
                <li><a href=\"?modul=clustering\"><i class=\"fa fa-circle-o\"></i> Clustering</a></li>
              </ul>
            </li>		
		";
		
		echo $view;
	}
	
	function viewContent($modul){
		if($modul=="masterscrap"){
			include "modul/m_scrap.php";
		}elseif($modul=="masterstopwords"){
      include "modul/m_stopwords.php";
    }elseif($modul=='test')
    {
      include "modul/test.php";
    }elseif($modul=="clustering"){
			?>
        <section class="content-header">
          <h1>
          Setup Web Scraping Process
          </h1>
          <ol class="breadcrumb">
          <li><a href="../index.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Setup Scrap</li>
          </ol>
        </section>
        <section class="content">
          	<div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="box box-default">
                  <div class="box-header with-border">
                    <h3>Clustering Process</h3>
                  </div>
                  <form class="form-horizontal" method="POST" action="">
                    <div class="box-body">
                        <div class="form-group">
                          <label class="col-sm-2 control-label">Solver</label>
                          <div class="col-sm-6">
                            <input type="text" name="solver" class="form-control" placeholder="enter solver here"/>
                          </div>
                        </div>
                      
                    </div>
                    <div class="box-footer">
                      <button type="submit" class="btn btn-info pull-right">
                        <i class="fa fa-play">Start</i>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <?php
              if(isset($_POST['solver'])){
                include 'modul/core-process.php';
                $result = coreProcess($_POST['solver']);
                ?>
                <div class="row">
                  <div class="col-lg-8 col-md-8">
                    <div class="box box-primary">
                      <div class="box-header with border">
                        <h3>Clustering Result</h3>
                      </div>
                      <div class="box-body">
                        <div class="row">
                          <?php
                            foreach($result as $r){
                              ?>
                                <div class="col-lg-12 col-md-12">
                                  <div class="callout callout-info">
                                    <h4>Cluster ke <?php echo ($r['indexer']+1)?></h4>
                                    <hr/>
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12">
                                        <p><h4><span class="label label-success">Centroid [x, y] <i class="fa fa-arrow-right"></i> [<?=$r['positionX']?>, <?=$r['positionY']?>]</span></h4> </p>
                                      </div>
                                      <div class="col-lg-12 col-md-12">
                                        <div class="panel" style="background-color:#2e3131">
                                          <div class="panel panel-heading" style="background-color:#2e3131">
                                          Jumlah Anggota Cluster : <?=$r['counter']?>
                                          </div>
                                          <div class="panel panel-body" style="background-color:#2e3131">
                                            <ol>
                                            <?php
                                              foreach($r['cluster'] as $clusterMember){
                                                ?>
                                                  <li>[ <?=$clusterMember['C0']?>,<?=$clusterMember['C1']?>,<?=$clusterMember['C2']?>,<?=$clusterMember['C3']?> ]</li>
                                                <?php
                                              }
                                            ?>
                                            </ol>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php
                            }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4">
                    <div class="panel panel-primary">
                     <div class="panel panel-heading">
                      <h3>Website Recomendation</h3>
                     </div>
                     <div class="panel panel-body">
                      <?php
                      include 'config/connect.php';
                      $i = 1;
                       $payloadRecomendation = array();
                        foreach($result as $r){
                          foreach($r['cluster'] as $clusterMember){
                            $c0 = $clusterMember['C0'];
                            $c1 = $clusterMember['C1'];
                            $c2 = $clusterMember['C2'];
                            $c3 = $clusterMember['C3'];
                            $scopePayload[] = array('sc0' => $c0); 
                          }
                        }
                        //extract recomenation rank by cluster
                        $query = "SELECT DISTINCT(website_url) as 'website_url' FROM words WHERE words_tfidf IN ($c0, $c1, $c2, $c3) LIMIT 1";
                            foreach($conn->query($query) as $dataRecomendation){
                                $payloadRecomendation[] = array('url' =>$dataRecomendation['website_url']);
                                // $i++;
                        }
                        ?><ul><?php
                        foreach($payloadRecomendation as $websiteUrlRecomendation){
                          ?>
                            <li>
                                <a href="<?=$websiteUrlRecomendation['url']?>" target="_blank">
                                  <?=$websiteUrlRecomendation['url']?>
                                </a>
                            </li>
                          <?php
                        }
                        ?></ul><?php
                      ?>
                     </div>
                    </div>
                  </div>  
                </div>
                <?php
              }
            ?>
        </section>
			
			<?php
		}
    else{
			include "modul/home.php";
		}
	}

?>



<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Web Scraping</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
     <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  </head>
  <!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
  <!-- the fixed layout is not compatible with sidebar-mini -->
  <body class="hold-transition skin-blue fixed sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="../../index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>E</b>SCRAP</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Web</b>Scraping</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li><!-- end message -->
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">10</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 10 notifications</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li>
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <span class="label label-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="img/head-logo.png" class="user-image" alt="User Image">
                  <span class="hidden-xs">Darmawiguna</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="img/head-logo.png" class="img-circle" alt="User Image">
                    <p>
                      Gede Aditra Pradnyana - Web Developer
                      <small>Member since Nov. 2012</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="#" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
			<?php viewSidebarMenu(); ?>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
       
			<?php 
				viewContent($modul);
			?>
        </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2018 <a href="http://pti.undiksha.ac.id">Universitas Pendidikan Ganesha</a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Home tab content -->
          <div class="control-sidebar-menu" id="control-sidebar-home-tab">
            <ul class="control-sidebar-menu">
              
            </ul><!-- /.control-sidebar-menu -->
          </div><!-- /.tab-pane -->
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
	<!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
	 <script>
      $(function () {
        $('#datatable').DataTable();
      });
    </script>
  </body>
</html>
