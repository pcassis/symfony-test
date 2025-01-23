<?php
namespace App\Model;

class Points {
	/**
	 * @param int $count
	 * @param int $page
	 * @param int $total_pages
	 * @param PointItem[] $items
	 */
	public function __construct(
		private int   $count,
		private int   $page,
		private int   $total_pages,
		private array $items,
	) {
	}

	public function getCount(): int
	{
		return $this->count;
	}

	public function setCount( int $count ): void
	{
		$this->count = $count;
	}

	public function getPage(): int
	{
		return $this->page;
	}

	public function setPage( int $page ): void
	{
		$this->page = $page;
	}

	public function getTotalPages(): int
	{
		return $this->total_pages;
	}

	public function setTotalPages( int $total_pages ): void
	{
		$this->total_pages = $total_pages;
	}

	/**
	 * @return PointItem[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * @param PointItem[] $items
	 * @return void
	 */
	public function setItems( array $items ): void
	{
		$this->items = $items;
	}
}