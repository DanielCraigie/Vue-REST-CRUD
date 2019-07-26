<?php

namespace App\controllers\api;

use App\Database;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Exception;

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
     * @return string|ResponseInterface
     */
    public function getContacts(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $sql = 'select * from contacts';
        $params = [];

        if (isset($args['id'])) {
            $sql .= ' where id = :id';
            $params[':id'] = $args['id'];
        }

        try {
            $contacts = Database::fetchAll($sql, $params);
        } catch (Exception $e) {
            error_log(__CLASS__ . '[' . __LINE__ . '] ' . $e->getMessage());
            return $response->withStatus(500, 'Contact search could not be performed.');
        }

        return json_encode($contacts);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return string|ResponseInterface
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
            return $response->withStatus(400, 'You must supply Contact information.');
        }

        $sql = rtrim($sql, ',') . ')';

        try {
            if ($id = Database::insert($sql, $params)) {
                return json_encode(['id' => $id]);
            }
        } catch (Exception $e) {
            error_log(__CLASS__ . '[' . __LINE__ . '] ' . $e->getMessage());
        }

        return $response->withStatus(500, 'The Contact could not be added.');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return null|ResponseInterface
     */
    public function delContact(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if (!isset($args['id'])) {
            return $response->withStatus(400, 'You must specify a Contact to remove.');
        }

        try {
            if (Database::execute('delete from contacts where id = :id', [':id' => $args['id']]) == 0) {
                throw new Exception('No records were deleted.');
            }
        } catch (Exception $e) {
            error_log(__CLASS__ . '[' . __LINE__ . '] ' . $e->getMessage());
            return $response->withStatus(500, 'The Contact could not be deleted.');
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function updateContact(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if (!isset($args['id'])) {
            return $response->withStatus(400, 'You must specify a Contact to update.');
        }

        $sql = 'update contacts set ';
        $params = [];

        foreach ($request->getParsedBody() as $key => $value) {
            if (!in_array($key, $this->attrs)) {
                continue;
            }

            $sql .= "$key = :$key,";
            $params[":$key"] = $value;
        }

        if (empty($params)) {
            return $response->withStatus(400, 'You must supply Contact information.');
        }

        $sql = rtrim($sql, ',') . ' where id = :id';
        $params[':id'] = $args['id'];

        try {
            if (Database::update($sql, $params) != 1) {
                throw new Exception('Record could not be updated.');
            }
        } catch (Exception $e) {
            error_log(__CLASS__ . '[' . __LINE__ . '] ' . $e->getMessage());
            return $response->withStatus(500, 'The Contact could not be updated.');
        }
    }
}
