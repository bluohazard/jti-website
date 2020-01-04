<?php
include "../config/connection.php";
include "../process/proses_berita.php";
?>
<main id="adminBerita" role="main" class="container-fluid">
    <div class="row">
        <div class="col-md-12 p-0">
            <div class="m-2 bg-white shadow-sm rounded">
                <div class="row">
                    <div class="col-md-auto pr-0">
                        <strong><span class="nav-link">Berita</span></strong>
                    </div>
                    <div class="col pl-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-2 m-0 bg-white">
                                <li class="breadcrumb-item"><a href="index.php?module=home">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Berita</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 p-0">
            <div class="m-2 p-3 bg-white rounded shadow-sm">
                <div class="col-md-12 p-0">
                    <div class="card border border-secondary">
                        <div class="card-header">
                            <strong>Buat Postingan Berita</strong>
                        </div>
                        <div class="card-body">
                            <form action="../process/proses_berita.php?module=beritaPengumuman&act=tambah" class="p-0" method="POST" enctype="multipart/form-data">
                                <div class="form-group berita-utama">
                                    <input type="text" class="form-control border-0" name="judulBerita" placeholder="Berita Utama ..." style="width:100%">
                                </div>
                                <hr>
                                <div class="form-group ketik-berita">
                                    <textarea name="isiBerita" id="" cols="30" rows="6" placeholder="Ketik Berita ..." maxlength="500" class="form-control border-0" oninput="Beritacharcountupdate(this.value)"></textarea>
                                </div>
                                <div class="col-md-12 p-1 alert alert-danger alert-dismissible fade show" role="alert">
                                    <p>Pak Dimas.jpg (Gagal Upload) - Kapasitas gambar lebih dari 5 Mb</p>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="ber col-md-12 p-1 alert alert-light alert-dismissible fade show" role="alert">
                                    <p>Pak Dimas.jpg</p>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <hr>
                                <div class="container-fluid m-0">
                                    <div class="row">
                                        <div class="col-6">
                                            <span style="color:grey" id=Bercharcount></span>

                                        </div>
                                        <div class="col-6 text-right d-flex justify-content-end lampir">
                                            <label for="file-input">
                                                <img src="../img/imgUpload.svg" alt="Image Upload" class="mr-3" data-placement="top" data-toggle="tooltip" data-placement="top" title="Lampirkan Gambar">
                                            </label>
                                            <input id="file-input" type="file" onchange="readURL(this,'Picture')" style="cursor: pointer; display: none " name="gambar[]" multiple="true"/>
                                            <label for="file-input1">
                                                <img src="../img/fileUpload.svg" alt="File Upload" class="mr-3" data-toggle="tooltip" data-placement="top" title="Lampirkan File">
                                            </label>
                                            <input id="file-input1" type="file" onchange="readURL(this,'Picture')" style="cursor: pointer;  display: none" name="file[]" multiple="true"/>
                                            <strong><label for="kategori-AdBer" class="labelBerita mt-1 mr-2">Kategori :
                                                </label>
                                            </strong>
                                            <select name="tipeBerita" id="tipeBerita" class="mr-3 pilihKategoriBerita w-auto">
                                                <option value="Berita">Berita</option>
                                                <option value="Pengumuman">Pengumuman</option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-kirim" name="insert">Kirim</button>

                                            <script>
                                                $(document).ready(function(){
                                                $('[data-toggle="tooltip"]').tooltip(); 
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br>

                    <div class="cari mt-2">
                        <div class="form-inline">
                            <i class="fas fa-search mr-2"></i>
                            <div class="col-2">
                                <div class="input-group date " id="datepickerCariBerita">
                                    <input type="text" class="form-control" id="tanggalBerita" placeholder="<?= date("d-m-Y") ?>">
                                    <div class="input-group-addon">
                                        <span>
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-success cari-btn" id="adminCariBerita">Cari</button>
                        </div>
                    </div>



                    <div class="scrolltable" id="tabelBerita">
                        <?php
                        $resultTampilBerita = tampilBerita($con);
                        $index = 1;
                        if (mysqli_num_rows($resultTampilBerita) > 0) {
                            ?>
                            <table class="table table-striped table-bordered mt-3">
                                <thead class="text-center">
                                    <tr class="p-2">
                                        <th>No</th>
                                        <th id="beritaBerita">Berita</th>
                                        <th>Tanggal Pembuatan</th>
                                        <th>Tanggal Perubahan</th>
                                        <th>Komentar</th>
                                        <th colspan="2">Proses</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center m-auto">
                                    <?php
                                    while ($row = mysqli_fetch_assoc($resultTampilBerita)) {
                                        ?>
                                        <tr>
                                            <td><?= $index ?></td>
                                            <td class="text-left detail-berita" data-toggle="modal" data-target="#modalPreview" data-info="<?php echo $row["id_info"]; ?>"><?php echo $row["judul"]; ?></td>
                                            <td><?= tampilTanggal($row["waktu_publish"]); ?></td>
                                            <td><?= tampilTanggal($row["waktu_perubahan"]); ?></td>
                                            <td><?php echo jumlahKomentar($con, $row["id_info"]); ?></td>

                                            <td><button class=" tmbl-table btn btn-danger" type="button" class="pratinjau btn" data-toggle="modal" data-target="#hapus<?= $row["id_info"] ?>" class="hapus">Hapus</button></td>
                                        </tr>
                                        <?php
                                        $index++;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        <?php
                    } else {
                        ?>
                            <div class="text-center">
                                <p class="text-muted">Data beasiswa kosong</p>
                            </div>
                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Hapus -->
    <?php
    $resultTampilBerita = tampilBerita($con);
    $index = 1;
    if (mysqli_num_rows($resultTampilBerita) > 0) {
        while ($row = mysqli_fetch_assoc($resultTampilBerita)) {
            $id_info = $row["id_info"];
            ?>
            <div class="modal fade hapusBerita-modal" id="hapus<?= $row["id_info"] ?>" tabindex="-1" role="dialog" aria-labelledby="hapus<?= $index ?>Title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content konten-modal">
                        <div class="modal-body ">
                            <h5 class="isiHapusBerita text-center">Apakah Anda Yakin?</h5>
                            <div class="tombolAksiHapusBerita text-center">
                                <form action="../process/proses_berita.php?module=beritaPengumuman&act=hapus" method="post">
                                    <input type="hidden" value="<?=$id_info?>" name="id_info">
                                    <button type="button" class="btn btn-tidak" data-dismiss="modal">Tidak</button>
                                    <input type="submit" class="btn btn-iya" name="hapus" id="hapus" value="Ya">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $index++;
        }
    }
    ?>

    <!-- Modal preview -->
    <div class="modal fade" id="modalPreview" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content pl-3 pr-3">

                <!-- isi -->
                <div class="container-fluid p-0" id="Tampildetail-berita">

                </div>
            </div>
        </div>
    </div>

</main>