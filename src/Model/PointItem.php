<?php
namespace App\Model;

class PointItem {
	public function __construct(
		private string $name,
		private PointItemAddress $address,
	) {
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	public function getAddress(): PointItemAddress
	{
		return $this->address;
	}

	public function setAddress( PointItemAddress $address ): void
	{
		$this->address = $address;
	}
}