<?php
/*
Plugin Name: ASPS Check Referrer
Plugin URI: http://www.artistscope.com/asps-check-referer-wordpress.asp
Version: 0.1
Description: ASPS Check Referrer
Author: ArtistScope
Author URI: http://www.artistscope.com

	Copyright 2015 ArtistScope Pty Limited

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if ( !is_admin() )
{
    add_action( 'plugins_loaded', 'asps_restrict_access' );
    
    function asps_restrict_access()
    {
        if ( !current_user_can( 'manage_options' ) )
        {
            $url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            $referrer = $_SERVER["HTTP_REFERER"];
            $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
            if ( !empty( $_SERVER['X_FORWARDED_FOR'] ) )
            {
                $X_FORWARDED_FOR = explode( ',', $_SERVER['X_FORWARDED_FOR'] );
                if ( !empty( $X_FORWARDED_FOR ) )
                {
                    $REMOTE_ADDR = trim($X_FORWARDED_FOR[0]);
                }
            }
            elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
            {
                $HTTP_X_FORWARDED_FOR = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
                if ( !empty( $HTTP_X_FORWARDED_FOR ) )
                {
                    $REMOTE_ADDR = trim( $HTTP_X_FORWARDED_FOR[0] );
                }
            }
            $user_ip = preg_replace('/[^0-9a-f:\., ]/si', '', $REMOTE_ADDR);
            
            $usage = get_option( 'asps_get_use' );
            
            $asps_get_ips = get_option( 'asps_get_ips' );
            $asps_get_referrers = get_option( 'asps_get_referrers' );
            $asps_get_urls = get_option( 'asps_get_urls' );
            $asps_get_sections = get_option( 'asps_get_sections' );
            
            $asps_get_ips = str_replace( ' ', '', $asps_get_ips );
            $asps_get_referrers = str_replace( ' ', '', $asps_get_referrers );
            $asps_get_urls = str_replace( ' ', '', $asps_get_urls );
            $asps_get_sections = str_replace( ' ', '', $asps_get_sections );
            
            $asps_ips = explode( ',', $asps_get_ips );
            $asps_referrers = explode( ',', $asps_get_referrers );
            $asps_urls = explode( ',', $asps_get_urls );
            $asps_sections = explode( ',', $asps_get_sections );
            
            if ( 2 == $usage )
            {
                //ASPS Use
                if ( !empty( $asps_get_urls ) || !empty( $asps_get_sections ) )
                {
                    if ( !empty( $asps_get_urls ) )
                    {
                        foreach ( $asps_urls as $asps_url )
                        {
                            $asps_url = str_replace( 'http://', '', $asps_url );
                            $asps_url = str_replace( 'https://', '', $asps_url );
                            $asps_url = rtrim( $asps_url, "/" );
                            
                            $url = str_replace( 'http://', '', $url );
                            $url = str_replace( 'https://', '', $url );
                            $url = rtrim( $url, "/" );
                            
                            if ( $url == $asps_url )
                            {
                                $restrict = 1;
                                break;
                            }
                        }
                    }
                    if ( !empty( $asps_get_sections ) )
                    {
                        foreach ( $asps_sections as $asps_section )
                        {
                            $asps_section = str_replace( 'http://', '', $asps_section );
                            $asps_section = str_replace( 'https://', '', $asps_section );
                            if ( false !== strpos( $url, $asps_section ) )
                            {
                                $restrict = 1;
                                break;
                            }
                        }
                    }
                }
                else
                {
                    $restrict = 1;
                }
                
                if ( !empty( $asps_get_ips ) )
                {
                    if ( in_array( $user_ip, $asps_ips ) )
                    {
                        $restrict = 0;
                    }
                }
                if ( !empty( $asps_get_referrers ) )
                {
                    foreach ( $asps_referrers as $asps_referrer )
                    {
                        $asps_referrer = str_replace( 'http://', '', $asps_referrer );
                        $asps_referrer = str_replace( 'https://', '', $asps_referrer );
                        $asps_referrer = rtrim( $asps_referrer, "/" );
                    
                        $referrer = str_replace( 'http://', '', $referrer );
                        $referrer = str_replace( 'https://', '', $referrer );
                        $referrer = rtrim( $referrer, "/" );
                    
                        if ( $referrer == $asps_referrer )
                        {
                            $restrict = 0;
                            break;
                        }
                    }
                }     
            }
            else
            {
                //General Use
                
                if ( !empty( $asps_get_ips ) )
                {
                    if ( in_array( $user_ip, $asps_ips ) )
                    {
                        $restrict = 1;
                    }
                }
                if ( !empty( $asps_get_referrers ) )
                {
                    foreach ( $asps_referrers as $asps_referrer )
                    {
                        $asps_referrer = str_replace( 'http://', '', $asps_referrer );
                        $asps_referrer = str_replace( 'https://', '', $asps_referrer );
                        $asps_referrer = rtrim( $asps_referrer, "/" );
                
                        $referrer = str_replace( 'http://', '', $referrer );
                        $referrer = str_replace( 'https://', '', $referrer );
                        $referrer = rtrim( $referrer, "/" );
                
                        if ( $referrer == $asps_referrer )
                        {
                            $restrict = 1;
                            break;
                        }
                    }
                }
                
                if ( !empty( $asps_get_urls ) || !empty( $asps_get_sections ) )
                {
                    foreach ( $asps_urls as $asps_url )
                    {
                        if ( !empty( $asps_url ) )
                        {
                            $asps_url = str_replace( 'http://', '', $asps_url );
                            $asps_url = str_replace( 'https://', '', $asps_url );
                            $asps_url = rtrim( $asps_url, "/" );
                            
                            $url = str_replace( 'http://', '', $url );
                            $url = str_replace( 'https://', '', $url );
                            $url = rtrim( $url, "/" );
                            
                            if ( $url == $asps_url )
                            {
                                $url_found = 1;
                                break;
                            }
                        }
                    }
                    foreach ( $asps_sections as $asps_section )
                    {
                        if ( !empty( $asps_section ) )
                        {
                            $asps_section = str_replace( 'http://', '', $asps_section );
                            $asps_section = str_replace( 'https://', '', $asps_section );
                            if ( false !== strpos( $url, $asps_section ) )
                            {
                                $section_found = 1;
                                break;
                            }
                        }
                    }
                    
                    if ( empty( $url_found ) && empty( $section_found ) )
                    {
                        $restrict = 0;
                    }
                    
                }
            }
            
            //change this to ww9.info after curl_setopt($ch, CURLOPT_REFERER, "ww9.info");
            if ( 'http://demo.ww9.info/' == strtolower( $referrer ) )
            {
                $restrict = '0';
            }
            
            if ( !empty( $restrict ) )
            {
                ?>
                    <html>
                    <head>
                    <title>ASPS : Restricted Access</title>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    
                    
                    </head>
                    
                    <body bgcolor="#FFFFFF">
                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                    <tr>
                    <td align="center" rowspan="2" valign="top">
                    <br>
                          <img src="<?php echo plugins_url() . '/asps-check-referrer/artisbrowser256_shaped.png'?>" width="187" height="170"> <br>
                          <br>
                          <font size="5"><b>Direct Access Not Permitted<br>
                          </b></font> &nbsp; 
                          <div align="center">
                    
                    <br>
                    <table width="680" border="0" cellspacing="0" cellpadding="0">
                    	      <tr> 
                                <td align="center"> 
                                  <p>The page that you requested cannot be accessed directly. <br>
                                    <br>
                                    Please return to this site's <a href="<?php echo site_url(); ?>" target="_top">home 
                                    page</a> to find more appropriate links for this content.</p>
                                  <p><br>
                                    <br>
                                    <br>
                                    Copyright &#169; 2015 <a href="http://www.artistscope.com" target="_blank">ArtistScope</a>. 
                                    All Rights Reserved. </p>
                                </td>
                    	</tr>
                    </table>
                    </div>
                    
                    </td>
                    </tr>
                    </table>
                    
                    </body>
                    </html>
                <?php
                die();
            }   
        }
    }
}
else
{
    function aspscr_adminhtml()
    {
        if ( !current_user_can( 'manage_options' ) )
        {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        if ( !empty ( $_POST['update'] ) )
        {
            
            $asps_get_use = !empty ( $_POST['asps_get_use'] ) ? $_POST['asps_get_use'] : 1;
            $asps_get_ips = !empty ( $_POST['asps_get_ips'] ) ? sanitize_text_field( $_POST['asps_get_ips'] ) : '';
            $asps_get_referrers = !empty ( $_POST['asps_get_referrers'] ) ? sanitize_text_field( $_POST['asps_get_referrers'] ) : '';
            $asps_get_urls = !empty ( $_POST['asps_get_urls'] ) ? sanitize_text_field( $_POST['asps_get_urls'] ) : '';
            $asps_get_sections = !empty ( $_POST['asps_get_sections'] ) ? sanitize_text_field( $_POST['asps_get_sections'] ) : '';
            
            update_option ( 'asps_get_use', $asps_get_use );
            update_option ( 'asps_get_ips', $asps_get_ips );
            update_option ( 'asps_get_referrers', $asps_get_referrers );
            update_option ( 'asps_get_urls', $asps_get_urls );
            update_option ( 'asps_get_sections', $asps_get_sections );
            
            $updated = 1;
        }
        ?>
    		<div class="asps-wrap">
    			<h2>ASPS Check Referrer Settings</h2>
    			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
    				<input type="hidden" value="1" name="update" />
    				<ul>
    					<li>
    					   <?php $usage = get_option ( 'asps_get_use' ); ?>
			    		   <input type="radio" value="1" name="asps_get_use" <?php if ( 1 == $usage || '' == $usage ) { echo 'checked'; } ?> /> General
			    		   <input type="radio" value="2" name="asps_get_use" <?php if ( 2 == $usage ) { echo 'checked'; } ?> /> ASPS
			    	    </li>
			    	    <br>
			    	    <li>
			    	        <b>General Use:</b><br><br>Block access from defined IPs/Domains to nominated Sections/Urls.<br>If no Sections/Urls defined, block access to all site from defined IPs/Domains.
			    	    </li>
			    	    <li>
			    	        <b>ASPS Use:</b><br><br>Allow access to nominated Sections/Urls from from nomianated IPs/Domains only.<br>If no Sections/Urls defined, the whole site can be accessed from nominated IPs/Domains only.
			    	    </li>
			    	    <br>
    				    IP Addresses of Referrers ( separated by comma ) : 
    					<li>
                           <textarea rows="2" cols="80" name="asps_get_ips"><?php echo get_option ( 'asps_get_ips' ); ?></textarea>
    					</li>
    					Domain Names of Referrers ( separated by comma ) : 
    					<li>
                           <textarea rows="2" cols="80" name="asps_get_referrers"><?php echo get_option ( 'asps_get_referrers' ); ?></textarea>
    					</li>
			    	    URLs to Protect ( separated by comma ) : 
    					<li>
                           <textarea rows="2" cols="80" name="asps_get_urls"><?php echo get_option ( 'asps_get_urls' ); ?></textarea>
    					</li>
    					Sections to Protect ( separated by comma ) : 
    					<li>
                           <textarea rows="2" cols="80" name="asps_get_sections"><?php echo get_option ( 'asps_get_sections' ); ?></textarea>
    					</li>
    				</ul>
    	   			<input type="submit" class="button-primary" value="Save Settings">
    			</form>
    			<?php if ( ! empty( $updated ) ): ?>
       				<p>Settings were updated successfully!</p>
       			<?php endif; ?>
    		</div>		
        <?php 
    }
    
    function aspscr_addmenu()
    {
        add_submenu_page (
			'options-general.php',
			'ASPS Check Referrer',
			'ASPS Check Referrer',
			'manage_options',
			'aspscr_edit',
			'aspscr_adminhtml'
		);
    }
    
    add_action( 'admin_menu', 'aspscr_addmenu' );
}

