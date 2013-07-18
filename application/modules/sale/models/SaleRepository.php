<?php

namespace sale\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SaleRepository extends EntityRepository {
	public function getSales($filter = null) {
		$qry_str = 'SELECT s 
					FROM sale\models\Sale s
					LEFT JOIN s.customer c';
		
		$qry_array = array();
		
		$qry_array[] = 's.deleted_at IS NULL';
		
		if (!empty($filter['id'])) {
			$qry_array[] = 's.id = \'' . $filter['id'] . '\' ';
		}
		
		if (!empty($filter['customer'])) {
			$qry_array[] = 'c.id = \'' . $filter['customer'] . '\' ';
		}
		
		if (!empty($filter['status'])) {
			$qry_array[] = 's.status = \'' . $filter['status'] . '\' ';
		}
		
		if (!empty($filter['created_at_from'])) {
			$datatime = new \DateTime($filter['created_at_from']);
			$qry_array[] = 's.created_at >= \'' . $datatime->format('Y-m-d H:i:s') . '\' ';
		}
		
		if (!empty($filter['created_at_to'])) {
			$datatime = new \DateTime($filter['created_at_to']);
			$qry_array[] = 's.created_at <= \'' . $datatime->format('Y-m-d H:i:s') . '\' ';
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry_str .= ' ORDER BY s.' . (!empty($filter['order']) ? $filter['order']: 'created_at') . ' ' . (!empty($filter['sort']) ? $filter['sort']: 'DESC');
		
		// Pagination
		if (!empty($filter['per_page'])) {
			$qry = $this->_em->createQuery($qry_str)
						->setFirstResult($filter['current_page'])
						->setMaxResults($filter['per_page']);
			
			$sales = new Paginator($qry, $fetchJoinCollection = true);
		} else {
			$qry = $this->_em->createQuery($qry_str);
			$sales = $qry->getResult();
		}
		
		return $sales;
	}
}

?>