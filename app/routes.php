<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // tabel detail
    //get
    $app->get('/detail_pesanan', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM detail_pesanan"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get untuk satu data, by id
    $app->get('/detail_pesanan/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetDetailPesanan
        $sql = "CALL GetDetailPesanan(:id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data pabrik tidak ditemukan']));
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
    

    // Post
    $app->post('/detail_pesanan', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idDetail = $parsedBody['id_detail'];
        $idBarang = $parsedBody['id_barang'];
        $idPesanan = $parsedBody['id_pemesanan'];
        $jumlah = $parsedBody['jumlah'];
        $harga = $parsedBody['harga'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddDetailPesanan(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idDetail, PDO::PARAM_STR);
            $query->bindParam(2, $idBarang, PDO::PARAM_STR);
            $query->bindParam(3, $idPesanan, PDO::PARAM_STR);
            $query->bindParam(4, $jumlah, PDO::PARAM_INT);
            $query->bindParam(5, $harga, PDO::PARAM_INT);
    
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

    // put
    $app->put('/detail_pesanan/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
        $parsedBody = $request->getParsedBody();
    
        $newIdBarang = $parsedBody['new_id_barang'];
        $newIdPemesanan = $parsedBody['new_id_pemesanan'];
        $newJumlah = $parsedBody['new_jumlah'];
        $newHarga = $parsedBody['new_harga'];
    
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

    // delete
    $app->delete('/detail_pesanan/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
    
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
    
    
    // Barang
    $app->get('/barang', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM detail_pesanan"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get untuk satu data, by id
    
};
