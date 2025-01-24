<?php
namespace App\Service;

use App\Model\Points;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ShipXDataProvider
{
	const RESOURCE_POINTS = 'points';

	private string $baseUrl = 'https://api-shipx-pl.easypack24.net/v1/';
	private string $url;

	private array $typesMap = [
		self::RESOURCE_POINTS => Points::class,
	];

	public function __construct(
		private readonly SerializerInterface $serializer,
		private readonly HttpClientInterface $client,
		private readonly ?CacheInterface     $cache = null,
	) {}

	public function getData( string $resourceName, array $params): Points
	{
		if (!isset( $this->typesMap[$resourceName])) {
			throw new \InvalidArgumentException( 'Unknown resource name "'.$resourceName.'"');
		}

		$this->url = $this->getUrl( $resourceName, $params);

		$content = ($this->cache) ? $this->cache->get( $this->url, $this->fetchContent(...)) : $this->fetchContent();

		return $this->serializer->deserialize( $content, $this->typesMap[$resourceName], 'json' );
	}

	private function getUrl( string $resourceName, array $params): string
	{
		return $this->baseUrl . $resourceName . '?' . http_build_query( $params);
	}

	private function fetchContent(): string
	{
		$response = $this->client->request( Request::METHOD_GET, $this->url, [
			'headers' => [
				'Accept' => 'application/json',
			],
		]);

		return $response->getContent();
	}
}