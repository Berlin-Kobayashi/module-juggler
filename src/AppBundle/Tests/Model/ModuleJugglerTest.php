<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Document\Module;
use AppBundle\Model\ModuleJuggler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ModuleJugglerTest extends WebTestCase
{

	public function testJuggle()
	{
		$testData = [];

		$testData['A'] = new Module();
		$testData['B'] = new Module();
		$testData['C'] = new Module();
		$testData['D'] = new Module();
		$testData['E'] = new Module();
		$testData['F'] = new Module();
		$testData['G'] = new Module();

		$testData['A']->setId('A');
		$testData['B']->setId('B');
		$testData['C']->setId('C');
		$testData['D']->setId('D');
		$testData['E']->setId('E');
		$testData['F']->setId('F');
		$testData['G']->setId('G');

		$testData['A']->setDependsOn(['E']);
		$testData['B']->setDependsOn(['C']);
		$testData['C']->setDependsOn(['D']);
		$testData['D']->setDependsOn(['A', 'F']);
		$testData['F']->setDependsOn(['G']);

		// test juggling of this structure for 10 random permutations
		for ($i = 0; $i < 10; $i++) {

			$testInput = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

			shuffle($testInput);

			$actual = ModuleJuggler::juggleMap($testData);

			echo 'Testing ModuleJuggler with: ' . implode(',', $testInput) . ' Result: ' . implode(',', $actual) . PHP_EOL;

			$this->assertTrue(array_search('E', $actual) < array_search('A', $actual));
			$this->assertTrue(array_search('C', $actual) < array_search('B', $actual));
			$this->assertTrue(array_search('D', $actual) < array_search('C', $actual));
			$this->assertTrue(array_search('A', $actual) < array_search('D', $actual));
			$this->assertTrue(array_search('F', $actual) < array_search('D', $actual));
			$this->assertTrue(array_search('G', $actual) < array_search('F', $actual));

		}
	}

}
