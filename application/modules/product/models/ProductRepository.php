<?php

namespace product\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductRepository extends EntityRepository {
	public function getProducts($filter = null) {
		$qry_str = 'SELECT p
					FROM product\models\Product p
					LEFT JOIN p.tags t
					JOIN p.category c
					LEFT JOIN p.vendors v';
		
		$qry_array = array();
		
		$qry_array[] = 'p.deleted_at IS NULL';
		
		if (!empty($filter['ids'])) {
			$qry_array[] = 'p.id IN(' . implode(',', $filter['ids']) . ') ';
		}
		
		if (!empty($filter['name'])) {
			$qry_array[] = 'p.name LIKE \'%' . $filter['name'] . '%\' ';
		}
		
		if (!empty($filter['barcode'])) {
			$qry_array[] = 'p.barcode = \'' . $filter['barcode'] . '\' ';
		}
		
		if (!empty($filter['section'])) {
			$qry_array[] = 'p.section = \'' . $filter['section'] . '\' ';
		}
		
		if (!empty($filter['qty'])) {
			$qry_array[] = 'p.qty = \'' . $filter['qty'] . '\' ';
		}
		
		if (empty($filter['qty']) && !empty($filter['max_qty'])) {
			$qry_array[] = 'p.qty <= \'' . $filter['max_qty'] . '\' ';
		}
		if (empty($filter['qty']) && !empty($filter['min_qty'])) {
			$qry_array[] = 'p.qty >= \'' . $filter['min_qty'] . '\' ';
		}
		
		if (!empty($filter['category'])) {
			$qry_array[] = 'c.id = \'' . $filter['category'] . '\' ';
		}
		
		if (!empty($filter['vendor'])) {
			$qry_array[] = 'v.id = \'' . $filter['vendor'] . '\' ';
		}
		
		if (!empty($filter['tags']) && is_array($filter['tags'])) {
			foreach ($filter['tags'] as $tag) {
				if (!empty($tag)) {
					$tag_qry_array[] = ' t.name = \'' . $tag . '\' ';
				}
			}
			if (!empty($tag_qry_array)) {
				$qry_array[] = implode(' AND ', $tag_qry_array);
			}
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry_str .= ' ORDER BY p.name';
		
		$current_page = !empty($filter['current_page']) ? $filter['current_page'] : 0;
		
		if (!empty($filter['per_page'])) {
			$per_page = $filter['per_page'];
			
			$qry = $this->_em->createQuery($qry_str)
						 ->setFirstResult($current_page)
						 ->setMaxResults($per_page);
						 
			$products = new Paginator($qry, $fetchJoinCollection = true);
		} else {
			$qry = $this->_em->createQuery($qry_str);
			$products = $qry->getResult();
		}
		
		return $products;
	}
}

?>