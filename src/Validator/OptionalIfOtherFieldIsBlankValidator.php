<?php
namespace App\Validator;

use App\Model\AddressQuery;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\ValidatorException;

class OptionalIfOtherFieldIsBlankValidator extends NotBlankValidator
{
	public function validate(mixed $value, Constraint $constraint): void
	{
		if (!$constraint instanceof OptionalIfOtherFieldIsBlank) {
			throw new UnexpectedTypeException( $constraint, OptionalIfOtherFieldIsBlank::class);
		}

		/** @var AddressQuery $data */
		$data = $this->context->getObject();
		$getter = 'get' . ucfirst( $constraint->field);
		if (!method_exists( $data, $getter)) {
			throw new ValidatorException( 'OptionalIfOtherFieldIsBlank validation error (getter for field "' . $constraint->field . '" not found)' );
		}

		if (empty( $data->$getter())) {
			return;
		}

		parent::validate( $value, $constraint);
	}
}