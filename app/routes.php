<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // tabel barang
    //get
    $app->get('/barang', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query("SELECT * FROM barang"); //query digunakan agar langsung execute
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get untuk satu data, by id
    $app->get('/barang/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare("SELECT * FROM barang where id_barang=?"); //prepare digunakan agar tidak langsung execute
        $query->execute([$args['id_barang']]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            $response->getBody()->write(json_encode($result[0]));
        } else {
            // Jika data tidak ditemukan, kirim respons dengan status 404
            $response->getBody()->write(json_encode(['error' => 'Country not found']));
            $response = $response->withStatus(404);
        }

        return $response->withHeader("Content-Type", "application/json");
    });

    // post
    $app->post('/barang', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        
        // kalo ga auto increment
        $id_country = $parsedBody['id_barang'];
        $countryName = $parsedBody['nama'];

        $db = $this->get(PDO::class);
        $query = $db->prepare('INSERT INTO barang (id_barang, nama) values (?, ?)');
        $query->execute([$id_country, $countryName]);

        $lastId = $db->lastInsertId();

        $response->getBody()->write(json_encode(
            [
                'message' => 'country disimpan dengan id'. $lastId
            ]
        ));
        return $response->withHeader("Content-Type", "application/json");
    });

    // put data (update)
    $app->put('/barang{id}', function(Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        $currentId = $args['id_barang'];
        $countryName = $parsedBody['nama'];
        $db = $this->get(PDO::class);

        $query = $db->prepare('UPDATE barang set nama = ? where id_barang = ?');
        $query->execute([$countryName, $currentId]);

        $response->getBody()->write(json_encode(
            [
                'message' => 'barang dengan id' . $currentId . 'telah diupdate dengan nama' . $countryName
            ]
        ));
        return $response->withHeader("Content-Type", "application/json");
    });

    // delete data
    $app->delete('/barang/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id_barang'];
        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('DELETE FROM barang WHERE id_barang = ?');
            $query->execute([$currentId]);

            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'barang dengan id ' . $currentId . ' dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }
        return $response->withHeader("Content-Type", "application/json");
    });
};
