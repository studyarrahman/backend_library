<?php
include 'connection.php';

$conn = getConnection();
$response = array();

try {
    if ($_POST) {
        $kode = $_POST["kode"];
        $kategori = $_POST["kategori"];

        // Memeriksa apakah kode sudah ada dalam tabel kategori
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM kategori WHERE kode = :kode");
        $stmt->bindParam(':kode', $kode);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            $response["message"] = "kode sudah ada";
        } else {
            $statement = $conn->prepare("INSERT INTO `kategori`(`kode`, `kategori`) VALUES (:kode, :kategori)");
            $statement->bindParam(':kode', $kode);
            $statement->bindParam(':kategori', $kategori);
            $statement->execute();
            $response["message"] = "Data berhasil direkam";
        }
    } else {
        $response["message"] = "Permintaan tidak valid";
    }
} catch (PDOException $e) {
    $response["message"] = "Error: " . $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
