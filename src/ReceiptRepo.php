<?php

namespace oddEvan\FetchEvaluation;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Tools\DsnParser;
use Ramsey\Uuid\UuidInterface;

/**
 * Store receipt information between requests.
 */
class ReceiptRepo {
	/**
	 * Database connection.
	 *
	 * @var Connection
	 */
	private Connection $dbalConnection;

	private const DSN_MAPPING = [
		'mysql' => 'mysqli',
		'postgres' => 'pdo_pgsql',
		'sqlite' => 'pdo_sqlite',
	];

	/**
	 * Create the manager and connect to the given database.
	 *
	 * @see https://www.doctrine-project.org/projects/doctrine-dbal/en/4.2/reference/configuration.html#configuration
	 *
	 * @param string $dsn Connection URL for a database.
	 */
	public function __construct(string $dsn) {
		$connectionParameters = (new DsnParser(static::DSN_MAPPING))->parse($dsn);
		$this->dbalConnection = DriverManager::getConnection($connectionParameters);

		if (!$this->dbalConnection->createSchemaManager()->tableExists('receipts')) {
			$this->createTable();
		}
	}

	/**
	 * Save a receipt to the database.
	 *
	 * @param UuidInterface $id      ID of the receipt.
	 * @param Receipt       $receipt Receipt object.
	 * @param integer       $points  Calculated points for the receipt.
	 * @return void
	 */
	public function saveReceipt(
		UuidInterface $id,
		Receipt $receipt,
		int $points
	): void {
		$this->dbalConnection->insert(
			'receipts',
			[
				'id' => $id,
				'receipt_obj' => \json_encode($receipt),
				'points' => $points,
			],
		);
	}

	/**
	 * Get the points associated with a given receipt.
	 *
	 * @param UuidInterface $id ID of the receipt.
	 * @return integer
	 */
	public function getPointsForReceipt(UuidInterface $id): int {
		return $this->dbalConnection->fetchOne('SELECT points FROM receipts WHERE id = ?', [$id]);
	}

	/**
	 * Create the table in the database.
	 *
	 * @return void
	 */
	private function createTable(): void {
		$schema = new Schema();
		$table = $schema->createTable('receipts');
		$table->addColumn('id', 'guid');
		$table->addColumn('receipt_obj', 'json');
		$table->addColumn('points', 'integer');
		$table->setPrimaryKey(['id']);

		$this->dbalConnection->createSchemaManager()->migrateSchema($schema);
	}
}
