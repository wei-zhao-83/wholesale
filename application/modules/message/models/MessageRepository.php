<?php

namespace message\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class MessageRepository extends EntityRepository {
	public function getMessages($filter = null) {
		$qry_str = 'SELECT m
					FROM message\models\Message m
					JOIN m.from f
					JOIN m.to t';
		
		$qry_array = array();
		
		if (!empty($filter['subject'])) {
			$qry_array[] = 'm.subject LIKE \'%' . $filter['subject'] . '%\' ';
		}
		if (!empty($filter['from'])) {
			$qry_array[] .= 'f.id = \'' . $filter['from'] . '\' ';
		}
		
		$qry_array[] = 't.id = \'' . $filter['to'] . '\' ';
		
		if (count($qry_array) > 0) {
			$qry_str .= ' WITH ' . implode(' AND ', $qry_array);
		}

		$qry = $this->_em->createQuery($qry_str);
		$messages = $qry->getResult();
		
		return $messages;
	}
	
	public function getTotalUnreadMessage($to) {
		$qry_str = 'SELECT COUNT(m.id)
					FROM message\models\Message m
					JOIN m.to t
					WITH t.id = \'' . $to . '\'
					AND m.unread = 1';
		
		$qry = $this->_em->createQuery($qry_str);
		$num = $qry->getSingleScalarResult();
		
		return (int)$num;
	}
}

?>