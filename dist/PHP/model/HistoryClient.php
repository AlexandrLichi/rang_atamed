<?php
namespace Model\HistoryClientModel;

use Pet\Model\Model;

class HistoryClientModel extends Model {
    public $table = 'history_client';
    public $insert = false;
    public $join = [];

    public function getHistory($clientId) {
        return $this->find(['client_id' => $clientId],['date-time', 'status', 'comment']);
    }

    public function setHistory($clientId, $status, $text) {
        $this->insert = true;
        $this->insert(['client_id' => $clientId, 'status' => $status, 'comment' => $text]);
    }
}