<?php
    include "../config/connection.php";
    include "../process/proses_krsDosen.php";
?>

<main role="main" class="container-fluid" id="dosenKrs">
    <link rel="stylesheet" href="../css/dosenKrs.css">
    <div class="row">
        <!-- Profil Dosen -->
        <div class="col-md-3 p-0">
            <div class="sticky-sidebar sticky-top">
                <div class="m-2 p-3 bg-white rounded shadow-sm">
                    <div class="media text-muted pt-3">
                        <div class="media-body pb-3 mb-0 small lh-125">
                            <div class="isi">
                                <?php
                            $resultnyaTampilProfilDosen = menampilkanDataProfilDosen($con, $idUser);

                            if(mysqli_num_rows($resultnyaTampilProfilDosen) > 0){
                                while($row = mysqli_fetch_assoc($resultnyaTampilProfilDosen)){
                            ?>
                                <div class="d-flex justify-content-center">
                                    <img src="../attachment/img/<?php echo ($row['foto'] == null)? 'avatar.jpeg' : $row['foto'] ; ?>" alt="dosen"
                                        style="width:150px;height:150px;border-radius:50%;">
                                </div>
                                <div class="data-dosen text-center">
                                    <h6 class="detail-dosen border-bottom border-gray pb-2 mb-0"><?= $row["nama"]?></h6>
                                    <h6 class="detail-dosen border-bottom border-gray pb-2 mb-0"><?= $row["nip"]?></h6>
                                    <h6 class="detail-dosen border-bottom border-gray pb-2 mb-0">DOSEN JTI</h6>
                                    <h6 class="detail-dosen border-bottom border-gray pb-2 mb-0">SARJANA</h6>
                                    <h6 class="detail-dosen border-bottom border-gray pb-2 mb-0">DOSEN TETAP</h6>
                                    <h6 class="detail-dosen border-bottom border-gray pb-2 mb-0">AKTIF</h6>
                                </div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Krs Dosen -->
        <div class="col-md-6 p-0">
            <div class="m-2 p-3 bg-white rounded shadow-sm">
                <h6 class="border-bottom border-gray pb-2 mb-0 judul">
                    PENGAJUAN KRS MAHASISWA
                </h6>
                <div class="media text-muted pt-3">
                    <div class="media-body pb-3 mb-0 small lh-125">
                        <div class="isi">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Kelas</th>
                                        <th>Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $queryKrs="SELECT tm.nama, tm.nim, tke.kode_kelas, tke.tingkat, tp.kode, tkr.status_verifikasi FROM tabel_mahasiswa tm, tabel_kelas tke, tabel_prodi tp, tabel_krs tkr WHERE tm.id_kelas = tke.id_kelas AND tm.id_prodi = tp.id_prodi AND tm.id_mahasiswa = tkr.id_mahasiswa AND status_daftar_ulang = 'Sudah';";

                                    $resultKrs = mysqli_query($con, $queryKrs);

                                    $index = 1;

                                    if(mysqli_num_rows($resultKrs) > 0){
                                        while($row = mysqli_fetch_assoc($resultKrs)){
                                            ?>
                                    <tr>
                                        <td><?php echo $index?></td>
                                        <td><?php echo $row['nim']?></td>
                                        <td><?php echo $row['nama']?></td>
                                        <td><?php echo $row["kode"]; ?>-<?php echo $row["tingkat"]; echo $row["kode_kelas"] ?>
                                        </td>
                                        <td><label><input name=verifikasiDosen type="checkbox"
                                                    value="<?php echo $row["status_verifikasi"]?>"></label></td>
                                    </tr>
                                    <?php $index++;
                                            }
                                            ?>
                                    <?php          
                                    } else{
                                    ?>
                                    <div class="verifikasi-dosen-kosong text-center">
                                        <p>Daftar verifikasi tidak tersedia</p>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kompen Mahasiswa -->
        <div class="col-md-3 p-2">
            <div class="sticky-sidebar sticky-top">
                <div class="kompen-bar m-2 p-3 bg-white rounded shadow-sm my-auto">
                    <h6 class="border-bottom border-gray pb-2 mb-0 judul">KOMPEN MAHASISWA</h6>
                    <div class="media text-muted pt-3">
                        <div class="media-body pb-3 mb-0 small">
                            <div>
                                <!-- Kompen Tabel -->

                                <div class="col-12 p-0 data-kompen-ada scrollbar">
                                    <div class="border-bottom border-gray pb-2 mb-0"></div>
                                    <?php
                                    $resultQueryTask= menampilkanTaskDosen($con, $idUser);

                                    if(mysqli_num_rows($resultQueryTask) > 0){
                                        $indeks=1;
                                        
                                        while($rows=mysqli_fetch_assoc($resultQueryTask)){
                                        ?>
                                    <form action="">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-md-1 my-auto">
                                                        <?= $indeks ?>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?= $rows["pekerjaan"]?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                kuota: <?= $rows["kuota"]?> mahasiswa
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto my-auto">
                                                <button type="submit" class="btn btn-success kompen-submit-btn">
                                                    submit
                                                </button>
                                            </div>
                                            <div class="col-md-auto my-auto">
                                                <div class="dropdown">
                                                    <a data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v fa-2x waves-effect">
                                                        </i>
                                                    </a>
                                                    <div class="dropdown-kompen dropdown-menu">
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#hapusKompen<?= $rows["id_task"]?>">
                                                            <i class="far fa-trash-alt">
                                                            </i>
                                                            Hapus
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border-bottom border-gray pb-2 mb-0"></div>
                                    </form>
                                    <?php
                                        $indeks++;
                                        }
                                    }   else{
                                    ?>
                                    <!-- Jika kompen kosong -->

                                    <div class="isi py-5 text-center">
                                        <div class="text-center mb-2">
                                            <img src="../img/clipboard.svg" alt="clipBoard" class="clipBoard">
                                        </div>
                                        <div class="data-kompen text-center w-50 mr-auto ml-auto">
                                            <h6>Anda tidak mempunyai daftar pekerjaan</h6>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <!-- Modal -->
                                <?php
                                    $resultQueryTask=menampilkanTaskDosen($con, $idUser);

                                    if(mysqli_num_rows($resultQueryTask) > 0){
                                        while($rowss = mysqli_fetch_assoc($resultQueryTask)){
                                        ?>
                                <div class="modal fade hapusKompen-modal" id="hapusKompen<?= $rowss["id_task"] ?>"
                                    tabindex="-1" role="dialog"
                                    aria-labelledby="hapusKompen<?= $rowss["id_task"] ?>Title" aria-hidden="true"
                                    data-backdrop="false">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content konten-modal">
                                            <div class="modal-body">
                                                <h5 class="tombolAksiHapusKompen text-center">Apakah Anda Yakin?</h5>
                                                <div class="tombolAksiHapusKompen text-center">
                                                    <form
                                                        action="../process/proses_krsDosen.php?module=krs&act=hapus"
                                                        method="post">
                                                        <button type="button" class="btn btn-tidak"
                                                            data-dismiss="modal">Tidak</button>
                                                        <input type="hidden" name="idTask"
                                                            value="<?= $rowss["id_task"] ?>">
                                                        <input type="submit" class="btn btn-iya" name="hapusTask"
                                                            value="Ya">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <?php

                                        }
                                    }
                                    ?>
                                <!-- Tombol -->
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn tambah-pekerjaan-kompen" data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                        Tambah Pekerjaan
                                    </button>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="false">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <button type="button" class="close text-right active" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <center>
                                                <h5 class="modal-title text-center border-bottom border-gray pb-2 mb-0"
                                                    id="exampleModalCenterTitle" style="margin: 0 auto;">Tambah
                                                    Pekerjaan</h5>
                                            </center>
                                            <form action="../process/proses_krsDosen.php?module=krs&act=tambah" method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <div class="col-2"></div>
                                                        <div class="col-3">
                                                            <label for="pekerjaanKompensasi">
                                                                <h6>Pekerjaan Kompensasi</h6>
                                                            </label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="hidden" name="idDosen" value="<?= $idUser ?>">
                                                            <input type="text" id="pekerjaanKompensasi"
                                                                class="form-control" name="taskPekerjaan">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-2"></div>
                                                        <div class="col-3">
                                                            <label for="kuotaMahasiswa">
                                                                <h6>Kuota Mahasiswa</h6>
                                                            </label>
                                                        </div>
                                                        <div class="col-3">
                                                            <select name="kuotaMhs" id="kuotaMahasiswa"
                                                                class="form-control kuota-mahasiswa">
                                                        </div>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                        <option value="13">13</option>
                                                        <option value="14">14</option>
                                                        <option value="15">15</option>
                                                        <option value="16">16</option>
                                                        <option value="17">17</option>
                                                        <option value="18">18</option>
                                                        <option value="19">19</option>
                                                        <option value="20">20</option>
                                                        <option value="21">21</option>
                                                        <option value="22">22</option>
                                                        <option value="23">23</option>
                                                        <option value="24">24</option>
                                                        <option value="25">25</option>
                                                        <option value="26">26</option>
                                                        <option value="27">27</option>
                                                        <option value="28">28</option>
                                                        <option value="29">29</option>
                                                        <option value="30">30</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer col-12 tambahkan-modal-parent text-right">
                                                        <button type="submit" class="btn tambahkan-modal"
                                                            name="tambahTask">Tambahkan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>