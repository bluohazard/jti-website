<?php
    include "../config/connection.php";

    function kelasDipesan($con){
        $kelasDipesan="select a.*, b.* from tabel_info_kelas_kosong a, tabel_ruangan b where a.id_ruang = b.id_ruang and status_dipinjam='Dipinjam' and a.peminjam='$_SESSION[id]'";
        $resultKelasDipesan=mysqli_query($con, $kelasDipesan);
        return $resultKelasDipesan;
    }
    
    function kelasKosong($con,$jam,$hari){
        $kelasKosong="select a.*, b.* from tabel_info_kelas_kosong a, tabel_ruangan b where a.id_ruang = b.id_ruang and a.waktu_mulai='$jam' and a.hari='$hari'";
        $resultKelasKosong=mysqli_query($con, $kelasKosong);
        return $resultKelasKosong;
    }

    function cekKelasLogin($con){
        if($_SESSION['level']=="mahasiswa"){
            $login=mysqli_query($con, "select * from tabel_mahasiswa where id_mahasiswa='$_SESSION[id]'");
            $resultLogin=mysqli_fetch_assoc($login);
            return $resultLogin["id_kelas"];
        }
    }

    function cekPeminjamSekelas($con, $jam, $hari){
        $resultPeminjam=mysqli_query($con, "select a.*,b.id_kelas from tabel_info_kelas_kosong a, tabel_mahasiswa b where a.status_dipinjam='Dipinjam' and a.peminjam=b.id_mahasiswa and a.waktu_mulai='$jam' and a.hari='$hari' and b.id_kelas='".cekKelasLogin($con)."'");
        if (mysqli_num_rows($resultPeminjam) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function tampilWaktu($waktu){
        return date('H.i',strtotime($waktu));
    }

    function tampilWaktuDefault($waktu){
        return date('H:i',strtotime($waktu));
    }

    function tampilJam($con){
        $resultJam=mysqli_query($con, "select distinct waktu_mulai from tabel_info_kelas_kosong");
        return $resultJam;
    }

    // Pesan
    if(isset($_POST["pesan"])){
        session_start();
        if($_GET["act"]=="pesan"){
            $dateNow=date('Y-m-d H:i:s');
            mysqli_query($con, "update tabel_info_kelas_kosong set status_dipinjam='Dipinjam', 
            peminjam='$_SESSION[id]', waktu_pinjam='$dateNow' where id_info_kelas_kosong='$_GET[id]'");
            header("location: ../module/index.php?module=kelasKosong");
        }
    }

    // Checkout
    if(isset($_POST["checkout"])){
        if($_GET["module"]=="kelasKosong" && $_GET["act"]=="checkout"){
            mysqli_query($con, "update tabel_info_kelas_kosong set status_dipinjam='Kosong' where id_info_kelas_kosong='$_GET[id]'");
            header("location: ../module/index.php?module=kelasKosong");
        }
    }
?>