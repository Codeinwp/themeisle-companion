<?php
/**
 * Compatibility helpers for legacy SVG exports.
 *
 * @package Orbit_Fox
 */

/**
 * Normalizes legacy SVG namespace references before sanitization.
 */
class Orbit_Fox_Svg_Compatibility {

	/**
	 * Replace the fixed namespace entities used by older Adobe SVG exports.
	 *
	 * The sanitizer removes DTD declarations before parsing. Resolving only this
	 * fixed namespace allowlist in exact attribute values keeps those exports
	 * readable without processing document-defined entities.
	 *
	 * @param string $svg SVG markup.
	 *
	 * @return string
	 */
	public static function resolve_legacy_namespace_entities( $svg ) {
		if ( false === stripos( $svg, '&ns_' ) ) {
			return $svg;
		}

		$namespaces = array(
			'ns_svg'         => 'http://www.w3.org/2000/svg',
			'ns_xlink'       => 'http://www.w3.org/1999/xlink',
			'ns_extend'      => 'http://ns.adobe.com/Extensibility/1.0/',
			'ns_ai'          => 'http://ns.adobe.com/AdobeIllustrator/10.0/',
			'ns_graphs'      => 'http://ns.adobe.com/Graphs/1.0/',
			'ns_vars'        => 'http://ns.adobe.com/Variables/1.0/',
			'ns_imrep'       => 'http://ns.adobe.com/ImageReplacement/1.0/',
			'ns_sfw'         => 'http://ns.adobe.com/SaveForWeb/1.0/',
			'ns_custom'      => 'http://ns.adobe.com/GenericCustomNamespace/1.0/',
			'ns_adobe_xpath' => 'http://ns.adobe.com/XPath/1.0/',
		);

		$output   = '';
		$offset   = 0;
		$length   = strlen( $svg );
		$sections = array(
			'<!--'      => '-->',
			'<![CDATA[' => ']]>',
			'<?'        => '?>',
		);

		while ( true ) {
			$start = strpos( $svg, '<', $offset );
			if ( false === $start ) {
				break;
			}

			$output .= substr( $svg, $offset, $start - $offset );

			foreach ( $sections as $opener => $closer ) {
				$opener_length = strlen( $opener );
				if ( $opener !== substr( $svg, $start, $opener_length ) ) {
					continue;
				}

				$closer_length = strlen( $closer );
				$end           = strpos( $svg, $closer, $start + $opener_length );
				if ( false === $end ) {
					return $output . substr( $svg, $start );
				}

				$end    += $closer_length;
				$output .= substr( $svg, $start, $end - $start );
				$offset  = $end;
				continue 2;
			}

			$is_doctype = 0 === strncasecmp( substr( $svg, $start, 9 ), '<!DOCTYPE', 9 );
			$is_markup  = isset( $svg[ $start + 1 ] ) && ( '!' === $svg[ $start + 1 ] || '/' === $svg[ $start + 1 ] );
			$end        = self::find_markup_end( $svg, $start + 1, $is_doctype );
			if ( false === $end ) {
				return $output . substr( $svg, $start );
			}

			$markup = substr( $svg, $start, $end - $start + 1 );
			if ( ! $is_doctype && ! $is_markup ) {
				$markup = self::resolve_attribute_entities( $markup, $namespaces );
			}

			$output .= $markup;
			$offset  = $end + 1;
		}

		return $output . substr( $svg, $offset, $length - $offset );
	}

	/**
	 * Find the closing bracket for a tag or declaration without resolving it.
	 *
	 * @param string $markup        Full SVG markup.
	 * @param int    $offset        Scan offset after the opening bracket.
	 * @param bool   $track_brackets Whether to track a DTD internal subset.
	 *
	 * @return int|false
	 */
	private static function find_markup_end( $markup, $offset, $track_brackets = false ) {
		$length = strlen( $markup );
		$depth  = 0;
		$quote  = '';

		for ( $index = $offset; $index < $length; $index++ ) {
			$character = $markup[ $index ];
			if ( '' !== $quote ) {
				if ( $character === $quote ) {
					$quote = '';
				}
				continue;
			}

			if ( $track_brackets && '<!--' === substr( $markup, $index, 4 ) ) {
				$comment_end = strpos( $markup, '-->', $index + 4 );
				if ( false === $comment_end ) {
					return false;
				}
				$index = $comment_end + 2;
				continue;
			}

			if ( '"' === $character || "'" === $character ) {
				$quote = $character;
				continue;
			}

			if ( $track_brackets && '[' === $character ) {
				$depth++;
				continue;
			}

			if ( $track_brackets && ']' === $character && $depth > 0 ) {
				$depth--;
				continue;
			}

			if ( '>' === $character && 0 === $depth ) {
				return $index;
			}
		}

		return false;
	}

	/**
	 * Resolve allowlisted namespace entity references used as attribute values.
	 *
	 * @param string               $tag        One complete start tag.
	 * @param array<string,string> $namespaces Fixed legacy namespace mapping.
	 *
	 * @return string
	 */
	private static function resolve_attribute_entities( $tag, $namespaces ) {
		$length       = strlen( $tag );
		$offset       = 1;
		$replacements = array();

		while ( $offset < $length && false === strpos( " \t\r\n/>", $tag[ $offset ] ) ) {
			$offset++;
		}

		while ( $offset < $length ) {
			while ( $offset < $length && false !== strpos( " \t\r\n", $tag[ $offset ] ) ) {
				$offset++;
			}

			if ( $offset >= $length || '/' === $tag[ $offset ] || '>' === $tag[ $offset ] ) {
				break;
			}

			if ( 1 !== preg_match( '/\G[^\s\/>=]+/', $tag, $name_match, 0, $offset ) ) {
				$offset++;
				continue;
			}

			$name    = $name_match[0];
			$offset += strlen( $name );
			while ( $offset < $length && false !== strpos( " \t\r\n", $tag[ $offset ] ) ) {
				$offset++;
			}

			if ( $offset >= $length || '=' !== $tag[ $offset ] ) {
				continue;
			}

			$offset++;
			while ( $offset < $length && false !== strpos( " \t\r\n", $tag[ $offset ] ) ) {
				$offset++;
			}

			if ( $offset >= $length || ( '"' !== $tag[ $offset ] && "'" !== $tag[ $offset ] ) ) {
				continue;
			}

			$quote       = $tag[ $offset ];
			$value_start = $offset + 1;
			$value_end   = strpos( $tag, $quote, $value_start );
			if ( false === $value_end ) {
				break;
			}

			$offset = $value_end + 1;
			$value  = substr( $tag, $value_start, $value_end - $value_start );
			if ( 1 !== preg_match( '/^&([A-Za-z_][A-Za-z0-9_.-]*);$/', $value, $entity_match ) ) {
				continue;
			}

			$entity_name = $entity_match[1];
			if ( isset( $namespaces[ $entity_name ] ) ) {
				$replacements[] = array( $value_start, $value_end - $value_start, $namespaces[ $entity_name ] );
			}
		}

		if ( empty( $replacements ) ) {
			return $tag;
		}

		$resolved = '';
		$cursor   = 0;
		foreach ( $replacements as $replacement ) {
			$resolved .= substr( $tag, $cursor, $replacement[0] - $cursor ) . $replacement[2];
			$cursor    = $replacement[0] + $replacement[1];
		}

		return $resolved . substr( $tag, $cursor );
	}
}
