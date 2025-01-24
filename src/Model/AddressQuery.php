<?php

namespace App\Model;

use App\Validator\OptionalIfOtherFieldIsBlank;
use Symfony\Component\Validator\Constraints as Assert;

class AddressQuery
{
	public function __construct(
		#[Assert\Length(min: 3, max: 64)]
		private string  $city = '',

		#[
			Assert\Regex(pattern: '/^\d{2}-\d{3}$/'),
			OptionalIfOtherFieldIsBlank(field: 'street')
		]
		private ?string $postcode = null,

		#[Assert\Length(min: 3, max: 64)]
		private ?string $street = null,

		private ?string $name = null,
	)
	{}

	public function getCity(): string
	{
		return $this->city;
	}

	public function setCity( string $city ): void
	{
		$this->city = $city;
	}

	public function getPostcode(): ?string
	{
		return $this->postcode;
	}

	public function setPostcode( string $postcode ): void
	{
		$this->postcode = $postcode;
	}

	public function getStreet(): ?string
	{
		return $this->street;
	}

	public function setStreet( string $street ): void
	{
		$this->street = $street;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName( ?string $name ): void
	{
		$this->name = $name;
	}
}