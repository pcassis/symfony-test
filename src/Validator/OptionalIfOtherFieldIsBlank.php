<?php
namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraints\NotBlank;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class OptionalIfOtherFieldIsBlank extends NotBlank
{
	#[HasNamedArguments]
	public function __construct(
		public string $field,
		?array $groups = null,
		mixed $payload = null,
	) {
		parent::__construct([], null, null, null, $groups, $payload);
	}
}