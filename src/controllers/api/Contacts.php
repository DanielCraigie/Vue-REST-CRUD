<?php

namespace App\controllers\api;

use App\Database;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Contacts
 * @package App\controllers\api
 */
class Contacts
{
    /**
     * @var array
     */
    private $attrs = [
        'name',
        'email',
        'city',
        'country',
        'job',
    ];

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return false|string
     */
    public function getContacts(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $sql = 'select * from contacts';
        $params = [];

        if (isset($args['id'])) {
            $sql .= ' where id = :id';
            $params[':id'] = $args['id'];
        }

        return json_encode(Database::fetchAll($sql, $params));
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function addContact(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $sql = 'insert into contacts (name, email, city, country, job) values (';
        $params = [];

        foreach ($request->getParsedBody() as $key => $value) {
            if (!in_array($key, $this->attrs)) {
                continue;
            }

            $sql .= ":$key,";
            $params[":$key"] = $value;
        }

        if (empty($params)) {
            return $response->withStatus(400, "You must supply Contact information.");
        }

        $sql = rtrim($sql, ',') . ')';

        if ($id = Database::insert($sql, $params)) {
            return json_encode(['id' => $id]);
        }

        return $response->withStatus(500, "The Contact could not be added.");
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function delContact(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if (!isset($args['id'])) {
            return $response->withStatus(400, "You must specify a Contact to remove.");
        }

        if (!Database::execute('delete from contacts where id = :id', [':id' => $args['id']])) {
            return $response->withStatus(500, "The Contact could not be added.");
        }
    }
}
