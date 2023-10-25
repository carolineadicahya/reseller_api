<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // GET = SELECT
    $app->get('/detail_pesanan', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM detail_pesanan"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // GET by Id = SELECT by Id
    $app->get('/detail_pesanan/{detail_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['detail_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetDetailPesanan
        $sql = "CALL GetDetailPesanan(:detail_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':detail_id', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data detail pesanan tidak ditemukan']));
                $response = $response->withStatus(404);
            }
        } else {
            // Tangani kesalahan eksekusi query
            $errorInfo = $stmt->errorInfo();
            $response->getBody()->write(json_encode(['error' => $errorInfo]));
            $response = $response->withStatus(500); // Atur status kode 500 untuk kesalahan server
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // POST = INSERT
    $app->post('/detail_pesanan', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idDetail = $parsedBody['detail_id'];
        $idBarang = $parsedBody['barang_id'];
        $idPesanan = $parsedBody['pesanan_id'];
        $jumlahDetail = $parsedBody['jumlah_barang'];
        $hargaDetail = $parsedBody['harga_barang'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddDetailPesanan(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idDetail, PDO::PARAM_STR);
            $query->bindParam(2, $idBarang, PDO::PARAM_STR);
            $query->bindParam(3, $idPesanan, PDO::PARAM_STR);
            $query->bindParam(4, $jumlahDetail, PDO::PARAM_INT);
            $query->bindParam(5, $hargaDetail, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail pesanan disimpan dengan id ' . $idDetail
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan detail pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // PUT = UPDATE
    // Gagal memperbarui detail pesanan: 
    // SQLSTATE[23000]: Integrity constraint violation: 
    // 1452 Cannot add or update a child row: 
    // a foreign key constraint fails (`reseller`.`detail_pesanan`, 
    // CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_pemesanan`) REFERENCES `memesan` (`id_memesan`))"
    $app->put('/detail_pesanan/{detail_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['detail_id'];
        $parsedBody = $request->getParsedBody();

        $newIdBarang = isset($parsedBody['new_barang_id']) ? $parsedBody['new_barang_id'] : null;
        $newIdPemesanan = isset($parsedBody['new_pesanan_id']) ? $parsedBody['new_pesanan_id'] : null;
        $newJumlah = isset($parsedBody['new_jumlah_barang']) ? $parsedBody['new_jumlah_barang'] : null;
        $newHarga = isset($parsedBody['new_harga_barang']) ? $parsedBody['new_harga_barang'] : null;
    
        if ($newIdBarang === null && $newIdPemesanan === null && $newJumlah === null && $newHarga === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
    
        try {
            $query = $db->prepare('CALL UpdateDetailPesanan(?, ?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newIdBarang, PDO::PARAM_STR);
            $query->bindParam(3, $newIdPemesanan, PDO::PARAM_STR);
            $query->bindParam(4, $newJumlah, PDO::PARAM_INT);
            $query->bindParam(5, $newHarga, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail pesanan dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui detail pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // DELETE = DELETE
     // "Gagal menghapus detail pesanan: SQLSTATE[42S22]: 
    // Column not found: 1054 Unknown column 'barang.id_barang' in 'where clause'
    $app->delete('/detail_pesanan/{detail_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['detail_id'];
    
        try {
            $query = $db->prepare('CALL DeleteDetailPesanan(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail pesanan dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus detail pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });
};