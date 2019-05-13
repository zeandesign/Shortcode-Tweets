<?php

require_once ( dirname( __FILE__ ) . '/TwitterAPIExchange.php' );
require_once ( dirname( __FILE__ ) . '/class-zd-helper.php' );

add_shortcode( 'tweets', 'wp_tweets' );

/*--------------------------------------------------------------------------------------
     *
     * Tweets
     *
     *-------------------------------------------------------------------------------------*/
    function wp_tweets( $atts, $content = null ) {
        $atts = shortcode_atts( array(
            'class'                     => false,
			'oauth_access_token'        => false,
            'oauth_access_token_secret' => false,
            'consumer_key'              => false,
            'consumer_secret'           => false,
			'screen_name'               => 'twitterapi',
			'count'                     => 5
        ), $atts );
        
        $class   = array();
		$class[] = 'tweet-item';
		$class[] = ( $atts['class'] ) ? $atts['class'] : null;

		$settings = array(
			'oauth_access_token'        => $atts['oauth_access_token'],
			'oauth_access_token_secret' => $atts['oauth_access_token_secret'],
			'consumer_key'              => $atts['consumer_key'],
			'consumer_secret'           => $atts['consumer_secret']
		);
	 
		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name='. $atts['screen_name'] .'&count='. $atts['count'];
		$request_method = 'GET';
		 
		$twitter_instance = new TwitterAPIExchange( $settings );
		 
		$query = $twitter_instance
			->setGetfield( $getfield )
			->buildOauth( $url, $request_method )
			->performRequest();
		 
		$timelines = json_decode($query);
		
	    $out = '';
		if ( $timelines ) :
 
			// Add links to URL and username mention in tweets.
			$patterns = array( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '/@([A-Za-z0-9_]{1,15})/' );
			$replace = array( '<a href="$1">$1</a>', '<a href="http://twitter.com/$1">@$1</a>' );
	 
			foreach ( $timelines as $timeline ) :
				$result = preg_replace( $patterns, $replace, $timeline->text );
	 
				$out .= '<div class="'. implode( ' ', array_filter( $class ) ) .'">';
				$out .= '<div class="tweet-item-content">'. $result .'</div>';
				$out .= '<div class="tweet-item-time">'. ZD_Helper::when( $timeline->created_at ) .'</div>';
				$out .= '</div>';
			
			endforeach;
	 
		else :
			$out .= _e( 'Error fetching feeds. Please verify the Twitter settings in the widget', 'zd' ) .'.';
		endif;
		
		return $out;
		
		// Example Shortcode [tweets oauth_access_token="35760472-LdtmFaDQeDrMd3Ypc6CYfDvn6HZKz3Qu7OwhQGtEO" oauth_access_token_secret="229bnrLc80tuZ0l46htDnmT1l0hQTgxgzS1pIa7iCfExT" consumer_key="IyHBSB2gZRFuaEO2XQJUzPgUb" consumer_secret="E9Cj4Jom5ncfpurXBT8rxvOgxSCaceb4pCiOrz9M941ghE2b8Z" screen_name="zeandesign" count="5"]
    }
