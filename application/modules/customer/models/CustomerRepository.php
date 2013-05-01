<?php

namespace customer\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CustomerRepository extends EntityRepository {
	public function getCustomers($filter = null) {
		$qry_str = 'SELECT c
					FROM customer\models\Customer c
					LEFT JOIN c.tags t';
		
		$qry_array = array();
		
		$qry_array[] = 'c.deleted_at IS NULL';
		
		if (!empty($filter['name'])) {
			$qry_array[] = 'c.name LIKE \'%' . $filter['name'] . '%\' ';
		}
		
		if (!empty($filter['phone'])) {
			$qry_array[] = 'c.phone LIKE \'%' . $filter['phone'] . '%\' ';
		}
		
		if (!empty($filter['tags']) && is_array($filter['tags'])) {
			foreach ($filter['tags'] as $tag) {
				if (!empty($tag)) {
					$tag_qry_array[] = ' t.name = \'' . $tag . '\' ';
				}
			}
			$qry_array[] = implode(' AND ', $tag_qry_array);
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry = $this->_em->createQuery($qry_str);
		$customers = $qry->getResult();
		
		return $customers;
	}
}

?>