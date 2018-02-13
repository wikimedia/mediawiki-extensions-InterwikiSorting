<?php

namespace InterwikiSorting;

use MediaWiki\MediaWikiServices;
use ParserOutput;
use Title;

/**
 * @license GPL-2.0-or-later
 */
class InterwikiSortingHookHandlers {

	/**
	 * @var InterwikiSorter
	 */
	private $interwikiSorter;

	public static function newFromGlobalState() {
		$config = MediaWikiServices::getInstance()->getMainConfig();

		return new InterwikiSortingHookHandlers(
			new InterwikiSorter(
				$config->get( 'InterwikiSortingSort' ),
				$config->get( 'InterwikiSortingInterwikiSortOrders' ),
				$config->get( 'InterwikiSortingSortPrepend' )
			)
		);
	}

	/**
	 * @param InterwikiSorter $sorter
	 */
	public function __construct(
		InterwikiSorter $sorter
	) {
		$this->interwikiSorter = $sorter;
	}

	/**
	 * Hook runs after internal parsing
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ContentAlterParserOutput
	 *
	 * @param Title $title
	 * @param ParserOutput $parserOutput
	 *
	 * @return bool
	 */
	public function doContentAlterParserOutput( Title $title, ParserOutput $parserOutput ) {
		$interwikiLinks = $parserOutput->getLanguageLinks();
		$sortedLinks = $this->interwikiSorter->sortLinks( $interwikiLinks );
		$parserOutput->setLanguageLinks( $sortedLinks );

		return true;
	}

}
