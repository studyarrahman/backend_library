<?php
include 'connection.php';

$conn = getConnection();
$response = array();

try {
    if ($_POST) {
        $kode = $_POST["kode"];
        $kategori = $_POST["kategori"];

        // Memeriksa apakah kode ada dalam tabel kategori
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM kategori WHERE kode = :kode");
        $stmt->bindParam(':kode', $kode);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            // Melakukan pembaruan data kategori
            $statement = $conn->prepare("UPDATE kategori SET kategori = :kategori WHERE kode = :kode");
            $statement->bindParam(':kategori', $kategori);
            $statement->bindParam(':kode', $kode);
            $statement->execute();
            $response["message"] = "Data kategori berhasil diperbarui";
        } else {
            $response["message"] = "Kode tidak ditemukan";
        }
    } else {
        $response["message"] = "Permintaan tidak valid";
    }
} catch (PDOException $e) {
    $response["message"] = "Error: " . $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>