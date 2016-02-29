<?php
namespace Subugoe\Shorts\Tests\Unit\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 ***************************************************************/
use Subugoe\Shorts\Controller\UrlController;
use TYPO3\CMS\Core\Tests\BaseTestCase;

/**
 * Test case for class Tx_Shorts_Controller_UrlController
 *
 * @package shorts
 */
class UrlControllerTest extends BaseTestCase
{
    /**
     * @var UrlController
     */
    protected $fixture;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->fixture = $this->getAccessibleMock(UrlController::class, [], []);
    }

    /**
     * @test
     * @return void
     */
    public function testPageIdSetting()
    {
        $this->fixture->pageId = 3;
        $this->assertEquals($this->fixture->pageId, 3);
    }

}
