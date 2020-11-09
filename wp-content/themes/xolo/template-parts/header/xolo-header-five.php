<header id="header-section" class="header" role="banner" <?php xolo_schema_markup( 'header' ); ?>>
    
    <!-- Header Widget Info -->
    <div class="header-widget-info d-none d-xl-block">
        <div class="xl-container">
            <div class="header-wrapper">                
                <div class="flex-fill">
                    <div class="header-info">
                        <div class="header-item widget-left header-widget">
                             <?php if ( function_exists( 'xolo_header_widget_area_first' ) ) : xolo_header_widget_area_first(); endif; ?>
                        </div>
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="logo text-center" <?php xolo_schema_markup( 'organization' ); ?>>
						<?php if ( function_exists( 'xolo_logo_title_desc' ) ) : xolo_logo_title_desc(); endif; ?>
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="header-info">
                        <div class="header-item widget-right header-widget">
                             <?php if ( function_exists( 'xolo_header_widget_area_second' ) ) : xolo_header_widget_area_second(); endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / -->

    <!-- Top Menu -->
    <div class="navigator-wrapper">
        <!-- Mobile Toggle -->
        <?php if ( function_exists( 'xolo_mobile_menu' ) ) : xolo_mobile_menu(); endif; ?>
        <!-- / -->
        <!-- Desktop Menu -->
        <div class="xl-nav-area d-none d-xl-block">
            <div class="navigation <?php echo esc_attr(xolo_sticky_menu()); ?>">
                <div class="xl-container">
                    <div class="xl-columns-area">
                        <div class="xl-column-12">
                            <div class="theme-menu">
                                <nav class="menubar" role="navigation"<?php xolo_schema_markup( 'site_navigation' ); ?>>
                                    <?php if ( function_exists( 'xolo_nav' ) ) : xolo_nav(); endif; ?>                           
                                </nav>
                                <div class="menu-right">
                                    <ul class="wrap-right">                            	
                                    	<?php if ( function_exists( 'xolo_header_search' ) ) : xolo_header_search(); endif; ?>	
        								<?php if ( function_exists( 'xolo_header_button' ) ) : xolo_header_button(); endif; ?>	
                                    </ul>                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / -->
    </div>
    <!-- / -->

</header>
