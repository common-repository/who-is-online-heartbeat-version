<?php

class UsersOnLine {
	var $currentlyLoggedIn;
	var $currentUser;
	
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}
	
	function init() {
		$this->currentUser = wp_get_current_user();
		if ( $this->currentUser instanceof WP_User) {
			
			$this->currentlyLoggedIn = get_option( 'currently_logged_in' );
			
			if ( ! is_array( $this->currentlyLoggedIn ) )
				$this->currentlyLoggedIn = array();
			
			$this->cleanup();
			$this->update();

			wp_enqueue_script( 'heartbeat' );
			
			add_filter( 'heartbeat_received', array( $this, 'update' ) );
			add_action( 'wp_footer', array( $this, 'doJS' ) );
			add_action( 'admin_footer', array( $this, 'doJS' ) );
			
			add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 99 );
		}
	}
	
	function update( $response = array(), $data = array() ) {
		if ( $this->currentUser instanceof WP_User) {
			$this->currentlyLoggedIn[ $this->currentUser->user_login ] = time();
			update_option( 'currently_logged_in', $this->currentlyLoggedIn );

			$response[ 'logged_in_users' ] = $this->currentlyLoggedIn;
		}
		return $response;
	}
	
	function cleanup() {
		foreach( $this->currentlyLoggedIn as $user => $lastTime ) {
			if ( time() - $lastTime > 30 )
				unset( $this->currentlyLoggedIn[ $user ] );
		}
	}
	
	function doJS() {
		?>
		<script>
		(function( $ ) {
			$( document ).on( 'heartbeat-tick', function( e, data ) {
				var ul = $( '#wp-admin-bar-users_online ul' );
				ul.html( '' );
				for (var key in data[ 'logged_in_users' ] ) {
					if ( data[ 'logged_in_users' ].hasOwnProperty(key) ) {
						html = '<li><div class="ab-item ab-empty-item">' + key + '</div></li>';
						ul.append( html );
					}
				};
			});
		}( jQuery ));
		</script>
		<?php
	}
	
	function admin_bar( $wp_admin_bar ) {
		$args = array(
			'id'    => 'users_online',
			'title' => 'Active Users',
		);
		$wp_admin_bar->add_node( $args );
		
		foreach( $this->currentlyLoggedIn as $user => $time ) {
			$args = array(
				'id'    => 'users_online_' . $user,
				'parent' => 'users_online',
				'title' => $user,
			);
			$wp_admin_bar->add_node( $args );
		}
	}
}
$whoIsOnlineHeartbeat = new UsersOnLine();

function wiohb_get_active_users() {
	global $whoIsOnlineHeartbeat;

	return array_keys( $whoIsOnlineHeartbeat->currentlyLoggedIn );
}