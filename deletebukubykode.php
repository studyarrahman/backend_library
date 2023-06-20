<?php

include 'connection.php';

$conn = getConnection();

try {
    if (isset($_GET["kode"])) {
        $kode = $_GET["kode"];

        $statement = $conn->prepare("SELECT * FROM buku WHERE kode = :kode;");
        $statement->bindParam(':kode', $kode);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $result = $statement->fetch();

        if ($result) {
            // Menghapus file cover terlebih dahulu
            $file_path = $result->file_cover;
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Menghapus data buku dari tabel
            $statement = $conn->prepare("DELETE FROM buku WHERE kode = :kode");
            $statement->bindParam("kode", $kode);
            $statement->execute();

            $response['message'] = "Delete Data Berhasil";
        } else {
            http_response_code(404);
            $response['message'] = "Informasi buku tidak ditemukan";
        }

    } else {
        $response['message'] = "Delete Data Gagal";
    }
} catch (PDOException $e) {
    echo $e;
}

$json = json_encode($response, JSON_PRETTY_PRINT);
echo $json;
?>
