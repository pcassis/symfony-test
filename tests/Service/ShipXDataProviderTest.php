<?php

namespace App\Tests\Service;

use App\Model\PointItem;
use App\Model\PointItemAddress;
use App\Model\Points;
use App\Service\ShipXDataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\JsonMockResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ShipXDataProviderTest extends KernelTestCase
{

	public function testGetData()
	{
		$mockResponse = new JsonMockResponse([
			'count' => 1,
			'page' => 2,
			'total_pages' => 3,
			'items' => [
				['name' => 'Poznan', 'address' => ['line1' => 'Testowa 27', 'line2' => '12-345 Poznan']],
			],
		]);
		$mockHttpClient = new MockHttpClient($mockResponse);

		$expect = new Points(
			1,
			2,
			3,
			[
				new PointItem(
					'Poznan',
					new PointItemAddress( 'Testowa 27', '12-345 Poznan')
				)
			]
		);

		$provider = new ShipXDataProvider(
			self::getContainer()->get( SerializerInterface::class),
			$mockHttpClient
		);
		$data = $provider->getData( 'points', ['city' => 'Poznan']);
		$this->assertEquals( $expect, $data);
	}
}
