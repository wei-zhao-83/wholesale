<?php

namespace sale\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SaleRepository extends EntityRepository {
	public function getSales($filter = null) {
		$qry_str = 'SELECT s 
					FROM sale\models\Sale s
					JOIN s.status st
					LEFT JOIN s.customer c';
		
		$qry_array = array();
		
		$qry_array[] = 's.deleted_at IS NULL';
		
		if (!empty($filter['id'])) {
			$qry_array[] = 's.id = \'' . $filter['id'] . '\' ';
		}
		
		if (!empty($filter['customer'])) {
			$qry_array[] = 'c.id = \'' . $filter['vendor'] . '\' ';
		}
		
		if (!empty($filter['status'])) {
			$qry_array[] = 'st.id = \'' . $filter['status'] . '\' ';
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry_str .= ' ORDER BY s.created_at DESC';
		
		$qry = $this->_em->createQuery($qry_str);
		$sales = $qry->getResult();
		
		return $sales;
	}
}

?>