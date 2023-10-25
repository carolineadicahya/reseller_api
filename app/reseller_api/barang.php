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
    $app->get('/barang', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM barang"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // GET by Id = SELECT by Id
    $app->get('/barang/{barang_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['barang_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetBarang(:barang_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':barang_id', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data barang tidak ditemukan']));
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
    $app->post('/barang', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idBarang = $parsedBody['barang_id'];
        $NamaBarang = $parsedBody['barang_nama'];
        $BrandBarang = $parsedBody['barang_brand'];
        $ModelBarang = $parsedBody['barang_model'];
        $JumlahBarang = $parsedBody['barang_jumlah'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL AddBarang(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idBarang, PDO::PARAM_STR);
            $query->bindParam(2, $NamaBarang, PDO::PARAM_STR);
            $query->bindParam(3, $BrandBarang, PDO::PARAM_STR);
            $query->bindParam(4, $ModelBarang, PDO::PARAM_STR);
            $query->bindParam(5, $JumlahBarang, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail barang disimpan dengan id ' . $idBarang
                    ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan barang: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // PUT = UPDATE
    $app->put('/barang/{barang_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['barang_id']; // Menggunakan 'id_barang' sesuai dengan yang Anda definisikan dalam rute
        $parsedBody = $request->getParsedBody();
        
        $newNama = isset($parsedBody['new_nama']) ? $parsedBody['new_nama'] : null;
        $newBrand = isset($parsedBody['new_brand']) ? $parsedBody['new_brand'] : null;
        $newModel = isset($parsedBody['new_model']) ? $parsedBody['new_model'] : null;
        $newJumlah = isset($parsedBody['new_jumlah']) ? $parsedBody['new_jumlah'] : null;
    
        if ($newNama === null && $newBrand === null && $newModel === null && $newJumlah === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
        try {
            $query = $db->prepare('CALL UpdateBarang(?, ?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newNama, PDO::PARAM_STR);
            $query->bindParam(3, $newBrand, PDO::PARAM_STR);
            $query->bindParam(4, $newModel, PDO::PARAM_STR); // Ganti PDO::PARAM_INT menjadi PDO::PARAM_STR karena 'new_model' adalah VARCHAR
            $query->bindParam(5, $newJumlah, PDO::PARAM_INT);
        
            $query->execute();
        
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail barang dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui barang: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // DELETE = DELETE
    $app->delete('/barang/{barang_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['barang_id'];
    
        try {
            $query = $db->prepare('CALL DeleteBarang(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail barang dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus barang: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });
};