
<?php

include 'connection.php';

$conn = getConnection();

try {
    if (isset($_GET["kode"])) {
        $kode = $_GET["kode"];

        $statement = $conn->prepare("SELECT * FROM kategori WHERE kode = :kode;");
        $statement->bindParam(':kode', $kode);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $result = $statement->fetch();

        if ($result) {
            $statement = $conn->prepare("DELETE FROM kategori WHERE kode = :kode");
            $statement->bindParam("kode", $kode);
            $statement->execute();

            $response['message'] = "Delete Data Berhasil";
        } else {
            http_response_code(404);
            $response['message'] = "informasi kategori tidak ditemukan";
        }

    } else {
        $response['message'] = "Delete Data Gagal";
    }
} catch (PDOException $e) {
    echo $e;
}

$json = json_encode($response, JSON_PRETTY_PRINT);
echo $json;