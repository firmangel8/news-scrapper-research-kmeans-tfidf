<?php
	include "config/connect.php";
	
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
	
	function viewDataMahasiswa($conn){
		$msg = $_GET["msg"];
		$view = "
		<div class=\"box\">
			<div class=\"box-header with-border\">
			  <h3 class=\"box-title\">Data Mahasiswa</h3>
			   <div class=\"box-tools pull-right\">
					<a href=\"?modul=mastermahasiswa&act=viewtambahmahasiswa\" class=\"btn btn-primary btn-flat\" title=\"Tambah Mahasiswa\"><i class=\"fa fa-plus\"></i> Tambah</a>
				</div>
			</div>
		<div class=\"box-body\">
		".viewMessage($msg)."
		 <table id=\"datatable\" class=\"table table-bordered table-striped\">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Semester</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
		";
		
		$no=1;
		$sql = "SELECT * FROM mahasiswa";
		$q = mysqli_query($conn,$sql);
		while($data=mysqli_fetch_array($q)){
			$view .= "
			<tr>
				<td>".$no."</td>
				<td>".$data["nim"]."</td>
				<td>".$data["nama"]."</td>
				<td>".$data["semester"]."</td>
				<td>
					<a href=\"?modul=mastermahasiswa&act=vieweditmahasiswa&id=".$data["nim"]."\" class=\"btn btn-info btn-flat btn-xs\" title=\"Edit\"><i class=\"fa fa-pencil\"></i> Edit</a>
					<a href=\"?modul=mastermahasiswa&msg=hapus&id=".$data["nim"]."\"  class=\"btn btn-danger btn-flat btn-xs\" title=\"Edit\"><i class=\"fa fa-trash\"></i> Hapus</a>
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
	
	function viewTambahMahasiswa(){
		$view = "
		<div class=\"box box-default\">
              <div class=\"box-header with-border\">
                <h3 class=\"box-title\">Tambah Data Mahasiswa</h3>
              </div>
               <form class=\"form-horizontal\" action=\"?modul=mastermahasiswa&act=prosestambahmahasiswa\" method=\"POST\">
                  <div class=\"box-body\">
                    <div class=\"form-group\">
                      <label class=\"col-sm-2 control-label\">NIM</label>
                      <div class=\"col-sm-6\">
                        <input type=\"text\" class=\"form-control\" name=\"nim\" placeholder=\"NIM\">
                      </div>
                    </div>
                    <div class=\"form-group\">
                      <label class=\"col-sm-2 control-label\">Nama</label>
                      <div class=\"col-sm-6\">
                        <input type=\"text\" class=\"form-control\" name=\"nama\" placeholder=\"Nama\">
                      </div>
                    </div>
					<div class=\"form-group\">
                      <label class=\"col-sm-2 control-label\">Semester</label>
                      <div class=\"col-sm-6\">
                        <input type=\"text\" class=\"form-control\" name=\"smt\" placeholder=\"Semester\">
                      </div>
                    </div>
				 </div><!-- /.box-body -->
                  <div class=\"box-footer\">
                    <a href=\"?modul=mastermahasiswa\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Kembali</a>
					<button type=\"submit\" class=\"btn btn-info pull-right\"><i class=\"fa fa-save\"></i> Simpan</button>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
		";
		
		echo $view;
	}
	
	function viewEditMahasiswa($conn){
		$id=$_GET["id"];
		$sql="SELECT * FROM mahasiswa WHERE nim='".$id."'";
		$q = mysqli_query($conn,$sql);
		if($data=mysqli_fetch_array($q)){
			$view = "
			<div class=\"box box-default\">
				  <div class=\"box-header with-border\">
					<h3 class=\"box-title\">Edit Data Mahasiswa</h3>
				  </div>
				   <form class=\"form-horizontal\" action=\"?modul=mastermahasiswa&act=proseseditmahasiswa\" method=\"POST\">
					  <div class=\"box-body\">
						<div class=\"form-group\">
						  <label class=\"col-sm-2 control-label\">NIM</label>
						  <div class=\"col-sm-6\">
							<input type=\"text\" class=\"form-control\" value=\"".$data["nim"]."\" disabled>
							<input type=\"hidden\" class=\"form-control\" name=\"nim\" value=\"".$data["nim"]."\">
						  </div>
						</div>
						<div class=\"form-group\">
						  <label class=\"col-sm-2 control-label\">Nama</label>
						  <div class=\"col-sm-6\">
							<input type=\"text\" class=\"form-control\" name=\"nama\" value=\"".$data["nama"]."\">
						  </div>
						</div>
						<div class=\"form-group\">
						  <label class=\"col-sm-2 control-label\">Semester</label>
						  <div class=\"col-sm-6\">
							<input type=\"text\" class=\"form-control\" name=\"smt\" value=\"".$data["semester"]."\">
						  </div>
						</div>
					 </div><!-- /.box-body -->
					  <div class=\"box-footer\">
						<a href=\"?modul=mastermahasiswa\" class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i> Kembali</a>
						<button type=\"submit\" class=\"btn btn-info pull-right\"><i class=\"fa fa-save\"></i> Edit</button>
					  </div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			";
		}
		
		
		echo $view;
	}
	
	function prosesTambahMahasiswa($conn){
		$nim = $_POST["nim"];
		$nama = $_POST["nama"];
		$smt = $_POST["smt"];
		
		$sql = "INSERT INTO mahasiswa(nim,nama,semester) VALUES('".$nim."','".$nama."','".$smt."')";
		$q = mysqli_query($conn,$sql);
		if($q){
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=mastermahasiswa&msg=1\");
			</script>
			";
		}else{
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=mastermahasiswa&msg=2\");
			</script>
			";
		}
	}
	function prosesEditMahasiswa($conn){
		$nim = $_POST["nim"];
		$nama = $_POST["nama"];
		$smt = $_POST["smt"];
		
		$sql = "UPDATE mahasiswa SET nama='".$nama."',semester='".$smt."' WHERE nim='".$nim."'";
		$q = mysqli_query($conn,$sql);
		if($q){
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=mastermahasiswa&msg=3\");
			</script>
			";
		}else{
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=mastermahasiswa&msg=4\");
			</script>
			";
		}
	}
	
	function prosesHapusMahasiswa($conn){
		$id = $_GET["id"];
		
		$sql = "DELETE FROM mahasiswa WHERE nim='".$id."'";
		$q = mysqli_query($conn,$sql);
		if($q){
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=mastermahasiswa&msg=5\");
			</script>
			";
		}else{
			echo "
			<script type=\"text/javascript\" language=\"javascript\">
				window.location.replace(\"?modul=mastermahasiswa&msg=6\");
			</script>
			";
		}
	}
?>



<section class="content-header">
  <h1>
	Master Data Mahasiswa
  </h1>
  <ol class="breadcrumb">
	<li><a href="../index.php"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">data mahasiswa</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	
	  <?php 
		$act = $_GET["act"];
		if($act=="viewtambahmahasiswa"){
			viewTambahMahasiswa();
		}elseif($act=="prosestambahmahasiswa"){
			prosesTambahMahasiswa($conn);
		}elseif($act=="vieweditmahasiswa"){
			viewEditMahasiswa($conn);
		}elseif($act=="proseseditmahasiswa"){
			prosesEditMahasiswa($conn);
		}elseif($act=="proseshapusmahasiswa"){
			prosesHapusMahasiswa($conn);
		}else{
			viewDataMahasiswa($conn);
		}
		
	  ?>
</section><!-- /.content -->