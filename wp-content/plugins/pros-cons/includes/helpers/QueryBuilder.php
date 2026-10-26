<?php

namespace ProsCons\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Fluent query builder for WordPress custom tables.
 *
 * Wraps $wpdb with a chainable API that handles table prefixing
 * and prepared statements internally.
 *
 * Usage:
 *   QueryBuilder::table( 'clicks' )
 *       ->select( 'url, COUNT(*) as clicks' )
 *       ->where_between( 'created_at', $from, $to )
 *       ->group_by( 'url_hash' )
 *       ->order_by( 'clicks', 'DESC' )
 *       ->limit( 10 )
 *       ->get();
 *
 * @package ProsCons
 */
class QueryBuilder {

	/**
	 * Full table name including prefix.
	 *
	 * @var string
	 */
	private string $table;

	/**
	 * SELECT columns expression.
	 *
	 * @var string
	 */
	private string $columns = '*';

	/**
	 * Prepared WHERE clause fragments.
	 *
	 * @var array
	 */
	private array $wheres = array();

	/**
	 * GROUP BY expression.
	 *
	 * @var string
	 */
	private string $group = '';

	/**
	 * ORDER BY expression.
	 *
	 * @var string
	 */
	private string $order = '';

	/**
	 * LIMIT value.
	 *
	 * @var int|null
	 */
	private ?int $limit_val = null;

	/**
	 * OFFSET value.
	 *
	 * @var int|null
	 */
	private ?int $offset_val = null;

	/**
	 * WordPress database instance.
	 *
	 * @var \wpdb
	 */
	private \wpdb $db;

	/**
	 * Constructor — use QueryBuilder::table() instead.
	 *
	 * @param string $short_name Table name without prefix (e.g. 'clicks').
	 */
	private function __construct( string $short_name ) {
		global $wpdb;
		$this->db    = $wpdb;
		$this->table = $wpdb->prefix . 'prosandcons_' . $short_name;
	}

	/**
	 * Start building a query for a table.
	 *
	 * @param string $short_name Table name without 'prosandcons_' prefix.
	 * @return self
	 */
	public static function table( string $short_name ): self {
		return new self( $short_name );
	}

	/**
	 * Set columns to select.
	 *
	 * @param string $columns Column expression.
	 * @return self
	 */
	public function select( string $columns ): self {
		$this->columns = $columns;
		return $this;
	}

	/**
	 * Add WHERE column = string value.
	 *
	 * @param string $column   Column name.
	 * @param string $operator Comparison operator.
	 * @param string $value    Value.
	 * @return self
	 */
	public function where( string $column, string $operator, string $value ): self {
		$safe_op        = in_array( strtoupper( $operator ), array( '=', '!=', '>', '<', '>=', '<=', 'LIKE' ), true ) ? $operator : '=';
		$this->wheres[] = $this->db->prepare( $column . ' ' . $safe_op . ' %s', $value );
		return $this;
	}

	/**
	 * Add WHERE column = integer value.
	 *
	 * @param string $column   Column name.
	 * @param string $operator Comparison operator.
	 * @param int    $value    Integer value.
	 * @return self
	 */
	public function where_int( string $column, string $operator, int $value ): self {
		$safe_op        = in_array( strtoupper( $operator ), array( '=', '!=', '>', '<', '>=', '<=' ), true ) ? $operator : '=';
		$this->wheres[] = $this->db->prepare( $column . ' ' . $safe_op . ' %d', $value );
		return $this;
	}

	/**
	 * Add WHERE BETWEEN clause.
	 *
	 * @param string $column Column name.
	 * @param string $start  Start value.
	 * @param string $end    End value.
	 * @return self
	 */
	public function where_between( string $column, string $start, string $end ): self {
		$this->wheres[] = $this->db->prepare( $column . ' BETWEEN %s AND %s', $start, $end );
		return $this;
	}

	/**
	 * Add a raw WHERE clause for expressions that can't be parameterized.
	 *
	 * @param string $clause Safe SQL expression.
	 * @return self
	 */
	public function where_raw( string $clause ): self {
		$this->wheres[] = $clause;
		return $this;
	}

	/**
	 * Add WHERE LIKE clause with % wrapping.
	 *
	 * @param string $column Column name.
	 * @param string $value  Search term.
	 * @return self
	 */
	public function where_like( string $column, string $value ): self {
		$this->wheres[] = $this->db->prepare(
			$column . ' LIKE %s',
			'%' . $this->db->esc_like( $value ) . '%'
		);
		return $this;
	}

	/**
	 * Set GROUP BY.
	 *
	 * @param string $columns Column(s).
	 * @return self
	 */
	public function group_by( string $columns ): self {
		$this->group = $columns;
		return $this;
	}

	/**
	 * Set ORDER BY.
	 *
	 * @param string $column    Column name.
	 * @param string $direction ASC or DESC.
	 * @return self
	 */
	public function order_by( string $column, string $direction = 'ASC' ): self {
		$dir         = strtoupper( $direction ) === 'DESC' ? 'DESC' : 'ASC';
		$this->order = $column . ' ' . $dir;
		return $this;
	}

	/**
	 * Set raw ORDER BY expression.
	 *
	 * @param string $expression Full ORDER BY expression.
	 * @return self
	 */
	public function order_by_raw( string $expression ): self {
		$this->order = $expression;
		return $this;
	}

	/**
	 * Set LIMIT.
	 *
	 * @param int $limit Max rows.
	 * @return self
	 */
	public function limit( int $limit ): self {
		$this->limit_val = $limit;
		return $this;
	}

	/**
	 * Set OFFSET.
	 *
	 * @param int $offset Row offset.
	 * @return self
	 */
	public function offset( int $offset ): self {
		$this->offset_val = $offset;
		return $this;
	}

	/**
	 * Build the full SQL query. All WHERE clauses are already prepared.
	 *
	 * @return string
	 */
	private function build_sql(): string {
		$parts = array( 'SELECT ' . $this->columns . ' FROM ' . $this->table );

		if ( ! empty( $this->wheres ) ) {
			$parts[] = 'WHERE ' . implode( ' AND ', $this->wheres );
		}

		if ( $this->group ) {
			$parts[] = 'GROUP BY ' . $this->group;
		}

		if ( $this->order ) {
			$parts[] = 'ORDER BY ' . $this->order;
		}

		if ( null !== $this->limit_val ) {
			$parts[] = $this->db->prepare( 'LIMIT %d', $this->limit_val );
		}

		if ( null !== $this->offset_val ) {
			$parts[] = $this->db->prepare( 'OFFSET %d', $this->offset_val );
		}

		return implode( ' ', $parts );
	}

	/**
	 * Execute and return all rows.
	 *
	 * @return array
	 */
	public function get(): array {
		$results = $this->db->get_results( $this->build_sql(), ARRAY_A );
		return is_array( $results ) ? $results : array();
	}

	/**
	 * Execute and return a single scalar value.
	 *
	 * @return string|null
	 */
	public function get_value(): ?string {
		return $this->db->get_var( $this->build_sql() );
	}

	/**
	 * Execute and return a single row.
	 *
	 * @return array|null
	 */
	public function get_row(): ?array {
		$result = $this->db->get_row( $this->build_sql(), ARRAY_A );
		return is_array( $result ) ? $result : null;
	}

	/**
	 * Count matching rows.
	 *
	 * @param string $expression Count expression (default '*').
	 * @return int
	 */
	public function count( string $expression = '*' ): int {
		$this->columns = 'COUNT(' . $expression . ')';
		return (int) $this->get_value();
	}

	/**
	 * Insert a row and return the insert ID.
	 *
	 * @param array $data   Column => value pairs.
	 * @param array $format Format array (%s, %d, etc.).
	 * @return int|false Insert ID or false.
	 */
	public function insert( array $data, array $format = array() ) {
		$result = empty( $format )
			? $this->db->insert( $this->table, $data )
			: $this->db->insert( $this->table, $data, $format );

		return false !== $result ? $this->db->insert_id : false;
	}

	/**
	 * Update rows matching a WHERE clause.
	 *
	 * @param array $data         Data to set.
	 * @param array $where_pairs  Column => value WHERE pairs.
	 * @param array $format       Data format.
	 * @param array $where_format Where format.
	 * @return int|false Rows affected or false.
	 */
	public function update( array $data, array $where_pairs, array $format = array(), array $where_format = array() ) {
		return $this->db->update( $this->table, $data, $where_pairs, $format, $where_format );
	}

	/**
	 * Delete rows matching a WHERE clause.
	 *
	 * @param array $where_pairs Column => value WHERE pairs.
	 * @param array $format      Format array.
	 * @return int|false Rows deleted or false.
	 */
	public function delete( array $where_pairs, array $format = array() ) {
		return empty( $format )
			? $this->db->delete( $this->table, $where_pairs )
			: $this->db->delete( $this->table, $where_pairs, $format );
	}

	/**
	 * Run a DELETE with the fluent WHERE clauses.
	 *
	 * @return int|bool Rows deleted or false.
	 */
	public function delete_where() {
		$sql = 'DELETE FROM ' . $this->table;

		if ( ! empty( $this->wheres ) ) {
			$sql .= ' WHERE ' . implode( ' AND ', $this->wheres );
		}

		return $this->db->query( $sql );
	}

	/**
	 * Run a raw UPDATE SET with the fluent WHERE clauses.
	 *
	 * @param string $set_clause SET expression.
	 * @return int|bool Rows affected or false.
	 */
	public function update_raw( string $set_clause ) {
		$sql = 'UPDATE ' . $this->table . ' SET ' . $set_clause;

		if ( ! empty( $this->wheres ) ) {
			$sql .= ' WHERE ' . implode( ' AND ', $this->wheres );
		}

		return $this->db->query( $sql );
	}

	/**
	 * Get the full prefixed table name.
	 *
	 * @return string
	 */
	public function get_table_name(): string {
		return $this->table;
	}
}
