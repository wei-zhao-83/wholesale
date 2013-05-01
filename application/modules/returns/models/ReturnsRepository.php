<?php

namespace returns\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ReturnsRepository extends EntityRepository {
	public function getReturns($filter = null) {
		$qry_str = 'SELECT r 
					FROM returns\models\Returns r
					LEFT JOIN r.customer c';
		
		$qry_array = array();
		
		$qry_array[] = 'r.deleted_at IS NULL';
		
		if (!empty($filter['id'])) {
			$qry_array[] = 'r.id = \'' . $filter['id'] . '\' ';
		}
		
		if (!empty($filter['customer'])) {
			$qry_array[] = 'c.id = \'' . $filter['customer'] . '\' ';
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry_str .= ' ORDER BY r.created_at DESC';
		
		$qry = $this->_em->createQuery($qry_str);
		$returns = $qry->getResult();
		
		return $returns;
	}
}

?>