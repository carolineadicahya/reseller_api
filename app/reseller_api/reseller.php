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
    $app->get('/reseller', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM reseller"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // GET by Id = SELECT by Id
    $app->get('/reseller/{reseller_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['reseller_id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetBarang
        $sql = "CALL GetReseller(:reseller_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':reseller_id', $id, PDO::PARAM_STR);
    
        // Jalankan query
        if ($stmt->execute()) {
            // Mengambil hasil dari procedure
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $response->getBody()->write(json_encode($result[0]));
            } else {
                // Jika data tidak ditemukan, kirim respons dengan status 404
                $response->getBody()->write(json_encode(['error' => 'Data reseller tidak ditemukan']));
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
    $app->post('/reseller', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
    $idReseller = $parsedBody['reseller_id'];
    $NamaReseller = $parsedBody['reseller_name'];
    $KontakReseller = $parsedBody['reseller_contact'];
    $AlamatReseller = $parsedBody['reseller_address'];

    $db = $this->get(PDO::class);

    try {
        $query = $db->prepare('CALL AddReseller(?, ?, ?, ?)');
        $query->bindParam(1, $idReseller, PDO::PARAM_STR);
        $query->bindParam(2, $NamaReseller, PDO::PARAM_STR);
        $query->bindParam(3, $KontakReseller, PDO::PARAM_STR);
        $query->bindParam(4, $AlamatReseller, PDO::PARAM_STR);

        $query->execute();

        $response->getBody()->write(json_encode(
            [
                'message' => 'Detail Reseller disimpan dengan id ' . $idReseller
                ]
        ));
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(
            [
                'error' => 'Gagal menyimpan reseller: ' . $e->getMessage()
            ]
        ));
    }
    return $response->withHeader("Content-Type", "application/json");
});

    // PUT = UPDATE
    $app->put('/reseller/{reseller_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['reseller_id']; // Menggunakan 'id_barang' sesuai dengan yang Anda definisikan dalam rute
        $parsedBody = $request->getParsedBody();
        
        $newName = isset($parsedBody['new_name']) ? $parsedBody['new_name'] : null;
        $newContact = isset($parsedBody['new_contact']) ? $parsedBody['new_contact'] : null;
        $newAddress = isset($parsedBody['new_address']) ? $parsedBody['new_address'] : null;
    
        if ($newName === null && $newContact === null && $newAddress === null) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Tidak ada data yang diperbarui.'
                ]
            ));
            return $response->withStatus(400); // Atur status kode ke 400 Bad Request atau sesuai kebutuhan
        }
        try {
            $query = $db->prepare('CALL UpdateReseller(?, ?, ?, ?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
            $query->bindParam(2, $newName, PDO::PARAM_STR);
            $query->bindParam(3, $newContact, PDO::PARAM_STR);
            $query->bindParam(4, $newAddress, PDO::PARAM_STR);
        
            $query->execute();
        
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail reseller dengan id ' . $id . ' telah diperbarui'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal memperbarui reseller: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });

    // DELETE = DELETE
    $app->delete('/reseller/{reseller_id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['reseller_id'];
    
        try {
            $query = $db->prepare('CALL DeleteReseller(?)');
            $query->bindParam(1, $id, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail reseller dengan id ' . $id . ' telah dihapus'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menghapus reseller: ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });
};