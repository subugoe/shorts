<?php
namespace Subugoe\Shorts\Tests\Unit\Controller;
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Ingo Pfennigstorf <pfennigstorf@sub-goettingen.de>
 *      Goettingen State Library
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Tests for shortening service
 */
class ShorteningServiceTest extends \TYPO3\CMS\Core\Tests\Unit\Resource\BaseTestCase {

    protected $fixture;

	public function setUp() {
		$className = 'Subugoe\\Shorts\\Service\\ShorteningService';
		$this->fixture = new $className;
	}

	/**
	 * @test
	 * @return void
	 */
	public function testCHashRemovalFunctionIfSomethingHappensWhenChashIsSet() {

		$url = 'index.php?id=3&cHash=2343483sefsdf&anotherParam=egtt45rwsfvcty6';
		$cleaner = $this->fixture->removeChashParamaterFromString($url);

		$this->assertNotEquals($url, $cleaner);
		$this->assertNotNull($cleaner);
	}

	/**
	 * @test
	 */
	public function testCHashRemovalIfQuestionMarkIsUrlParameter() {
		$url = '/foo/bar/?cHash=2343483sefsdf&anotherParam=egtt45rwsfvcty6';
		$cleaner = $this->fixture->removeChashParamaterFromString($url);

		$this->assertNotEquals($url, $cleaner);
		$this->assertNotNull($cleaner);
	}

	/**
	 * @test
	 * @return void
	 */
	public function testOtherParameterRemovalByCHashRemoval() {

		$url = 'index.php?id=1197&anotherParam=egtt45rwsfvcty6';
		$cleaner = $this->fixture->removeChashParamaterFromString($url);

		$this->assertNotEmpty($cleaner);

		$this->assertEquals($url, $cleaner);
	}

}
