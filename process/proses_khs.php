<?php
include "../config/connection.php";

function semester($con, $minKelas)
{
    $mhs = mysqli_query($con, "select a.*, b.semester, c.tingkat from tabel_mahasiswa a, tabel_semester b, tabel_kelas c where a.id_semester=b.id_semester and a.id_kelas=c.id_kelas and a.id_kelas='$minKelas'");
    if (mysqli_num_rows($mhs) > 0) {
        $rowMhs = mysqli_fetch_assoc($mhs);
        $semester = $rowMhs["semester"];
        $tingkat = $rowMhs["tingkat"];
        $tahunMasuk = date('Y', strtotime($rowMhs["waktu_tambah"]));
        return "SEMESTER " . $semester . " (" . ($tahunMasuk + $tingkat - 1) . "/" . ($tahunMasuk + $tingkat) . ")";
    } else {
        return " - ";
    }
}

function tahunAkademik($con, $mahasiswa, $semester)
{
    $mhs = mysqli_query($con, "select a.*, b.tingkat from tabel_mahasiswa a, tabel_kelas b where a.id_kelas=b.id_kelas and a.id_mahasiswa=$mahasiswa");
    $semester = mysqli_query($con, "select * from tabel_semester where id_semester=$semester");
    $rowSemester = mysqli_fetch_assoc($semester);

    if (mysqli_num_rows($mhs) > 0) {
        $rowMhs = mysqli_fetch_assoc($mhs);
        $semester = $rowSemester["semester"];
        $tingkat = $rowMhs["tingkat"];
        $tahunMasuk = date('Y', strtotime($rowMhs["waktu_tambah"]));
        if ($semester % 2 == 0) {
            return "" . ($tahunMasuk + $tingkat - 1) . "/" . ($tahunMasuk + $tingkat) . " Genap";
        } else {
            return "" . ($tahunMasuk + $tingkat - 1) . "/" . ($tahunMasuk + $tingkat) . " Ganjil";
        }
    } else {
        return " - ";
    }
}

function khs($con, $kelas, $semester)
{
    $khs = "select distinct(a.id_mahasiswa), a.*, a.nim, 
    a.nama as nm_mahasiswa, c.*, SUM(c.sks) as sks, d.id_kelas, e.*, e.semester, f.*
    from tabel_mahasiswa a, tabel_matkul c, tabel_kelas d, tabel_semester e, tabel_jadwal f
    where a.id_kelas = d.id_kelas
    and d.id_kelas = f.id_kelas
    and e.id_semester = f.id_semester
    and c.id_matkul = f.id_matkul 
    and a.id_kelas=$kelas
    and f.id_semester=$semester
    group by a.id_mahasiswa";

    $resultTampilKhs = mysqli_query($con, $khs);
    return $resultTampilKhs;
}

function khsLihat($con)
{
    $khsLihat = "select distinct(a.id_mahasiswa), a.*, a.nim, 
    a.nama as nm_mahasiswa, c.*, SUM(c.sks) as sks, d.id_kelas, e.*, e.semester, f.*
    from tabel_mahasiswa a, tabel_matkul c, tabel_kelas d, tabel_semester e, tabel_jadwal f
    where a.id_kelas = d.id_kelas
    and d.id_kelas = f.id_kelas
    and a.id_semester = e.id_semester
    and e.id_semester = f.id_semester
    and c.id_matkul = f.id_matkul 
    group by a.id_mahasiswa";

    $resultTampilKhsLihat = mysqli_query($con, $khsLihat);
    return $resultTampilKhsLihat;
}

function khsKelas($con, $kelas)
{
    $khsKelas = "select distinct(a.id_mahasiswa), a.*, a.nim, 
    a.nama as nm_mahasiswa, c.*, SUM(c.sks) as sks, d.id_kelas, e.*, e.semester, f.*
    from tabel_mahasiswa a, tabel_matkul c, tabel_kelas d, tabel_semester e, tabel_jadwal f
    where a.id_kelas = d.id_kelas
    and d.id_kelas = f.id_kelas
    and a.id_semester = e.id_semester
    and e.id_semester = f.id_semester
    and c.id_matkul = f.id_matkul 
    and a.id_kelas = $kelas
    group by a.id_mahasiswa";

    $resultTampilKhsKelas = mysqli_query($con, $khsKelas);
    return $resultTampilKhsKelas;
}

function cekSemester($con, $id_mahasiswa)
{
    $cekSemester = "select a.*, b.*, a.id_semester from tabel_semester a, tabel_mahasiswa b 
    where a.id_semester=b.id_semester and b.id_mahasiswa = $id_mahasiswa";
    $resultCekSemester = mysqli_query($con, $cekSemester);
    $row = mysqli_fetch_assoc($resultCekSemester);
    return $row["id_semester"];
}

function khsNilai($con, $id_mahasiswa, $id_semester)
{
    $khsNilai = "select distinct(ROUND(SUM(CASE
    WHEN a.nilai > 80 THEN 4.00*c.sks
    WHEN a.nilai > 70 && a.nilai <= 80 THEN 3.50*c.sks
    WHEN a.nilai > 65 && a.nilai <= 70 THEN 3.00*c.sks
    WHEN a.nilai > 60 && a.nilai <= 65 THEN 2.30*c.sks
    WHEN a.nilai > 50 && a.nilai <= 60 THEN 2.00*c.sks
    WHEN a.nilai > 40 && a.nilai <= 50 THEN 1.00*c.sks
    ELSE
    0.00*c.sks
    END)/SUM(c.sks),2)) as ip , a.*, b.*, 
    c.*, d.*, e.* from tabel_khs a,
    tabel_mahasiswa b, tabel_matkul c, tabel_jadwal d, tabel_semester e
    where a.id_mahasiswa = b.id_mahasiswa
    and d.id_matkul = c.id_matkul 
    and d.id_semester = e.id_semester
    and a.id_mahasiswa = $id_mahasiswa and a.id_semester = $id_semester group by a.id_mahasiswa";

    $resultTampilKhsNilai = mysqli_query($con, $khsNilai);
    if (mysqli_num_rows($resultTampilKhsNilai) > 0) {
        $rowKhsNilai = mysqli_fetch_assoc($resultTampilKhsNilai);
        return $rowKhsNilai["ip"];
    } else {
        return 0;
    }
}

function kelas($con)
{
    $kelas = "select * from tabel_kelas";
    $resultKelas = mysqli_query($con, $kelas);
    return $resultKelas;
}

function tampilSemester($con)
{
    $semester = "select * from tabel_semester";
    $resultSemester = mysqli_query($con, $semester);
    return $resultSemester;
}

function tampilKelas($con, $id_kelas)
{
    $kelas = "select a.*, b.* from tabel_kelas a, tabel_prodi b where a.id_prodi=b.id_prodi and a.id_kelas=$id_kelas";
    $resultKelas = mysqli_query($con, $kelas);
    $row = mysqli_fetch_assoc($resultKelas);
    $hasil = $row["kode"] . " - " . $row["tingkat"] . $row["kode_kelas"];
    return $hasil;
}

function tampilTahun($con)
{
    $tahun = "select distinct(YEAR(waktu_edit)) as tahun from tabel_hasil_kuisioner order by waktu_edit desc limit 5";
    $resultTahun = mysqli_query($con, $tahun);
    return $resultTahun;
}

function dosen($con)
{
    $dosen = "select * from tabel_dosen";

    $resultDosen = mysqli_query($con, $dosen);
    return $resultDosen;
}

function matkul($con)
{
    $matkul = "select * from tabel_matkul";

    $resultMatkul = mysqli_query($con, $matkul);
    return $resultMatkul;
}

function minKelas($con)
{
    $minKelas = "select min(id_kelas) as minKelas from tabel_kelas";
    $resultMinKelas = mysqli_query($con, $minKelas);
    $rowMinKelas = mysqli_fetch_assoc($resultMinKelas);
    return $rowMinKelas["minKelas"];
}

function minSemester($con)
{
    $minSemester = "select min(id_semester) as minSemester from tabel_semester";
    $resultMinSemester = mysqli_query($con, $minSemester);
    $rowMinSemester = mysqli_fetch_assoc($resultMinSemester);
    return $rowMinSemester["minSemester"];
}

//FungsiGrade
function nindex($nilai)
{
    if ($nilai > 80) {
        $nindex = 4;
    } else if (($nilai > 70) && ($nilai <= 80)) {
        $nindex = 3.5;
    } else if (($nilai > 65) && ($nilai <= 70)) {
        $nindex = 3.00;
    } else if (($nilai > 60) && ($nilai <= 65)) {
        $nindex = 2.30;
    } else if (($nilai > 50) && ($nilai <= 60)) {
        $nindex = 2.00;
    } else if (($nilai > 40) && ($nilai <= 50)) {
        $nindex = 1.00;
    } else if ($nilai <= 40) {
        // $grade='E';
        $nindex = 0.00;
    }

    return $nindex;
}

// Modal KHS LIHAT
if (isset($_POST["tampilDetailMhs"]) && isset($_POST["tampilDetailSemester"])) {
    $id_mahasiswa = $_POST['tampilDetailMhs'];
    $id_semester = $_POST['tampilDetailSemester'];

    $detailMahasiswa = "select a.*, a.nama as nm_mahasiswa, b.*, c.* from tabel_mahasiswa a, tabel_kelas b, tabel_prodi c where a.id_kelas=b.id_kelas and b.id_prodi=c.id_prodi and a.id_mahasiswa=$id_mahasiswa";

    $resultDetailMahasiswa = mysqli_query($con, $detailMahasiswa);

    if (mysqli_num_rows($resultDetailMahasiswa) > 0) {
        $row = mysqli_fetch_assoc($resultDetailMahasiswa);
?>
        <div class="modal-body">
            <div class="isi-modaLihat border-bottom1 border-gray">
                <p>Tahun Akademik : <?= tahunAkademik($con, $id_mahasiswa, $id_semester) ?></p>
                <p>Nama : <?php echo $row["nm_mahasiswa"]; ?></p>
                <p>NIM : <?php echo $row["nim"]; ?></p>
                <p>Kelas : <?php echo $row["kode"] . " - " . $row["tingkat"] . $row["kode_kelas"] ?></p>
                <p>Prodi : <?php echo $row["nama"]; ?></p>
            </div>
            <div id="khsModal">
                <div class="media text-muted pt-8">
                    <?php

                    $nilai = "select c.nama as nm_matkul, c.sks, c.jam, a.nilai from tabel_khs a, tabel_jadwal b, tabel_matkul c, tabel_mahasiswa d where a.id_matkul=c.id_matkul and a.id_mahasiswa=d.id_mahasiswa and a.id_kelas=b.id_kelas  and c.id_matkul=b.id_matkul and a.id_mahasiswa=$id_mahasiswa and a.id_semester=$id_semester and b.id_semester=$id_semester";

                    $resultTampilNilai = mysqli_query($con, $nilai);
                    if (mysqli_num_rows($resultTampilNilai) > 0) {
                    ?>
                        <div class="media-body pb-8 mb-0">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th>SKS</th>
                                        <th>Jam</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $index = 1;
                                    while ($row1 = mysqli_fetch_assoc($resultTampilNilai)) {
                                        if ($row1["nilai"] != 0) {
                                    ?>
                                            <tr>
                                                <td><?php echo $index; ?></td>
                                                <td><?php echo $row1["nm_matkul"]; ?></td>
                                                <td><?php echo $row1["sks"]; ?></td>
                                                <td><?php echo $row1["jam"]; ?></td>
                                                <td><?php echo nindex($row1["nilai"]); ?></td>
                                            </tr>
                                        <?php
                                        } else {
                                        ?>
                                            <tr>
                                                <td><?php echo $index; ?></td>
                                                <td><?php echo $row1["nm_matkul"]; ?></td>
                                                <td><?php echo $row1["sks"]; ?></td>
                                                <td><?php echo $row1["jam"]; ?></td>
                                                <td><?php echo "Belum Diisi"; ?></td>
                                            </tr>
                                    <?php
                                        }
                                        $index++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                    } else {
                        ?>
                            <div class='text-center col-md-12'>
                                <img src='../img/magnifier.svg' alt='pencarian' class='p-3'>
                                <p class='text-muted'>Data Nilai Masih Kosong</p>
                            </div>
                        <?php
                    }
                        ?>
                        </div>
                </div>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="text-center text-muted">Data Tidak Ditemukan</div>
        <?php
    }
}
// MODAL KHS LIHAT END

function nilaiMhs($con, $id_mahasiswa, $id_semester)
{
    $nilaiMhs = "select distinct(c.id_matkul), a.id_mahasiswa, a.*, c.*, d.* ,e.*, e.semester, f.*, g.*, h.*
    from tabel_mahasiswa a, tabel_matkul c, tabel_kelas d, tabel_semester e, tabel_jadwal f, tabel_khs g, tabel_prodi h
    where a.id_kelas = d.id_kelas
    and d.id_kelas = f.id_kelas
    and h.id_prodi = d.id_prodi
    and a.id_semester = e.id_semester
    and a.id_semester = f.id_semester
    and c.id_matkul = f.id_matkul
    and g.id_matkul = f.id_matkul 
    and g.id_mahasiswa = a.id_mahasiswa
    and g.id_semester = $id_semester
    and g.id_mahasiswa = $id_mahasiswa
    ";

    $resultNilaiMhs = mysqli_query($con, $nilaiMhs);
    if (mysqli_num_rows($resultNilaiMhs) > 0) {
        $rowNilaiMhs = mysqli_fetch_assoc($resultNilaiMhs);
        return $rowNilaiMhs["nilai"];
    } else {
        return 0;
    }
}

if (isset($_POST["insert"])) {

    session_start();
    if ($_GET["module"] == "khs" && $_GET["act"] == "tambah") {
        $idMhs = mysqli_fetch_assoc(mysqli_query($con, "SELECT id_mahasiswa FROM tabel_mahasiswa WHERE id_user = $_SESSION[id]"));
        $idMhs = $idMhs["id_mahasiswa"];

        $resultNilaiMatkul = mysqli_query($con, "select distinct(a.id_matkul), b.nama as nm_matkul from tabel_jadwal a, tabel_matkul b, 
        tabel_mahasiswa c, tabel_user d where a.id_matkul=b.id_matkul 
        and a.id_kelas=c.id_kelas");
        if (mysqli_num_rows($resultNilaiMatkul) > 0) {
            $i = 1;
            while ($rowNilaiMatkul = mysqli_fetch_assoc($resultNilaiMatkul)) {
                $nm_matkul = $_POST['id_matkul' . $i];
                $nilai = $_POST['nilai' . $i];
                $waktu = date('Y-m-d H:i:s');
                mysqli_query($con, "INSERT INTO tabel_khs (id_mahasiswa, id_kelas, id_semester, id_matkul, nilai, waktu_edit)
            VALUES ('$_POST[id_mahasiswa]', '$_POST[id_kelas]','$_POST[id_semester]',$nm_matkul, $nilai, '$waktu')");

                $i++;
            }
        }
        header('location:../module/index.php?module=' . $_GET["module"]);
    }
}

// Modal KHS UPLOAD 
if (isset($_POST["updateNilaiMhs"]) && isset($_POST["updateNilaiSemester"])) {
    $id_mahasiswa = $_POST['updateNilaiMhs'];
    $id_semester = $_POST['updateNilaiSemester'];

    $updateNilaiMahasiswa = "select * from tabel_mahasiswa where id_mahasiswa='$id_mahasiswa' and id_semester='$id_semester'";

    $resultUpdateNilaiMahasiswa = mysqli_query($con, $updateNilaiMahasiswa);

    if (mysqli_num_rows($resultUpdateNilaiMahasiswa) == 0) {
    } else {
        $no = 1;
        while ($row = mysqli_fetch_assoc($resultUpdateNilaiMahasiswa)) {
        ?>
            <form action="../process/proses_khs.php?module=khs&act=tambah" method="post" id="InputNilai">
                <div class="modal-body">
                    <div class="isi-modaLihat">
                        <input type="hidden" name="id_mahasiswa" value="<?= $row["id_mahasiswa"] ?>">
                        <p>Nama : <?php echo $row["nama"]; ?></p>
                        <p>Nim : <?php echo $row["nim"]; ?></p>
                    </div>
                    <input type="hidden" name="id_kelas" value="<?= $row["id_kelas"] ?>">
                    <input type="hidden" name="id_semester" value="<?= $row["id_semester"] ?>">
                    <?php

                    $updateNilaiMhs = "select b.id_matkul, b.nama as nm_matkul from tabel_jadwal a, tabel_matkul b, tabel_mahasiswa c where a.id_kelas=c.id_kelas and a.id_semester='$id_semester' and c.id_mahasiswa='$id_mahasiswa' and a.id_matkul=b.id_matkul";

                    $resultUpdateNilaiMhs = mysqli_query($con, $updateNilaiMhs);
                    if (mysqli_num_rows($resultUpdateNilaiMhs)) {
                    ?>
                        <div class="border-bottom border-gray">
                            <div class="row">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                    <div class="row isi-modaLihat">
                                        <p>Input Data Nilai Mahasiswa</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal isi-->
                        <div>
                            <?php
                            $i = 1;
                            while ($row1 = mysqli_fetch_assoc($resultUpdateNilaiMhs)) {
                            ?>
                                <div class="row isi-modaLihat">
                                    <div class="col-sm-6">
                                        <input type="hidden" name="id_matkul<?= $i ?>" value="<?= $row1["id_matkul"] ?>">
                                        <p><?php echo $row1["nm_matkul"]; ?></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="form-group row">
                                                <div class="col">
                                                    <input type="number" min="0" max="100" class="form-control" name="nilai<?= $i ?>" placeholder="Nilai.." required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $i++;
                            }
                            ?>
                        </div>
                        <!-- Modal isi End-->
                </div>
                <!-- Modal body End-->

                <div class="modal-footer">
                    <button type="submit" class="tmbl-kirim btn btn-success float-right update-nilai" name="insert">Kirim</button>
                </div>
            </form>
        <?php
                    }
        ?>
        </div>
    <?php
        }
    ?>
    </div>
<?php
    }
    $no++;
}
// MODAL KHS UPLOAD END
if (isset($_POST["update"])) {

    session_start();
    if ($_GET["module"] == "khs" && $_GET["act"] == "edit") {

        $resultMatkul = mysqli_query($con, "
            select a.*, b.nama as nm_mahasiswa, b.nim, c.nama as nm_matkul from tabel_khs a, tabel_mahasiswa b, tabel_matkul c where a.id_mahasiswa = b.id_mahasiswa and a.id_matkul=c.id_matkul and a.id_mahasiswa='$_POST[id_mahasiswa]' and a.id_semester='$_POST[id_semester]'");

        if (mysqli_num_rows($resultMatkul) > 0) {
            $i = 1;
            while ($rowMatkul = mysqli_fetch_assoc($resultMatkul)) {
                $id_khs = $_POST['id_khs' . $i];
                $nilai = $_POST['nilai' . $i];
                $waktu = date('Y-m-d H:i:s');
                mysqli_query($con, "UPDATE tabel_khs SET nilai='$nilai', waktu_edit='$waktu' 
                WHERE id_khs = '$id_khs'");

                $i++;
            }
        }
        header('location:../module/index.php?module=' . $_GET["module"]);
    }
}

// Modal KHS EDIT
if (isset($_POST["editNilaimhs"]) && isset($_POST["editNilaiSemester"])) {
    session_start();
    $id_mahasiswa = $_POST['editNilaimhs'];
    $id_semester = $_POST['editNilaiSemester'];

    $editNilaiMahasiswa = "select * from tabel_mahasiswa where id_mahasiswa='$id_mahasiswa' and id_semester='$id_semester'";

    $resultEditNilaiMahasiswa = mysqli_query($con, $editNilaiMahasiswa);

    if (mysqli_num_rows($resultEditNilaiMahasiswa) > 0) {
        $rowEditNilaiMahasiswa = mysqli_fetch_assoc($resultEditNilaiMahasiswa)
?>
    <form action="../process/proses_khs.php?module=khs&act=edit" method="post" id="EditNilai">
        <div class="modal-body">
            <input type="hidden" name="id_mahasiswa" value="<?= $rowEditNilaiMahasiswa["id_mahasiswa"] ?>">
            <input type="hidden" name="id_semester" value="<?= $rowEditNilaiMahasiswa["id_semester"] ?>">
            <div class="isi-modaLihat row">
                <div class="col-md-1">
                    <p>Nama</p>
                </div>
                <div class="col-md-auto">
                    <p>:</p>
                </div>
                <div class="col-md-8">
                    <p><?php echo $rowEditNilaiMahasiswa["nama"]; ?></p>
                </div>
            </div>
            <div class="isi-modaLihat row">
                <div class="col-md-1">
                    <p>NIM</p>
                </div>
                <div class="col-md-auto">
                    <p>:</p>
                </div>
                <div class="col-md-8">
                    <p><?php echo $rowEditNilaiMahasiswa["nim"]; ?></p>
                </div>
            </div>

            <div class="border-bottom border-gray">
                <div class="row">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <div class="row isi-modaLihat">
                            <p>Nilai Mahasiswa</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $editNilai = mysqli_query($con, "
                    select a.*, b.nama as nm_mahasiswa, b.nim, c.nama as nm_matkul from tabel_khs a, tabel_mahasiswa b, tabel_matkul c where a.id_mahasiswa = b.id_mahasiswa and a.id_matkul=c.id_matkul and a.id_mahasiswa='$id_mahasiswa' and a.id_semester='$id_semester'");

            if (mysqli_num_rows($editNilai) > 0) {
                $i = 1;
                while ($row1 = mysqli_fetch_assoc($editNilai)) {
            ?>
                    <div class="row isi-modaLihat">
                        <div class="col-sm-6">
                            <input type="hidden" name="id_khs<?= $i ?>" value="<?= $row1["id_khs"] ?>">
                            <p><?php echo $row1["nm_matkul"]; ?></p>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="number" min="0" max="100" class="form-control" name="nilai<?= $i ?>" value="<?php echo $row1["nilai"] ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                    $i++;
                }
            }
            ?>
        </div>
        <div class="modal-footer">
            <button type="submit" class="tmbl-kirim btn btn-success float-right" name="update">Kirim</button>
        </div>
    </form>
<?php
    }
}

// MODAL KHS EDIT END
?>