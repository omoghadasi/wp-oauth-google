<?php
/**
 * Plugin Name: OAuth Omoghadasi
 * Description: Login With Google
 */
function login_with_google( $clientID, $clientSecret, $redirectUri, $aTagClass, $aTagContent ) {

    require( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );

    $client = new Google_Client();
    $client->setApplicationName( 'Login to CodexWorld.com' );
    $client->setClientId( $clientID );
    $client->setClientSecret( $clientSecret );
    $client->setRedirectUri( $redirectUri );
    $client->addScope( "email" );
    $client->addScope( "profile" );
    $google_oauth = new Google_Service_Oauth2( $client );

    if ( isset( $_GET['code'] ) ) {
        $token = $client->fetchAccessTokenWithAuthCode( $_GET['code'] );
        $client->setAccessToken( $token );
        if ( array_key_exists( 'error', $token ) ) {
            throw new Exception( join( ',', $token ) );
        }

        // get profile info
        $google_account_info = $google_oauth->userinfo->get();
        $email=$google_account_info->email;
        $fname=$google_account_info->givenName;
        $lname=$google_account_info->familyName;

    } else {
        $authLink = $client->createAuthUrl();
        echo '<a class="' . $aTagClass . '" href="' . $authLink . '">' . $aTagContent . '</a>';
    }

    return $google_account_info;
}

function gmail_connect_menu_item() {
    add_options_page( 'Oauth', 'Oauth', 'manage_options', 'gmail-connect-menu-item', 'gmail_connect_page' );
}

add_action( 'admin_menu', 'gmail_connect_menu_item' );

function gmail_connect_page() {
    ?>
    <div class="wrap">
        <h1>salam</h1>
        <?php
        $clientID     = '474732213240-cplcrnah1trv9lns146dvl78fh7dril8.apps.googleusercontent.com';
        $clientSecret = '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';
        $redirectUri  = 'http://127.0.0.1:3020/wp-admin/options-general.php?page=gmail-connect-menu-item';
        $aClass       = 'gmail-login';
        $aContent     = '<i class="icon-google"></i>salam';
        $data         = login_with_google( $clientID, $clientSecret, $redirectUri, $aClass, $aContent );
        echo "<pre>";
        print_r( $data);
        echo "</pre>";
        ?>
    </div>
    <?php
}

?>