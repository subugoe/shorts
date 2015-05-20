<?php
namespace typo3conf\ext\shorts\Tests\Unit\Service;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Ingo Pfennigstorf <pfennigstorf@sub-goettingen.de>
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
use Subugoe\Shorts\Service\ShorteningService;
use TYPO3\CMS\Core\Tests\BaseTestCase;

/**
 * Test for shortening service
 */
class ShorteningServiceTest extends BaseTestCase {

	/**
	 * @var ShorteningService
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getMockBuilder(ShorteningService::class)
			->disableOriginalConstructor()
			->setMethods(['isUniqueHash'])
			->getMock();
	}

	/**
	 * @test
	 */
	public function configuredParametersAreRemoved() {
		$parameterList = 'tx_subforms_feedback[pageId], tx_subforms_feedback[action], tx_subforms_feedback[controller], noCache, tx_solr[q], tx_subtabs_tabs[__referrer], tx_solr[filter]';
		$url = 'index.php?id=1616&tx_solr[q]=sheytan';

		$this->assertSame('index.php?id=1616', $this->fixture->removeConfiguredParametersFromString($url, $parameterList));
	}

	/**
	 * @test
	 */
	public function noParametersAreRemovedBecauseTheyDontExist() {
		$parameterList = 'tx_subforms_feedback[pageId], tx_subforms_feedback[action], tx_subforms_feedback[controller], noCache, tx_solr[q], tx_subtabs_tabs[__referrer], tx_solr[filter]';
		$url = 'index.php?id=1616';

		$this->assertSame('index.php?id=1616', $this->fixture->removeConfiguredParametersFromString($url, $parameterList));
	}

	/**
	 * @test
	 */
	public function multipleConfiguredParametersAreRemoved() {
		$parameterList = 'tx_subforms_feedback[pageId], tx_subforms_feedback[action], tx_subforms_feedback[controller], noCache, tx_solr[q], tx_subtabs_tabs[__referrer], tx_solr[filter]';
		$url = 'index.php?id=1616&tx_solr[q]=sheytan&tx_subforms_feedback[action]=action';

		$this->assertSame('index.php?id=1616', $this->fixture->removeConfiguredParametersFromString($url, $parameterList));
	}

	/**
	 * @test
	 */
	public function simpleCharactersWithoutBracketsAreRemoved() {
		$parameterList = 'tx_subforms_feedback[pageId], tx_subforms_feedback[action], tx_subforms_feedback[controller], noCache, tx_solr[q], tx_subtabs_tabs[__referrer], tx_solr[filter], q';
		$url = 'index.php?id=1616&q=sheytan&tx_subforms_feedback[action]=action';

		$this->assertSame('index.php?id=1616', $this->fixture->removeConfiguredParametersFromString($url, $parameterList));
	}

	/**
	 * @test
	 */
	public function cHashParameterIsRemovedFromUrl() {
		$url = 'index.php?id=1616&cHash=' . md5(microtime());
		$this->assertSame('index.php?id=1616', $this->fixture->removeChashParamaterFromString($url));

	}

	/**
	 * @test
	 */
	public function cHashParameterIsRemovedFromUrlWhenThereAreOtherParameters() {
		$url = 'index.php?id=1616&tx_solr[q]=sheytan&tx_subforms_feedback[action]=action&cHash=' . md5(microtime());

		$this->assertSame('index.php?id=1616&tx_solr[q]=sheytan&tx_subforms_feedback[action]=action', $this->fixture->removeChashParamaterFromString($url));
	}

	/**
	 * @test
	 */
	public function hashWithLengthFiveIsGenerated() {
		$this->fixture->method('isUniqueHash')->willReturn(TRUE);
		$this->assertSame(5, strlen($this->fixture->generateHash()));
	}

	/**
	 * @test
	 */
	public function aNewHashIsGeneratedOnCollisions() {
		$this->fixture->method('isUniqueHash')->will($this->onConsecutiveCalls(FALSE, TRUE));
		$this->assertSame(5, strlen($this->fixture->generateHash()));
	}

}
