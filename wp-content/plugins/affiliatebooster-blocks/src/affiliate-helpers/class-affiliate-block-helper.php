<?php

/**
 * AFFILIATE Block Helper.
 *
 * @package AFFILIATE
 */
if (!class_exists('AFFILIATE_Block_Helper')) {

  /**
   * Class AFFILIATE_Block_Helper.
   */
  class AFFILIATE_Block_Helper
  {

    /**
     * Get affiliate-booster CSS
     *
     * @since 1.4.0
     * @param array  $attr The block attributes.
     * @param string $id The selector ID.
     * @return array The Widget List.
     */
    public static function get_propcons_css($attr, $id)
    {    // @codingStandardsIgnoreStart
      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/propsandcons']['attributes'];

      $attr = array_merge($defaults, (array) $attr);

      $selectors = self::get_propcons_selectors($attr);

      $m_selectors = self::get_propcons_mobiletselectors($attr);

      $t_selectors = self::get_propcons_tabletselectors($attr);
      // // @codingStandardsIgnoreEnd

      $desktop = AFFILIATE_Helper::generate_css($selectors, '#affiliate-style-' . $id);

      $tablet = AFFILIATE_Helper::generate_css($t_selectors, '#affiliate-style-' . $id);

      $mobile = AFFILIATE_Helper::generate_css($m_selectors, '#affiliate-style-' . $id);

      $generated_css = array(
        'desktop' => $desktop,
        'tablet'  => $tablet,
        'mobile'  => $mobile,
      );

      return $generated_css;
    }

    public static function get_propcons_selectors($attr){
      $boxshadow = array();
      if (!empty($attr['boxShadow']['openShadow']) && $attr['boxShadow']['openShadow'] == 1) {
        if ($attr['boxShadow']['inset'] === 0) {
          $inset = '';
        } else {
          $inset = $attr['boxShadow']['inset'];
        }
        $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
      }

      $desktop_css = array(
        " .affiliate-procon-inner" => array(
          "box-shadow" => implode('',$boxshadow),
        ),
        " .affiliate-propcon-title" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop'], $attr['titlefontSizeType']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing'], $attr['titleletterSpacingType']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform']),
        ),
        " .affiliate-list" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeDesktop'], $attr['listfontSizeType']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeight']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacing'], $attr['listletterSpacingType']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform']),
        ),
        " .affiliate-props-title" => array(
          'background' => AFFILIATE_Helper::get_css_value($attr['propsBgColor']),
          'color' => AFFILIATE_Helper::get_css_value($attr['propsTextColor']),
        ),
        " .affiliate-const-title" => array(
          'background' => AFFILIATE_Helper::get_css_value($attr['consBgColor']),
          'color' => AFFILIATE_Helper::get_css_value($attr['consTextColor']),
        ),
        " .affiliate-props-list li:before" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['propsIconColor']),
        ),
        " .affiliate-cons-list li:before" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['consIconColor']),
        ),
        " .affiliate-props-list li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['propsListColor']),
        ),
        " .affiliate-cons-list li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['consListColor']),
        ),
      );

      $desktop_css =  AFFILIATE_Helper::regenerate_stylesheet($desktop_css);

      return $desktop_css;
    }

    public static function get_propcons_tabletselectors($attr){
      $tablet_css = array(
        " .affiliate-propcon-title" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet'], $attr['titlefontSizeTypeTablet']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet'], $attr['titleletterSpacingTypeTablet']),
        ),
        " .affiliate-list" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet'], $attr['listfontSizeTypeTablet']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet'], $attr['listletterSpacingTypeTablet']),
        ),
      );

      $tablet_css =  AFFILIATE_Helper::regenerate_stylesheet($tablet_css);

      return $tablet_css;
    }

    public static function get_propcons_mobiletselectors($attr){
      $mobile_css = array(
        " .affiliate-propcon-title" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile'], $attr['titlefontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile'], $attr['titleletterSpacingTypeMobile']),
        ),
        " .affiliate-list" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile'], $attr['listfontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile'], $attr['listletterSpacingTypeMobile']),
        ),
      );

      $mobile_css =  AFFILIATE_Helper::regenerate_stylesheet($mobile_css);

      return $mobile_css;
    }

    public static function get_abnotification_css( $attr, $id ) {       // @codingStandardsIgnoreStart

      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-notification-box']['attributes'];

      $attr = array_merge( $defaults, $attr );

      $m_selectors = array();
      $t_selectors = array();
      
      $boxshadow = array();
      if ($attr['boxShadow']['openShadow'] == 1) {
        if ($attr['boxShadow']['inset'] === 0) {
          $inset = '';
        } else {
          $inset = $attr['boxShadow']['inset'];
        }
        $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
      }

      $selectors = array(
        " .affiliate-notification-inner" => array(
          'width' => AFFILIATE_Helper::get_css_value($attr['customWidth'], $attr['customWidthType']),
          'box-shadow'=> implode('',$boxshadow),
        ),  
        " .affiliate-notification-content" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['cntnfontFamily']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeDesktop'], $attr['cntnfontSizeType']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['cntnfontWeight']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['cntnfontStyle']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeight']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacing'], $attr['cntnletterSpacingType']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['cntntextDecoration']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['cntntextTransform']),
          "color" => AFFILIATE_Helper::get_css_value($attr['cntnTextColor']),
          "background" => AFFILIATE_Helper::get_css_value($attr['cntnBgColor']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['cntnBorderColor']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['cntnBorderType']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['cntnBorder']['top'], AFFILIATE_Helper::get_css_value($attr['cntnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorder']['right'], AFFILIATE_Helper::get_css_value($attr['cntnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorder']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorder']['left'], AFFILIATE_Helper::get_css_value($attr['cntnBorder']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['top'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['right'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['left'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])),
          "text-align" => AFFILIATE_Helper::get_css_value($attr['cntnAlignment']),
          'padding' => AFFILIATE_Helper::get_css_value($attr['cntnPadding']['top'], AFFILIATE_Helper::get_css_value($attr['cntnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPadding']['right'], AFFILIATE_Helper::get_css_value($attr['cntnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPadding']['left'], AFFILIATE_Helper::get_css_value($attr['cntnPadding']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['cntnMargin']['top'], AFFILIATE_Helper::get_css_value($attr['cntnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMargin']['right'], AFFILIATE_Helper::get_css_value($attr['cntnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMargin']['left'], AFFILIATE_Helper::get_css_value($attr['cntnMargin']['unit'])),
        ),
      );

      
      $m_selectors = array(
        " .affiliate-notification-inner" => array(
          'width' => AFFILIATE_Helper::get_css_value($attr['customWidthMobile'], $attr['customWidthTypeTablet']),
        ),
        " .affiliate-notification-content" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeMobile'], $attr['cntnfontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingMobile'], $attr['cntnletterSpacingTypeMobile']),
          "text-align" => AFFILIATE_Helper::get_css_value($attr['cntnAlignmentMobile']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['cntnBorderMobile']['top'], AFFILIATE_Helper::get_css_value($attr['cntnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderMobile']['right'], AFFILIATE_Helper::get_css_value($attr['cntnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderMobile']['left'], AFFILIATE_Helper::get_css_value($attr['cntnBorderMobile']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusMobile']['top'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusMobile']['right'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusMobile']['left'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusMobile']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['cntnPaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['cntnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['cntnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['cntnPaddingMobile']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['cntnMarginMobile']['top'], AFFILIATE_Helper::get_css_value($attr['cntnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMarginMobile']['right'], AFFILIATE_Helper::get_css_value($attr['cntnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMarginMobile']['left'], AFFILIATE_Helper::get_css_value($attr['cntnMarginMobile']['unit'])),
        ),
      );

      $t_selectors = array(
        " .affiliate-notification-inner" => array(
          'width' => AFFILIATE_Helper::get_css_value($attr['customWidthTablet'], $attr['customWidthTypeTablet']),
        ),
        " .affiliate-notification-content" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeTablet'], $attr['cntnfontSizeTypeTablet']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightTablet']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingTablet'], $attr['cntnletterSpacingTypeTablet']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['cntnBorderTablet']['top'], AFFILIATE_Helper::get_css_value($attr['cntnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderTablet']['right'], AFFILIATE_Helper::get_css_value($attr['cntnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderTablet']['left'], AFFILIATE_Helper::get_css_value($attr['cntnBorderTablet']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusTablet']['top'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusTablet']['right'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadiusTablet']['left'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['cntnPaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['cntnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['cntnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['cntnPaddingTablet']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['cntnMarginTablet']['top'], AFFILIATE_Helper::get_css_value($attr['cntnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMarginTablet']['right'], AFFILIATE_Helper::get_css_value($attr['cntnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMarginTablet']['left'], AFFILIATE_Helper::get_css_value($attr['cntnMarginTablet']['unit'])),
        ),
      );


      // @codingStandardsIgnoreEnd

      $regenerate_d = AFFILIATE_Helper::regenerate_stylesheet($selectors);
      $regenerate_m = AFFILIATE_Helper::regenerate_stylesheet($t_selectors);
      $regenerate_t = AFFILIATE_Helper::regenerate_stylesheet($m_selectors);

      $desktop = AFFILIATE_Helper::generate_css( $regenerate_d, '#affiliate-style-' . $id );

      $tablet = AFFILIATE_Helper::generate_css( $regenerate_t, '#affiliate-style-' . $id );

      $mobile = AFFILIATE_Helper::generate_css( $regenerate_m, '#affiliate-style-' . $id );

      $generated_css = array(
        'desktop' => $desktop,
        'tablet'  => $tablet,
        'mobile'  => $mobile,
      );
      return $generated_css;
    }

    public static function blocks_abnotification_gfont($attr)
    {
      $cntn_load_google_font = isset($attr['cntnLoadGoogleFonts']) ? $attr['cntnLoadGoogleFonts'] : '';
      $cntn_font_family      = isset($attr['cntnfontFamily']) ? $attr['cntnfontFamily'] : '';
      $cntn_font_weight      = isset($attr['cntnfontWeight']) ? $attr['cntnfontWeight'] : '';
      $cntn_font_subset      = isset($attr['cntnfontSubset']) ? $attr['cntnfontSubset'] : '';

      AFFILIATE_Helper::blocks_google_font($cntn_load_google_font, $cntn_font_family, $cntn_font_weight, $cntn_font_subset);
    }

    public static function blocks_propcons_gfont($attr)
    {

      $title_load_google_font = isset($attr['titleLoadGoogleFonts']) ? $attr['titleLoadGoogleFonts'] : '';
      $title_font_family      = isset($attr['titlefontFamily']) ? $attr['titlefontFamily'] : '';
      $title_font_weight      = isset($attr['titlefontWeight']) ? $attr['titlefontWeight'] : '';
      $title_font_subset      = isset($attr['titlefontSubset']) ? $attr['titlefontSubset'] : '';

      $list_load_google_font = isset($attr['listLoadGoogleFonts']) ? $attr['listLoadGoogleFonts'] : '';
      $list_font_family      = isset($attr['listfontFamily']) ? $attr['listfontFamily'] : '';
      $list_font_weight      = isset($attr['listfontWeight']) ? $attr['listfontWeight'] : '';
      $list_font_subset      = isset($attr['listfontSubset']) ? $attr['listfontSubset'] : '';

      AFFILIATE_Helper::blocks_google_font($title_load_google_font, $title_font_family, $title_font_weight, $title_font_subset);
      AFFILIATE_Helper::blocks_google_font($list_load_google_font, $list_font_family, $list_font_weight, $list_font_subset);
    }


    public static function get_comparison_table_css($attr, $id)
    {    // @codingStandardsIgnoreStart
      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-comparison-table']['attributes'];

      $attr = array_merge($defaults, (array) $attr);

      $selectors = self::get_comparison_table_deskselectors($attr);

      $m_selectors = self::get_comparison_table_mobselectors($attr);

      $t_selectors = self::get_comparison_table_tabselectors($attr);
      // // @codingStandardsIgnoreEnd

      $desktop = AFFILIATE_Helper::generate_css($selectors, '#affiliate-style-' . $id);

      $tablet = AFFILIATE_Helper::generate_css($t_selectors, '#affiliate-style-' . $id);

      $mobile = AFFILIATE_Helper::generate_css($m_selectors, '#affiliate-style-' . $id);

      $generated_css = array(
        'desktop' => $desktop,
        'tablet'  => $tablet,
        'mobile'  => $mobile,
      );

      return $generated_css;
    }

    public static function get_comparison_table_deskselectors($attr){

      $btnBg = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg']['openBg'] == 1){
          if($attr['btnBg']['bgType'] == 'color'){
             $btnBg = $attr['btnBg']['bgDefaultColor']; 
          }
      } 

      $btnBgHover = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover']['openBg'] == 1){
          if($attr['btnBgHover']['bgType'] == 'color'){
             $btnBgHover = $attr['btnBgHover']['bgDefaultColor']; 
          }
      }

      if($attr['btnBorderColor'] == ''){
        $attr['btnBorderColor'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor'] == ''){
        $attr['btnBorderHoverColor'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType'] == ''){
        $attr['btnBorderType'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor'] == ''){
        $attr['btnTextColor'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor'] == ''){
        $attr['btnTextHoverColor'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }
 
      $boxshadow = array();
        if (!empty($attr['boxShadow']['openShadow']) && $attr['boxShadow']['openShadow'] == 1) {
          if ($attr['boxShadow']['inset'] === 0) {
            $inset = '';
          } else {
            $inset = $attr['boxShadow']['inset'];
          }
          $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
        }
      $desktop_css = array(
        " .af-is-custom" => array(
          "width" => AFFILIATE_Helper::get_css_value($attr['btnCustomWidth'],'px'),
        ),
        " .affiliate-cmpr-inner" => array(
            "box-shadow" => implode('',$boxshadow),
        ),
        " .affiliate-compr-btn,  .affiliate-compr-btn span" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop'], $attr['btnfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing'], $attr['btnletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform']),
            //"background" => AFFILIATE_Helper::get_css_value($attr['btnBgColor']),
            "background" => AFFILIATE_Helper::get_css_value($btnBg),
            "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor']),
            "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor']),
            'border-style' => AFFILIATE_Helper::get_css_value($attr['btnBorderType']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])),
          ),
          " .affiliate-compr-btn:hover,  .affiliate-compr-btn span:hover" => array(
            //"background" => AFFILIATE_Helper::get_css_value($attr['btnHoverBgColor']),
            "background" => AFFILIATE_Helper::get_css_value($btnBgHover),
            "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor']),
            "color" => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor']),
          ),  
          " .affiliate-txtbox-title" => array(
              'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily']),
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop'], $attr['titlefontSizeType']),
              'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight']),
              'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing'], $attr['titleletterSpacingType']),
              'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration']),
              'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform']),
              "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor']),
        ),
        " .affiliate-item-badge" => array(
              'font-family' => AFFILIATE_Helper::get_css_value($attr['badgefontFamily']),
              'font-size' => AFFILIATE_Helper::get_css_value($attr['badgefontSizeDesktop'], $attr['badgefontSizeType']),
              'font-weight' => AFFILIATE_Helper::get_css_value($attr['badgefontWeight']),
              'font-style' => AFFILIATE_Helper::get_css_value($attr['badgefontStyle']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['badgelineHeight']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['badgeletterSpacing'], $attr['badgeletterSpacingType']),
              'text-decoration' => AFFILIATE_Helper::get_css_value($attr['badgetextDecoration']),
              'text-transform' => AFFILIATE_Helper::get_css_value($attr['badgetextTransform']),
              "color" => AFFILIATE_Helper::get_css_value($attr['badgeTextColor']),
              "background" => AFFILIATE_Helper::get_css_value($attr['badgeBgColor']),
          ),
          " .affiliate-cmpr-readmore" => array(
                'font-family' => AFFILIATE_Helper::get_css_value($attr['readMorefontFamily']),
                'font-size' => AFFILIATE_Helper::get_css_value($attr['readMorefontSizeDesktop'], $attr['readMorefontSizeType']),
                'font-weight' => AFFILIATE_Helper::get_css_value($attr['readMorefontWeight']),
                'font-style' => AFFILIATE_Helper::get_css_value($attr['readMorefontStyle']),
                'line-height' => AFFILIATE_Helper::get_css_value($attr['readMorelineHeight']),
                'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['readMoreletterSpacing'], $attr['readMoreletterSpacingType']),
                'text-decoration' => AFFILIATE_Helper::get_css_value($attr['readMoretextDecoration']),
                'text-transform' => AFFILIATE_Helper::get_css_value($attr['readMoretextTransform']),
                "color" => AFFILIATE_Helper::get_css_value($attr['readMoreTextColor']),
          ),
          " .affiliate-compr-th th" => array(
              'font-family' => AFFILIATE_Helper::get_css_value($attr['thfontFamily']),
              'font-size' => AFFILIATE_Helper::get_css_value($attr['thfontSizeDesktop'], $attr['thfontSizeType']),
              'font-weight' => AFFILIATE_Helper::get_css_value($attr['thfontWeight']),
              'font-style' => AFFILIATE_Helper::get_css_value($attr['thfontStyle']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['thlineHeight']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['thletterSpacing'], $attr['thletterSpacingType']),
              'text-decoration' => AFFILIATE_Helper::get_css_value($attr['thtextDecoration']),
              'text-transform' => AFFILIATE_Helper::get_css_value($attr['thtextTransform']),
              "background" => AFFILIATE_Helper::get_css_value($attr['HBgColor']),
              "color" => AFFILIATE_Helper::get_css_value($attr['HTxtColor']),
        ),
        " .affiliate-compr-cntn .affiliate-col-ct1 li, .affiliate-compr-cntn .affiliate-col-ct2 li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['cntnfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeDesktop'], $attr['cntnfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['cntnfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['cntnfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacing'], $attr['cntnletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['cntntextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['cntntextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['tdTxtColor']),
        ),
        " tr:nth-of-type(odd)" => array(
            "background" => AFFILIATE_Helper::get_css_value($attr['tOddColor']),
        ),
        " tr:nth-of-type(even)" => array(
          "background" => AFFILIATE_Helper::get_css_value($attr['tEvenColor']),
        ),
        " .affiliate-img-rating" => array(
            "color" => AFFILIATE_Helper::get_css_value($attr['itemRatingTextColor']),
            "background" => AFFILIATE_Helper::get_css_value($attr['itemRatingBgColor']),
        ),
      );
 


    if($attr['btnBg']['openBg'] == 1){
        if($attr['btnBg']['bgType'] == 'gradient'){
          if ($attr['btnBg']['bgGradient']['type'] == 'linear') {
            $desktop_css[" .affiliate-compr-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
          } else {
            $desktop_css[" .affiliate-compr-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  


      if($attr['btnBgHover']['openBg'] == 1){
        if($attr['btnBgHover']['bgType'] == 'gradient'){
          if ($attr['btnBgHover']['bgGradient']['type'] == 'linear') {
            $desktop_css[" .affiliate-compr-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
          } else {
            $desktop_css[" .affiliate-compr-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  

      $desktop_css =  AFFILIATE_Helper::regenerate_stylesheet($desktop_css);

      return $desktop_css;
    }

    public static function get_comparison_table_tabselectors($attr){
      $tablet_css = array(
        " .affiliate-compr-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet'], $attr['btnfontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet'], $attr['btnletterSpacingTypeTablet']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])),
        ),
        " .affiliate-compr-th th" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['thfontSizeTablet'], $attr['thfontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['thlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['thletterSpacingTablet'], $attr['thletterSpacingTypeTablet']),
        ),

        " .affiliate-compr-cntn .affiliate-col-ct1, .affiliate-compr-cntn .affiliate-col-ct2" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeTablet'], $attr['cntnfontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingTablet'], $attr['cntnletterSpacingTypeTablet']),
        ),

      );

      $tablet_css =  AFFILIATE_Helper::regenerate_stylesheet($tablet_css);

      return $tablet_css;
    }

    public static function get_comparison_table_mobselectors($attr){
      $mobile_css = array(
        " .affiliate-compr-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile'], $attr['btnfontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile'], $attr['btnletterSpacingTypeMobile']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])),
        ),
        " .affiliate-compr-th th" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['thfontSizeMobile'], $attr['thfontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['thlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['thletterSpacingMobile'], $attr['thletterSpacingTypeMobile']),
        ),
        " .affiliate-compr-cntn .affiliate-col-ct1, .affiliate-compr-cntn .affiliate-col-ct2" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeMobile'], $attr['cntnfontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingMobile'], $attr['cntnletterSpacingTypeMobile']),
        ),
      );

      $mobile_css =  AFFILIATE_Helper::regenerate_stylesheet($mobile_css);

      return $mobile_css;
    }

    public static function blocks_comparison_table_gfont($attr)
    {

      $btn_load_google_font = isset($attr['btnLoadGoogleFonts']) ? $attr['btnLoadGoogleFonts'] : '';
      $btn_font_family      = isset($attr['btnfontFamily']) ? $attr['btnfontFamily'] : '';
      $btn_font_weight      = isset($attr['btnfontWeight']) ? $attr['btnfontWeight'] : '';
      $btn_font_subset      = isset($attr['btnfontSubset']) ? $attr['btnfontSubset'] : '';

      $title_load_google_font = isset($attr['titleLoadGoogleFonts']) ? $attr['titleLoadGoogleFonts'] : '';
      $title_font_family      = isset($attr['titlefontFamily']) ? $attr['titlefontFamily'] : '';
      $title_font_weight      = isset($attr['titlefontWeight']) ? $attr['titlefontWeight'] : '';
      $title_font_subset      = isset($attr['titlefontSubset']) ? $attr['titlefontSubset'] : '';

      $readMore_load_google_font = isset($attr['readMoreLoadGoogleFonts']) ? $attr['readMoreLoadGoogleFonts'] : '';
      $readMore_font_family      = isset($attr['readMorefontFamily']) ? $attr['readMorefontFamily'] : '';
      $readMore_font_weight      = isset($attr['readMorefontWeight']) ? $attr['readMorefontWeight'] : '';
      $readMore_font_subset      = isset($attr['readMorefontSubset']) ? $attr['readMorefontSubset'] : '';

      $badge_load_google_font = isset($attr['badgeLoadGoogleFonts']) ? $attr['badgeLoadGoogleFonts'] : '';
      $badge_font_family      = isset($attr['badgefontFamily']) ? $attr['badgefontFamily'] : '';
      $badge_font_weight      = isset($attr['badgefontWeight']) ? $attr['badgefontWeight'] : '';
      $badge_font_subset      = isset($attr['badgefontSubset']) ? $attr['badgefontSubset'] : '';

      $th_load_google_font = isset($attr['thLoadGoogleFonts']) ? $attr['thLoadGoogleFonts'] : '';
      $th_font_family      = isset($attr['thfontFamily']) ? $attr['thfontFamily'] : '';
      $th_font_weight      = isset($attr['thfontWeight']) ? $attr['thfontWeight'] : '';
      $th_font_subset      = isset($attr['thfontSubset']) ? $attr['thfontSubset'] : '';

      $cntn_load_google_font = isset($attr['cntnLoadGoogleFonts']) ? $attr['cntnLoadGoogleFonts'] : '';
      $cntn_font_family      = isset($attr['cntnfontFamily']) ? $attr['cntnfontFamily'] : '';
      $cntn_font_weight      = isset($attr['cntnfontWeight']) ? $attr['cntnfontWeight'] : '';
      $cntn_font_subset      = isset($attr['cntnfontSubset']) ? $attr['cntnfontSubset'] : '';

      AFFILIATE_Helper::blocks_google_font($btn_load_google_font, $btn_font_family, $btn_font_weight, $btn_font_subset);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font, $title_font_family, $title_font_weight, $title_font_subset);
      AFFILIATE_Helper::blocks_google_font($readMore_load_google_font, $readMore_font_family, $readMore_font_weight, $readMore_font_subset);
      AFFILIATE_Helper::blocks_google_font($badge_load_google_font, $badge_font_family, $badge_font_weight, $badge_font_subset);
      AFFILIATE_Helper::blocks_google_font($th_load_google_font, $th_font_family, $th_font_weight, $th_font_subset);
      AFFILIATE_Helper::blocks_google_font($cntn_load_google_font, $cntn_font_family, $cntn_font_weight, $cntn_font_subset);
    }

    

    public static function get_abnotice_css( $attr, $id ) { 			// @codingStandardsIgnoreStart

      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-notice-box']['attributes'];
      
      $attr = array_merge( $defaults, $attr );

      $m_selectors = array();
      $t_selectors = array();
      
      $boxshadow = array();
      if (!empty($attr['boxShadow']['openShadow']) && $attr['boxShadow']['openShadow'] == 1) {
        if ($attr['boxShadow']['inset'] === 0) {
          $inset = '';
        } else {
          $inset = $attr['boxShadow']['inset'];
        }
        $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
      }


      $selectors = array(
        " .affiliate-notice-inner" => array(
          "box-shadow"=>implode(',',$boxshadow),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['titleBorderColor']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['titleBorderType']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['titleBorder']['top'], AFFILIATE_Helper::get_css_value($attr['titleBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorder']['right'], AFFILIATE_Helper::get_css_value($attr['titleBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorder']['bottom'], AFFILIATE_Helper::get_css_value($attr['titleBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorder']['left'], AFFILIATE_Helper::get_css_value($attr['titleBorder']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['top'], AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['right'], AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['bottom'], AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['left'], AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['unit'])),
        ),
        " .affiliate-notice-title " => array( 
          "background" => AFFILIATE_Helper::get_css_value($attr['noticeTitleBgColor']),
        ),  
        " .affiliate-notice-title " . $attr['titleTag1']  => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop'], $attr['titlefontSizeType']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing'], $attr['titleletterSpacingType']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform']),
          "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor']),
          "text-align" => AFFILIATE_Helper::get_css_value($attr['titleAlignment']),
          'padding' => AFFILIATE_Helper::get_css_value($attr['titlePadding']['top'], AFFILIATE_Helper::get_css_value($attr['titlePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titlePadding']['right'], AFFILIATE_Helper::get_css_value($attr['titlePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titlePadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['titlePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titlePadding']['left'], AFFILIATE_Helper::get_css_value($attr['titlePadding']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['titleMargin']['top'], AFFILIATE_Helper::get_css_value($attr['titleMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleMargin']['right'], AFFILIATE_Helper::get_css_value($attr['titleMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['titleMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleMargin']['left'], AFFILIATE_Helper::get_css_value($attr['titleMargin']['unit'])),
        ),
        " .affiliate-notice-content li:before" => array(
          "color" => AFFILIATE_Helper::get_css_value($attr['iconColor']),
        ),
        " .affiliate-notice-cntn-wrapper" => array(
         "background" => AFFILIATE_Helper::get_css_value($attr['cntnBgColor']),
       ),
        " .affiliate-notice-content, .affiliate-notice-content li" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['cntnfontFamily']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeDesktop'], $attr['cntnfontSizeType']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['cntnfontWeight']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['cntnfontStyle']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeight']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacing'], $attr['cntnletterSpacingType']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['cntntextDecoration']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['cntntextTransform']),
          "color" => AFFILIATE_Helper::get_css_value($attr['cntnTextColor']),
          "text-align" => AFFILIATE_Helper::get_css_value($attr['cntnAlignment']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['cntnBorderColor']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['cntnBorderType']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['cntnBorder']['top'], AFFILIATE_Helper::get_css_value($attr['cntnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorder']['right'], AFFILIATE_Helper::get_css_value($attr['cntnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorder']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorder']['left'], AFFILIATE_Helper::get_css_value($attr['cntnBorder']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['top'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['right'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['left'], AFFILIATE_Helper::get_css_value($attr['cntnBorderRadius']['unit'])),
          "text-align" => AFFILIATE_Helper::get_css_value($attr['cntnAlignment']),
          'padding' => AFFILIATE_Helper::get_css_value($attr['cntnPadding']['top'], AFFILIATE_Helper::get_css_value($attr['cntnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPadding']['right'], AFFILIATE_Helper::get_css_value($attr['cntnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnPadding']['left'], AFFILIATE_Helper::get_css_value($attr['cntnPadding']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['cntnMargin']['top'], AFFILIATE_Helper::get_css_value($attr['cntnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMargin']['right'], AFFILIATE_Helper::get_css_value($attr['cntnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['cntnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['cntnMargin']['left'], AFFILIATE_Helper::get_css_value($attr['cntnMargin']['unit'])),
        ),
      );


      $m_selectors = array(
        " .affiliate-notice-title " . $attr['titleTag1'] => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile'], $attr['titlefontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile'], $attr['titleletterSpacingTypeMobile']),
          "text-align" => AFFILIATE_Helper::get_css_value($attr['titleAlignmentMobile']),
        ),
        " .affiliate-notice-content, .affiliate-notice-content li" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeMobile'], $attr['cntnfontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingMobile'], $attr['cntnletterSpacingTypeMobile']),
          "text-align" => AFFILIATE_Helper::get_css_value($attr['cntnAlignmentMobile']),
        ),
      );

      $t_selectors = array(
       " .affiliate-notice-title " . $attr['titleTag1'] => array(
        'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet'], $attr['titlefontSizeTypeTablet']),
        'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet']),
        'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet'], $attr['titleletterSpacingTypeTablet']),
      ),
       " .affiliate-notice-content, .affiliate-notice-content li" => array(
        'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeTablet'], $attr['cntnfontSizeTypeTablet']),
        'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightTablet']),
        'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingTablet'], $attr['cntnletterSpacingTypeTablet']),
      ),
      );


			// @codingStandardsIgnoreEnd

      $regenerate_d = AFFILIATE_Helper::regenerate_stylesheet($selectors);
      $regenerate_m = AFFILIATE_Helper::regenerate_stylesheet($t_selectors);
      $regenerate_t = AFFILIATE_Helper::regenerate_stylesheet($m_selectors);

      $desktop = AFFILIATE_Helper::generate_css( $regenerate_d, '#affiliate-style-' . $id );

      $tablet = AFFILIATE_Helper::generate_css( $regenerate_t, '#affiliate-style-' . $id );

      $mobile = AFFILIATE_Helper::generate_css( $regenerate_m, '#affiliate-style-' . $id );

      $generated_css = array(
        'desktop' => $desktop,
        'tablet'  => $tablet,
        'mobile'  => $mobile,
      );
      return $generated_css;
    }

    public static function blocks_abnotice_gfont($attr)
    {
      $title_load_google_font = isset($attr['titleLoadGoogleFonts']) ? $attr['titleLoadGoogleFonts'] : '';
      $title_font_family      = isset($attr['titlefontFamily']) ? $attr['titlefontFamily'] : '';
      $title_font_weight      = isset($attr['titlefontWeight']) ? $attr['titlefontWeight'] : '';
      $title_font_subset      = isset($attr['titlefontSubset']) ? $attr['titlefontSubset'] : '';

      $cntn_load_google_font = isset($attr['cntnLoadGoogleFonts']) ? $attr['cntnLoadGoogleFonts'] : '';
      $cntn_font_family      = isset($attr['cntnfontFamily']) ? $attr['cntnfontFamily'] : '';
      $cntn_font_weight      = isset($attr['cntnfontWeight']) ? $attr['cntnfontWeight'] : '';
      $cntn_font_subset      = isset($attr['cntnfontSubset']) ? $attr['cntnfontSubset'] : '';

      AFFILIATE_Helper::blocks_google_font($title_load_google_font, $title_font_family, $title_font_weight, $title_font_subset);
      AFFILIATE_Helper::blocks_google_font($cntn_load_google_font, $cntn_font_family, $cntn_font_weight, $cntn_font_subset);
    }

    public static function get_abbutton_css( $attr, $id ) { 			// @codingStandardsIgnoreStart

     $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-advance-button']['attributes'];

     $attr = array_merge( $defaults, $attr );

     $m_selectors = array();
     $t_selectors = array();

     $btnBg = '';
      if($attr['btnBg']['openBg'] == 1){
          if($attr['btnBg']['bgType'] == 'color'){
             $btnBg = $attr['btnBg']['bgDefaultColor']; 
          }
      } 

      $btnBgHover = '';
      if($attr['btnBgHover']['openBg'] == 1){
          if($attr['btnBgHover']['bgType'] == 'color'){
             $btnBgHover = $attr['btnBgHover']['bgDefaultColor']; 
          }
      } 

      $btnShadow = array();
        if (!empty($attr['btnShadow']['openShadow']) && $attr['btnShadow']['openShadow'] == 1) {
          if ($attr['btnShadow']['inset'] === 0) {
            $inset = '';
          } else {
            $inset = $attr['btnShadow']['inset'];
          }
          $btnShadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['btnShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['btnShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['btnShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['btnShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['btnShadow']['color']));
        }

     $selectors = array(
      " .affiliate-abbtn" => array(
        "box-shadow" => implode('',$btnShadow),
        'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily']),
        'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop'], $attr['titlefontSizeType']),
        'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight']),
        'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle']),
        'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight']),
        'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing'], $attr['titleletterSpacingType']),
        'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration']),
        'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform']),
        "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor']),
        "background" => AFFILIATE_Helper::get_css_value($btnBg),
        "border-color" => AFFILIATE_Helper::get_css_value($attr['titleBorderColor']),
        "border-style" => AFFILIATE_Helper::get_css_value($attr['titleBorderType']),
        "border-width" => AFFILIATE_Helper::get_css_value($attr['titleBorder']['top'], AFFILIATE_Helper::get_css_value($attr['titleBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorder']['right'], AFFILIATE_Helper::get_css_value($attr['titleBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorder']['bottom'], AFFILIATE_Helper::get_css_value($attr['titleBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorder']['left'], AFFILIATE_Helper::get_css_value($attr['titleBorder']['unit'])),
        "border-radius" => AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['top'], AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['right'], AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['bottom'], AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['left'], AFFILIATE_Helper::get_css_value($attr['titleBorderRadius']['unit'])),
        "text-align" => AFFILIATE_Helper::get_css_value($attr['titleAlignment']),
        'padding' => AFFILIATE_Helper::get_css_value($attr['titlePadding']['top'], AFFILIATE_Helper::get_css_value($attr['titlePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titlePadding']['right'], AFFILIATE_Helper::get_css_value($attr['titlePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titlePadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['titlePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titlePadding']['left'], AFFILIATE_Helper::get_css_value($attr['titlePadding']['unit'])),
        'margin' => AFFILIATE_Helper::get_css_value($attr['titleMargin']['top'], AFFILIATE_Helper::get_css_value($attr['titleMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleMargin']['right'], AFFILIATE_Helper::get_css_value($attr['titleMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['titleMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['titleMargin']['left'], AFFILIATE_Helper::get_css_value($attr['titleMargin']['unit'])),
        ),
        " .affiliate-abbtn-inner" => array(
          "justify-content" => AFFILIATE_Helper::get_css_value($attr['titleAlignment']),
        ),
        " .affiliate-abbtn:hover" => array(
          "color" => AFFILIATE_Helper::get_css_value($attr['titleTextHoverColor']),
          "background" => AFFILIATE_Helper::get_css_value($btnBgHover),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['titleBorderHoverColor']),
        ),
      );

     
       $m_selectors = array(
        " .affiliate-abbtn" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile'], $attr['titlefontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile'], $attr['titleletterSpacingTypeMobile']),
          "text-align" => AFFILIATE_Helper::get_css_value($attr['titleAlignmentMobile']),
        ),
      );

       $t_selectors = array(
         " .affiliate-abbtn" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet'], $attr['titlefontSizeTypeTablet']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet'], $attr['titleletterSpacingTypeTablet']),
        ),
       );

       if($attr['btnBg']['openBg'] == 1){
        if($attr['btnBg']['bgType'] == 'gradient'){
          if ($attr['btnBg']['bgGradient']['type'] == 'linear') {
            $selectors[" .affiliate-abbtn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
          } else {
            $selectors[" .affiliate-abbtn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  


      if($attr['btnBgHover']['openBg'] == 1){
        if($attr['btnBgHover']['bgType'] == 'gradient'){
          if ($attr['btnBgHover']['bgGradient']['type'] == 'linear') {
            $selectors[" .affiliate-abbtn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
          } else {
            $selectors[" .affiliate-abbtn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  


			// @codingStandardsIgnoreEnd

       $regenerate_d = AFFILIATE_Helper::regenerate_stylesheet($selectors);
       $regenerate_m = AFFILIATE_Helper::regenerate_stylesheet($t_selectors);
       $regenerate_t = AFFILIATE_Helper::regenerate_stylesheet($m_selectors);

       $desktop = AFFILIATE_Helper::generate_css( $regenerate_d, '#affiliate-style-' . $id );

       $tablet = AFFILIATE_Helper::generate_css( $regenerate_t, '#affiliate-style-' . $id );

       $mobile = AFFILIATE_Helper::generate_css( $regenerate_m, '#affiliate-style-' . $id );

       $generated_css = array(
        'desktop' => $desktop,
        'tablet'  => $tablet,
        'mobile'  => $mobile,
      );
       return $generated_css;
    }

    public static function blocks_abbutton_gfont($attr) {
      $title_load_google_font = isset($attr['titleLoadGoogleFonts']) ? $attr['titleLoadGoogleFonts'] : '';
      $title_font_family      = isset($attr['titlefontFamily']) ? $attr['titlefontFamily'] : '';
      $title_font_weight      = isset($attr['titlefontWeight']) ? $attr['titlefontWeight'] : '';
      $title_font_subset      = isset($attr['titlefontSubset']) ? $attr['titlefontSubset'] : '';

      AFFILIATE_Helper::blocks_google_font($title_load_google_font, $title_font_family, $title_font_weight, $title_font_subset);
    }

    public static function get_starating_css( $attr, $id ) {
      // @codingStandardsIgnoreStart

      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-star-rating']['attributes'];

      $attr = array_merge( $defaults, $attr );
      
      $m_selectors = array();
      $t_selectors = array();
      
      $selectors = array(
        " .affiliate-star-inner-container" => array(
          "justify-content" => AFFILIATE_Helper::get_css_value($attr['ratingAlignemt']), 
        ),
        " .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSize'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSize'], 'px'),
        ),

        " .affiliate-star-item:not(:last-child)" => array(
          'padding-right' => AFFILIATE_Helper::get_css_value($attr['itemPadding'], 'px'),
        ),
      );
      
      $m_selectors = array(
        " .affiliate-star-inner-container" => array(
          "justify-content" => AFFILIATE_Helper::get_css_value($attr['ratingAlignemtMobile']),  
        ),
        " .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile'], 'px'),
        ),

        " .affiliate-star-item:not(:last-child)" => array(
          'padding-right' => AFFILIATE_Helper::get_css_value($attr['itemPaddingMobile'], 'px'),
        ),
      );

      $t_selectors = array(
        " .affiliate-star-inner-container" => array(
          "justify-content" => AFFILIATE_Helper::get_css_value($attr['ratingAlignemtTablet']),  
        ),
        " .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet'], 'px'),
        ),

        " .affiliate-star-item:not(:last-child)" => array(
          'padding-right' => AFFILIATE_Helper::get_css_value($attr['itemPaddingTablet'], 'px'),
        ),
      );
      
      $regenerate_d = AFFILIATE_Helper::regenerate_stylesheet($selectors);
      $regenerate_t = AFFILIATE_Helper::regenerate_stylesheet($t_selectors);
      $regenerate_m = AFFILIATE_Helper::regenerate_stylesheet($m_selectors);

      $desktop = AFFILIATE_Helper::generate_css( $regenerate_d, '#affiliate-style-' . $id );

      $tablet = AFFILIATE_Helper::generate_css( $regenerate_t, '#affiliate-style-' . $id );

      $mobile = AFFILIATE_Helper::generate_css( $regenerate_m, '#affiliate-style-' . $id );

      $generated_css = array(
        'desktop' => $desktop,
        'tablet'  => $tablet,
        'mobile'  => $mobile,
      );
      return $generated_css;
    }

    public static function get_sp_css( $attr, $id ) {       // @codingStandardsIgnoreStart

        $inset = '';
        $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-single-product']['attributes'];

        $attr = array_merge( $defaults, $attr );

        $btnBg = '';
        if($attr['btnBg']['openBg'] == 1){
            if($attr['btnBg']['bgType'] == 'color'){
               $btnBg = $attr['btnBg']['bgDefaultColor']; 
            }
        } 

        $btnBgHover = '';
        if($attr['btnBgHover']['openBg'] == 1){
            if($attr['btnBgHover']['bgType'] == 'color'){
               $btnBgHover = $attr['btnBgHover']['bgDefaultColor']; 
            }
        } 

        $m_selectors = array();
        $t_selectors = array();

        $boxshadow = array();
        if (!empty($attr['boxShadow']['openShadow']) && $attr['boxShadow']['openShadow'] == 1) {
          if ($attr['boxShadow']['inset'] === 0) {
            $inset = '';
          } else {
            $inset = $attr['boxShadow']['inset'];
          }
          $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
        }

        $selectors = array(
          " .affiliate-sp-wrapper" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['boxPadding']['top'], AFFILIATE_Helper::get_css_value($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPadding']['right'], AFFILIATE_Helper::get_css_value($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPadding']['left'], AFFILIATE_Helper::get_css_value($attr['boxPadding']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['boxMargin']['top'], AFFILIATE_Helper::get_css_value($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMargin']['right'], AFFILIATE_Helper::get_css_value($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMargin']['left'], AFFILIATE_Helper::get_css_value($attr['boxMargin']['unit'])),
            'border-style' => AFFILIATE_Helper::get_css_value($attr['boxBorderType']),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['boxBorderColor']),
            'border-width' => AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['top'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['right'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['left'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])),
            "box-shadow"=>implode(',',$boxshadow),
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBackgroundColor']),
          ),
          " .affiliate-sp-image" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding']['unit'])),
          ),
          " .affiliate-sp-image img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth'],'px'),
          ),
          " .affiliate-list li:before" => array(
            'color' => AFFILIATE_Helper::get_css_value($attr['iconColor']),
          ),
          " .affiliate-sp-title" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop'], $attr['titlefontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing'], $attr['titleletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor']),
            "text-align" => AFFILIATE_Helper::get_css_value($attr['titleAlignment']),
          ),
          " .affiliate-sp-subtitle" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['subtitlefontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['subtitlefontSizeDesktop'], $attr['subtitlefontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['subtitlefontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['subtitlefontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['subtitlelineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['subtitleletterSpacing'], $attr['subtitleletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['subtitletextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['subtitletextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['subtitleTextColor']),
            "text-align" => AFFILIATE_Helper::get_css_value($attr['subtitleAlignment']),
          ),
          " .affiliate-sp-content li, .affiliate-pr-pcontent" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['cntnfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeDesktop'], $attr['cntnfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['cntnfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['cntnfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacing'], $attr['cntnletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['cntntextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['cntntextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['cntnTextColor']),
            "text-align" => AFFILIATE_Helper::get_css_value($attr['cntnAlignment']),
          ),
          " .affiliate-sp-btn" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop'], $attr['btnfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing'], $attr['btnletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform']),
            "background" => AFFILIATE_Helper::get_css_value($btnBg),
            "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor']),
            "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor']),
            "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])),
          ),
          " .affiliate-sp-btn:hover" => array(
            "color" => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor']),
            "background" => AFFILIATE_Helper::get_css_value($btnBgHover),
            "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor']),
          ),
        );


        $m_selectors = array(
          " .affiliate-sp-wrapper" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['top'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['right'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['left'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])),
          ),
          " .affiliate-sp-image" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['unit'])),
         ),
          " .affiliate-sp-image img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile'],'px'),
          ),
          " .affiliate-sp-title" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile'], $attr['titlefontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile'], $attr['titleletterSpacingTypeMobile']),
            "text-align" => AFFILIATE_Helper::get_css_value($attr['titleAlignmentMobile']),
          ),
          " .affiliate-sp-subtitle" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['subtitlefontSizeMobile'], $attr['subtitlefontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['subtitlelineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['subtitleletterSpacingMobile'], $attr['subtitleletterSpacingTypeMobile']),
            "text-align" => AFFILIATE_Helper::get_css_value($attr['subtitleAlignmentMobile']),
          ),
          " .affiliate-sp-content li, .affiliate-pr-pcontent" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeMobile'], $attr['cntnfontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingMobile'], $attr['cntnletterSpacingTypeMobile']),
            "text-align" => AFFILIATE_Helper::get_css_value($attr['cntnAlignmentMobile']),
          ),
          " .affiliate-sp-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile'], $attr['btnfontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile'], $attr['btnletterSpacingTypeMobile']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])),
          ),
        );

        $t_selectors = array(
          " .affiliate-sp-wrapper" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['top'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['right'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['left'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])),
          ),
          " .affiliate-sp-title" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet'], $attr['titlefontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet'], $attr['titleletterSpacingTypeTablet']),
          ),
          " .affiliate-sp-image" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['unit'])),
          ),
          " .affiliate-sp-image img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet'],'px'),
          ),
          " .affiliate-sp-subtitle" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['subtitlefontSizeTablet'], $attr['subtitlefontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['subtitlelineHeightTablet']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['subtitleletterSpacingTablet'], $attr['subtitleletterSpacingTypeTablet']),
          ),
          " .affiliate-sp-content li, .affiliate-pr-pcontent" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeTablet'], $attr['cntnfontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightTablet']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingTablet'], $attr['cntnletterSpacingTypeTablet']),
          ),
          " .affiliate-sp-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet'], $attr['btnfontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet'], $attr['btnletterSpacingTypeTablet']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])),
          ),
        );


        if($attr['btnBg']['openBg'] == 1){
        if($attr['btnBg']['bgType'] == 'gradient'){
          if ($attr['btnBg']['bgGradient']['type'] == 'linear') {
            $selectors[" .affiliate-sp-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
          } else {
            $selectors[" .affiliate-sp-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  


      if($attr['btnBgHover']['openBg'] == 1){
        if($attr['btnBgHover']['bgType'] == 'gradient'){
          if ($attr['btnBgHover']['bgGradient']['type'] == 'linear') {
            $selectors[" .affiliate-sp-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
          } else {
            $selectors[" .affiliate-sp-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  
        
        // @codingStandardsIgnoreEnd

        $regenerate_d = AFFILIATE_Helper::regenerate_stylesheet($selectors);
        $regenerate_m = AFFILIATE_Helper::regenerate_stylesheet($t_selectors);
        $regenerate_t = AFFILIATE_Helper::regenerate_stylesheet($m_selectors);

        $desktop = AFFILIATE_Helper::generate_css( $regenerate_d, '#affiliate-style-' . $id );

        $tablet = AFFILIATE_Helper::generate_css( $regenerate_t, '#affiliate-style-' . $id );

        $mobile = AFFILIATE_Helper::generate_css( $regenerate_m, '#affiliate-style-' . $id );

        $generated_css = array(
          'desktop' => $desktop,
          'tablet'  => $tablet,
          'mobile'  => $mobile,
        );
        return $generated_css;
    }

    public static function blocks_sp_gfont($attr)
    {

      $btn_load_google_font = isset($attr['btnLoadGoogleFonts']) ? $attr['btnLoadGoogleFonts'] : '';
      $btn_font_family      = isset($attr['btnfontFamily']) ? $attr['btnfontFamily'] : '';
      $btn_font_weight      = isset($attr['btnfontWeight']) ? $attr['btnfontWeight'] : '';
      $btn_font_subset      = isset($attr['btnfontSubset']) ? $attr['btnfontSubset'] : '';

      $title_load_google_font = isset($attr['titleLoadGoogleFonts']) ? $attr['titleLoadGoogleFonts'] : '';
      $title_font_family      = isset($attr['titlefontFamily']) ? $attr['titlefontFamily'] : '';
      $title_font_weight      = isset($attr['titlefontWeight']) ? $attr['titlefontWeight'] : '';
      $title_font_subset      = isset($attr['titlefontSubset']) ? $attr['titlefontSubset'] : '';

      $cntn_load_google_font = isset($attr['cntnLoadGoogleFonts']) ? $attr['cntnLoadGoogleFonts'] : '';
      $cntn_font_family      = isset($attr['cntnfontFamily']) ? $attr['cntnfontFamily'] : '';
      $cntn_font_weight      = isset($attr['cntnfontWeight']) ? $attr['cntnfontWeight'] : '';
      $cntn_font_subset      = isset($attr['cntnfontSubset']) ? $attr['cntnfontSubset'] : '';

      $subtitle_load_google_font = isset($attr['subtitleLoadGoogleFonts']) ? $attr['subtitleLoadGoogleFonts'] : '';
      $subtitle_font_family      = isset($attr['subtitlefontFamily']) ? $attr['subtitlefontFamily'] : '';
      $subtitle_font_weight      = isset($attr['subtitlefontWeight']) ? $attr['subtitlefontWeight'] : '';
      $subtitle_font_subset      = isset($attr['subtitlefontSubset']) ? $attr['subtitlefontSubset'] : '';

      AFFILIATE_Helper::blocks_google_font($btn_load_google_font, $btn_font_family, $btn_font_weight, $btn_font_subset);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font, $title_font_family, $title_font_weight, $title_font_subset);
      AFFILIATE_Helper::blocks_google_font($cntn_load_google_font, $cntn_font_family, $cntn_font_weight, $cntn_font_subset);
      AFFILIATE_Helper::blocks_google_font($subtitle_load_google_font, $subtitle_font_family, $subtitle_font_weight, $subtitle_font_subset);
    }

    public static function get_cta_css($attr, $id){    // @codingStandardsIgnoreStart
      
      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-callto-action']['attributes'];

      $attr = array_merge($defaults, (array) $attr);

      $selectors = self::get_cta_selectors($attr);

      $m_selectors = self::get_cta_mobiletselectors($attr);

      $t_selectors = self::get_cta_tabletselectors($attr);
      // // @codingStandardsIgnoreEnd

      $desktop = AFFILIATE_Helper::generate_css($selectors, '#affiliate-style-' . $id);

      $tablet = AFFILIATE_Helper::generate_css($t_selectors, '#affiliate-style-' . $id);

      $mobile = AFFILIATE_Helper::generate_css($m_selectors, '#affiliate-style-' . $id);

      $generated_css = array(
        'desktop' => $desktop,
        'tablet'  => $tablet,
        'mobile'  => $mobile,
      );

      return $generated_css;
    }

    // CTA Desktop Selector
    public static function get_cta_selectors($attr){
     
      $background = $bgurl = $bg_position = $bg_repeat = $bg_size = '';
      if($attr['boxBg']['openBg'] == 1){
          if($attr['boxBg']['bgType'] == 'color'){
             $background = $attr['boxBg']['bgDefaultColor']; 
          }
          if($attr['boxBg']['bgType'] == 'image'){
            $background_img = $attr['boxBg']['bgImage']['url']; 
            $bg_position = $attr['boxBg']['bgimgPosition'];
            $bg_repeat = $attr['boxBg']['bgimgRepeat'];
            $bg_size =  $attr['boxBg']['bgimgSize'];
         }
      }

      $btnBg = '';
      if($attr['btnBg']['openBg'] == 1){
          if($attr['btnBg']['bgType'] == 'color'){
             $btnBg = $attr['btnBg']['bgDefaultColor']; 
          }
      } 

      $btnBgHover = '';
      if($attr['btnBgHover']['openBg'] == 1){
          if($attr['btnBgHover']['bgType'] == 'color'){
             $btnBgHover = $attr['btnBgHover']['bgDefaultColor']; 
          }
      } 

      $boxshadow = array();
        if (!empty($attr['boxShadow']['openShadow']) && $attr['boxShadow']['openShadow'] == 1) {
          if ($attr['boxShadow']['inset'] === 0) {
            $inset = '';
          } else {
            $inset = $attr['boxShadow']['inset'];
          }
          $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
        }

      
      $desktop_css = array(
        " .affiliate-cta-overlay" => array(
            'background-color'=> AFFILIATE_Helper::get_css_value($attr['bgOverlay']),
        ),
        " .affiliate-cta-btn-wrapper" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment']),
        ),
        " .affiliate-cta-inner" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['boxPadding']['top'], AFFILIATE_Helper::get_css_value($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPadding']['right'], AFFILIATE_Helper::get_css_value($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPadding']['left'], AFFILIATE_Helper::get_css_value($attr['boxPadding']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['boxMargin']['top'], AFFILIATE_Helper::get_css_value($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMargin']['right'], AFFILIATE_Helper::get_css_value($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMargin']['left'], AFFILIATE_Helper::get_css_value($attr['boxMargin']['unit'])),
            "background" => AFFILIATE_Helper::get_css_value($background),
            "background-image" => (isset( $background_img) && isset( $background_img ) ) ? 'url("'.$background_img.'")' : null,
            'background-position'=> AFFILIATE_Helper::get_css_value($bg_position),
            'background-repeat'=> AFFILIATE_Helper::get_css_value($bg_repeat),
            'background-size'=> AFFILIATE_Helper::get_css_value($bg_size),
            'text-align'=> AFFILIATE_Helper::get_css_value($attr['boxAlignment']),
            "box-shadow"=>implode(',',$boxshadow),

            'border-style' => AFFILIATE_Helper::get_css_value($attr['boxBorderType']),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['boxBorderColor']),
            'border-width' => AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['top'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['right'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['left'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])),
            

        ),
        "  .affiliate-cta-inner p.affiliate-cta-title, .affiliate-cta-inner p.affiliate-cta-content" => array(
             'text-align'=> AFFILIATE_Helper::get_css_value($attr['boxAlignment']),
        ),
        " .affiliate-cta-title" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop'], $attr['titlefontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing'], $attr['titleletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor']),
        ),
        " .affiliate-cta-content" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['cntnfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeDesktop'], $attr['cntnfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['cntnfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['cntnfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacing'], $attr['cntnletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['cntntextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['cntntextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['cntnTextColor']),
        ),

        " .affiliate-cta-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop'], $attr['btnfontSizeType']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing'], $attr['btnletterSpacingType']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor']),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])),
        ),
        " .affiliate-cta-btn:hover" => array(
            "color" => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor']),
            "background" => AFFILIATE_Helper::get_css_value($btnBgHover),
            "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor']),
        ),
      );
      if($attr['boxBg']['openBg'] == 1){
        if($attr['boxBg']['bgType'] == 'gradient'){
          if ($attr['boxBg']['bgGradient']['type'] == 'linear') {
            $desktop_css[" .affiliate-cta-inner"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['stop'], '%') . ')';
          } else {
            $desktop_css[" .affiliate-cta-inner"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBg']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  


      if($attr['btnBg']['openBg'] == 1){
        if($attr['btnBg']['bgType'] == 'gradient'){
          if ($attr['btnBg']['bgGradient']['type'] == 'linear') {
            $desktop_css[" .affiliate-cta-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
          } else {
            $desktop_css[" .affiliate-cta-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  


      if($attr['btnBgHover']['openBg'] == 1){
        if($attr['btnBgHover']['bgType'] == 'gradient'){
          if ($attr['btnBgHover']['bgGradient']['type'] == 'linear') {
            $desktop_css[" .affiliate-cta-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
          } else {
            $desktop_css[" .affiliate-cta-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
          }
        }
      }  

      $desktop_css =  AFFILIATE_Helper::regenerate_stylesheet($desktop_css);

      return $desktop_css;
    }

    // CTA Mobile Selector
    public static function get_cta_mobiletselectors($attr){
      $mobile_css = array(
        " .affiliate-cta-inner" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['top'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['right'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['left'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])),
          'text-align'=> AFFILIATE_Helper::get_css_value($attr['boxAlignmentMobile']),  
        ),
        " .affiliate-cta-title" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile'], $attr['titlefontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile'], $attr['titleletterSpacingTypeMobile']),
      ),
        " .affiliate-cta-content" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeMobile'], $attr['cntnfontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingMobile'], $attr['cntnletterSpacingTypeMobile']),
        ),
        " .affiliate-cta-btn" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile'], $attr['btnfontSizeTypeMobile']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile'], $attr['btnletterSpacingTypeMobile']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])),
        ),
      );

      $mobile_css =  AFFILIATE_Helper::regenerate_stylesheet($mobile_css);

      return $mobile_css;
    }

    // CTA Content Selector
    public static function get_cta_tabletselectors($attr){
      
      
      $tablet_css = array(
        " .affiliate-cta-inner" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['top'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['right'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['left'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])),
          'text-align'=> AFFILIATE_Helper::get_css_value($attr['boxAlignmentTablet']),  
        ),
        " .affiliate-cta-title" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet'], $attr['titlefontSizeTypeTablet']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet'], $attr['titleletterSpacingTypeTablet']),
      ),
        " .affiliate-cta-content" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['cntnfontSizeTablet'], $attr['cntnfontSizeTypeTablet']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['cntnlineHeightTablet']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['cntnletterSpacingTablet'], $attr['cntnletterSpacingTypeTablet']),
        ),
        " .affiliate-cta-btn" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet'], $attr['btnfontSizeTypeTablet']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet'], $attr['btnletterSpacingTypeTablet']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])),
        ),
      );

      $tablet_css =  AFFILIATE_Helper::regenerate_stylesheet($tablet_css);

      return $tablet_css;
    }

    public static function blocks_cta_gfont($attr)
    {

      $btn_load_google_font = isset($attr['btnLoadGoogleFonts']) ? $attr['btnLoadGoogleFonts'] : '';
      $btn_font_family      = isset($attr['btnfontFamily']) ? $attr['btnfontFamily'] : '';
      $btn_font_weight      = isset($attr['btnfontWeight']) ? $attr['btnfontWeight'] : '';
      $btn_font_subset      = isset($attr['btnfontSubset']) ? $attr['btnfontSubset'] : '';

      $title_load_google_font = isset($attr['titleLoadGoogleFonts']) ? $attr['titleLoadGoogleFonts'] : '';
      $title_font_family      = isset($attr['titlefontFamily']) ? $attr['titlefontFamily'] : '';
      $title_font_weight      = isset($attr['titlefontWeight']) ? $attr['titlefontWeight'] : '';
      $title_font_subset      = isset($attr['titlefontSubset']) ? $attr['titlefontSubset'] : '';

      $cntn_load_google_font = isset($attr['cntnLoadGoogleFonts']) ? $attr['cntnLoadGoogleFonts'] : '';
      $cntn_font_family      = isset($attr['cntnfontFamily']) ? $attr['cntnfontFamily'] : '';
      $cntn_font_weight      = isset($attr['cntnfontWeight']) ? $attr['cntnfontWeight'] : '';
      $cntn_font_subset      = isset($attr['cntnfontSubset']) ? $attr['cntnfontSubset'] : '';

      AFFILIATE_Helper::blocks_google_font($btn_load_google_font, $btn_font_family, $btn_font_weight, $btn_font_subset);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font, $title_font_family, $title_font_weight, $title_font_subset);
      AFFILIATE_Helper::blocks_google_font($cntn_load_google_font, $cntn_font_family, $cntn_font_weight, $cntn_font_subset);
    }


    public static function get_coupon2_css( $attr, $id ) {
      // @codingStandardsIgnoreStart
      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-coupon2']['attributes'];

      $attr = array_merge( $defaults, $attr );


      $btnBg = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg']['openBg'] == 1){
          if($attr['btnBg']['bgType'] == 'color'){
             $btnBg = $attr['btnBg']['bgDefaultColor']; 
          }
      } 

      $btnBgHover = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover']['openBg'] == 1){
          if($attr['btnBgHover']['bgType'] == 'color'){
             $btnBgHover = $attr['btnBgHover']['bgDefaultColor']; 
          }
      }

      $btnBg2 = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg2']['openBg'] == 1){
          if($attr['btnBg2']['bgType'] == 'color'){
             $btnBg2 = $attr['btnBg2']['bgDefaultColor']; 
          }
      } 

      $btnBgHover2 = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover2']['openBg'] == 1){
          if($attr['btnBgHover2']['bgType'] == 'color'){
             $btnBgHover2 = $attr['btnBgHover2']['bgDefaultColor']; 
          }
      }


      $btnBg3 = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg3']['openBg'] == 1){
          if($attr['btnBg3']['bgType'] == 'color'){
             $btnBg3 = $attr['btnBg3']['bgDefaultColor']; 
          }
      } 

      $btnBgHover3 = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover3']['openBg'] == 1){
          if($attr['btnBgHover3']['bgType'] == 'color'){
             $btnBgHover3 = $attr['btnBgHover3']['bgDefaultColor']; 
          }
      }


      $btnBg4 = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg4']['openBg'] == 1){
          if($attr['btnBg4']['bgType'] == 'color'){
             $btnBg4 = $attr['btnBg4']['bgDefaultColor']; 
          }
      } 

      $btnBgHover4 = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover4']['openBg'] == 1){
          if($attr['btnBgHover4']['bgType'] == 'color'){
             $btnBgHover4 = $attr['btnBgHover4']['bgDefaultColor']; 
          }
      }

      $btnBg5 = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg5']['openBg'] == 1){
          if($attr['btnBg5']['bgType'] == 'color'){
             $btnBg5 = $attr['btnBg5']['bgDefaultColor']; 
          }
      } 

      $btnBgHover5 = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover5']['openBg'] == 1){
          if($attr['btnBgHover5']['bgType'] == 'color'){
             $btnBgHover5 = $attr['btnBgHover5']['bgDefaultColor']; 
          }
      }


      $btnBg6 = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg6']['openBg'] == 1){
          if($attr['btnBg6']['bgType'] == 'color'){
             $btnBg6 = $attr['btnBg6']['bgDefaultColor']; 
          }
      } 

      $btnBgHover6 = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover6']['openBg'] == 1){
          if($attr['btnBgHover6']['bgType'] == 'color'){
             $btnBgHover6 = $attr['btnBgHover6']['bgDefaultColor']; 
          }
      }
  
      $btnBg7 = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg7']['openBg'] == 1){
          if($attr['btnBg7']['bgType'] == 'color'){
             $btnBg7 = $attr['btnBg7']['bgDefaultColor']; 
          }
      } 

      $btnBgHover7 = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover7']['openBg'] == 1){
          if($attr['btnBgHover7']['bgType'] == 'color'){
             $btnBgHover7 = $attr['btnBgHover7']['bgDefaultColor']; 
          }
      }      


      if($attr['btnBorderColor1'] == ''){
        $attr['btnBorderColor1'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor1'] == ''){
        $attr['btnBorderHoverColor1'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType1'] == ''){
        $attr['btnBorderType1'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor1'] == ''){
        $attr['btnTextColor1'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor1'] == ''){
        $attr['btnTextHoverColor1'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }


      if($attr['btnBorderColor2'] == ''){
        $attr['btnBorderColor2'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor2'] == ''){
        $attr['btnBorderHoverColor2'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType2'] == ''){
        $attr['btnBorderType2'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor2'] == ''){
        $attr['btnTextColor2'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor2'] == ''){
        $attr['btnTextHoverColor2'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }


      if($attr['btnBorderColor3'] == ''){
        $attr['btnBorderColor3'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor3'] == ''){
        $attr['btnBorderHoverColor3'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType3'] == ''){
        $attr['btnBorderType3'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor3'] == ''){
        $attr['btnTextColor3'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor3'] == ''){
        $attr['btnTextHoverColor3'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }


      if($attr['btnBorderColor4'] == ''){
        $attr['btnBorderColor4'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor4'] == ''){
        $attr['btnBorderHoverColor4'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType4'] == ''){
        $attr['btnBorderType4'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor4'] == ''){
        $attr['btnTextColor4'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor4'] == ''){
        $attr['btnTextHoverColor4'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }


      if($attr['btnBorderColor5'] == ''){
        $attr['btnBorderColor5'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor5'] == ''){
        $attr['btnBorderHoverColor5'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType5'] == ''){
        $attr['btnBorderType5'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor5'] == ''){
        $attr['btnTextColor5'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor5'] == ''){
        $attr['btnTextHoverColor5'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }



      if($attr['btnBorderColor6'] == ''){
        $attr['btnBorderColor6'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor6'] == ''){
        $attr['btnBorderHoverColor6'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType6'] == ''){
        $attr['btnBorderType6'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor6'] == ''){
        $attr['btnTextColor6'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor6'] == ''){
        $attr['btnTextHoverColor6'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }


      if($attr['btnBorderColor7'] == ''){
        $attr['btnBorderColor7'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor7'] == ''){
        $attr['btnBorderHoverColor7'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType7'] == ''){
        $attr['btnBorderType7'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor7'] == ''){
        $attr['btnTextColor7'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor7'] == ''){
        $attr['btnTextHoverColor7'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }

      
      $m_selectors = array();
      $t_selectors = array();
      $boxshadow = array();
        if (!empty($attr['boxShadow']['openShadow']) && $attr['boxShadow']['openShadow'] == 1) {
          if ($attr['boxShadow']['inset'] === 0) {
            $inset = '';
          } else {
            $inset = $attr['boxShadow']['inset'];
          }
          $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
        }

      
      $selectors = array(

        " .affiliate-coupon-inner.style2 .affiliate-block-col" => array(
            "box-shadow"=>implode(',',$boxshadow),
        ),

        " .affiliate-coupon-inner.style2 .affiliate-block-col.box1" => array(
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBgColor1']),
        ),
        " .box1 .affiliate-coupon-starrating1 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSize1'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSize1'], 'px'),
        ),

        " .alignfull .affiliate-coupon-inner.style2" => array(
           'padding' => AFFILIATE_Helper::get_css_value1($attr['boxPadding']['top'], AFFILIATE_Helper::get_css_value1($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPadding']['right'], AFFILIATE_Helper::get_css_value1($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPadding']['bottom'], AFFILIATE_Helper::get_css_value1($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPadding']['left'], AFFILIATE_Helper::get_css_value1($attr['boxPadding']['unit'])),
        ),

        " .alignwide .affiliate-coupon-inner.style2" => array(
           'margin' => AFFILIATE_Helper::get_css_value1($attr['boxMargin']['top'], AFFILIATE_Helper::get_css_value1($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMargin']['right'], AFFILIATE_Helper::get_css_value1($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMargin']['bottom'], AFFILIATE_Helper::get_css_value1($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMargin']['left'], AFFILIATE_Helper::get_css_value1($attr['boxMargin']['unit'])),
        ),


        " .box1 .affiliate-coupon-image1" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding1']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding1']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding1']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding1']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding1']['unit'])),
        ),
        " .box1 .affiliate-coupon-image1 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth1'],'px'),
        ),
        " .box1 .affiliate-coupon-title1 " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily1']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop1'], $attr['titlefontSizeType1']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight1']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle1']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight1']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing1'], $attr['titleletterSpacingType1']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration1']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform1']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor1']),
        ),        
        " .box1 .affiliate-list1 li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily1']),            
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight1']),
        ),
        " .box1 .affiliate-coupon-btn-wrapper1" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment1']),
        ),   
        
        " .affiliate-coupon-btn-wrapper1" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment1']),
        ),   
        " .box1 .affiliate-coupon-btn-wrapper1 .affiliate-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily1']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop1'], $attr['btnfontSizeType1']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight1']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle1']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight1']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing1'], $attr['btnletterSpacingType1']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration1']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform1']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor1']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor1']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType1']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder1']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder1']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder1']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder1']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius1']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius1']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius1']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius1']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding1']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding1']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding1']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding1']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin1']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin1']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin1']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin1']['unit'])),
        ),
        " .box1 .affiliate-coupon-btn-wrapper1 .affiliate-btn:hover" => array(
            'background' => AFFILIATE_Helper::get_css_value($btnBgHover),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor1']),
            'color' => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor1']),
        ),     
        " .box1 .affiliate-coupon-list1 li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['listColor1']),
        ),

        " .box1 .affiliate-coupon-list1 li:before" => array(
            'color' => AFFILIATE_Helper::get_css_value($attr['listIconColor1']),
        ),


        " .box1 .affiliate-coupon-content1, .box1 .affiliate-coupon-list1 li " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily1']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeDesktop1'], $attr['listfontSizeType1']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight1']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle1']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeight1']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacing1'], $attr['listletterSpacingType1']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration1']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform1']),
            "color" => AFFILIATE_Helper::get_css_value($attr['listTextColor1']),
        ),    
        " .box1 .affiliate-coupon-toptext1" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['topTextColor1']),
            "background"=>AFFILIATE_Helper::get_css_value($attr['topTextBgColor1']),
            'font-family' => AFFILIATE_Helper::get_css_value($attr['topTextfontFamily1']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['topTextfontSizeDesktop1'], $attr['topTextfontSizeType1']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['topTextfontWeight1']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['topTextfontStyle1']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['topTextlineHeight1']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['topTextletterSpacing1'], $attr['topTextletterSpacingType1']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['topTextDecoration1']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['topTextTransform1']),
        ),
        " .box1 .affiliate-coupon-bottomtext1" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['bottomTextColor1']), 
            'font-family' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontFamily1']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontSizeDesktop1'], $attr['bottomTextfontSizeType1']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontWeight1']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontStyle1']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['bottomTextlineHeight1']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['bottomTextletterSpacing1'], $attr['bottomTextletterSpacingType1']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['bottomTextDecoration1']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['bottomTextTransform1']),
        ),


        //Box2
        " .affiliate-coupon-inner.style2 .affiliate-block-col.box2" => array(
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBgColor2']),
        ),
        " .box2 .affiliate-coupon-starrating2 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSize2'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSize2'], 'px'),
        ),
        " .box2 .affiliate-coupon-image2" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding2']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding2']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding2']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding2']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding2']['unit'])),
        ),
        " .box2 .affiliate-coupon-image2 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth2'],'px'),
        ),
        " .box2 .affiliate-coupon-title2 " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily2']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop2'], $attr['titlefontSizeType2']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight2']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle2']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight2']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing2'], $attr['titleletterSpacingType2']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration2']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform2']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor2']),
        ),        
        " .box2 .affiliate-list2 li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily2']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight2']),
        ),
        " .box2 .affiliate-coupon-btn-wrapper2" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment2']),
        ),   
        " .affiliate-coupon-btn-wrapper2" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment2']),
        ),   
        " .box2 .affiliate-coupon-btn-wrapper2 .affiliate-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily2']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop2'], $attr['btnfontSizeType2']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight2']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle2']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight2']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing2'], $attr['btnletterSpacingType2']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration2']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform2']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg2),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor2']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor2']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType2']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder2']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder2']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder2']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder2']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius2']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius2']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius2']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius2']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding2']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding2']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding2']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding2']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin2']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin2']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin2']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin2']['unit'])),
        ),
        " .box2 .affiliate-coupon-btn-wrapper2 .affiliate-btn:hover" => array(
            'background' => AFFILIATE_Helper::get_css_value($btnBgHover2),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor2']),
            'color' => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor2']),
        ),
        " .box2 .affiliate-coupon-list2 li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['listColor2']),
        ),
        " .box2 .affiliate-coupon-list2 li:before" => array(
            'color' => AFFILIATE_Helper::get_css_value($attr['listIconColor2']),
        ),


        " .box2 .affiliate-coupon-content2, .box2 .affiliate-coupon-list2 li  " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily2']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeDesktop2'], $attr['listfontSizeType2']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight2']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle2']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeight2']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacing2'], $attr['listletterSpacingType2']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration2']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform2']),
            "color" => AFFILIATE_Helper::get_css_value($attr['listTextColor2']),
        ),    
        " .box2 .affiliate-coupon-toptext2" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['topTextColor2']),
            "background"=>AFFILIATE_Helper::get_css_value($attr['topTextBgColor2']),
            'font-family' => AFFILIATE_Helper::get_css_value($attr['topTextfontFamily2']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['topTextfontSizeDesktop2'], $attr['topTextfontSizeType2']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['topTextfontWeight2']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['topTextfontStyle2']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['topTextlineHeight2']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['topTextletterSpacing2'], $attr['topTextletterSpacingType2']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['topTextDecoration2']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['topTextTransform2']),
        ),
        " .box2 .affiliate-coupon-bottomtext2" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['bottomTextColor2']), 
            'font-family' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontFamily2']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontSizeDesktop2'], $attr['bottomTextfontSizeType2']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontWeight2']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontStyle2']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['bottomTextlineHeight2']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['bottomTextletterSpacing2'], $attr['bottomTextletterSpacingType2']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['bottomTextDecoration2']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['bottomTextTransform2']),
        ),



        //Box3
        " .affiliate-coupon-inner.style2 .affiliate-block-col.box3" => array(
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBgColor3']),
        ),
        " .box3 .affiliate-coupon-starrating3 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSize3'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSize3'], 'px'),
        ),
        " .box3 .affiliate-coupon-image3" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding3']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding3']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding3']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding3']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding3']['unit'])),
        ),
        " .box3 .affiliate-coupon-image3 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth3'],'px'),
        ),
        " .box3 .affiliate-coupon-title3 " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily3']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop3'], $attr['titlefontSizeType3']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight3']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle3']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight3']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing3'], $attr['titleletterSpacingType3']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration3']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform3']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor3']),
        ),        
        " .box3 .affiliate-list3 li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily3']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight3']),
        ),
        " .box3 .affiliate-coupon-btn-wrapper3" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment3']),
        ),   
        
        " .affiliate-coupon-btn-wrapper3" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment3']),
        ),   
        " .box3 .affiliate-coupon-btn-wrapper3 .affiliate-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily3']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop3'], $attr['btnfontSizeType3']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight3']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle3']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight3']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing3'], $attr['btnletterSpacingType3']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration3']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform3']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg3),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor3']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor3']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType3']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder3']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder3']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder3']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder3']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius3']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius3']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius3']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius3']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding3']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding3']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding3']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding3']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin3']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin3']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin3']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin3']['unit'])),
        ),
        " .box3 .affiliate-coupon-btn-wrapper3 .affiliate-btn:hover" => array(
            'background' => AFFILIATE_Helper::get_css_value($btnBgHover3),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor3']),
            'color' => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor3']),
        ),   
        " .box3 .affiliate-coupon-list3 li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['listColor3']),
        ),
        " .box3 .affiliate-coupon-list3 li:before" => array(
            'color' => AFFILIATE_Helper::get_css_value($attr['listIconColor3']),
        ),

        " .box3 .affiliate-coupon-content3, .box3 .affiliate-coupon-list3 li  " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily3']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeDesktop3'], $attr['listfontSizeType3']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight3']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle3']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeight3']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacing3'], $attr['listletterSpacingType3']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration3']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform3']),
            "color" => AFFILIATE_Helper::get_css_value($attr['listTextColor3']),
        ),    
        " .box3 .affiliate-coupon-toptext3" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['topTextColor3']),
            "background"=>AFFILIATE_Helper::get_css_value($attr['topTextBgColor3']),
            'font-family' => AFFILIATE_Helper::get_css_value($attr['topTextfontFamily3']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['topTextfontSizeDesktop3'], $attr['topTextfontSizeType3']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['topTextfontWeight3']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['topTextfontStyle3']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['topTextlineHeight3']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['topTextletterSpacing3'], $attr['topTextletterSpacingType3']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['topTextDecoration3']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['topTextTransform3']),
        ),
        " .box3 .affiliate-coupon-bottomtext3" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['bottomTextColor3']), 
            'font-family' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontFamily3']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontSizeDesktop3'], $attr['bottomTextfontSizeType3']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontWeight3']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontStyle3']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['bottomTextlineHeight3']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['bottomTextletterSpacing3'], $attr['bottomTextletterSpacingType3']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['bottomTextDecoration3']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['bottomTextTransform3']),
        ),


        //Box4
        " .affiliate-coupon-inner.style2 .affiliate-block-col.box4" => array(
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBgColor4']),
        ),
        " .box4 .affiliate-coupon-starrating4 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSize4'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSize4'], 'px'),
        ),
        " .box4 .affiliate-coupon-image4" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding4']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding4']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding4']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding4']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding4']['unit'])),
        ),
        " .box4 .affiliate-coupon-image4 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth4'],'px'),
        ),
        " .box4 .affiliate-coupon-title4 " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily4']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop4'], $attr['titlefontSizeType4']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight4']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle4']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight4']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing4'], $attr['titleletterSpacingType4']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration4']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform4']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor4']),
        ),        
        " .box4 .affiliate-list4 li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily4']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight4']),
        ),
        " .box4 .affiliate-coupon-btn-wrapper4" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment4']),
        ),   
        
        " .affiliate-coupon-btn-wrapper4" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment4']),
        ),   
        " .box4 .affiliate-coupon-btn-wrapper4 .affiliate-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily4']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop4'], $attr['btnfontSizeType4']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight4']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle4']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight4']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing4'], $attr['btnletterSpacingType4']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration4']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform4']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg4),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor4']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor4']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType4']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder4']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder4']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder4']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder4']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius4']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius4']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius4']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius4']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding4']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding4']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding4']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding4']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin4']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin4']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin4']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin4']['unit'])),
        ),
        " .box4 .affiliate-coupon-btn-wrapper4 .affiliate-btn:hover" => array(
            'background' => AFFILIATE_Helper::get_css_value($btnBgHover4),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor4']),
            'color' => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor4']),
        ),   
        " .box4 .affiliate-coupon-list4 li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['listColor4']),
        ),
        " .box4 .affiliate-coupon-list4 li:before" => array(
            'color' => AFFILIATE_Helper::get_css_value($attr['listIconColor4']),
        ),


        " .box4 .affiliate-coupon-content4, .box4 .affiliate-coupon-list4 li  " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily4']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeDesktop4'], $attr['listfontSizeType4']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight4']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle4']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeight4']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacing4'], $attr['listletterSpacingType4']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration4']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform4']),
            "color" => AFFILIATE_Helper::get_css_value($attr['listTextColor4']),
        ),    
        " .box4 .affiliate-coupon-toptext4" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['topTextColor4']),
            "background"=>AFFILIATE_Helper::get_css_value($attr['topTextBgColor4']),
            'font-family' => AFFILIATE_Helper::get_css_value($attr['topTextfontFamily4']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['topTextfontSizeDesktop4'], $attr['topTextfontSizeType4']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['topTextfontWeight4']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['topTextfontStyle4']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['topTextlineHeight4']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['topTextletterSpacing4'], $attr['topTextletterSpacingType4']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['topTextDecoration4']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['topTextTransform4']),
        ),
        " .box4 .affiliate-coupon-bottomtext4" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['bottomTextColor4']), 
            'font-family' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontFamily4']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontSizeDesktop4'], $attr['bottomTextfontSizeType4']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontWeight4']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontStyle4']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['bottomTextlineHeight4']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['bottomTextletterSpacing4'], $attr['bottomTextletterSpacingType4']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['bottomTextDecoration4']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['bottomTextTransform4']),
        ),


        //Box5
        " .affiliate-coupon-inner.style2 .affiliate-block-col.box5" => array(
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBgColor5']),
        ),
        " .box5 .affiliate-coupon-starrating5 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSize5'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSize5'], 'px'),
        ),
        " .box5 .affiliate-coupon-image5" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding5']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding5']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding5']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding5']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding5']['unit'])),
        ),
        " .box5 .affiliate-coupon-image5 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth5'],'px'),
        ),
        " .box5 .affiliate-coupon-title5 " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily5']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop5'], $attr['titlefontSizeType5']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight5']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle5']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight5']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing5'], $attr['titleletterSpacingType5']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration5']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform5']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor5']),
        ),        
        " .box5 .affiliate-list5 li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily5']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight5']),
        ),
        " .box5 .affiliate-coupon-btn-wrapper5" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment5']),
        ),   
        
        " .affiliate-coupon-btn-wrapper5" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment5']),
        ),   
        " .box5 .affiliate-coupon-btn-wrapper5 .affiliate-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily5']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop5'], $attr['btnfontSizeType5']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight5']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle5']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight5']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing5'], $attr['btnletterSpacingType5']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration5']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform5']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg5),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor5']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor5']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType5']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder5']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder5']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder5']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder5']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius5']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius5']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius5']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius5']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding5']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding5']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding5']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding5']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin5']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin5']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin5']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin5']['unit'])),
        ),
        " .box5 .affiliate-coupon-btn-wrapper5 .affiliate-btn:hover" => array(
            'background' => AFFILIATE_Helper::get_css_value($btnBgHover5),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor5']),
            'color' => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor5']),
        ),   
        " .box5 .affiliate-coupon-list5 li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['listColor5']),
        ),
        " .box5 .affiliate-coupon-list5 li:before" => array(
            'color' => AFFILIATE_Helper::get_css_value($attr['listIconColor5']),
        ),


        " .box5 .affiliate-coupon-content5, .box5 .affiliate-coupon-list5 li  " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily5']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeDesktop5'], $attr['listfontSizeType5']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight5']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle5']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeight5']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacing5'], $attr['listletterSpacingType5']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration5']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform5']),
            "color" => AFFILIATE_Helper::get_css_value($attr['listTextColor5']),
        ),    
        " .box5 .affiliate-coupon-toptext5" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['topTextColor5']),
            "background"=>AFFILIATE_Helper::get_css_value($attr['topTextBgColor5']),
            'font-family' => AFFILIATE_Helper::get_css_value($attr['topTextfontFamily5']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['topTextfontSizeDesktop5'], $attr['topTextfontSizeType5']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['topTextfontWeight5']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['topTextfontStyle5']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['topTextlineHeight5']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['topTextletterSpacing5'], $attr['topTextletterSpacingType5']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['topTextDecoration5']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['topTextTransform5']),
        ),
        " .box5 .affiliate-coupon-bottomtext5" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['bottomTextColor5']), 
            'font-family' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontFamily5']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontSizeDesktop5'], $attr['bottomTextfontSizeType5']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontWeight5']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontStyle5']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['bottomTextlineHeight5']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['bottomTextletterSpacing5'], $attr['bottomTextletterSpacingType5']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['bottomTextDecoration5']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['bottomTextTransform5']),
        ),

        //Box6
        " .affiliate-coupon-inner.style2 .affiliate-block-col.box6" => array(
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBgColor6']),
        ),
        " .box6 .affiliate-coupon-starrating6 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSize6'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSize6'], 'px'),
        ),
        " .box6 .affiliate-coupon-image6" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding6']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding6']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding6']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding6']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding6']['unit'])),
        ),
        " .box6 .affiliate-coupon-image6 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth6'],'px'),
        ),
        " .box6 .affiliate-coupon-title6 " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily6']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop6'], $attr['titlefontSizeType6']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight6']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle6']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight6']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing6'], $attr['titleletterSpacingType6']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration6']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform6']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor6']),
        ),        
        " .box6 .affiliate-list6 li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily6']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight6']),
        ),
        " .box6 .affiliate-coupon-btn-wrapper6" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment6']),
        ),   
        
        " .affiliate-coupon-btn-wrapper6" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment6']),
        ),   
        " .box6 .affiliate-coupon-btn-wrapper6 .affiliate-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily6']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop6'], $attr['btnfontSizeType6']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight6']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle6']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight6']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing6'], $attr['btnletterSpacingType6']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration6']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform6']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg6),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor6']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor6']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType6']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder6']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder6']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder6']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder6']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius6']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius6']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius6']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius6']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding6']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding6']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding6']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding6']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin6']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin6']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin6']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin6']['unit'])),
        ),
        " .box6 .affiliate-coupon-btn-wrapper6 .affiliate-btn:hover" => array(
            'background' => AFFILIATE_Helper::get_css_value($btnBgHover6),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor6']),
            'color' => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor6']),
        ),   
        " .box6 .affiliate-coupon-list6 li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['listColor6']),
        ),
        " .box6 .affiliate-coupon-list6 li:before" => array(
            'color' => AFFILIATE_Helper::get_css_value($attr['listIconColor6']),
        ),


        " .box6 .affiliate-coupon-content6, .box6 .affiliate-coupon-list6 li  " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily6']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeDesktop6'], $attr['listfontSizeType6']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight6']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle6']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeight6']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacing6'], $attr['listletterSpacingType6']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration6']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform6']),
            "color" => AFFILIATE_Helper::get_css_value($attr['listTextColor6']),
        ),    
        " .box6 .affiliate-coupon-toptext6" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['topTextColor6']),
            "background"=>AFFILIATE_Helper::get_css_value($attr['topTextBgColor6']),
            'font-family' => AFFILIATE_Helper::get_css_value($attr['topTextfontFamily6']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['topTextfontSizeDesktop6'], $attr['topTextfontSizeType6']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['topTextfontWeight6']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['topTextfontStyle6']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['topTextlineHeight6']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['topTextletterSpacing6'], $attr['topTextletterSpacingType6']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['topTextDecoration6']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['topTextTransform6']),
        ),
        " .box6 .affiliate-coupon-bottomtext6" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['bottomTextColor6']), 
            'font-family' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontFamily6']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontSizeDesktop6'], $attr['bottomTextfontSizeType6']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontWeight6']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontStyle6']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['bottomTextlineHeight6']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['bottomTextletterSpacing6'], $attr['bottomTextletterSpacingType6']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['bottomTextDecoration6']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['bottomTextTransform6']),
        ),

        //Box7
        " .affiliate-coupon-inner.style2 .affiliate-block-col.box7" => array(
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBgColor7']),
        ),
        " .box7 .affiliate-coupon-starrating7 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSize7'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSize7'], 'px'),
        ),
        " .box7 .affiliate-coupon-image7" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding7']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding7']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding7']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding7']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding7']['unit'])),
        ),
        " .box7 .affiliate-coupon-image7 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth7'],'px'),
        ),
        " .box7 .affiliate-coupon-title7 " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily7']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop7'], $attr['titlefontSizeType7']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight7']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle7']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight7']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing7'], $attr['titleletterSpacingType7']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration7']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform7']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor7']),
        ),        
        " .box7 .affiliate-list7 li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily7']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight7']),
        ),
        " .box7 .affiliate-coupon-btn-wrapper7" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment7']),
        ),   
        
        " .affiliate-coupon-btn-wrapper7" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment7']),
        ),   
        " .box7 .affiliate-coupon-btn-wrapper7 .affiliate-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily7']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop7'], $attr['btnfontSizeType7']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight7']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle7']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight7']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing7'], $attr['btnletterSpacingType7']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration7']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform7']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg7),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor7']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor7']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType7']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder7']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder7']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder7']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder7']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius7']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius7']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius7']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius7']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding7']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding7']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding7']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding7']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin7']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin7']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin7']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin7']['unit'])),
        ),
        " .box7 .affiliate-coupon-btn-wrapper7 .affiliate-btn:hover" => array(
            'background' => AFFILIATE_Helper::get_css_value($btnBgHover7),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor7']),
            'color' => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor7']),
        ),   
        " .box7 .affiliate-coupon-list7 li" => array(
          'color' => AFFILIATE_Helper::get_css_value($attr['listColor7']),
        ),
        " .box7 .affiliate-coupon-list7 li:before" => array(
            'color' => AFFILIATE_Helper::get_css_value($attr['listIconColor7']),
        ),

        " .box7 .affiliate-coupon-content7, .box7 .affiliate-coupon-list7 li  " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily7']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeDesktop7'], $attr['listfontSizeType7']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['listfontWeight7']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle7']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeight7']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacing7'], $attr['listletterSpacingType7']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration7']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform7']),
            "color" => AFFILIATE_Helper::get_css_value($attr['listTextColor7']),
        ),    
        " .box7 .affiliate-coupon-toptext7" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['topTextColor7']),
            "background"=>AFFILIATE_Helper::get_css_value($attr['topTextBgColor7']),
            'font-family' => AFFILIATE_Helper::get_css_value($attr['topTextfontFamily7']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['topTextfontSizeDesktop7'], $attr['topTextfontSizeType7']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['topTextfontWeight7']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['topTextfontStyle7']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['topTextlineHeight7']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['topTextletterSpacing7'], $attr['topTextletterSpacingType7']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['topTextDecoration7']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['topTextTransform7']),
        ),
        " .box7 .affiliate-coupon-bottomtext7" => array(
            "color"=>AFFILIATE_Helper::get_css_value($attr['bottomTextColor7']), 
            'font-family' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontFamily7']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontSizeDesktop7'], $attr['bottomTextfontSizeType7']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontWeight7']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['bottomTextfontStyle7']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['bottomTextlineHeight7']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['bottomTextletterSpacing7'], $attr['bottomTextletterSpacingType7']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['bottomTextDecoration7']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['bottomTextTransform7']),
        ),
    
      );
      
      $m_selectors = array(

        " .affiliate-coupon-wrapper.style2.alignfull" => array(
           'padding' => AFFILIATE_Helper::get_css_value1($attr['boxPaddingMobile']['top'], AFFILIATE_Helper::get_css_value1($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPaddingMobile']['right'], AFFILIATE_Helper::get_css_value1($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value1($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPaddingMobile']['left'], AFFILIATE_Helper::get_css_value1($attr['boxPaddingMobile']['unit'])),
        ),

        " .affiliate-coupon-wrapper.style2.alignwide" => array(
           'margin' => AFFILIATE_Helper::get_css_value1($attr['boxMarginMobile']['top'], AFFILIATE_Helper::get_css_value1($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMarginMobile']['right'], AFFILIATE_Helper::get_css_value1($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value1($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMarginMobile']['left'], AFFILIATE_Helper::get_css_value1($attr['boxMarginMobile']['unit'])),
        ),

        " .box1 .affiliate-coupon-title1 " .$attr['titleTag1'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile1'], $attr['titlefontSizeTypeMobile1']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile1']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile1'], $attr['titleletterSpacingTypeMobile1']),
        ),
        " .box1 .affiliate-coupon-starrating1 .affiliate-star-item svg" => array(
            "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile1'], 'px'),  
            "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile1'], 'px'),
        ),
        " .box1 .affiliate-coupon-image1" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile1']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile1']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile1']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile1']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile1']['unit'])),
        ),
        " .box1 .affiliate-coupon-image1 img" => array(
          'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile1'],'px'),
        ),
        /*" .box1 .affiliate-list1 li" => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile1'], $attr['listfontSizeTypeMobile1']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile1']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile1'], $attr['listletterSpacingTypeMobile1']),
        ),*/
        " .box1 .affiliate-coupon-btn-wrapper1 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile1'], $attr['btnfontSizeTypeMobile1']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile1']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile1'], $attr['btnletterSpacingTypeMobile1']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile1']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile1']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile1']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile1']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile1']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile1']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile1']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile1']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile1']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile1']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile1']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile1']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile1']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile1']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile1']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile1']['unit'])),
          ),


          //Box2
          " .box2 .affiliate-coupon-title2 " .$attr['titleTag2'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile2'], $attr['titlefontSizeTypeMobile2']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile2']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile2'], $attr['titleletterSpacingTypeMobile2']),
          ),
          " .box2 .affiliate-coupon-starrating2 .affiliate-star-item svg" => array(
            "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile2'], 'px'),  
            "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile2'], 'px'),
          ),

          " .box2 .affiliate-coupon-image2" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile2']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile2']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile2']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile2']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile2']['unit'])),
          ),
          " .box2 .affiliate-coupon-image2 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile2'],'px'),
          ),
          /*" .box2 .affiliate-list2 li" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile2'], $attr['listfontSizeTypeMobile2']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile2']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile2'], $attr['listletterSpacingTypeMobile2']),
          ),*/
          " .box2 .affiliate-coupon-btn-wrapper2 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile2'], $attr['btnfontSizeTypeMobile2']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile2']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile2'], $attr['btnletterSpacingTypeMobile2']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile2']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile2']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile2']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile2']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile2']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile2']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile2']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile2']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile2']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile2']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile2']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile2']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile2']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile2']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile2']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile2']['unit'])),
          ),


          //Box3
          " .box3 .affiliate-coupon-title3 " .$attr['titleTag3'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile3'], $attr['titlefontSizeTypeMobile3']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile3']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile3'], $attr['titleletterSpacingTypeMobile3']),
          ),
          " .box3 .affiliate-coupon-starrating3 .affiliate-star-item svg" => array(
            "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile3'], 'px'),  
            "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile3'], 'px'),
          ),
          " .box3 .affiliate-coupon-image3" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile3']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile3']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile3']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile3']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile3']['unit'])),
          ),
          " .box3 .affiliate-coupon-image3 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile3'],'px'),
          ),
          /*" .box3 .affiliate-list3 li" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile3'], $attr['listfontSizeTypeMobile3']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile3']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile3'], $attr['listletterSpacingTypeMobile3']),
          ),*/
          " .box3 .affiliate-coupon-btn-wrapper3 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile3'], $attr['btnfontSizeTypeMobile3']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile3']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile3'], $attr['btnletterSpacingTypeMobile3']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile3']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile3']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile3']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile3']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile3']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile3']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile3']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile3']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile3']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile3']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile3']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile3']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile3']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile3']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile3']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile3']['unit'])),
          ),



          //Box4
          " .box4 .affiliate-coupon-title4 " .$attr['titleTag4'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile4'], $attr['titlefontSizeTypeMobile4']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile4']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile4'], $attr['titleletterSpacingTypeMobile4']),
          ),
          " .box4 .affiliate-coupon-starrating4 .affiliate-star-item svg" => array(
            "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile4'], 'px'),  
            "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile4'], 'px'),
          ),
          " .box4 .affiliate-coupon-image4" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile4']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile4']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile4']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile4']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile4']['unit'])),
          ),
          " .box4 .affiliate-coupon-image4 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile4'],'px'),
          ),
          /*" .box4 .affiliate-list4 li" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile4'], $attr['listfontSizeTypeMobile4']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile4']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile4'], $attr['listletterSpacingTypeMobile4']),
          ),*/
          " .box4 .affiliate-coupon-btn-wrapper4 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile4'], $attr['btnfontSizeTypeMobile4']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile4']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile4'], $attr['btnletterSpacingTypeMobile4']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile4']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile4']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile4']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile4']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile4']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile4']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile4']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile4']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile4']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile4']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile4']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile4']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile4']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile4']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile4']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile4']['unit'])),
          ),



          //Box5
          " .box5 .affiliate-coupon-title5 " .$attr['titleTag5'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile5'], $attr['titlefontSizeTypeMobile5']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile5']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile5'], $attr['titleletterSpacingTypeMobile5']),
          ),
          " .box5 .affiliate-coupon-starrating5 .affiliate-star-item svg" => array(
            "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile5'], 'px'),  
            "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile5'], 'px'),
          ),
          " .box5 .affiliate-coupon-image5" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile5']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile5']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile5']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile5']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile5']['unit'])),
          ),
          " .box5 .affiliate-coupon-image5 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile5'],'px'),
          ),
          /*" .box5 .affiliate-list5 li" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile5'], $attr['listfontSizeTypeMobile5']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile5']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile5'], $attr['listletterSpacingTypeMobile5']),
          ),*/
          " .box5 .affiliate-coupon-btn-wrapper5 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile5'], $attr['btnfontSizeTypeMobile5']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile5']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile5'], $attr['btnletterSpacingTypeMobile5']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile5']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile5']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile5']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile5']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile5']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile5']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile5']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile5']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile5']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile5']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile5']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile5']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile5']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile5']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile5']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile5']['unit'])),
          ),


          //Box6
          " .box6 .affiliate-coupon-title6 " .$attr['titleTag6'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile6'], $attr['titlefontSizeTypeMobile6']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile6']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile6'], $attr['titleletterSpacingTypeMobile6']),
          ),
          " .box6 .affiliate-coupon-starrating6 .affiliate-star-item svg" => array(
            "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile6'], 'px'),  
            "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile6'], 'px'),
          ),
          " .box6 .affiliate-coupon-image6" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile6']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile6']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile6']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile6']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile6']['unit'])),
          ),
          " .box6 .affiliate-coupon-image6 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile6'],'px'),
          ),
          /*" .box6 .affiliate-list6 li" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile6'], $attr['listfontSizeTypeMobile6']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile6']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile6'], $attr['listletterSpacingTypeMobile6']),
          ),*/
          " .box6 .affiliate-coupon-btn-wrapper6 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile6'], $attr['btnfontSizeTypeMobile6']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile6']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile6'], $attr['btnletterSpacingTypeMobile6']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile6']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile6']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile6']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile6']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile6']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile6']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile6']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile6']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile6']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile6']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile6']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile6']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile6']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile6']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile6']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile6']['unit'])),
          ),


          //Box7
          " .box7 .affiliate-coupon-title7 " .$attr['titleTag7'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile7'], $attr['titlefontSizeTypeMobile7']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile7']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile7'], $attr['titleletterSpacingTypeMobile7']),
          ),
          " .box7 .affiliate-coupon-starrating7 .affiliate-star-item svg" => array(
            "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile7'], 'px'),  
            "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile7'], 'px'),
          ),
          " .box7 .affiliate-coupon-image7" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile7']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile7']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile7']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile7']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile7']['unit'])),
          ),
          " .box7 .affiliate-coupon-image7 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile7'],'px'),
          ),
          /*" .box7 .affiliate-list7 li" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile7'], $attr['listfontSizeTypeMobile7']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile7']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile7'], $attr['listletterSpacingTypeMobile7']),
          ),*/
          " .box7 .affiliate-coupon-btn-wrapper7 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile7'], $attr['btnfontSizeTypeMobile7']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile7']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile7'], $attr['btnletterSpacingTypeMobile7']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile7']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile7']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile7']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile7']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile7']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile7']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile7']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile7']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile7']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile7']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile7']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile7']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile7']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile7']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile7']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile7']['unit'])),
          ),


          
      );

      $t_selectors = array(

        " .affiliate-coupon-wrapper.style2.alignfull" => array(
           'padding' => AFFILIATE_Helper::get_css_value1($attr['boxPaddingTablet']['top'], AFFILIATE_Helper::get_css_value1($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPaddingTablet']['right'], AFFILIATE_Helper::get_css_value1($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value1($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPaddingTablet']['left'], AFFILIATE_Helper::get_css_value1($attr['boxPaddingTablet']['unit'])),
        ),

        " .affiliate-coupon-wrapper.style2.alignwide" => array(
           'margin' => AFFILIATE_Helper::get_css_value1($attr['boxMarginTablet']['top'], AFFILIATE_Helper::get_css_value1($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMarginTablet']['right'], AFFILIATE_Helper::get_css_value1($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value1($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMarginTablet']['left'], AFFILIATE_Helper::get_css_value1($attr['boxMarginTablet']['unit'])),
        ),

        " .box1 .affiliate-coupon-title1 " .$attr['titleTag1'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet1'], $attr['titlefontSizeTypeTablet1']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet1']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet1'], $attr['titleletterSpacingTypeTablet1']),
        ),
        " .box1 .affiliate-coupon-starrating1 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet1'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet1'], 'px'),
        ),
        " .box1 .affiliate-coupon-image1 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet1'],'px'),
        ),
        " .box1 .affiliate-coupon-image1" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet1']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet1']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet1']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet1']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet1']['unit'])),
          ),
        /*" .box1 .affiliate-list1 li" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet1'], $attr['listfontSizeTypeTablet1']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet1']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet1'], $attr['listletterSpacingTypeTablet1']),
        ),*/
        " .box1 .affiliate-coupon-btn-wrapper1 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet1'], $attr['btnfontSizeTypeTablet1']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet1']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet1'], $attr['btnletterSpacingTypeTablet1']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet1']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet1']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet1']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet1']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet1']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet1']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet1']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet1']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet1']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet1']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet1']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet1']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet1']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet1']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet1']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet1']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet1']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet1']['unit'])),
        ),


        //Box2
        " .box2 .affiliate-coupon-title2 " .$attr['titleTag2'] => array(
          'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet2'], $attr['titlefontSizeTypeTablet2']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet2']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet2'], $attr['titleletterSpacingTypeTablet2']),
        ),
        " .box2 .affiliate-coupon-starrating2 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet2'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet2'], 'px'),
        ),
        " .box2 .affiliate-coupon-image2 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet2'],'px'),
        ),
        " .box2 .affiliate-coupon-image2" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet2']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet2']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet2']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet2']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet2']['unit'])),
        ),
        /*" .box2 .affiliate-list2 li" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet2'], $attr['listfontSizeTypeTablet2']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet2']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet2'], $attr['listletterSpacingTypeTablet2']),
        ),*/
        " .box2 .affiliate-coupon-btn-wrapper2 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet2'], $attr['btnfontSizeTypeTablet2']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet2']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet2'], $attr['btnletterSpacingTypeTablet2']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet2']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet2']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet2']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet2']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet2']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet2']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet2']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet2']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet2']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet2']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet2']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet2']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet2']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet2']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet2']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet2']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet2']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet2']['unit'])),
        ),
        

        //Box3
        " .box3 .affiliate-coupon-title3 " .$attr['titleTag3'] => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet3'], $attr['titlefontSizeTypeTablet3']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet3']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet3'], $attr['titleletterSpacingTypeTablet3']),
        ),
        " .box3 .affiliate-coupon-starrating3 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet3'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet3'], 'px'),
        ),
        " .box3 .affiliate-coupon-image3 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet3'],'px'),
        ),       
        " .box3 .affiliate-coupon-image3" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet3']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet3']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet3']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet3']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet3']['unit'])),
        ),
        /*" .box3 .affiliate-list3 li" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet3'], $attr['listfontSizeTypeTablet3']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet3']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet3'], $attr['listletterSpacingTypeTablet3']),
        ),    */
        " .box3 .affiliate-coupon-btn-wrapper3 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet3'], $attr['btnfontSizeTypeTablet3']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet3']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet3'], $attr['btnletterSpacingTypeTablet3']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet3']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet3']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet3']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet3']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet3']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet3']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet3']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet3']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet3']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet3']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet3']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet3']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet3']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet3']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet3']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet3']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet3']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet3']['unit'])),
        ),



        //Box4
        " .box4 .affiliate-coupon-title4 " .$attr['titleTag4'] => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet4'], $attr['titlefontSizeTypeTablet4']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet4']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet4'], $attr['titleletterSpacingTypeTablet4']),
        ),
        " .box4 .affiliate-coupon-starrating4 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet4'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet4'], 'px'),
        ),
        " .box4 .affiliate-coupon-image4 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet4'],'px'),
        ),       
        " .box4 .affiliate-coupon-image4" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet4']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet4']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet4']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet4']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet4']['unit'])),
        ),
        /*" .box4 .affiliate-list4 li" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet4'], $attr['listfontSizeTypeTablet4']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet4']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet4'], $attr['listletterSpacingTypeTablet4']),
        ),    */
        " .box4 .affiliate-coupon-btn-wrapper4 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet4'], $attr['btnfontSizeTypeTablet4']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet4']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet4'], $attr['btnletterSpacingTypeTablet4']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet4']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet4']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet4']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet4']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet4']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet4']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet4']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet4']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet4']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet4']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet4']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet4']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet4']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet4']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet4']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet4']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet4']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet4']['unit'])),
          ),



          //Box5
        " .box5 .affiliate-coupon-title5 " .$attr['titleTag5'] => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet5'], $attr['titlefontSizeTypeTablet5']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet5']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet5'], $attr['titleletterSpacingTypeTablet5']),
        ),
        " .box5 .affiliate-coupon-starrating5 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet5'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet5'], 'px'),
        ),
        " .box5 .affiliate-coupon-image5 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet5'],'px'),
        ),       
        " .box5 .affiliate-coupon-image5" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet5']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet5']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet5']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet5']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet5']['unit'])),
        ),
        /*" .box5 .affiliate-list5 li" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet5'], $attr['listfontSizeTypeTablet5']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet5']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet5'], $attr['listletterSpacingTypeTablet5']),
        ),   */ 
        " .box5 .affiliate-coupon-btn-wrapper5 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet5'], $attr['btnfontSizeTypeTablet5']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet5']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet5'], $attr['btnletterSpacingTypeTablet5']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet5']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet5']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet5']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet5']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet5']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet5']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet5']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet5']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet5']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet5']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet5']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet5']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet5']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet5']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet5']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet5']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet5']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet5']['unit'])),
          ),


          //Box6
        " .box6 .affiliate-coupon-title6 " .$attr['titleTag6'] => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet6'], $attr['titlefontSizeTypeTablet6']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet6']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet6'], $attr['titleletterSpacingTypeTablet6']),
        ),
        " .box6 .affiliate-coupon-starrating6 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet6'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet6'], 'px'),
        ),
        " .box6 .affiliate-coupon-image6 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet6'],'px'),
        ),       
        " .box6 .affiliate-coupon-image6" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet6']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet6']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet6']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet6']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet6']['unit'])),
        ),
        /*" .box6 .affiliate-list6 li" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet6'], $attr['listfontSizeTypeTablet6']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet6']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet6'], $attr['listletterSpacingTypeTablet6']),
        ),    */
        " .box6 .affiliate-coupon-btn-wrapper6 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet6'], $attr['btnfontSizeTypeTablet6']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet6']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet6'], $attr['btnletterSpacingTypeTablet6']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet6']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet6']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet6']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet6']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet6']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet6']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet6']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet6']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet6']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet6']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet6']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet6']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet6']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet6']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet6']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet6']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet6']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet6']['unit'])),
          ),


          //Box7
        " .box7 .affiliate-coupon-title7 " .$attr['titleTag7'] => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet7'], $attr['titlefontSizeTypeTablet7']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet7']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet7'], $attr['titleletterSpacingTypeTablet7']),
        ),
        " .box7 .affiliate-coupon-starrating7 .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet7'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet7'], 'px'),
        ),
        " .box7 .affiliate-coupon-image7 img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet7'],'px'),
        ),       
        " .box7 .affiliate-coupon-image7" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet7']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet7']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet7']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet7']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet7']['unit'])),
        ),
        /*" .box7 .affiliate-list7 li" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet7'], $attr['listfontSizeTypeTablet7']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet7']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet7'], $attr['listletterSpacingTypeTablet7']),
        ),    */
        " .box7 .affiliate-coupon-btn-wrapper7 .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet7'], $attr['btnfontSizeTypeTablet7']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet7']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet7'], $attr['btnletterSpacingTypeTablet7']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet7']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet7']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet7']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet7']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet7']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet7']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet7']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet7']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet7']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet7']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet7']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet7']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet7']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet7']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet7']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet7']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet7']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet7']['unit'])),
          ),


          
      );

      


      if($attr['btnBg']['openBg'] == 1){
          if($attr['btnBg']['bgType'] == 'gradient'){
            if ($attr['btnBg']['bgGradient']['type'] == 'linear') {
              $selectors[" .box1 .affiliate-coupon-btn-wrapper1 .affiliate-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box1 .affiliate-coupon-btn-wrapper1 .affiliate-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


        if($attr['btnBgHover']['openBg'] == 1){
          if($attr['btnBgHover']['bgType'] == 'gradient'){
            if ($attr['btnBgHover']['bgGradient']['type'] == 'linear') {
              $selectors[" .box1 .affiliate-coupon-btn-wrapper1 .affiliate-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box1 .affiliate-coupon-btn-wrapper1 .affiliate-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  



        if($attr['btnBg2']['openBg'] == 1){
          if($attr['btnBg2']['bgType'] == 'gradient'){
            if ($attr['btnBg2']['bgGradient']['type'] == 'linear') {
              $selectors[" .box2 .affiliate-coupon-btn-wrapper2 .affiliate-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box2 .affiliate-coupon-btn-wrapper2 .affiliate-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg2']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


        if($attr['btnBgHover2']['openBg'] == 1){
          if($attr['btnBgHover2']['bgType'] == 'gradient'){
            if ($attr['btnBgHover2']['bgGradient']['type'] == 'linear') {
              $selectors[" .box2 .affiliate-coupon-btn-wrapper2 .affiliate-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box2 .affiliate-coupon-btn-wrapper2 .affiliate-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover2']['bgGradient']['stop'], '%') . ')';
            }
          }
        }



        if($attr['btnBg3']['openBg'] == 1){
          if($attr['btnBg3']['bgType'] == 'gradient'){
            if ($attr['btnBg3']['bgGradient']['type'] == 'linear') {
              $selectors[" .box3 .affiliate-coupon-btn-wrapper3 .affiliate-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box3 .affiliate-coupon-btn-wrapper3 .affiliate-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg3']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


        if($attr['btnBgHover3']['openBg'] == 1){
          if($attr['btnBgHover3']['bgType'] == 'gradient'){
            if ($attr['btnBgHover3']['bgGradient']['type'] == 'linear') {
              $selectors[" .box3 .affiliate-coupon-btn-wrapper3 .affiliate-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box3 .affiliate-coupon-btn-wrapper3 .affiliate-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover3']['bgGradient']['stop'], '%') . ')';
            }
          }
        }
      

      if($attr['btnBg4']['openBg'] == 1){
          if($attr['btnBg4']['bgType'] == 'gradient'){
            if ($attr['btnBg4']['bgGradient']['type'] == 'linear') {
              $selectors[" .box4 .affiliate-coupon-btn-wrapper4 .affiliate-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box4 .affiliate-coupon-btn-wrapper4 .affiliate-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg4']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


        if($attr['btnBgHover4']['openBg'] == 1){
          if($attr['btnBgHover4']['bgType'] == 'gradient'){
            if ($attr['btnBgHover4']['bgGradient']['type'] == 'linear') {
              $selectors[" .box4 .affiliate-coupon-btn-wrapper4 .affiliate-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box4 .affiliate-coupon-btn-wrapper4 .affiliate-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover4']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  



        if($attr['btnBg5']['openBg'] == 1){
          if($attr['btnBg5']['bgType'] == 'gradient'){
            if ($attr['btnBg5']['bgGradient']['type'] == 'linear') {
              $selectors[" .box5 .affiliate-coupon-btn-wrapper5 .affiliate-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box5 .affiliate-coupon-btn-wrapper5 .affiliate-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg5']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


        if($attr['btnBgHover5']['openBg'] == 1){
          if($attr['btnBgHover5']['bgType'] == 'gradient'){
            if ($attr['btnBgHover5']['bgGradient']['type'] == 'linear') {
              $selectors[" .box5 .affiliate-coupon-btn-wrapper5 .affiliate-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box5 .affiliate-coupon-btn-wrapper5 .affiliate-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover5']['bgGradient']['stop'], '%') . ')';
            }
          }
        }



        if($attr['btnBg6']['openBg'] == 1){
          if($attr['btnBg6']['bgType'] == 'gradient'){
            if ($attr['btnBg6']['bgGradient']['type'] == 'linear') {
              $selectors[" .box6 .affiliate-coupon-btn-wrapper6 .affiliate-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box6 .affiliate-coupon-btn-wrapper6 .affiliate-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg6']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


        if($attr['btnBgHover6']['openBg'] == 1){
          if($attr['btnBgHover6']['bgType'] == 'gradient'){
            if ($attr['btnBgHover6']['bgGradient']['type'] == 'linear') {
              $selectors[" .box6 .affiliate-coupon-btn-wrapper6 .affiliate-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box6 .affiliate-coupon-btn-wrapper6 .affiliate-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover6']['bgGradient']['stop'], '%') . ')';
            }
          }
        }
    


        if($attr['btnBg7']['openBg'] == 1){
          if($attr['btnBg7']['bgType'] == 'gradient'){
            if ($attr['btnBg7']['bgGradient']['type'] == 'linear') {
              $selectors[" .box7 .affiliate-coupon-btn-wrapper7 .affiliate-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box7 .affiliate-coupon-btn-wrapper7 .affiliate-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg7']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


        if($attr['btnBgHover7']['openBg'] == 1){
          if($attr['btnBgHover7']['bgType'] == 'gradient'){
            if ($attr['btnBgHover7']['bgGradient']['type'] == 'linear') {
              $selectors[" .box7 .affiliate-coupon-btn-wrapper7 .affiliate-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .box7 .affiliate-coupon-btn-wrapper7 .affiliate-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover7']['bgGradient']['stop'], '%') . ')';
            }
          }
        }



      
      $regenerate_d = AFFILIATE_Helper::regenerate_stylesheet($selectors);
      $regenerate_m = AFFILIATE_Helper::regenerate_stylesheet($t_selectors);
      $regenerate_t = AFFILIATE_Helper::regenerate_stylesheet($m_selectors);

      $desktop = AFFILIATE_Helper::generate_css( $regenerate_d, '#affiliate-style-' . $id );

      $tablet = AFFILIATE_Helper::generate_css( $regenerate_t, '#affiliate-style-' . $id );

      $mobile = AFFILIATE_Helper::generate_css( $regenerate_m, '#affiliate-style-' . $id );

      $generated_css = array(
        'desktop' => $desktop,
        'tablet'  => $tablet,
        'mobile'  => $mobile,
      );
      return $generated_css;
    }

    public static function blocks_coupon2_gfont($attr)
    {
      
      $btn_load_google_font1 = isset($attr['btnLoadGoogleFonts1']) ? $attr['btnLoadGoogleFonts1'] : '';
      $btn_font_family1      = isset($attr['btnfontFamily1']) ? $attr['btnfontFamily1'] : '';
      $btn_font_weight1      = isset($attr['btnfontWeight1']) ? $attr['btnfontWeight1'] : '';
      $btn_font_subset1      = isset($attr['btnfontSubset1']) ? $attr['btnfontSubset1'] : '';

      $title_load_google_font1 = isset($attr['titleLoadGoogleFonts1']) ? $attr['titleLoadGoogleFonts1'] : '';
      $title_font_family1      = isset($attr['titlefontFamily1']) ? $attr['titlefontFamily1'] : '';
      $title_font_weight1      = isset($attr['titlefontWeight1']) ? $attr['titlefontWeight1'] : '';
      $title_font_subset1      = isset($attr['titlefontSubset1']) ? $attr['titlefontSubset1'] : '';

      $list_load_google_font1 = isset($attr['listLoadGoogleFonts1']) ? $attr['listLoadGoogleFonts1'] : '';
      $list_font_family1      = isset($attr['listfontFamily1']) ? $attr['listfontFamily1'] : '';
      $list_font_weight1      = isset($attr['listfontWeight1']) ? $attr['listfontWeight1'] : '';
      $list_font_subset1      = isset($attr['listfontSubset1']) ? $attr['listfontSubset1'] : '';
      

      $btn_load_google_font2 = isset($attr['btnLoadGoogleFonts2']) ? $attr['btnLoadGoogleFonts2'] : '';
      $btn_font_family2      = isset($attr['btnfontFamily2']) ? $attr['btnfontFamily2'] : '';
      $btn_font_weight2      = isset($attr['btnfontWeight2']) ? $attr['btnfontWeight2'] : '';
      $btn_font_subset2      = isset($attr['btnfontSubset2']) ? $attr['btnfontSubset2'] : '';

      $title_load_google_font2 = isset($attr['titleLoadGoogleFonts2']) ? $attr['titleLoadGoogleFonts2'] : '';
      $title_font_family2      = isset($attr['titlefontFamily2']) ? $attr['titlefontFamily2'] : '';
      $title_font_weight2      = isset($attr['titlefontWeight2']) ? $attr['titlefontWeight2'] : '';
      $title_font_subset2      = isset($attr['titlefontSubset2']) ? $attr['titlefontSubset2'] : '';

      $list_load_google_font2 = isset($attr['listLoadGoogleFonts2']) ? $attr['listLoadGoogleFonts2'] : '';
      $list_font_family2      = isset($attr['listfontFamily2']) ? $attr['listfontFamily2'] : '';
      $list_font_weight2      = isset($attr['listfontWeight2']) ? $attr['listfontWeight2'] : '';
      $list_font_subset2      = isset($attr['listfontSubset2']) ? $attr['listfontSubset2'] : '';


      $btn_load_google_font3 = isset($attr['btnLoadGoogleFonts3']) ? $attr['btnLoadGoogleFonts3'] : '';
      $btn_font_family3      = isset($attr['btnfontFamily3']) ? $attr['btnfontFamily3'] : '';
      $btn_font_weight3      = isset($attr['btnfontWeight3']) ? $attr['btnfontWeight3'] : '';
      $btn_font_subset3      = isset($attr['btnfontSubset3']) ? $attr['btnfontSubset3'] : '';

      $title_load_google_font3 = isset($attr['titleLoadGoogleFonts3']) ? $attr['titleLoadGoogleFonts3'] : '';
      $title_font_family3      = isset($attr['titlefontFamily3']) ? $attr['titlefontFamily3'] : '';
      $title_font_weight3      = isset($attr['titlefontWeight3']) ? $attr['titlefontWeight3'] : '';
      $title_font_subset3      = isset($attr['titlefontSubset3']) ? $attr['titlefontSubset3'] : '';

      $list_load_google_font3 = isset($attr['listLoadGoogleFonts3']) ? $attr['listLoadGoogleFonts3'] : '';
      $list_font_family3      = isset($attr['listfontFamily3']) ? $attr['listfontFamily3'] : '';
      $list_font_weight3      = isset($attr['listfontWeight3']) ? $attr['listfontWeight3'] : '';
      $list_font_subset3      = isset($attr['listfontSubset3']) ? $attr['listfontSubset3'] : '';



      $btn_load_google_font4 = isset($attr['btnLoadGoogleFonts4']) ? $attr['btnLoadGoogleFonts4'] : '';
      $btn_font_family4      = isset($attr['btnfontFamily4']) ? $attr['btnfontFamily4'] : '';
      $btn_font_weight4      = isset($attr['btnfontWeight4']) ? $attr['btnfontWeight4'] : '';
      $btn_font_subset4      = isset($attr['btnfontSubset4']) ? $attr['btnfontSubset4'] : '';

      $title_load_google_font4 = isset($attr['titleLoadGoogleFonts4']) ? $attr['titleLoadGoogleFonts4'] : '';
      $title_font_family4      = isset($attr['titlefontFamily4']) ? $attr['titlefontFamily4'] : '';
      $title_font_weight4      = isset($attr['titlefontWeight4']) ? $attr['titlefontWeight4'] : '';
      $title_font_subset4      = isset($attr['titlefontSubset4']) ? $attr['titlefontSubset4'] : '';

      $list_load_google_font4 = isset($attr['listLoadGoogleFonts4']) ? $attr['listLoadGoogleFonts4'] : '';
      $list_font_family4      = isset($attr['listfontFamily4']) ? $attr['listfontFamily4'] : '';
      $list_font_weight4      = isset($attr['listfontWeight4']) ? $attr['listfontWeight4'] : '';
      $list_font_subset4      = isset($attr['listfontSubset4']) ? $attr['listfontSubset4'] : '';



      $btn_load_google_font5 = isset($attr['btnLoadGoogleFonts5']) ? $attr['btnLoadGoogleFonts5'] : '';
      $btn_font_family5      = isset($attr['btnfontFamily5']) ? $attr['btnfontFamily5'] : '';
      $btn_font_weight5      = isset($attr['btnfontWeight5']) ? $attr['btnfontWeight5'] : '';
      $btn_font_subset5      = isset($attr['btnfontSubset5']) ? $attr['btnfontSubset5'] : '';

      $title_load_google_font5 = isset($attr['titleLoadGoogleFonts5']) ? $attr['titleLoadGoogleFonts5'] : '';
      $title_font_family5      = isset($attr['titlefontFamily5']) ? $attr['titlefontFamily5'] : '';
      $title_font_weight5      = isset($attr['titlefontWeight5']) ? $attr['titlefontWeight5'] : '';
      $title_font_subset5      = isset($attr['titlefontSubset5']) ? $attr['titlefontSubset5'] : '';

      $list_load_google_font5 = isset($attr['listLoadGoogleFonts5']) ? $attr['listLoadGoogleFonts5'] : '';
      $list_font_family5      = isset($attr['listfontFamily5']) ? $attr['listfontFamily5'] : '';
      $list_font_weight5      = isset($attr['listfontWeight5']) ? $attr['listfontWeight5'] : '';
      $list_font_subset5      = isset($attr['listfontSubset5']) ? $attr['listfontSubset5'] : '';


      $btn_load_google_font6 = isset($attr['btnLoadGoogleFonts6']) ? $attr['btnLoadGoogleFonts6'] : '';
      $btn_font_family6      = isset($attr['btnfontFamily6']) ? $attr['btnfontFamily6'] : '';
      $btn_font_weight6      = isset($attr['btnfontWeight6']) ? $attr['btnfontWeight6'] : '';
      $btn_font_subset6      = isset($attr['btnfontSubset6']) ? $attr['btnfontSubset6'] : '';

      $title_load_google_font6 = isset($attr['titleLoadGoogleFonts6']) ? $attr['titleLoadGoogleFonts6'] : '';
      $title_font_family6      = isset($attr['titlefontFamily6']) ? $attr['titlefontFamily6'] : '';
      $title_font_weight6      = isset($attr['titlefontWeight6']) ? $attr['titlefontWeight6'] : '';
      $title_font_subset6      = isset($attr['titlefontSubset6']) ? $attr['titlefontSubset6'] : '';

      $list_load_google_font6 = isset($attr['listLoadGoogleFonts6']) ? $attr['listLoadGoogleFonts6'] : '';
      $list_font_family6      = isset($attr['listfontFamily6']) ? $attr['listfontFamily6'] : '';
      $list_font_weight6      = isset($attr['listfontWeight6']) ? $attr['listfontWeight6'] : '';
      $list_font_subset6      = isset($attr['listfontSubset6']) ? $attr['listfontSubset6'] : '';


      $btn_load_google_font7 = isset($attr['btnLoadGoogleFonts7']) ? $attr['btnLoadGoogleFonts7'] : '';
      $btn_font_family7      = isset($attr['btnfontFamily7']) ? $attr['btnfontFamily7'] : '';
      $btn_font_weight7      = isset($attr['btnfontWeight7']) ? $attr['btnfontWeight7'] : '';
      $btn_font_subset7      = isset($attr['btnfontSubset7']) ? $attr['btnfontSubset7'] : '';

      $title_load_google_font7 = isset($attr['titleLoadGoogleFonts7']) ? $attr['titleLoadGoogleFonts7'] : '';
      $title_font_family7      = isset($attr['titlefontFamily7']) ? $attr['titlefontFamily7'] : '';
      $title_font_weight7      = isset($attr['titlefontWeight7']) ? $attr['titlefontWeight7'] : '';
      $title_font_subset7      = isset($attr['titlefontSubset7']) ? $attr['titlefontSubset7'] : '';

      $list_load_google_font7 = isset($attr['listLoadGoogleFonts7']) ? $attr['listLoadGoogleFonts7'] : '';
      $list_font_family7      = isset($attr['listfontFamily7']) ? $attr['listfontFamily7'] : '';
      $list_font_weight7      = isset($attr['listfontWeight7']) ? $attr['listfontWeight7'] : '';
      $list_font_subset7      = isset($attr['listfontSubset7']) ? $attr['listfontSubset7'] : '';


  
      AFFILIATE_Helper::blocks_google_font($list_load_google_font1, $list_font_family1, $list_font_weight1, $list_font_subset1);
      AFFILIATE_Helper::blocks_google_font($btn_load_google_font1, $btn_font_family1, $btn_font_weight1, $btn_font_subset1);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font1, $title_font_family1, $title_font_weight1, $title_font_subset1);



      AFFILIATE_Helper::blocks_google_font($list_load_google_font2, $list_font_family2, $list_font_weight2, $list_font_subset2);
      AFFILIATE_Helper::blocks_google_font($btn_load_google_font2, $btn_font_family2, $btn_font_weight2, $btn_font_subset2);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font2, $title_font_family2, $title_font_weight2, $title_font_subset2);



      AFFILIATE_Helper::blocks_google_font($list_load_google_font3, $list_font_family3, $list_font_weight3, $list_font_subset3);
      AFFILIATE_Helper::blocks_google_font($btn_load_google_font3, $btn_font_family3, $btn_font_weight3, $btn_font_subset3);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font3, $title_font_family3, $title_font_weight3, $title_font_subset3);



      AFFILIATE_Helper::blocks_google_font($list_load_google_font4, $list_font_family4, $list_font_weight4, $list_font_subset4);
      AFFILIATE_Helper::blocks_google_font($btn_load_google_font4, $btn_font_family4, $btn_font_weight4, $btn_font_subset4);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font4, $title_font_family4, $title_font_weight4, $title_font_subset4);



      AFFILIATE_Helper::blocks_google_font($list_load_google_font5, $list_font_family5, $list_font_weight5, $list_font_subset5);
      AFFILIATE_Helper::blocks_google_font($btn_load_google_font5, $btn_font_family5, $btn_font_weight5, $btn_font_subset5);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font5, $title_font_family5, $title_font_weight5, $title_font_subset5);


      AFFILIATE_Helper::blocks_google_font($list_load_google_font6, $list_font_family6, $list_font_weight6, $list_font_subset6);
      AFFILIATE_Helper::blocks_google_font($btn_load_google_font6, $btn_font_family6, $btn_font_weight6, $btn_font_subset6);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font6, $title_font_family6, $title_font_weight6, $title_font_subset6);


      AFFILIATE_Helper::blocks_google_font($list_load_google_font7, $list_font_family7, $list_font_weight7, $list_font_subset7);
      AFFILIATE_Helper::blocks_google_font($btn_load_google_font7, $btn_font_family7, $btn_font_weight7, $btn_font_subset7);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font7, $title_font_family7, $title_font_weight7, $title_font_subset7);


      
    }


    public static function get_iconlist_css( $attr, $id ) {
      // @codingStandardsIgnoreStart
      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-icon-list']['attributes'];

      $attr = array_merge( $defaults, $attr );
      

      $m_selectors = array();
      $t_selectors = array();

      $boxshadow = array();
        if (!empty($attr['boxShadow']['openShadow']) && $attr['boxShadow']['openShadow'] == 1) {
          if ($attr['boxShadow']['inset'] === 0) {
            $inset = '';
          } else {
            $inset = $attr['boxShadow']['inset'];
          }
          $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
        }

        if(!isset($attr['boxMargin']['top'])){
          $attr['boxMargin']['top'] = '0';
        }
        if(!isset($attr['boxMargin']['left'])){
          $attr['boxMargin']['left'] = '0';
        }
        if(!isset($attr['boxMargin']['right'])){
          $attr['boxMargin']['right'] = '0';
        }
        if(!isset($attr['boxMargin']['bottom'])){
          $attr['boxMargin']['bottom'] = '0';
        }

        if($attr['listIconColor'] == ''){
          $attr['listIconColor'] = get_option( 'affiliate_booster_iconlisticoncolor' );
        }
      
      $selectors = array(
        " .affiliate-iconlist-inner" => array(
          'padding' => AFFILIATE_Helper::get_css_value1($attr['boxPadding']['top'], AFFILIATE_Helper::get_css_value1($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPadding']['right'], AFFILIATE_Helper::get_css_value1($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPadding']['bottom'], AFFILIATE_Helper::get_css_value1($attr['boxPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxPadding']['left'], AFFILIATE_Helper::get_css_value1($attr['boxPadding']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value1($attr['boxMargin']['top'], AFFILIATE_Helper::get_css_value1($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMargin']['right'], AFFILIATE_Helper::get_css_value1($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMargin']['bottom'], AFFILIATE_Helper::get_css_value1($attr['boxMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxMargin']['left'], AFFILIATE_Helper::get_css_value1($attr['boxMargin']['unit'])),
          "background"  => AFFILIATE_Helper::get_css_value($attr['bgColor']), 
          "box-shadow" => implode('' ,$boxshadow )
        ),
        " .affiliate-list li" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['listfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value1($attr['listfontSizeDesktop'], $attr['listfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value1($attr['listfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['listfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value1($attr['listlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value1($attr['listletterSpacing'], $attr['listletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['listtextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['listtextTransform']),
            "color"  => AFFILIATE_Helper::get_css_value($attr['listTextColor']),
          ),
          " .affiliate-list" => array(
              "columns"=>AFFILIATE_Helper::get_css_value1($attr['numberofColumns']),
          ),
          " .affiliate-list li:before" => array(
              "padding-right"  => AFFILIATE_Helper::get_css_value1($attr['iconSpacing'], 'px'),
              "color"  => AFFILIATE_Helper::get_css_value1($attr['listIconColor']),
              "font-size"  => AFFILIATE_Helper::get_css_value1($attr['iconSize'], 'px'),
          ),
          " .affiliate-list li:not(:last-child)" => array(
                "padding-bottom"  => AFFILIATE_Helper::get_css_value1($attr['itemSpacing'],'px'),
          ),
          // " .affiliate-list-img" => array(
          //     "padding-right" => AFFILIATE_Helper::get_css_value($attr['iconSpacing'],'px'),
          // ),
          // " .affiliate-list-img img" => array(
          //     "width" => AFFILIATE_Helper::get_css_value($attr['iconSize'],'px'),
          //     "max-width" => AFFILIATE_Helper::get_css_value($attr['iconSize'],'px'), 
          // ),
          " .aff-list-image > li:before" => array(
              "background-image" => "url('" .AFFILIATE_Helper::get_css_value1($attr['image']['url']). "')",
              "margin-right" => AFFILIATE_Helper::get_css_value1($attr['iconSpacing'],'px'),
              "height" => AFFILIATE_Helper::get_css_value1($attr['iconSize'],'px'), 
              "width" => AFFILIATE_Helper::get_css_value1($attr['iconSize'],'px'),
          ),
      );
      
      $m_selectors = array(
        " .affiliate-iconlist-inner" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['boxPaddingMobile']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['top'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['right'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['left'], AFFILIATE_Helper::get_css_value($attr['boxMarginMobile']['unit'])),
        ),
        " .affiliate-list-item" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeMobile'], $attr['listfontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingMobile'], $attr['listletterSpacingTypeMobile']),
          ),
          " .affiliate-list" => array(
              "columns"=>AFFILIATE_Helper::get_css_value($attr['numberofColumnsMobile']),
          ),
          " .affiliate-list-item:before" => array(
              "padding-right"  => AFFILIATE_Helper::get_css_value($attr['iconSpacingMobile'], 'px'),
              "font-size"  => AFFILIATE_Helper::get_css_value($attr['iconSizeMobile'], 'px'),
          ),
          " .affiliate-list-item:not(:last-child)" => array(
                "padding-bottom"  => AFFILIATE_Helper::get_css_value($attr['itemSpacingMobile'],'px'),
          ),
      );

      $t_selectors = array(
        " .affiliate-iconlist-inner" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['boxPaddingTablet']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['top'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['right'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['left'], AFFILIATE_Helper::get_css_value($attr['boxMarginTablet']['unit'])),
        ),
        " .affiliate-list-item" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['listfontSizeTablet'], $attr['listfontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['listlineHeightTablet']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['listletterSpacingTablet'], $attr['listletterSpacingTypeTablet']),
          ),
          " .affiliate-list" => array(
              "columns"=>AFFILIATE_Helper::get_css_value($attr['numberofColumnsTablet']),
          ),
          " .affiliate-list-item:before" => array(
              "padding-right"  => AFFILIATE_Helper::get_css_value($attr['iconSpacingTablet'], 'px'),
              "font-size"  => AFFILIATE_Helper::get_css_value($attr['iconSizeTablet'], 'px'),
          ),
          " .affiliate-list-item:not(:last-child)" => array(
                "padding-bottom"  => AFFILIATE_Helper::get_css_value($attr['itemSpacingTablet'],'px'),
          ),
      );
      
        $regenerate_d = AFFILIATE_Helper::regenerate_stylesheet($selectors);
        $regenerate_t = AFFILIATE_Helper::regenerate_stylesheet($t_selectors);
        $regenerate_m = AFFILIATE_Helper::regenerate_stylesheet($m_selectors);

        $desktop = AFFILIATE_Helper::generate_css( $regenerate_d, '#affiliate-style-' . $id );

        $tablet = AFFILIATE_Helper::generate_css( $regenerate_t, '#affiliate-style-' . $id );

        $mobile = AFFILIATE_Helper::generate_css( $regenerate_m, '#affiliate-style-' . $id );

        $generated_css = array(
          'desktop' => $desktop,
          'tablet'  => $tablet,
          'mobile'  => $mobile,
        );
        return $generated_css;
    }

    public static function blocks_iconlist_gfont($attr)
    {
      
      $list_load_google_font = isset($attr['listLoadGoogleFonts']) ? $attr['listLoadGoogleFonts'] : '';
      $list_font_family      = isset($attr['listfontFamily']) ? $attr['listfontFamily'] : '';
      $list_font_weight      = isset($attr['listfontWeight']) ? $attr['listfontWeight'] : '';
      $list_font_subset      = isset($attr['listfontSubset']) ? $attr['listfontSubset'] : '';

      AFFILIATE_Helper::blocks_google_font($list_load_google_font, $list_font_family, $list_font_weight, $list_font_subset);
    }


    public static function get_coupon4_css( $attr, $id ) {
      // @codingStandardsIgnoreStart
      $defaults = AFFILIATE_Helper::$block_list['affiliate-booster/ab-coupon4']['attributes'];

      $attr = array_merge( $defaults, $attr );

      $btnBg = get_option( 'affiliate_booster_btnbgcolor' );
      if($attr['btnBg']['openBg'] == 1){
          if($attr['btnBg']['bgType'] == 'color'){
             $btnBg = $attr['btnBg']['bgDefaultColor']; 
          }
      } 

      $btnBgHover = get_option( 'affiliate_booster_btnbghovercolor' );
      if($attr['btnBgHover']['openBg'] == 1){
          if($attr['btnBgHover']['bgType'] == 'color'){
             $btnBgHover = $attr['btnBgHover']['bgDefaultColor']; 
          }
      }


      if($attr['btnBorderColor'] == ''){
        $attr['btnBorderColor'] = get_option( 'affiliate_booster_btnbordercolor' );
      }
      if($attr['btnBorderHoverColor'] == ''){
        $attr['btnBorderHoverColor'] = get_option( 'affiliate_booster_btnborderhovercolor' );
      }
      if($attr['btnBorderType'] == ''){
        $attr['btnBorderType'] = get_option( 'affiliate_booster_btnbordertype' );
      }
      if($attr['btnTextColor'] == ''){
        $attr['btnTextColor'] = get_option( 'affiliate_booster_btntextcolor' );
      }
      if($attr['btnTextHoverColor'] == ''){
        $attr['btnTextHoverColor'] = get_option( 'affiliate_booster_btntexthovercolor' );
      }


      if($attr['boxBorderColor'] == ''){
        $attr['boxBorderColor'] = get_option( 'affiliate_booster_boxbordercolor' );
      }


      
      $m_selectors = array();
      $t_selectors = array();
      $boxshadow = array();
        if (!empty($attr['boxShadow']['openShadow']) && $attr['boxShadow']['openShadow'] == 1) {
          if ($attr['boxShadow']['inset'] === 0) {
            $inset = '';
          } else {
            $inset = $attr['boxShadow']['inset'];
          }
          $boxshadow = array(AFFILIATE_Helper::get_css_value1($inset) . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['horizontal'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['vertical'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['blur'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['spread'], 'px') . ' ' . AFFILIATE_Helper::get_css_value1($attr['boxShadow']['color']));
        }

        $center = 'left';
        if($attr['ratingAlignemt'] == 'flex-start'){
            $center = 'left';
         }
         if($attr['ratingAlignemt'] == 'center'){
            $center = 'center';
         }
         if($attr['ratingAlignemt'] == 'flex-end'){
            $center = 'right';
         }

      
      $selectors = array(

          " .affiliate-coupon-inner .affiliate-coupon-content-wrapper" => array(
             'border-style' => AFFILIATE_Helper::get_css_value($attr['boxBorderType']),
            'border-color' => AFFILIATE_Helper::get_css_value($attr['boxBorderColor']),
            'border-width' => AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['top'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['right'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['bottom'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['left'], AFFILIATE_Helper::get_css_value($attr['boxBorderWidth']['unit'])),
            
          ),
 

        " .affiliate-coupon-inner" => array(
            "box-shadow"=>implode(',',$boxshadow),
            "background"=>AFFILIATE_Helper::get_css_value($attr['boxBgColor']),
        ),

         " .affiliate-coupon-starrating-outer" => array(
              "justify-content" => AFFILIATE_Helper::get_css_value($attr['ratingAlignemt']), 
          ),

         

         " .affiliate-coupon-starratingTitle" => array(
              "text-align" => AFFILIATE_Helper::get_css_value($center), 
          ),

          " .affiliate-coupon-starrating .affiliate-star-item svg" => array(
              "height" => AFFILIATE_Helper::get_css_value($attr['starsSize'], 'px'),  
              "width" => AFFILIATE_Helper::get_css_value($attr['starsSize'], 'px'),
          ),
 

        " .affiliate-coupon-image" => array(
           'padding' => AFFILIATE_Helper::get_css_value($attr['imagePadding']['top'], AFFILIATE_Helper::get_css_value($attr['imagePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding']['right'], AFFILIATE_Helper::get_css_value($attr['imagePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePadding']['left'], AFFILIATE_Helper::get_css_value($attr['imagePadding']['unit'])),
        ),
        " .affiliate-coupon-image img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidth'],'px'),
        ),

 

        " .affiliate-coupon-maintitle " => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['titlefontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeDesktop'], $attr['titlefontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['titlefontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['titlefontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacing'], $attr['titleletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['titletextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['titletextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['titleTextColor']),
      ),        
      " .affiliate-coupon-inner .affiliate-coupon-content" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['contentfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['contentfontSizeDesktop'], $attr['contentfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['contentfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['contentfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['contentlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['contentletterSpacing'], $attr['contentletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['contenttextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['contenttextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['contentTextColor']),
       ),
      
        " .affiliate-coupon-btn-wrapper" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment']),
        ),   
        

        " .affiliate-coupon-inner .seccol .affiliate-coupon-Verified" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['verifiedfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['verifiedfontSizeDesktop'], $attr['verifiedfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['verifiedfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['verifiedfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['verifiedlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['verifiedletterSpacing'], $attr['verifiedletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['verifiedtextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['verifiedtextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['verifiedColor']),
        ),


        " .affiliate-coupon-inner .seccol .affiliate-coupon-StaffPick" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['staffpickfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['staffpickfontSizeDesktop'], $attr['staffpickfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['staffpickfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['staffpickfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['staffpicklineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['staffpickletterSpacing'], $attr['staffpickletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['staffpicktextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['staffpicktextTransform']),
            "background" => AFFILIATE_Helper::get_css_value($attr['staffpickColor']),
        ),


        " .affiliate-coupon-inner .seccol .affiliate-coupon-PeopleUsed" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['peopleUsedfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['peopleUsedfontSizeDesktop'], $attr['peopleUsedfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['peopleUsedfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['peopleUsedfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['peopleUsedlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['peopleUsedletterSpacing'], $attr['peopleUsedletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['peopleUsedtextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['peopleUsedtextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['peopleUsedColor']),
        ),

        " .affiliate-coupon-inner .seccol .affiliate-coupon-onlyLeft" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['onlyLeftfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['onlyLeftfontSizeDesktop'], $attr['onlyLeftfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['onlyLeftfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['onlyLeftfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['onlyLeftlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['onlyLeftletterSpacing'], $attr['onlyLeftletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['onlyLefttextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['onlyLefttextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['onlyLeftColor']),
        ),

        " .affiliate-coupon-inner .thirdcol .affiliate-coupon-onGoingOffer" => array(
            'font-family' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferfontFamily']),
            'font-size' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferfontSizeDesktop'], $attr['onGoingOfferfontSizeType']),
            'font-weight' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferfontWeight']),
            'font-style' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferfontStyle']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferlineHeight']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferletterSpacing'], $attr['onGoingOfferletterSpacingType']),
            'text-decoration' => AFFILIATE_Helper::get_css_value($attr['onGoingOffertextDecoration']),
            'text-transform' => AFFILIATE_Helper::get_css_value($attr['onGoingOffertextTransform']),
            "color" => AFFILIATE_Helper::get_css_value($attr['onGoingOfferColor']),
        ),


        " .affiliate-coupon-btn-wrapper" => array(
            'justify-content' => AFFILIATE_Helper::get_css_value($attr['btnAlignment']),
        ),   
      " .affiliate-coupon-btn-wrapper .affiliate-btn" => array(
          'font-family' => AFFILIATE_Helper::get_css_value($attr['btnfontFamily']),
          'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeDesktop'], $attr['btnfontSizeType']),
          'font-weight' => AFFILIATE_Helper::get_css_value($attr['btnfontWeight']),
          'font-style' => AFFILIATE_Helper::get_css_value($attr['btnfontStyle']),
          'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeight']),
          'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacing'], $attr['btnletterSpacingType']),
          'text-decoration' => AFFILIATE_Helper::get_css_value($attr['btntextDecoration']),
          'text-transform' => AFFILIATE_Helper::get_css_value($attr['btntextTransform']),
          "background" => AFFILIATE_Helper::get_css_value($btnBg),
          "color" => AFFILIATE_Helper::get_css_value($attr['btnTextColor']),
          "border-color" => AFFILIATE_Helper::get_css_value($attr['btnBorderColor']),
          "border-style" => AFFILIATE_Helper::get_css_value($attr['btnBorderType']),
          "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorder']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorder']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorder']['unit'])),
          "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadius']['unit'])),
          'padding' => AFFILIATE_Helper::get_css_value($attr['btnPadding']['top'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['right'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPadding']['left'], AFFILIATE_Helper::get_css_value($attr['btnPadding']['unit'])),
          'margin' => AFFILIATE_Helper::get_css_value($attr['btnMargin']['top'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['right'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMargin']['left'], AFFILIATE_Helper::get_css_value($attr['btnMargin']['unit'])),
        ),
         
         
      " .affiliate-coupon-btn-wrapper .affiliate-btn:hover" => array(
          'background' => AFFILIATE_Helper::get_css_value($btnBgHover),
          'border-color' => AFFILIATE_Helper::get_css_value($attr['btnBorderHoverColor']),
          'color' => AFFILIATE_Helper::get_css_value($attr['btnTextHoverColor']),
      ),
     
    
      );
      
      $m_selectors = array(
        " .affiliate-coupon-maintitle " .$attr['titleTag1'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeMobile'], $attr['titlefontSizeTypeMobile']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightMobile']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingMobile'], $attr['titleletterSpacingTypeMobile']),
        ),

         " .affiliate-coupon-starrating" => array(
          "justify-content" => AFFILIATE_Helper::get_css_value($attr['ratingAlignemtMobile']),  
      ),
      " .affiliate-coupon-starrating .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeMobile'], 'px'),
      ),

    


        " .affiliate-coupon-image" => array(
            'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingMobile']['unit'])),
          ),
        " .affiliate-coupon-image img" => array(
          'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthMobile'],'px'),
       ),
 


        " .affiliate-coupon-inner .affiliate-coupon-content" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['contentfontSizeMobile'], $attr['contentfontSizeTypeMobile']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['contentlineHeightMobile']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['contentletterSpacingMobile'], $attr['contentletterSpacingTypeMobile']),
            
        ),
        
        " .affiliate-coupon-inner .seccol .affiliate-coupon-Verified" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['verifiedfontSizeMobile'], $attr['verifiedfontSizeTypeMobile']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['verifiedlineHeightMobile']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['verifiedletterSpacingMobile'], $attr['verifiedletterSpacingTypeMobile']),
          ),


        " .affiliate-coupon-inner .seccol .affiliate-coupon-onlyLeft" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['onlyLeftfontSizeMobile'], $attr['onlyLeftfontSizeTypeMobile']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['onlyLeftlineHeightMobile']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['onlyLeftletterSpacingMobile'], $attr['onlyLeftletterSpacingTypeMobile']),
          ),


        " .affiliate-coupon-inner .seccol .affiliate-coupon-StaffPick" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['staffpickfontSizeMobile'], $attr['staffpickfontSizeTypeMobile']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['staffpicklineHeightMobile']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['staffpickletterSpacingMobile'], $attr['staffpickletterSpacingTypeMobile']),
          ),

        " .affiliate-coupon-inner .seccol .affiliate-coupon-PeopleUsed" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['peopleUsedfontSizeMobile'], $attr['peopleUsedfontSizeTypeMobile']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['peopleUsedlineHeightMobile']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['peopleUsedletterSpacingMobile'], $attr['peopleUsedletterSpacingTypeMobile']),
          ),


         " .affiliate-coupon-inner .thirdcol .affiliate-coupon-onGoingOffer" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferfontSizeMobile'], $attr['onGoingOfferfontSizeTypeMobile']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferlineHeightMobile']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferletterSpacingMobile'], $attr['onGoingOfferletterSpacingTypeMobile']),
          ),


        " .affiliate-coupon-btn-wrapper .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeMobile'], $attr['btnfontSizeTypeMobile']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightMobile']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingMobile'], $attr['btnletterSpacingTypeMobile']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderMobile']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusMobile']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingMobile']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginMobile']['unit'])),
          ),
           
      );

      $t_selectors = array(
        " .affiliate-coupon-maintitle " .$attr['titleTag1'] => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['titlefontSizeTablet'], $attr['titlefontSizeTypeTablet']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['titlelineHeightTablet']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['titleletterSpacingTablet'], $attr['titleletterSpacingTypeTablet']),
        ),

        " .affiliate-coupon-starrating" => array(
          "justify-content" => AFFILIATE_Helper::get_css_value($attr['ratingAlignemtTablet']),  
      ),
      " .affiliate-coupon-starrating .affiliate-star-item svg" => array(
          "height" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet'], 'px'),  
          "width" => AFFILIATE_Helper::get_css_value($attr['starsSizeTablet'], 'px'),
      ),

        " .affiliate-coupon-image img" => array(
            'width' => AFFILIATE_Helper::get_css_value($attr['customImgWidthTablet'],'px'),
        ),
        " .affiliate-coupon-inner .affiliate-coupon-content" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['contentfontSizeTablet'], $attr['contentfontSizeTypeTablet']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['contentlineHeightTablet']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['contentletterSpacingTablet'], $attr['contentletterSpacingTypeTablet']),
            
        ),
        " .affiliate-coupon-image" => array(
          'padding' => AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['imagePaddingTablet']['unit'])),
          ),
 

        " .affiliate-coupon-inner .seccol .affiliate-coupon-Verified" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['verifiedfontSizeTablet'], $attr['verifiedfontSizeTypeTablet']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['verifiedlineHeightTablet']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['verifiedletterSpacingTablet'], $attr['verifiedletterSpacingTypeTablet']),
          ),

        " .affiliate-coupon-inner .seccol .affiliate-coupon-StaffPick" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['staffpickfontSizeTablet'], $attr['staffpickfontSizeTypeTablet']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['staffpicklineHeightTablet']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['staffpickletterSpacingTablet'], $attr['staffpickletterSpacingTypeTablet']),
          ),

         " .affiliate-coupon-inner .seccol .affiliate-coupon-PeopleUsed" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['peopleUsedfontSizeTablet'], $attr['peopleUsedfontSizeTypeTablet']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['peopleUsedlineHeightTablet']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['peopleUsedletterSpacingTablet'], $attr['peopleUsedletterSpacingTypeTablet']),
          ),

         " .affiliate-coupon-inner .seccol .affiliate-coupon-onlyLeft" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['onlyLeftfontSizeTablet'], $attr['onlyLeftfontSizeTypeTablet']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['onlyLeftlineHeightTablet']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['onlyLeftletterSpacingTablet'], $attr['onlyLeftletterSpacingTypeTablet']),
          ),


         " .affiliate-coupon-inner .thirdcol .affiliate-coupon-onGoingOffer" => array(
              'font-size' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferfontSizeTablet'], $attr['onGoingOfferfontSizeTypeTablet']),
              'line-height' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferlineHeightTablet']),
              'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['onGoingOfferletterSpacingTablet'], $attr['onGoingOfferletterSpacingTypeTablet']),
          ),

        " .affiliate-coupon-btn-wrapper .affiliate-btn" => array(
            'font-size' => AFFILIATE_Helper::get_css_value($attr['btnfontSizeTablet'], $attr['btnfontSizeTypeTablet']),
            'line-height' => AFFILIATE_Helper::get_css_value($attr['btnlineHeightTablet']),
            'letter-spacing' => AFFILIATE_Helper::get_css_value($attr['btnletterSpacingTablet'], $attr['btnletterSpacingTypeTablet']),
            "border-width" => AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderTablet']['unit'])),
            "border-radius" => AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnBorderRadiusTablet']['unit'])),
            'padding' => AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnPaddingTablet']['unit'])),
            'margin' => AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['top'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['right'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['bottom'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['left'], AFFILIATE_Helper::get_css_value($attr['btnMarginTablet']['unit'])),
          ),
           
      );

        
        if($attr['btnBg']['openBg'] == 1){
          if($attr['btnBg']['bgType'] == 'gradient'){
            if ($attr['btnBg']['bgGradient']['type'] == 'linear') {
              $selectors[" .affiliate-coupon-btn-wrapper .affiliate-btn"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .affiliate-coupon-btn-wrapper .affiliate-btn"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBg']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


        if($attr['btnBgHover']['openBg'] == 1){
          if($attr['btnBgHover']['bgType'] == 'gradient'){
            if ($attr['btnBgHover']['bgGradient']['type'] == 'linear') {
              $selectors[" .affiliate-coupon-btn-wrapper .affiliate-btn:hover"]["background"] = 'linear-gradient(' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['direction'], 'deg,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
            } else {
              $selectors[" .affiliate-coupon-btn-wrapper .affiliate-btn:hover"]["background"] = 'radial-gradient( circle at ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['radial'], ',') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color1']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['start'], '%,') . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['color2']) . ' ' . AFFILIATE_Helper::get_css_value($attr['btnBgHover']['bgGradient']['stop'], '%') . ')';
            }
          }
        }  


      
        $regenerate_d = AFFILIATE_Helper::regenerate_stylesheet($selectors);
        $regenerate_m = AFFILIATE_Helper::regenerate_stylesheet($t_selectors);
        $regenerate_t = AFFILIATE_Helper::regenerate_stylesheet($m_selectors);

        $desktop = AFFILIATE_Helper::generate_css( $regenerate_d, '#affiliate-style-' . $id );

        $tablet = AFFILIATE_Helper::generate_css( $regenerate_t, '#affiliate-style-' . $id );

        $mobile = AFFILIATE_Helper::generate_css( $regenerate_m, '#affiliate-style-' . $id );

        $generated_css = array(
          'desktop' => $desktop,
          'tablet'  => $tablet,
          'mobile'  => $mobile,
        );
        return $generated_css;
    }

    public static function blocks_coupon4_gfont($attr)
    {
      
      $btn_load_google_font = isset($attr['btnLoadGoogleFonts']) ? $attr['btnLoadGoogleFonts'] : '';
      $btn_font_family      = isset($attr['btnfontFamily']) ? $attr['btnfontFamily'] : '';
      $btn_font_weight      = isset($attr['btnfontWeight']) ? $attr['btnfontWeight'] : '';
      $btn_font_subset      = isset($attr['btnfontSubset']) ? $attr['btnfontSubset'] : '';

      $title_load_google_font = isset($attr['titleLoadGoogleFonts']) ? $attr['titleLoadGoogleFonts'] : '';
      $title_font_family      = isset($attr['titlefontFamily']) ? $attr['titlefontFamily'] : '';
      $title_font_weight      = isset($attr['titlefontWeight']) ? $attr['titlefontWeight'] : '';
      $title_font_subset      = isset($attr['titlefontSubset']) ? $attr['titlefontSubset'] : '';
 

      $content_load_google_font = isset($attr['contentLoadGoogleFonts']) ? $attr['contentLoadGoogleFonts'] : '';
      $content_font_family      = isset($attr['contentfontFamily']) ? $attr['contentfontFamily'] : '';
      $content_font_weight      = isset($attr['contentfontWeight']) ? $attr['contentfontWeight'] : '';
      $content_font_subset      = isset($attr['contentfontSubset']) ? $attr['contentfontSubset'] : '';
 
      $verified_load_google_font = isset($attr['verifiedLoadGoogleFonts']) ? $attr['verifiedLoadGoogleFonts'] : '';
      $verified_font_family      = isset($attr['verifiedfontFamily']) ? $attr['verifiedfontFamily'] : '';
      $verified_font_weight      = isset($attr['verifiedfontWeight']) ? $attr['verifiedfontWeight'] : '';
      $verified_font_subset      = isset($attr['verifiedfontSubset']) ? $attr['verifiedfontSubset'] : '';


      $staffpick_load_google_font = isset($attr['staffpickLoadGoogleFonts']) ? $attr['staffpickLoadGoogleFonts'] : '';
      $staffpick_font_family      = isset($attr['staffpickfontFamily']) ? $attr['staffpickfontFamily'] : '';
      $staffpick_font_weight      = isset($attr['staffpickfontWeight']) ? $attr['staffpickfontWeight'] : '';
      $staffpick_font_subset      = isset($attr['staffpickfontSubset']) ? $attr['staffpickfontSubset'] : '';


      $peopleUsed_load_google_font = isset($attr['peopleUsedLoadGoogleFonts']) ? $attr['peopleUsedLoadGoogleFonts'] : '';
      $peopleUsed_font_family      = isset($attr['peopleUsedfontFamily']) ? $attr['peopleUsedfontFamily'] : '';
      $peopleUsed_font_weight      = isset($attr['peopleUsedfontWeight']) ? $attr['peopleUsedfontWeight'] : '';
      $peopleUsed_font_subset      = isset($attr['peopleUsedfontSubset']) ? $attr['peopleUsedfontSubset'] : '';

      $onlyLeft_load_google_font = isset($attr['onlyLeftLoadGoogleFonts']) ? $attr['onlyLeftLoadGoogleFonts'] : '';
      $onlyLeft_font_family      = isset($attr['onlyLeftfontFamily']) ? $attr['onlyLeftfontFamily'] : '';
      $onlyLeft_font_weight      = isset($attr['onlyLeftfontWeight']) ? $attr['onlyLeftfontWeight'] : '';
      $onlyLeft_font_subset      = isset($attr['onlyLeftfontSubset']) ? $attr['onlyLeftfontSubset'] : '';


      $onGoingOffer_load_google_font = isset($attr['onGoingOfferLoadGoogleFonts']) ? $attr['onGoingOfferLoadGoogleFonts'] : '';
      $onGoingOffer_font_family      = isset($attr['onGoingOfferfontFamily']) ? $attr['onGoingOfferfontFamily'] : '';
      $onGoingOffer_font_weight      = isset($attr['onGoingOfferfontWeight']) ? $attr['onGoingOfferfontWeight'] : '';
      $onGoingOffer_font_subset      = isset($attr['onGoingOfferfontSubset']) ? $attr['onGoingOfferfontSubset'] : '';


        AFFILIATE_Helper::blocks_google_font($btn_load_google_font, $btn_font_family, $btn_font_weight, $btn_font_subset);
      AFFILIATE_Helper::blocks_google_font($title_load_google_font, $title_font_family, $title_font_weight, $title_font_subset);
       
      AFFILIATE_Helper::blocks_google_font($content_load_google_font, $content_font_family, $content_font_weight, $content_font_subset);


      AFFILIATE_Helper::blocks_google_font($verified_load_google_font, $verified_font_family, $verified_font_weight, $verified_font_subset);

      AFFILIATE_Helper::blocks_google_font($staffpick_load_google_font, $staffpick_font_family, $staffpick_font_weight, $staffpick_font_subset);

      AFFILIATE_Helper::blocks_google_font($peopleUsed_load_google_font, $peopleUsed_font_family, $peopleUsed_font_weight, $peopleUsed_font_subset);


      AFFILIATE_Helper::blocks_google_font($onlyLeft_load_google_font, $onlyLeft_font_family, $onlyLeft_font_weight, $onlyLeft_font_subset);

      AFFILIATE_Helper::blocks_google_font($onGoingOffer_load_google_font, $onGoingOffer_font_family, $onGoingOffer_font_weight, $onGoingOffer_font_subset);

    }


  }
}
