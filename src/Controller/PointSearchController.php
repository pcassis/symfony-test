<?php

namespace App\Controller;

use App\Model\AddressQuery;
use App\Service\ShipXDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PointSearchController extends AbstractController
{
	private FormInterface $form;
	private array $viewParams = [];

	#[Route('/point/search', name: 'app_point_search')]
	public function index(
		Request           $request,
		ShipXDataProvider $provider,
	): Response
	{
		$this->buildForm();

		$this->form->handleRequest( $request );

		if ($this->form->isSubmitted() && $this->form->isValid()) {
			$this->handleForm( $provider );
		}

		return $this->render( 'point-search.html.twig', $this->viewParams );
	}

	private function buildForm(): void
	{
		$query = new AddressQuery();
		$formBuilder = $this->createFormBuilder( $query )
			->add( 'street', TextType::class, [ 'required' => false, 'label' => 'Ulica' ] )
			->add( 'postcode', TextType::class, [ 'required' => false, 'label' => 'Kod pocztowy' ] )
			->add( 'city', TextType::class, [ 'label' => 'Miasto' ] )
			->add( 'name', HiddenType::class )
			->add( 'save', SubmitType::class, [ 'label' => 'Szukaj' ] );


		$formBuilder->get( 'city' )->addModelTransformer( new CallbackTransformer(
			fn( $value ) => $value,
			fn( $value ) => mb_convert_case( $value, MB_CASE_TITLE ),
		) );

		$formBuilder->addEventListener( FormEvents::PRE_SUBMIT, $this->preSubmit(...) );

		$this->form = $formBuilder->getForm();
		$this->viewParams['form'] = $this->form;
	}

	private function preSubmit( FormEvent $event ): void
	{
		$data = $event->getData();

		if ($data['postcode'] === '01-234') {
			$event->getForm()
				->add( 'name', TextType::class, [ 'required' => false, 'label' => 'Nazwa' ] );
		}
	}

	private function handleForm( ShipXDataProvider $provider ): void
	{
		/** @var AddressQuery $data */
		$data = $this->form->getData();

		$city = $data->getCity();
		if (!empty( $city )) {
			$this->viewParams['points'] = $provider->getData(
				ShipXDataProvider::RESOURCE_POINTS,
				[ 'city' => $city ]
			);
		}
	}
}
