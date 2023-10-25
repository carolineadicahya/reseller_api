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
    $app->get('/memesan', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM memesan"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // GET by Id = SELECT by Id
    $app->get('/memesan/{pemesanan_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['pemesanan_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetPemesanan(:pemesanan_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':pemesanan_id', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data pesanan tidak ditemukan']));
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
    $app->post('/memesan', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idMemesan = $parsedBody['pemesanan_id'];
        $idSupplier = $parsedBody['supplier_id'];
        $idReseller = $parsedBody['reseller_id'];
        $Tanggal = $parsedBody['pemesanan_tanggal'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddPemesanan(?, ?, ?, ?)');
            $query->bindParam(1, $idMemesan, PDO::PARAM_STR);
            $query->bindParam(2, $idSupplier, PDO::PARAM_STR);
            $query->bindParam(3, $idReseller, PDO::PARAM_STR);
            $query->bindParam(4, $Tanggal, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail Memesan disimpan dengan id ' . $idMemesan
                    ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan memesan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // PUT = UPDATE
    $app->put('/memesan/{pemesanan_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['pemesanan_id']; // Menggunakan 'id_barang' sesuai dengan yang Anda definisikan dalam rute
        $parsedBody = $request->getParsedBody();
        
        $newSupplier = isset($parsedBody['new_supplier_id']) ? $parsedBody['new_supplier_id'] : null;
        $newReseller = isset($parsedBody['new_reseller_id']) ? $parsedBody['new_reseller_id'] : null;
        $newDate = isset($parsedBody['new_pemesanan_tanggal']) ? $parsedBody['new_pemesanan_tanggal'] : null;
    
        if ($newSupplier === null && $newReseller === null && $newDate === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
        try {
            $query = $db->prepare('CALL UpdatePemesanan(?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newSupplier, PDO::PARAM_STR);
            $query->bindParam(3, $newReseller, PDO::PARAM_STR);
            $query->bindParam(4, $newDate, PDO::PARAM_STR);
        
            $query->execute();
        
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pesanan dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // DELETE = DELETE
    $app->delete('/memesan/{pemesanan_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['pemesanan_id'];
    
        try {
            $query = $db->prepare('CALL DeletePemesanan(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pesanan dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus pesanan: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });
};