<?php
	include "config/connect.php";
	include "googlesearch.php";


	function viewMessage($msg){
		if($msg==1){
			$view = "
			<div class=\"alert alert-success alert-dismissable\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"><i class=\"icon fa fa-remove\"></i></button>
					<i class=\"icon fa fa-check\"></i> Simpan Data Berhasil
                  </div>
			";
		}elseif($msg==2){
			$view = "
			<div class=\"alert alert-danger alert-dismissable\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"><i class=\"icon fa fa-remove\"></i></button>
					<i class=\"icon fa fa-warning\"></i> Gagal Simpan Data
                  </div>
			";
		}elseif($msg==3){
			$view = "
			<div class=\"alert alert-success alert-dismissable\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"><i class=\"icon fa fa-remove\"></i></button>
					<i class=\"icon fa fa-check\"></i> Edit Data Berhasil
                  </div>
			";
		}
		elseif($msg==4){
			$view = "
			<div class=\"alert alert-danger alert-dismissable\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"><i class=\"icon fa fa-remove\"></i></button>
					<i class=\"icon fa fa-warning\"></i> Gagal Edit Data
                  </div>
			";
		}elseif($msg=="hapus"){
			$id=$_GET["id"];
			$view = "
			<div class=\"alert alert-warning alert-dismissable\">		
					<i class=\"icon fa fa-warning\"></i> Anda yakin akan menghapus <b>".$id."</b> ?
					<a href=\"?modul=mastermahasiswa&act=proseshapusmahasiswa&id=".$id."\" class=\"btn btn-info btn-flat btn-xs\"><i class=\"fa fa-check\"></i> Ya</a>
					<a href=\"?modul=mastermahasiswa\" class=\"btn btn-danger btn-flat btn-xs\"><i class=\"fa fa-remove\"></i> Tidak</a>
                  </div>
			";
		}elseif($msg==5){
			$view = "
			<div class=\"alert alert-success alert-dismissable\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\"><i class=\"icon fa fa-remove\"></i></button>
					<i class=\"icon fa fa-check\"></i> Hapus Data Berhasil
                  </div>
			";
		}
		echo $view;
	}
	
	
	
	function viewSetupScrap(){
		$view = "
		<div class=\"box box-default\">
              <div class=\"box-header with-border\">
                <h3 class=\"box-title\">Setting Parameter</h3>
              </div>
               <form class=\"form-horizontal\" action=\"?modul=masterscrap&act=prosesscrap\" method=\"POST\">
                  <div class=\"box-body\">
                    <div class=\"form-group\">
                      <label class=\"col-sm-2 control-label\">Google Keywords</label>
                      <div class=\"col-sm-6\">
                        <input type=\"text\" class=\"form-control\" name=\"keyword\" placeholder=\"Keyword\">
                      </div>
                    </div>
                    <div class=\"form-group\">
                      <label class=\"col-sm-2 control-label\">Number of Websites</label>
                      <div class=\"col-sm-2\">
                        <input type=\"number\" class=\"form-control\" name=\"number\" placeholder=\"\">
                      </div>
                    </div>
				 </div><!-- /.box-body -->
                  <div class=\"box-footer\">
                  	<button type=\"submit\" class=\"btn btn-info pull-right\"><i class=\"fa fa-play\"></i> Start Scraping Process</button>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
		";
		
		echo $view;
	}
	

	
	function prosesSetupScrap(){
		$keyword = $_POST["keyword"];
		$number= $_POST["number"];
		
		$view = "
		<div class=\"box box-default\">
              <div class=\"box-header with-border\">
                <h3 class=\"box-title\">Scraping Result</h3>
              </div>
               <form class=\"form-horizontal\" action=\"?modul=masterscrap&act=prosesscrap\" method=\"POST\">
                  <div class=\"box-body\">";


		$view .= googleSearch($keyword,$number);

		$view .= "</div> 
				<div class=\"box-footer\">
					<a href=\"?modul=masterscrap\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>
					<a href=\"?modul=masterscrap&act=preprocess&key=".$keyword."&no=".$number."\" class=\"btn btn-primary pull-right\"><i class=\"fa fa-play\"></i> Start Pre-Processing</a>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
		";

		echo $view;
	}


	function prosesPreprocessing(){
		$keyword = $_GET["key"];
		$number= $_GET["no"];

		$view = "
		<div class=\"box box-default\">
              <div class=\"box-header with-border\">
                <h3 class=\"box-title\">Scraping Result</h3>
              </div>
               <form class=\"form-horizontal\" action=\"?modul=masterscrap&act=prosesscrap\" method=\"POST\">
                  <div class=\"box-body\">";


		include "preprocess.php";

		$view .= preProcessing($keyword,$number);

		$view .= "</div> 
				<div class=\"box-footer\">
					<a href=\"?modul=masterscrap\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Back</a>
					<a href=\"?modul=masterscrap&act=preprocess&key=".$keyword."&no=".$number."\" class=\"btn btn-primary pull-right\"><i class=\"fa fa-play\"></i> Start Pre-Processing</a>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
		";

		echo $view;

	}

	function coreProcess(){
		// $view
	}
	
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
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	
	  <?php 
	  	if(isset($_GET["act"])){
	  		$act = $_GET["act"];
	  	}else{
	  		$act = "";
	  	}
		
		if($act=="prosesscrap"){
			prosesSetupScrap();
		}elseif($act=="preprocess"){
			prosesPreprocessing();
			
		}
		else{
			viewSetupScrap();
		}
		
	  ?>
</section><!-- /.content -->