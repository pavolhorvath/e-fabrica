<?php

namespace app\controllers;

use system\Response;
use system\Validator;
use system\View;
use app\models\Employee;
/**
 * Class AppController
 */
class AppController
{
	public function index ()
	{
		return View::init()->render('index');
	}

	public function getEmployeesList ()
	{
		$employees = Employee::get();
		return Response::init()->json($employees)->send();
	}

	public function employeeUpdate ($id, $firstName, $lastName, $phone, $email, $birthDate, $employmentDate)
	{
		$validationErrors = Validator::init()
			->setVariable($id, 'ID zamestnanca')->isInteger()->custom('Employee', 'exist')
			->setVariable($firstName, 'Krstné meno')->notEmpty("Krstné meno musí byť vyplnené.")
			->setVariable($lastName, 'Priezvisko')->notEmpty("Priezvisko musí byť vyplnené.")
			->setVariable($birthDate, 'Dátum narodenia')->isDate('d.m.Y')
			->setVariable($employmentDate, 'Dátum nástupu')->isDate('d.m.Y')
			->getErrors();
		if ($validationErrors) {
			return Response::init()->badRequest($validationErrors)->send();
		}

		$employee = Employee::get(['id' => $id])
			->set([
				'firstName' => $firstName,
				'lastName' => $lastName,
				'phone' => $phone,
				'email' => $email,
				'birthDate' => $birthDate,
				'employmentDate' => $employmentDate,
			])->update();

		$response = [
			'success' => "Zamestnanec bol upravený.",
			'employee' => $employee,
		];
		return Response::init()->json($response)->send();
	}

	public function employeeAdd ($firstName, $lastName, $phone, $email, $birthDate, $employmentDate)
	{
		$validationErrors = Validator::init()
			->setVariable($firstName, 'Krstné meno')->notEmpty("Krstné meno musí byť vyplnené.")
			->setVariable($lastName, 'Priezvisko')->notEmpty("Priezvisko musí byť vyplnené.")
			->setVariable($birthDate, 'Dátum narodenia')->isDate('d.m.Y')
			->setVariable($employmentDate, 'Dátum nástupu')->isDate('d.m.Y')
			->getErrors();
		if ($validationErrors) {
			return Response::init()->badRequest($validationErrors)->send();
		}

		$employee = new Employee([
				'id' => 0,
				'firstName' => $firstName,
				'lastName' => $lastName,
				'phone' => $phone,
				'email' => $email,
				'birthDate' => $birthDate,
				'employmentDate' => $employmentDate,
			]);
		$employee->add();

		$response = [
			'success' => "Zamestnanec bol vytvorený.",
			'employee' => $employee,
		];
		return Response::init()->json($response)->send();
	}

	public function employeeRemove ($id)
	{
		$validationErrors = Validator::init($id, 'ID zamestnanca')
			->isInteger()->custom('Employee', 'exist')
			->getErrors();
		if ($validationErrors) {
			return Response::init()->badRequest($validationErrors)->send();
		}

		$employee = Employee::get(['id' => $id])
			->remove();

		$response = [
			'success' => 'Zamestnanec bol odstránený.'
		];
		return Response::init()->json($response)->send();
	}
}