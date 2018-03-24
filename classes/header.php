<header class="header header-desktop">

    <div class="l-row l-row-center">

      <div class="header-container l-row">
        <nav class="nav">

          <?php
          if (has_nav_menu('menu_primary_left')) :
            wp_nav_menu(['theme_location' => 'menu_primary_left', 'menu_class' => 'l-box l-fill l-row l-row-left nav']);
          endif;
          ?>

        </nav>

        <div class="logo-wrapper">
          <a class="logo-mark" href="<?= esc_url(home_url('/')); ?>">
            <img src="<?php the_field('theme_logo_main', 'option'); ?>" alt="Purple Power" class="l-fill">
          </a>
        </div>

        <nav class="nav">

          <?php
          if (has_nav_menu('menu_primary_right')) :
            wp_nav_menu(['theme_location' => 'menu_primary_right', 'menu_class' => 'l-box l-fill l-row l-row-right nav']);
          endif;
          ?>

        </nav>
      </div>

    </div>


    <?php get_template_part('templates/page', 'header'); ?>

  </header>


  <header class="l-box header header-mobile l-col">

    <div class="l-row l-row-left l-contain">
      <a class="l-box logo-mark" href="<?= esc_url(home_url('/')); ?>">
        <img src="<?php the_field('theme_logo_main', 'option'); ?>" alt="Near North Co." class="l-fill">
      </a>

      <nav class="l-row nav">
        <div class="l-col l-row-left nav-wrapper">
          <?php
            if (has_nav_menu('menu_mobile')) :
              wp_nav_menu(['theme_location' => 'menu_mobile', 'menu_class' => 'nav-menu']);
            endif;
          ?>
        </div>
      </nav>

      <button class="mobile-menu"><span></span></button>
    </div>

    <?php get_template_part('templates/page', 'header'); ?>


</header>
