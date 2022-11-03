<?php
global $pedal_db;
// a helper function to lookup "env_FILE", "env", then fallback
// if (!function_exists('getenv_docker')) 
// {
// 	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
// 	function getenv_docker($env, $default) 
//     {
// 		if ($fileEnv = getenv($env . '_FILE')) 
//         {
// 			return rtrim(file_get_contents($fileEnv), "\r\n");
// 		}
// 		else if (($val = getenv($env)) !== false) 
//         {
// 			return $val;
// 		}
// 		else 
//         {
// 			return $default;
// 		}
// 	}
// }

/**
 * First, let's set the maximum content width based on the theme's design and stylesheet.
 * This will limit the width of all uploaded images and embeds.
 */
if ( ! function_exists( 'myfirsttheme_setup' ) )
{
    /**
    * Sets up theme defaults and registers support for various WordPress features
    *
    *  It is important to set up these functions before the init hook so that none of these
    *  features are lost.
    *
    *  @since MyFirstTheme 1.0
    */
    function myfirsttheme_setup()
    {
        
    }
}
add_action( 'after_setup_theme', 'myfirsttheme_setup' );

function prefix_register_pedal_rest_routes()
{
    require get_stylesheet_directory() . '/classes/class-pedal-rest-controller.php';
    $controller = new Pedal_REST_Controller();
    $controller->register_routes();
}

add_action( 'rest_api_init', 'prefix_register_pedal_rest_routes' );

function enqueue_scripts()
{
    wp_enqueue_script( 'my_script', 'path/to/my/script', array( 'wp-api' ) );
}

add_action( 'wp_enqueue_scripts', 'enqueue_scripts');

// function connect_pedal_db()
// {
//     require get_stylesheet_directory() . '/classes/class-pedaldb.php';
//     $pedal_db = new PedalDB('pedal_db', 'Pedal', 'SA', 'Passw0rd');
//     return $pedal_db;    
// }

// function test_pedal_db( $db )
// {
//     $num_tickets = $db->get_num_tickets();
//     echo "<p>Number of Tickets: ".$num_tickets."</p>";

//     $result = $db->get_tickets_summary();
//     foreach ( $result as $row)
//     {
//         echo "<p>Ticket Number: ".$row->ticket_number." - ".$row->first_name." ".$row->last_name."</p>";
//     }
// }