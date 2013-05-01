<?php

namespace quote\models;

use transaction\models\Transaction AS Transaction;

/** @Entity
 *  @Table(name="quotes")
 */
class Quote extends Transaction {
}