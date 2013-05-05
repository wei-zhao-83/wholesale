<?php

namespace purchase\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PurchaseRepository extends EntityRepository {
	public function getSalePendingProd($prod_ids = array()) {
		if (empty($prod_ids)) {
			return false;
		}
		
		$result = array();
		
		$qry_str = 'SELECT pd.id, SUM(i.qty) AS qty
					FROM transaction\models\TransactionItem i
					LEFT JOIN i.transaction t
					LEFT JOIN t.status s
					LEFT JOIN i.product pd
					WHERE pd.id IN(' . implode(',', $prod_ids) . ')
						AND s.id = 2
						AND t.deleted_at IS NULL
						AND t INSTANCE OF sale\models\Sale
					GROUP BY pd';
					
		$qry = $this->_em->createQuery($qry_str);
		
		$items = $qry->getResult();
		
		if (!empty($items)) {
			foreach ($items as $item) {
				$result[$item['id']] = $item['qty'];
			}
		}
		
		return $result;
	}
	
	public function getOrderFrequency($vendor, $prod_ids = array()) {
		if (empty($prod_ids) || !$vendor instanceof \vendor\models\Vendor) {
			return false;
		}
		
		$result = array();
		
		$qry_str = 'SELECT pd.id, SUM(i.qty) AS qty
					FROM transaction\models\TransactionItem i
					LEFT JOIN i.transaction t
					LEFT JOIN t.status s
					LEFT JOIN i.product pd
					WHERE pd.id IN(' . implode(',', $prod_ids) . ')
						AND s.id = 3
						AND t.deleted_at IS NULL
						AND t INSTANCE OF purchase\models\Purchase
						AND t.created_at > DATE_SUB(CURRENT_DATE(), ' . $vendor->getOrderFrequency() . ', \'day\')
						AND t.id IN(
							SELECT p.id
							FROM purchase\models\Purchase p
							LEFT JOIN p.vendor v
							WHERE v.id = ' . $vendor->getId() . '
						) 
					GROUP BY pd';
		
		$qry = $this->_em->createQuery($qry_str);
		
		$items = $qry->getResult();
		
		if (!empty($items)) {
			foreach ($items as $item) {
				$result[$item['id']] = $item['qty'];
			}
		}
		
		return $result;
	}
	
	public function getPurchases($filter = null) {
		$qry_str = 'SELECT p 
					FROM purchase\models\Purchase p
					JOIN p.status s
					LEFT JOIN p.vendor v';
		
		$qry_array = array();
		
		$qry_array[] = 'p.deleted_at IS NULL';
		
		if (!empty($filter['id'])) {
			$qry_array[] = 'p.id = \'' . $filter['id'] . '\' ';
		}
		
		if (!empty($filter['vendor'])) {
			$qry_array[] = 'v.id = \'' . $filter['vendor'] . '\' ';
		}
		
		if (!empty($filter['status'])) {
			$qry_array[] = 's.id = \'' . $filter['status'] . '\' ';
		}
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WHERE ' . implode(' AND ', $qry_array);
		}
		
		$qry_str .= ' ORDER BY p.created_at DESC';
		
		$qry = $this->_em->createQuery($qry_str);
		$purchases = $qry->getResult();
		
		return $purchases;
	}
}

?>