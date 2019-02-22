<?php
/**
 * Created by PhpStorm.
 * User: manoj
 * Date: 22.02.19
 * Time: 13:34
 */

namespace App\Service;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Class MarkdownHelper
 * @package App\Service
 */
class MarkdownHelper {

	private $cache;

	private $markdown;

	private $logger;

	/**
	 * MarkdownHelper constructor.
	 *
	 * @param AdapterInterface  $cache
	 * @param MarkdownInterface $markdown
	 */
	public function __construct ( AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger ) {
		$this->cache = $cache;
		$this->markdown = $markdown;
		$this->logger = $markdownLogger;
	}

	public function parse ( string $source ): string {

		if ( stripos ( $source, 'bacon' ) !== false ) {
			$this->logger->info ( 'They are talking about bacon again!' );
		}

		$item = $this->cache->getItem ( 'markdown_' . md5 ( $source ) );
		if ( !$item->isHit () ) {
			$item->set ( $this->markdown->transform ( $source ) );
			$this->cache->save ( $item );
		}

		return $item->get ();
	}
}