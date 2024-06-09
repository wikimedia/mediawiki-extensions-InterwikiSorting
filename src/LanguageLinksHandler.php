<?php

namespace InterwikiSorting;

use MediaWiki\Config\Config;
use MediaWiki\Output\Hook\LanguageLinksHook;
use MediaWiki\Title\Title;

/**
 * @license GPL-2.0-or-later
 */
class LanguageLinksHandler implements LanguageLinksHook {

	/**
	 * @var InterwikiSorter
	 */
	private $interwikiSorter;

	/**
	 * @param Config $config
	 */
	public function __construct(
		Config $config
	) {
		$this->interwikiSorter = new InterwikiSorter(
			$config->get( 'InterwikiSortingSort' ),
			$config->get( 'InterwikiSortingInterwikiSortOrders' ),
			$config->get( 'InterwikiSortingSortPrepend' )
		);
	}

	/**
	 * Sort Interwiki links according to predefined config settings
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/LanguageLinks
	 *
	 * @param Title $title
	 * @param string[] &$languageLinks
	 * @param array &$linkFlags
	 * @return void
	 */
	public function onLanguageLinks( $title, &$languageLinks, &$linkFlags ): void {
		// this hook tries to access repo SiteLinkTable
		// it interferes with any test that parses something, like a page or a message
		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			return;
		}

		$languageLinks = $this->interwikiSorter->sortLinks( $languageLinks );
	}

}
