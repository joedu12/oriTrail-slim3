<?php
namespace App\PagesControllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator as v;
use App\Models\Course;
use App\Models\Resultat;
use App\Models\BaliseResultat;

class ResultatController extends Controller {

	public function getCourse(RequestInterface $request, ResponseInterface $response) {
		$courses = Course::all();

		$this->render($response, 'pages/resultat/course.twig', [
  			'courses' => $courses
		]);
	}

	public function getResultat(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$resultats = Resultat::where('fk_course', $id)->get();

		$this->render($response, 'pages/resultat/resultat.twig', [
				'resultats' => $resultats,
    ]);
	}

	public function getRun(RequestInterface $request, ResponseInterface $response) {
		$id = $request->getAttribute('id');

		$resultat = Resultat::where('id_resultat', $id)->first();

		$balises = BaliseResultat::where('fk_resultat', $id)->with('balisesCourse')->get();

		$this->render($response, 'pages/resultat/run.twig', [
				'resultat' => $resultat,
				'balises' => $balises
    ]);
	}
}
