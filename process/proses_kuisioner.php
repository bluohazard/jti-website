<?php
include "../config/connection.php";

function kuisioner($con, $tahun, $semester)
{
  $kuisioner = "select distinct(a.id_dosen), b.*, b.nama as namaDosen, c.id_semester, d.* from tabel_hasil_kuisioner a, tabel_dosen b, tabel_mahasiswa c, tabel_semester d, tabel_kelas e where a.id_dosen=b.id_dosen and a.id_mahasiswa=c.id_mahasiswa and c.id_semester=d.id_semester and c.id_kelas=e.id_kelas and (YEAR(c.waktu_tambah)+e.tingkat-1)=$tahun and c.id_semester=$semester";
  $resultKuisioner = mysqli_query($con, $kuisioner);
  return $resultKuisioner;
}

function tampilTahun($con)
{
  $tahun = "select distinct(YEAR(a.waktu_tambah)) as tahun, b.tingkat from tabel_mahasiswa a, tabel_kelas b where a.id_kelas=b.id_kelas order by a.waktu_tambah desc limit 5";
  $resultTahun = mysqli_query($con, $tahun);
  return $resultTahun;
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

function kelasDosen($con, $id_dosen)
{
  $kelasDosen = "select c.id_kelas from tabel_dosen a, tabel_jadwal b, tabel_kelas c where b.id_kelas=c.id_kelas and a.id_dosen=b.id_dosen and a.id_dosen=$id_dosen group by c.id_kelas";
  $resultKelasDosen = mysqli_query($con, $kelasDosen);
  return $resultKelasDosen;
}

function tampilKelas($con, $id_kelas)
{
  $kelas = "select a.*, b.* from tabel_kelas a, tabel_prodi b where a.id_prodi=b.id_prodi and a.id_kelas=$id_kelas";
  $resultKelas = mysqli_query($con, $kelas);
  if (mysqli_num_rows($resultKelas) > 0) {
    $row = mysqli_fetch_assoc($resultKelas);
    $hasil = $row["kode"] . " - " . $row["tingkat"] . $row["kode_kelas"];
    return $hasil;
  } else {
    return "-";
  }
}

function cekStatusAktif($con)
{
  $status = "select distinct(status_aktif) as status_aktif from tabel_kuisioner";
  $resultStatus = mysqli_query($con, $status);
  $rowStatus = mysqli_fetch_assoc($resultStatus);
  if ($rowStatus["status_aktif"] == 'ya') {
    return true;
  } else if ($rowStatus["status_aktif"] == 'tidak') {
    return false;
  }
}

if (isset($_POST["hentikan"])) {
  mysqli_query($con, "update tabel_kuisioner set status_aktif='tidak'");
}

if (isset($_POST["aktifkan"])) {
  mysqli_query($con, "update tabel_kuisioner set status_aktif='ya'");
}

if (isset($_POST["namaDosen"])) {
  $output = '';
  $query = "select * from tabel_dosen where id_dosen='$_POST[namaDosen]' ";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
?>
    <h5 class="modal-title text-center"><?php echo $row["nama"]; ?></h5>
    <input type="hidden" name="id_dosen" id="id_dosen" value="<?php echo $row["id_dosen"]; ?>">
  <?php
  } else {
    echo "-";
  }
}

if (isset($_POST["id_dosen"])) {
  $query = "select distinct(a.id_mahasiswa),sum(a.nilai) as totalNilai, c.*, d.* from tabel_hasil_kuisioner a, tabel_dosen b, tabel_mahasiswa c, tabel_semester d, tabel_kelas e where a.id_dosen=b.id_dosen and a.id_mahasiswa=c.id_mahasiswa and c.id_semester=d.id_semester and c.id_kelas=e.id_kelas and c.id_kelas=$_POST[kelas] and (YEAR(c.waktu_tambah)+e.tingkat-1)=$_POST[tahun] and c.id_semester=$_POST[semester] and a.id_dosen=$_POST[id_dosen] group by a.id_mahasiswa";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) > 0) {
  ?>
    <table class='table table-striped table-bordered text-center'>
      <thead>
        <tr>
          <th>No</th>
          <th>NIM</th>
          <th>Nama</th>
          <th>Kelas</th>
          <th>Nilai Kuisioner</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $row["nim"]; ?></td>
            <td><?php echo $row["nama"]; ?></td>
            <td><?php echo tampilkelas($con, $_POST["kelas"]); ?></td>
            <td><?php echo $row["totalNilai"]; ?></td>
          </tr>
        <?php
          $no++;
        }
        ?>
      </tbody>
    </table>
  <?php
  } else {
  ?>
    <div class='text-center'>
      <img src='../img/magnifier.svg' alt='pencarian' class='p-3'>
      <p class='text-muted'>Data Tidak Ditemukan</p>
    </div>
  <?php
  }
}

function belumMengisiKuisioner($con, $id_mahasiswa)
{
  $totalMatkulBelum = mysqli_query($con, "select count(distinct(a.id_matkul)) as matkul from tabel_jadwal a, tabel_matkul b, tabel_mahasiswa c where a.id_matkul=b.id_matkul and a.id_kelas=c.id_kelas and c.id_mahasiswa='$id_mahasiswa' and a.id_matkul not in (select distinct(id_matkul) from tabel_hasil_kuisioner where id_mahasiswa='$id_mahasiswa')");
  $rowTotalMatkulBelum = mysqli_fetch_assoc($totalMatkulBelum);
  return $rowTotalMatkulBelum["matkul"];
}

function totalKuisioner($con, $id_mahasiswa)
{
  $totalMatkul = mysqli_query($con, "select count(distinct(a.id_matkul)) as matkul from tabel_jadwal a, tabel_matkul b, tabel_mahasiswa c where a.id_matkul=b.id_matkul and a.id_kelas=c.id_kelas and c.id_mahasiswa='$id_mahasiswa'");
  $rowTotalMatkul = mysqli_fetch_assoc($totalMatkul);
  return $rowTotalMatkul["matkul"];
}

if (isset($_POST["lihatPerKelas"])) {
  $query = "select distinct(a.id_mahasiswa),b.nim, b.nama from tabel_hasil_kuisioner a, tabel_mahasiswa b, tabel_kelas c where a.id_mahasiswa=b.id_mahasiswa and b.id_kelas=b.id_kelas and b.id_kelas=$_POST[lihatPerKelas] group by a.id_mahasiswa";
  $result = mysqli_query($con, $query);

  $querySemuaKuisioner = "select count(id_kuisioner) as 'semuaKuisioner' from tabel_kuisioner";
  $resultSemuaKuisioner = mysqli_query($con, $querySemuaKuisioner);
  $rowSemuaKuisioner = mysqli_fetch_assoc($resultSemuaKuisioner);

  if (mysqli_num_rows($result) > 0) {
  ?>
    <table class='table table-striped table-bordered text-center'>
      <thead>
        <tr>
          <th>No</th>
          <th>NIM</th>
          <th>Nama</th>
          <th>Telah Mengisi Kuisioner</th>
          <th>Belum Mengisi Kuisioner</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          $belumMengisi = $rowSemuaKuisioner["semuaKuisioner"];
        ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $row["nim"]; ?></td>
            <td><?php echo $row["nama"]; ?></td>
            <td><?php echo totalKuisioner($con, $row["id_mahasiswa"]) - belumMengisiKuisioner($con, $row["id_mahasiswa"]) ?></td>
            <td><?php echo belumMengisiKuisioner($con, $row["id_mahasiswa"]); ?></td>
          </tr>
        <?php
          $no++;
        }
        ?>
      </tbody>
    </table>
  <?php
  } else {
  ?>
    <div class='text-center'>
      <img src='../img/magnifier.svg' alt='pencarian' class='p-3'>
      <p class='text-muted'>Data Tidak Ditemukan</p>
    </div>
  <?php
  }
}

// Kriteria
function kriteria($con)
{
  $kriteria = "select * from tabel_kuisioner";
  $resultKriteria = mysqli_query($con, $kriteria);
  return $resultKriteria;
}

if (isset($_POST["cariKuisioner"])) {
  ?>
  <div class="col-md-12 d-flex text-center justify-content-center">
    <?php
    $resultKuisioner = kuisioner($con, $_POST["tahun"], $_POST["semester"]);

    if (mysqli_num_rows($resultKuisioner) > 0) {
    ?>
      <table class="table table-striped table-bordered text-center itemDosenKuisioner">
        <thead>
          <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama Dosen</th>
            <th>Kelas yang Diajar Semester Ini</th>
            <th>Proses</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($row = mysqli_fetch_assoc($resultKuisioner)) {
          ?>
            <tr class="itemDosenKuisioner">
              <td><?php echo $no; ?></td>
              <td class="nip"><?php echo $row["nip"]; ?></td>
              <td class="nama"><?php echo $row["namaDosen"]; ?></td>
              <td class="kelas">
                <?php
                $resultKelasDosen = kelasDosen($con, $row["id_dosen"]);
                if (mysqli_num_rows($resultKelasDosen) == 1) {
                  while ($rowKelas = mysqli_fetch_assoc($resultKelasDosen)) {
                    echo tampilKelas($con, $rowKelas["id_kelas"]);
                  }
                } else if (mysqli_num_rows($resultKelasDosen) > 1) {
                  while ($rowKelas = mysqli_fetch_assoc($resultKelasDosen)) {
                    echo tampilKelas($con, $rowKelas["id_kelas"]) . ", ";
                  }
                } else {
                  echo "-";
                }
                ?>
              </td>
              <td>
                <button type="button" id="<?php echo $row["id_dosen"]; ?>" class="btn btn-primary lihat-detail text-nowrap" data-toggle="modal" data-target="#modalLihatHasil">Lihat Hasil</button>
              </td>
            </tr>
          <?php
            $no++;
          }
          ?>
        </tbody>
      </table>
      <script>
        $(".lihat-detail").click(function() {
          var id_dosen = $(this).attr("id");

          $.ajax({
            url: "../process/proses_kuisioner.php",
            method: "post",
            data: {
              namaDosen: id_dosen
            },
            success: function(data) {
              $("#id_dosen").val(id_dosen);
              $("#judul").html(data);
              $("#modalLihatHasil").modal("show");
            }
          });
        });
      </script>
    <?php
    } else {
    ?>
      <div class="text-center">
        <p class="text-muted">Data Kuisioner Kosong</p>
      </div>
    <?php
    }
    ?>
  </div>
<?php
}

// CUD Kriteria
if (isset($_POST["edit_kuisioner"])) {
  $output = '';
  $query = "select * from tabel_kuisioner where id_kuisioner='$_POST[edit_kuisioner]'";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo $row["kriteria"];
  }
}

if (isset($_POST["tambahIsi"]) || isset($_POST["editIsi"]) || isset($_POST["hapus"])) {
  if ($_GET["module"] == "kriteriaKuisioner" && $_GET["act"] == "tambah") {
    $status_aktif = 'tidak';
    if (cekStatusAktif($con) == true) {
      $status_aktif = 'ya';
    }
    mysqli_query($con, "insert into tabel_kuisioner (kriteria, status) values ('$_POST[isiKriteria]','$status_aktif')");
    header('location:../module/index.php?module=' . $_GET["module"]);
  } else if ($_GET["module"] == "kriteriaKuisioner" && $_GET["act"] == "edit") {
    mysqli_query($con, "update tabel_kuisioner set kriteria='$_POST[isiKriteria]' where id_kuisioner='$_POST[id_kuisioner]'");
    header('location:../module/index.php?module=' . $_GET["module"]);
  } else if ($_GET["module"] == "kriteriaKuisioner" && $_GET["act"] == "hapus") {
    mysqli_query($con, "delete from tabel_kuisioner where id_kuisioner='$_POST[id_kuisioner]'");
    header('location:../module/index.php?module=' . $_GET["module"]);
  }
}
?>