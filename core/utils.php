<?php

class Utils{


	public static function pluralize($str){
		$plural = array(
            array( '/(quiz)$/i',               "$1zes"   ),
	        array( '/^(ox)$/i',                "$1en"    ),
	        array( '/([m|l])ouse$/i',          "$1ice"   ),
	        array( '/(matr|vert|ind)ix|ex$/i', "$1ices"  ),
	        array( '/(x|ch|ss|sh)$/i',         "$1es"    ),
	        array( '/([^aeiouy]|qu)y$/i',      "$1ies"   ),
	        array( '/([^aeiouy]|qu)ies$/i',    "$1y"     ),
            array( '/(hive)$/i',               "$1s"     ),
            array( '/(?:([^f])fe|([lr])f)$/i', "$1$2ves" ),
            array( '/sis$/i',                  "ses"     ),
            array( '/([ti])um$/i',             "$1a"     ),
            array( '/(buffal|tomat)o$/i',      "$1oes"   ),
            array( '/(bu)s$/i',                "$1ses"   ),
            array( '/(alias|status)$/i',       "$1es"    ),
            array( '/(octop|vir)us$/i',        "$1i"     ),
            array( '/(ax|test)is$/i',          "$1es"    ),
            array( '/s$/i',                    "s"       ),
            array( '/$/',                      "s"       )
        );

		foreach ( $plural as $pattern ){
        if ( preg_match( $pattern[0], $str ) )
            return preg_replace( $pattern[0], $pattern[1], $str );
        }
	}
}