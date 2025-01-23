<?php
namespace App\Service;

use App\Model\Points;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ShipXDataProvider
{
	private string $baseUrl = 'https://api-shipx-pl.easypack24.net/v1/';

	public function __construct(
		private readonly SerializerInterface $serializer,
		private readonly HttpClientInterface $client,
	) {}

	public function getData( string $resourceName, array $params): Points
	{
		return $this->serializer->deserialize(
			$this->fetchContent( $this->getUrl( $resourceName, $params)),
			Points::class,
			'json'
		);
	}

	private function getUrl( string $resourceName, array $params): string
	{
		return $this->baseUrl . $resourceName . '?' . http_build_query( $params);
	}

	private function fetchContent( string $url): string
	{
		$response = $this->client->request( 'GET', $url, [
			'headers' => [
				'Accept' => 'application/json',
			],
		]);

		return $response->getContent();
	}
}