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
		$initHook = InterwikiSortingHooks::class . '::onBeforeInitialize';
		$finalHook = InterwikiSortingHooks::class. '::onContentAlterParserOutput';

		// Make sure the first hook has been registered
		$this->assertContains( $initHook, Hooks::getHandlers( 'BeforeInitialize' ) );

		// Fire the init hook which should register the second hook.
		// In PHP7 we could just do $initHook();
		InterwikiSortingHooks::onBeforeInitialize();

		// Make sure that the hook has been registered and is at the end of the list.
		$onContentAlterParserOutputHooks = Hooks::getHandlers( 'ContentAlterParserOutput' );
		$this->assertContains( $finalHook, $onContentAlterParserOutputHooks );
		$this->assertEquals(
			$finalHook,
			end( $onContentAlterParserOutputHooks ),
			'Hook should be the last to be fired'
		);

	}

}
