<?php

namespace InterwikiSorting\Tests;

use Hooks;
use InterwikiSorting\InterwikiSortingHooks;
use MediaWiki;
use MediaWikiTestCase;

/**
 * @covers InterwikiSorting\InterwikiSortingHooks
 *
 * @license GPL-2.0+
 * @author Addshore
 */
class InterwikiSortingHooksTest extends MediaWikiTestCase {

	public function testHooksAreCorrectlyRegistered() {
		$expectedHook = InterwikiSortingHooks::class. '::onContentAlterParserOutput';

		// Make sure that the hook has been registered and is at the end of the list.
		$onContentAlterParserOutputHooks = Hooks::getHandlers( 'ContentAlterParserOutput' );
		$this->assertContains( $expectedHook, $onContentAlterParserOutputHooks );
		$this->assertEquals(
			$expectedHook,
			end( $onContentAlterParserOutputHooks ),
			'Hook should be the last to be fired'
		);

	}

}
