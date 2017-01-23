<?php

namespace InterwikiSorting;

use MediaWiki\MediaWikiServices;
use ParserOutput;
use Title;

class InterwikiSortingHookHandlers {

	/**
	 * @var InterwikiSorter
	 */
	private $interwikiSorter;

	/**
	 * @var bool
	 */
	private $alwaysSort;

	public static function newFromGlobalState() {
		$config = MediaWikiServices::getInstance()->getMainConfig();

		return new InterwikiSortingHookHandlers(
			new InterwikiSorter(
				$config->get( 'InterwikiSortingSort' ),
				$config->get( 'InterwikiSortingInterwikiSortOrders' ),
				$config->get( 'InterwikiSortingSortPrepend' )
			),
			$config->get( 'InterwikiSortingAlwaysSort' )
		);
	}

	/**
	 * @param InterwikiSorter $sorter
	 * @param boolean $alwaysSort
	 */
	public function __construct(
		InterwikiSorter $sorter,
		$alwaysSort
	) {
		$this->interwikiSorter = $sorter;
		$this->alwaysSort = $alwaysSort;
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
		if ( $this->alwaysSort ) {
			$interwikiLinks = $parserOutput->getLanguageLinks();
			$sortedLinks = $this->interwikiSorter->sortLinks( $interwikiLinks );
			$parserOutput->setLanguageLinks( $sortedLinks );
		}

		return true;
	}

}
