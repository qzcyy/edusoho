<?php

namespace Biz\User\Dao\Impl;

use Biz\Common\FieldSerializer;
use Biz\User\Dao\TokenDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class TokenDaoImpl extends GeneralDaoImpl implements TokenDao
{
    protected $table = 'user_token';

    private $fieldSerializer;

    public $serializeFields = array(
        'data' => 'phpserialize',
    );

    public function get($id, $lock = false)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        $token = $this->db()->fetchAssoc($sql, array($id)) ?: null;

        return $token ? $this->createSerializer()->unserialize($token, $this->serializeFields) : null;
    }

    public function getByToken($token)
    {
        $sql = "SELECT * FROM {$this->table} WHERE token = ? LIMIT 1";
        $token = $this->db()->fetchAssoc($sql, array($token));

        return $token ? $this->createSerializer()->unserialize($token, $this->serializeFields) : null;
    }

    public function create($token)
    {
        $token = $this->createSerializer()->serialize($token, $this->serializeFields);

        return parent::create($token);
    }

    public function findByUserIdAndType($userId, $type)
    {
        return $this->findByFields(array('userId' => $userId, 'type' => $type));
    }

    public function getByType($type)
    {
        $sql = "SELECT * FROM {$this->table} WHERE type = ?  and expiredTime > ? order  by createdTime DESC  LIMIT 1";
        $token = $this->db()->fetchAssoc($sql, array($type, time())) ?: null;

        return $token ? $this->createSerializer()->unserialize($token, $this->serializeFields) : null;
    }

    public function deleteTopsByExpiredTime($expiredTime, $limit)
    {
        $limit = (int) $limit;
        $sql = "DELETE FROM {$this->table} WHERE expiredTime < ? LIMIT {$limit} ";

        return $this->db()->executeQuery($sql, array($expiredTime));
    }

    public function declares()
    {
        return array(
            'conditions' => array('type = :type'),
            'cache' => 'table'
        );
    }

    private function createSerializer()
    {
        if (empty($fieldSerializer)) {
            $fieldSerializer = new FieldSerializer();
        }

        return $fieldSerializer;
    }
}