<main role="main" class="container-fluid">
    <div id="khs" class="row">
        <div class="col-md-12 p-0">
            <div class="m-2 bg-white shadow-sm rounded">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="pr-4 title"><a href="#"><strong>Kartu Hasil Studi</strong></a></li>
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="index.php?module=khsUpload">Kartu Hasil Studi(KHS)</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-md-12 p-0">
            <div class="m-2 p-3 bg-white rounded shadow-sm">
                <h4>SEMESTER 4 (2019/2020)</h4>
                <hr>
                <select class="kelas custom-select" style="width:150px">
                    <option selected>Pilih Kelas</option>
                    <?php
                    include('../koneksi/connection.php');
					$tampil=mysqli_query($con, "SELECT tabel_prodi.kode as kode, tingkat, kode_kelas FROM tabel_kelas INNER JOIN tabel_prodi ON tabel_kelas.id_prodi = tabel_prodi.id_prodi GROUP BY id_kelas;");
					while($r=mysqli_fetch_array($tampil)){
					echo"<option value=$r[id_kelas]>$r[kode] - $r[tingkat] $r[kode_kelas]</option>";
					}
                    ?>
                </select>
                <button type="button" class="tmbl-filter btn btn-success ml-3">Search</button>
                <a href ="index.php?module=khsLihat" class="tmbl-ruangan btn btn-info float-right">Lihat KHS</a>
                <br><br>
                <div class="media text-muted pt-8">
                    <div class="media-body pb-8 mb-0">
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>SKS</th>
                                    <th>IP</th>
                                    <th>Proses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Matkul A</td>
                                    <td>lalalalallalalalaal</td>
                                    <td>4</td>
                                    <td>A</td>
                                    <td><button class=" tmbl-table btn btn-success" type="button" class="pratinjau btn" data-toggle="modal"
                                            data-target="#edit" class="edit">Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Matkul A</td>
                                    <td>lllalalallalallalala</td>
                                    <td>4</td>
                                    <td>A</td>
                                    <td><button class=" tmbl-table btn btn-success" type="button" class="pratinjau btn" data-toggle="modal"
                                            data-target="#edit" class="edit">Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>