<?php
// insertbuku.php
include 'connection.php';

$conn = getConnection();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mendapatkan data dari permintaan POST
        $kode = $_POST['kode'];
        $kode_kategori = $_POST['kode_kategori'];
        $judul = $_POST['judul'];
        $pengarang = $_POST['pengarang'];
        $penerbit = $_POST['penerbit'];
        $tahun = $_POST['tahun'];
        $tanggal_input = $_POST['tanggal_input'];
        $harga = $_POST['harga'];

        // Mendapatkan informasi file cover
        $file_name = $_FILES['file_cover']['name'];
        $file_tmp = $_FILES['file_cover']['tmp_name'];
        $file_size = $_FILES['file_cover']['size'];
        $file_error = $_FILES['file_cover']['error'];

        // Menyimpan file cover ke dalam direktori tertentu
        $target_dir = "upload/file_cover/";
        $target_file = $target_dir . basename($file_name);

        // Memeriksa apakah file sudah berhasil diunggah
        if ($file_error === UPLOAD_ERR_OK) {
            // Memindahkan file cover ke direktori tujuan
            move_uploaded_file($file_tmp, $target_file);

            // Memeriksa apakah kode kategori ada dalam database
            $statement = $conn->prepare("SELECT COUNT(*) AS count FROM kategori WHERE kode = :kode_kategori");
            $statement->bindParam(':kode_kategori', $kode_kategori);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                try {
                    // Menyiapkan pernyataan SQL untuk menyisipkan data buku
                    $sql = "INSERT INTO buku (kode, kode_kategori, judul, pengarang, penerbit, tahun, tanggal_input, harga, file_cover)
                            VALUES (:kode, :kode_kategori, :judul, :pengarang, :penerbit, :tahun, :tanggal_input, :harga, :file_cover)";
                    $stmt = $conn->prepare($sql);

                    // Menetapkan nilai parameter
                    $stmt->bindParam(':kode', $kode);
                    $stmt->bindParam(':kode_kategori', $kode_kategori);
                    $stmt->bindParam(':judul', $judul);
                    $stmt->bindParam(':pengarang', $pengarang);
                    $stmt->bindParam(':penerbit', $penerbit);
                    $stmt->bindParam(':tahun', $tahun);
                    $stmt->bindParam(':tanggal_input', $tanggal_input);
                    $stmt->bindParam(':harga', $harga);
                    $stmt->bindParam(':file_cover', $target_file);

                    // Menjalankan pernyataan SQL
                    $stmt->execute();

                    echo "Data buku berhasil ditambahkan.";
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Kode kategori tidak ditemukan. Tidak bisa menambah data. Periksa kembali!";
            }
        } else {
            echo "Error uploading file.";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Tutup koneksi
$conn = null;
?>
