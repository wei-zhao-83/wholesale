<?php

namespace transaction_status\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class TransactionStatusRepository extends EntityRepository {
	public function getStatuses() {
		$qry_str = 'SELECT s
					FROM transaction_status\models\TransactionStatus s';
		
		$qry_array = array();
		
		$qry_array[] = 's.deleted_at IS NULL';
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry = $this->_em->createQuery($qry_str);
		$statuses = $qry->getResult();
		
		return $statuses;
	}
}

?>