<?php
class ZD_Helper {
	
 /*--------------------------------------------------------------------------------------
	*
	* When
	*
	*-------------------------------------------------------------------------------------*/	
	public static function when( $time ) {
        // Get current timestamp.
		$now = strtotime( 'now' );
	 
		// Get timestamp when tweet created.
		$created = strtotime( $time );
	 
		// Get difference.
		$difference = $now - $created;
	 
		// Calculate different time values.
		$minute = 60;
		$hour   = $minute * 60;
		$day    = $hour * 24;
		$week   = $day * 7;
	 
		if ( is_numeric( $difference ) && $difference > 0 ) :
	 
			// If less than 3 seconds.
			if ( $difference < 3 ) :
				return __( 'right now', 'zd' );
			endif;
	 
			// If less than minute.
			if ( $difference < $minute ) :
				return floor( $difference ) .' s';
			endif;
	 
			// If less than hour.
			if ( $difference < $hour ) :
				return floor( $difference / $minute ) .' min';
			endif;
			
			// If less than day.
			if ( $difference < $day ) :
				return floor( $difference / $hour ) .' h';
			endif;
			
			// If less than year.
			if ( $difference < $day * 365 ) :
				return floor( $difference / $day ) .' d';
			endif;
	 
			// Else return more than a year.
			return __( 'over a year ago', 'zd' );
		endif;
    }


}
