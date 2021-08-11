<?php 
    include "config/koneksi.php";
    include "library/controller.php";
    $go = new controller();
    $tabel = "log_buku";
    @$field = array('kd_buku'=>$_POST['Kd_buku'],
                    'judul_buku'=>$_POST['Judul_buku'],
                    'penulis'=>$_POST['Penulis'],
                    'penerbit'=>$_POST['Penerbit'],
                    'tahun_terbit'=>$_POST['Tahun_terbit']);
    $redirect = 'dashboard.php';
    @$where = "id = $_GET[id]";

    $query = mysqli_query($con, "SELECT max(kd_buku) as kd_buku FROM log_buku");
    @$data = mysqli_fetch_array($query);
    @$kodeBarang = $data['kd_buku'];
    $urutan = (int) substr($kodeBarang, 9, 9);
    $urutan++;
    $huruf = "BKU";
    $kodeBarang = $huruf . sprintf("%09s", $urutan);

    if(isset($_POST['simpan'])){
        $go->simpan($con, $tabel, $field, $redirect);
    }
    if(isset($_GET['hapus'])){
        $go->hapus($con, $tabel, $where, $redirect);
    }
    if(isset($_GET['edit'])){
        $edit = $go->edit($con, $tabel, $where);
    }
    if(isset($_POST['ubah'])){
        $go->ubah($con, $tabel, $field, $where, $redirect);
    }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <title>Dashboard</title>
</head>

<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a class="d-flex align-items-center col-md-3 mb-2 mt-3 mb-md-0 text-dark text-decoration-none">
                <h3>Katalog Buku</h3>
            </a>

            <div class="col-md-3 text-end">
                <div class="btn-group">
                  <button type="button" class="btn btn-primary">Acccount</button>
                  <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="user.php">Ubah Password</a></li>
                  </ul>
                </div>
                <a href="index.php" class="btn btn-outline-primary me-2" role="button" tab-index="-1">Logout </a>
            </div>
        </header>
    <br>

    <form method="post" class="mx-auto pb-5">
            <table align="center" class="display mt-2 pb-5" style="width:90%">
                <tr>
                    <td>Kode Buku</td>
                    <td>:</td>
                    <td><input class="form-control" type="text" name="Kd_buku" value="<?php if(@$edit['kd_buku']==null){echo $kodeBarang;}else{ echo @$edit['kd_buku'];}?>" readonly></td>
                </tr>
                <tr>
                    <td>Judul Buku</td>
                    <td>:</td>
                    <td><input class="form-control" type="text" name="Judul_buku" value="<?php echo @$edit['judul_buku'] ?>" required></td>
                </tr>
                <tr>
                    <td>Penulis</td>
                    <td>:</td>
                    <td><input class="form-control" type="text" name="Penulis" value="<?php echo @$edit['penulis'] ?>" required></td>
                </tr>
                <tr>
                    <td>Penerbit</td>
                    <td>:</td>
                    <td><input class="form-control" type="text" name="Penerbit" value="<?php echo @$edit['penerbit'] ?>" required></td>
                </tr>
                <tr>
                    <td>Tahun Terbit</td>
                    <td>:</td>
                    <td><input class="form-control" type="text" name="Tahun_terbit" value="<?php echo @$edit['tahun_terbit'] ?>" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <?php if(@$_GET['id']==""){ ?>
                                <button class="btn btn-primary col-6 mt-3" type="submit" name="simpan" value="SIMPAN">Simpan</button>
                            <?php }else{?>
                                <button class="btn btn-primary" type="submit" name="ubah" value="UBAH">Ubah</button>
                            <?php } ?>  
                    </td>
                </tr>
            </table>
        
            <table align="center" border="1" id="tabel-data" class="display mt-5" style="width:90%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Buku</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun Terbit</th>
                        <th colspan="2">Aksi</th>
                    </tr>
                </thead>
                <tbody>    
                    <?php 
                        $data = $go->tampil($con, $tabel);
                        $no = 0;
                        if($data==""){
                            echo "<tr><td colspan = '4'>No Record</td></tr>";
                        }else{
                        foreach($data as $r){
                        $no++
                    ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $r['kd_buku'] ?></td>
                        <td><?php echo $r['judul_buku'] ?></td>
                        <td><?php echo $r['penulis'] ?></td>
                        <td><?php echo $r['penerbit'] ?></td>
                        <td><?php echo $r['tahun_terbit'] ?></td>
                        <td><button type="submit" class="btn btn-danger"><a style="text-decoration:none; color:white" href="?menu=dashboard&hapus&id=<?php echo $r['id'] ?>" onclick="return confirm('Hapus Data?')">Hapus</a></button></td>
                        <td><button type="submit" class="btn btn-dark"><a style="text-decoration:none; color:white; hover" href="?menu=dashboard&edit&id=<?php echo $r['id'] ?>">Edit</a></button></td>                
                    </tr>
                    <?php }  } ?>
                </tbody>
            </table>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabel-data').DataTable();
        } );
    </script>
</body>
</html>