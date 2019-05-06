<?php
	include "config/connect.php";
	
	function viewMessage($msg){

		$view = "";

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
					<a href=\"?modul=masterstopwords&act=proseshapusstopwords&id=".$id."\" class=\"btn btn-info btn-flat btn-xs\"><i class=\"fa fa-check\"></i> Ya</a>
					<a href=\"?modul=masterstopwords\" class=\"btn btn-danger btn-flat btn-xs\"><i class=\"fa fa-remove\"></i> Tidak</a>
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
	
	function viewDataStopwords($conn){
		if(isset($_GET["msg"])){
			$msg = $_GET["msg"];
		}else{
			$msg = "";
		}
		
		$view = "
		<div class=\"box\">
			<div class=\"box-header with-border\">
			  <h3 class=\"box-title\">Data Stopwords</h3>
			   <div class=\"box-tools pull-right\">
					<a href=\"?modul=masterstopwords&act=viewtambahstopwords\" class=\"btn btn-primary btn-flat\" title=\"Tambah Stopwords\"><i class=\"fa fa-plus\"></i> Tambah</a>
				</div>
			</div>
		<div class=\"box-body\">
		".viewMessage($msg)."
		 <table id=\"datatable\" class=\"table table-bordered table-striped\">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Word</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
		";
		
		$no=1;
		$sql = "SELECT * FROM stopwords";
		$q = mysqli_query($conn,$sql);
		while($data=mysqli_fetch_array($q)){
			$view .= "
			<tr>
				<td>".$data["stopwords_id"]."</td>
				<td>".$data["stopwords_term"]."</td>
				<td>
					<a href=\"?modul=masterstopwords&act=vieweditstopwords&id=".$data["stopwords_id"]."\" class=\"btn btn-info btn-flat btn-xs\" title=\"Edit\"><i class=\"fa fa-pencil\"></i> Edit</a>
					<a href=\"?modul=masterstopwords&msg=hapus&id=".$data["stopwords_id"]."\"  class=\"btn btn-danger btn-flat btn-xs\" title=\"Edit\"><i class=\"fa fa-trash\"></i> Hapus</a>
				</td>
			</tr>
			";
			$no++;
		}
				
		$view .= "
					</tbody>
		</table>
		</div>
		</div>
		";
		
		echo $view;
	}
	
	function viewTambahStopwords(){
		$view = "
		<div class=\"box box-default\">
              <div class=\"box-header with-border\">
                <h3 class=\"box-title\">Tambah Data Stopwords</h3>
              </div>
               <form class=\"form-horizontal\" action=\"?modul=masterstopwords&act=prosestambahstopwords\" method=\"POST\">
                  <div class=\"box-body\">
                    <div class=\"form-group\">
                      <label class=\"col-sm-2 control-label\">Word</label>
                      <div class=\"col-sm-6\">
                        <input type=\"text\" class=\"form-control\" name=\"word\" placeholder=\"Word\">
                      </div>
                    </div>
				 </div><!-- /.box-body -->
                  <div class=\"box-footer\">
                    <a href=\"?modul=masterstopwords\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Kembali</a>
					<button type=\"submit\" class=\"btn btn-info pull-right\"><i class=\"fa fa-save\"></i> Simpan</button>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
		";
		
		echo $view;
	}
	
	function viewEditStopwords($conn){
		$id=$_GET["id"];
		$sql="SELECT * FROM stopwords WHERE stopwords_id='".$id."'";
		$q = mysqli_query($conn,$sql);
		if($data=mysqli_fetch_array($q)){
			$view = "
			<div class=\"box box-default\">
				  <div class=\"box-header with-border\">
					<h3 class=\"box-title\">Edit Stop Words</h3>
				  </div>
				   <form class=\"form-horizontal\" action=\"?modul=masterstopwords&act=proseseditstopwords\" method=\"POST\">
					  <div class=\"box-body\">
						<div class=\"form-group\">
						  <label class=\"col-sm-2 control-label\">ID</label>
						  <div class=\"col-sm-6\">
							<input type=\"text\" class=\"form-control\" value=\"".$data["stopwords_id"]."\" disabled>
							<input type=\"hidden\" class=\"form-control\" name=\"id\" value=\"".$data["stopwords_id"]."\">
						  </div>
						</div>
						<div class=\"form-group\">
						  <label class=\"col-sm-2 control-label\">Word</label>
						  <div class=\"col-sm-6\">
							<input type=\"text\" class=\"form-control\" name=\"word\" value=\"".$data["stopwords_term"]."\">
						  </div>
						</div>
					 </div><!-- /.box-body -->
					  <div class=\"box-footer\">
						<a href=\"?modul=masterstopwords\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Kembali</a>
						<button type=\"submit\" class=\"btn btn-info pull-right\"><i class=\"fa fa-save\"></i> Edit</button>
					  </div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			";
		}
		
		
		echo $view;
	}
	
	function prosesTambahStopwords($conn){
		$word = $_POST["word"];
		
		$sql = "INSERT INTO stopwords(stopwords_term) VALUES('".$word."')";
		$q = mysqli_query($conn,$sql);
		if($q){
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=masterstopwords&msg=1\");
			</script>
			";
		}else{
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=masterstopwords&msg=2\");
			</script>
			";
		}
	}
	function prosesEditStopwords($conn){
		$id = $_POST["id"];
		$word = $_POST["word"];
	
		
		$sql = "UPDATE stopwords SET stopwords_term='".$word."' WHERE stopwords_id='".$id."'";
		$q = mysqli_query($conn,$sql);
		if($q){
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=masterstopwords&msg=3\");
			</script>
			";
		}else{
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=masterstopwords&msg=4\");
			</script>
			";
		}
	}
	
	function prosesHapusStopwords($conn){
		$id = $_GET["id"];
		
		$sql = "DELETE FROM stopwords WHERE stopwords_id='".$id."'";
		$q = mysqli_query($conn,$sql);
		if($q){
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=masterstopwords&msg=5\");
			</script>
			";
		}else{
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=masterstopwords&msg=6\");
			</script>
			";
		}
	}
?>



<section class="content-header">
  <h1>
	Master Stop Words
  </h1>
  <ol class="breadcrumb">
	<li><a href="../index.php"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">data stop words</li>
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
		
		if($act=="viewtambahstopwords"){
			viewTambahStopwords();
		}elseif($act=="prosestambahstopwords"){
			prosesTambahStopwords($conn);
		}elseif($act=="vieweditstopwords"){
			viewEditStopwords($conn);
		}elseif($act=="proseseditstopwords"){
			prosesEditStopwords($conn);
		}elseif($act=="proseshapusstopwords"){
			prosesHapusStopwords($conn);
		}else{
			viewDataStopwords($conn);
		}
		
	  ?>
</section><!-- /.content -->