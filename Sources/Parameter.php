<?php declare(strict_types=1);
namespace Belin\Sql;

/**
 * Represents a parameter of a parameterized SQL statement.
 */
final class Parameter {

	/**
	 * The prefixes used for parameter placeholders.
	 * @var string[]
	 */
	private static array $prefixes = ["?", ":"];

	/**
	 * The database type of this parameter.
	 */
	public ?DbType $dbType;

	/**
	 * Value indicating whether this parameter is input-only, output-only, bidirectional, or a stored procedure return value parameter.
	 */
	public ?ParameterDirection $direction;

	/**
	 * The parameter name.
	 */
	public string $name { set => self::normalizeName($value); }

	/**
	 * The parameter value.
	 */
	public mixed $value;

	/**
	 * Creates a new parameter.
	 * @param string $name The parameter name.
	 * @param mixed $value The parameter value.
	 * @param DbType|null $dbType The parameter database type.
	 * @param ParameterDirection|null $direction The parameter direction.
	 */
	public function __construct(string $name, mixed $value = null, ?DbType $dbType = null, ?ParameterDirection $direction = null) {
		$this->dbType = $dbType;
		$this->direction = $direction;
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * Creates a new parameter from the specified tuple.
	 * @param array<int|string, mixed> $tuple The tuple providing the parameter properties.
	 * @return self The parameter corresponding to the specified tuple.
	 */
	public static function of(array $tuple): self {
		return array_is_list($tuple)
			? new self($tuple[0] ?? "", $tuple[1] ?? null, $tuple[2] ?? null, $tuple[3] ?? null)
			: new self($tuple["name"] ?? "", $tuple["value"] ?? null, $tuple["dbType"] ?? null, $tuple["direction"] ?? null);
	}

	/**
	 * Normalizes the specified parameter name.
	 * @param string $name The parameter name.
	 * @return string The normalized parameter name.
	 * @internal
	 */
	public static function normalizeName(string $name): string {
		return !mb_strlen($name) ? "?" : (in_array($name[0], self::$prefixes) ? $name : ":$name");
	}
}
