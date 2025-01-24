<?php

namespace App\Controller;

use App\Model\AddressQuery;
use App\Service\ShipXDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PointSearchController extends AbstractController
{
	#[Route('/point/search', name: 'app_point_search')]
	public function index(
		Request $request,
		ShipXDataProvider $provider,
	): Response
	{
		$query = new AddressQuery();
		$formBuilder = $this->createFormBuilder($query)
			->add( 'street', TextType::class, ['required' => false])
			->add( 'postcode', TextType::class, ['required' => false])
			->add( 'city', TextType::class)
			->add('save', SubmitType::class, ['label' => 'Search']);

		$formBuilder->get( 'city')->addModelTransformer( new CallbackTransformer(
			fn($value) => $value,
			fn($value) => mb_convert_case( $value, MB_CASE_TITLE),
		));

		$form = $formBuilder->getForm();
		$viewParams = [
			'form' => $form,
		];

		$form->handleRequest( $request);

		if ($form->isSubmitted() && $form->isValid()) {
			/** @var AddressQuery $data */
			$data = $form->getData();

			$viewParams['points'] = $provider->getData(
				ShipXDataProvider::RESOURCE_POINTS,
				['city' => $data->getCity()]
			);
		}

		return $this->render( 'point-search.html.twig', $viewParams);
	}
}
