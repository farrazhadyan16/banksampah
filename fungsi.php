<?php
$conn = mysqli_connect("localhost", "root", "", "db_pm_old");

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function getProjectIds()
{
    global $conn;

    $query = "SELECT id FROM sampah"; // Replace 'your_project_table' with the actual table name
    $result = mysqli_query($conn, $query);

    $projectIds = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $projectIds[] = $row['id'];
    }

    return $projectIds;
}


//tesss kategori
function addCategory($name) {
    global $koneksi;
    $query = "INSERT INTO kategori_sampah (name, created_at) VALUES (?, NOW())";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

function deleteCategory($id) {
    global $koneksi;
    $query = "DELETE FROM kategori_sampah WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}






//hapus nasabah/admin
function hapusNasabah($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM user WHERE id = '$id'");
    return mysqli_affected_rows($conn);
}

//hapus sampah
function hapusSampah($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM sampah WHERE id = '$id'");
    return mysqli_affected_rows($conn);
}


//input data monitoring
function inputdata($data)
{
    global $conn;
    $id = htmlspecialchars($data["id"]);
    $id_kategori = htmlspecialchars($data["id_kategori"]);
    $jenis = htmlspecialchars($data["jenis"]);
    $nama_project = htmlspecialchars($data["nama_project"]);
    $kode_gbj = htmlspecialchars($data["kode_gbj"]);
    $nilai_harga = htmlspecialchars($data["nilai_harga"]);
    $nama_panel = htmlspecialchars($data["nama_panel"]);
    $tipe_jenis = htmlspecialchars($data["tipe_jenis"]);
    $tipe_fswm = htmlspecialchars($data["tipe_fswm"]);
    $qty_unit = htmlspecialchars($data["qty_unit"]);
    $qty_cell = htmlspecialchars($data["qty_cell"]);
    $warna = htmlspecialchars($data["warna"]);
    $nomor_wo = htmlspecialchars($data["nomor_wo"]);
    $nomor_seri = htmlspecialchars($data["nomor_seri"]);
    $warna = htmlspecialchars($data["warna"]);
    $size_panel_height = htmlspecialchars($data["size_panel_height"]);
    $size_panel_width = htmlspecialchars($data["size_panel_width"]);
    $size_panel_deep = htmlspecialchars($data["size_panel_deep"]);
    $mh_std = htmlspecialchars($data["mh_std"]);
    $mh_aktual = htmlspecialchars($data["mh_aktual"]);
    $tgl_submit_dwg_for_approval = htmlspecialchars($data["tgl_submit_dwg_for_approval"]);
    $tgl_approved = htmlspecialchars($data["tgl_approved"]);
    $tgl_release_dwg_softcopy = htmlspecialchars($data["tgl_release_dwg_softcopy"]);
    $tgl_release_dwg_hardcopy = htmlspecialchars($data["tgl_release_dwg_hardcopy"]);
    $breakdown = htmlspecialchars($data["breakdown"]);
    $busbar = htmlspecialchars($data["busbar"]);
    $targetppcleadtime = htmlspecialchars($data["target_ppc"]);
    $targetengleadtime = htmlspecialchars($data["target_eng"]);
    $tgl_box_selesai = htmlspecialchars($data["tgl_box_selesai"]);
    $due_date = htmlspecialchars($data["due_date"]);
    $tgl_terbit_wo = htmlspecialchars($data["tgl_terbit_wo"]);
    $plan_start_produksi = htmlspecialchars($data["plan_start_produksi"]);
    $aktual_start_produksi = htmlspecialchars($data["aktual_start_produksi"]);
    $plan_fg_wo = htmlspecialchars($data["plan_fg_wo"]);
    $progress = htmlspecialchars($data["progress"]);
    $desc_progress = htmlspecialchars($data["desc_progress"]);
    $status = htmlspecialchars($data["status"]);
    $delivery_no = htmlspecialchars($data["delivery_no"]);
    $delivery_tgl = htmlspecialchars($data["delivery_tgl"]);
    $keterangan = htmlspecialchars($data["keterangan"]);

    $query = "INSERT INTO sampah
    (id,id_kategori,jenis,nama_project,kode_gbj,nilai_harga,nama_panel,tipe_jenis,tipe_fswm,qty_unit,qty_cell
    ,nomor_wo,nomor_seri,warna,size_panel_height,size_panel_width,size_panel_deep,mh_std,mh_aktual,
    tgl_submit_dwg_for_approval, tgl_approved,tgl_release_dwg_softcopy,tgl_release_dwg_hardcopy,breakdown,busbar,
    target_ppc,target_eng,tgl_box_selesai,due_date,tgl_terbit_wo,plan_start_produksi,aktual_start_produksi,
    plan_fg_wo,progress,desc_progress,status,delivery_no,delivery_tgl,keterangan)
    VALUES
    ('$id','$id_kategori','$jenis','$nama_project','$kode_gbj','$nilai_harga','$nama_panel','$tipe_jenis',
    '$tipe_fswm','$qty_unit','$qty_cell','$warna','$nomor_wo','$nomor_seri','$size_panel_height',
    '$size_panel_width','$size_panel_deep','$mh_std','$mh_aktual','$tgl_submit_dwg_for_approval','$tgl_approved',
    '$tgl_release_dwg_softcopy','$tgl_release_dwg_hardcopy','$breakdown','$busbar','$targetppcleadtime',
    '$targetengleadtime','$tgl_box_selesai','$due_date','$tgl_terbit_wo','$plan_start_produksi','$aktual_start_produksi',
    '$plan_fg_wo','$progress','$desc_progress','$status','$delivery_no','$delivery_tgl','$keterangan')";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

//input data konsesi
function inputdatakonsesi($data)
{
    global $conn;
    $idkonsesi = htmlspecialchars($data["id_konsesi"]);
    $jo = htmlspecialchars($data["jo"]);
    $wo = htmlspecialchars($data["wo"]);
    $nameproject = htmlspecialchars($data["nama_project"]);
    $namepanel = htmlspecialchars($data["nama_panel"]);
    $unit = htmlspecialchars($data["unit"]);
    $jenis = htmlspecialchars($data["jenis"]);
    $no_rpb = htmlspecialchars($data["no_rpb"]);
    $no_po = htmlspecialchars($data["no_po"]);
    $kode_material = htmlspecialchars($data["kode_material"]);
    $konsesi = htmlspecialchars($data["konsesi"]);
    $jumlah = htmlspecialchars($data["jumlah"]);
    $nolkpj = htmlspecialchars($data["no_lkpj"]);
    $status = htmlspecialchars($data["status"]);
    $tglmatrial = htmlspecialchars($data["tgl_matrial_dtg"]);
    $tglpasang = htmlspecialchars($data["tgl_pasang"]);
    $keterangan = htmlspecialchars($data["keterangan"]);

    $query = "INSERT INTO konsesi
                (id_konsesi,jo,wo,nama_project,nama_panel,unit,jenis,no_rpb,no_po,kode_material,konsesi,jumlah,no_lkpj,status,tgl_matrial_dtg,tgl_pasang,keterangan)
                VALUES
                ('$idkonsesi', '$jo', '$wo', '$nameproject', '$namepanel', '$unit', '$jenis','$no_rpb', '$no_po','$kode_material','$konsesi', '$jumlah',
                '$nolkpj', '$status', '$tglmatrial', '$tglpasang', '$keterangan')";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

//edit data nasabah/admin
function updatedatanasabah($data)
{
    global $conn;
    $id = htmlspecialchars($data["id"]);
    $username = htmlspecialchars($data["username"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $notelp = htmlspecialchars($data["notelp"]);
    $nik = htmlspecialchars($data["nik"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $tgl_lahir = htmlspecialchars($data["tgl_lahir"]);
    $kelamin = htmlspecialchars($data["kelamin"]);

    $query = "UPDATE user SET username='$username',nama='$nama',email='$email',notelp='$notelp',nik='$nik',alamat='$alamat',tgl_lahir='$tgl_lahir',kelamin='$kelamin' WHERE id='$id'";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

//edit sampah
function updatedatasampah($data)
{
    global $conn;
    $id = htmlspecialchars($data["id"]);
    $id_kategori = htmlspecialchars($data["id_kategori"]);
    $jenis = htmlspecialchars($data["jenis"]);
    $harga = htmlspecialchars($data["harga"]);
    $harga_pusat = htmlspecialchars($data["harga_pusat"]);
    $jumlah = htmlspecialchars($data["jumlah"]);
    
    $query = "UPDATE sampah SET id_kategori='$id_kategori',jenis='$jenis',harga='$harga',harga_pusat='$harga_pusat',jumlah='$jumlah' WHERE id='$id'";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}