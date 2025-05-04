<?php
include("koneksi.php");

$nama_lengkap = $email = $deskripsi_masalah = "";
$erros = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan input
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $deskripsi_masalah = trim($_POST['deskripsi_masalah']);

    // Validasi
    if (empty($nama_lengkap)) {
        $errors[] = "Nama lengkap tidak boleh kosong.";
    }

    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    if (empty($deskripsi_masalah)) {
        $errors[] = "Deskripsi masalah tidak boleh kosong.";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $stmt = $koneksi->prepare("INSERT INTO tb_masalah (nama_lengkap, email, deskripsi_masalah) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama_lengkap, $email, $deskripsi_masalah);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Data berhasil dikirim!</p>";
            // Reset form
            $nama_lengkap = $email = $deskripsi_masalah = "";
        } else {
            echo "<p style='color:red;'>Terjadi kesalahan saat menyimpan data.</p>";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pengaduan Masyarakat</title>
</head>
<body>
    <form action="" method="POST">
        <h3>Formulir Pengaduan Masyarakat</h3>
        <p>Isilah formulir sesuai dengan fakta dilapangan</p>
        <?php
	// Tampilkan error jika ada
        if (!empty($errors)) {
            echo "<ul style='color:red;'>";
            foreach ($errors as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul>";
        }
        ?>
        <table>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($nama_lengkap); ?>" size="30px"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td><input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" size="30px"></td>
            </tr>
            <tr>
                <td>Deskripsi Masalah</td>
                <td>:</td>
                <td><textarea name="deskripsi_masalah" cols="30" rows="10"><?php echo htmlspecialchars($deskripsi_masalah); ?></textarea></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:center"><input type="submit" value="Simpan">&nbsp;&nbsp;<input type="reset" value="Batal"></td>
            </tr>
        </table>
    </form>
</body>
</html>