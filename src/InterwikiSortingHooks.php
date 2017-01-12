<?php

namespace InterwikiSorting;

use Content;
use ParserOutput;
use Title;

class InterwikiSortingHooks {

	/**
	 * @param Content $content
	 * @param Title $title
	 * @param ParserOutput $parserOutput
	 */
	public static function onContentAlterParserOutput(
		Content $content,
		Title $title,
		ParserOutput $parserOutput
	) {
		// this hook tries to access repo SiteLinkTable
		// it interferes with any test that parses something, like a page or a message
		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			return;
		}

		$handler = InterwikiSortingHookHandlers::newFromGlobalState();
		$handler->doContentAlterParserOutput( $title, $parserOutput );
	}

	public static function onBeforeInitialize(
		/* Deliberately ignore all params ( We dont need them ) */
	) {
		global $wgHooks;

		/**
		 * The ContentAlterParserOutput hook is registered in the BeforeInitialize so that
		 * the sorting of interwiki links is always done after anything else might change the
		 * ParserOutput.
		 * Hooks::register() can not be used due to the array_merge in Hooks::getHandlers()
		 * which will return hooks in $wgHooks last.
		 */
		$wgHooks['ContentAlterParserOutput'][] = self::class . '::onContentAlterParserOutput';
	}

}
