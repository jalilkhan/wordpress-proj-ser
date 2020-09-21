<?php
if ( class_exists( 'BoldThemesFramework' ) && isset( BoldThemesFramework::$crush_vars ) ) {
	$boldthemes_crush_vars = BoldThemesFramework::$crush_vars;
}
if ( class_exists( 'BoldThemesFramework' ) && isset( BoldThemesFramework::$crush_vars_def ) ) {
	$boldthemes_crush_vars_def = BoldThemesFramework::$crush_vars_def;
}
if ( isset( $boldthemes_crush_vars['accentColor'] ) ) {
	$accentColor = $boldthemes_crush_vars['accentColor'];
} else {
	$accentColor = "#f66542";
}
if ( isset( $boldthemes_crush_vars['alternateColor'] ) ) {
	$alternateColor = $boldthemes_crush_vars['alternateColor'];
} else {
	$alternateColor = "#3f3f3f";
}
if ( isset( $boldthemes_crush_vars['bodyFont'] ) ) {
	$bodyFont = $boldthemes_crush_vars['bodyFont'];
} else {
	$bodyFont = "Nunito Sans";
}
if ( isset( $boldthemes_crush_vars['menuFont'] ) ) {
	$menuFont = $boldthemes_crush_vars['menuFont'];
} else {
	$menuFont = "Nunito Sans";
}
if ( isset( $boldthemes_crush_vars['headingFont'] ) ) {
	$headingFont = $boldthemes_crush_vars['headingFont'];
} else {
	$headingFont = "Zilla Slab";
}
if ( isset( $boldthemes_crush_vars['headingSuperTitleFont'] ) ) {
	$headingSuperTitleFont = $boldthemes_crush_vars['headingSuperTitleFont'];
} else {
	$headingSuperTitleFont = "Zilla Slab";
}
if ( isset( $boldthemes_crush_vars['headingSubTitleFont'] ) ) {
	$headingSubTitleFont = $boldthemes_crush_vars['headingSubTitleFont'];
} else {
	$headingSubTitleFont = "Nunito Sans";
}
if ( isset( $boldthemes_crush_vars['logoHeight'] ) ) {
	$logoHeight = $boldthemes_crush_vars['logoHeight'];
} else {
	$logoHeight = "100";
}
$accentColorDark = CssCrush\fn__l_adjust( $accentColor." -10" );$accentColorVeryDark = CssCrush\fn__l_adjust( $accentColor." -35" );$accentColorVeryVeryDark = CssCrush\fn__l_adjust( $accentColor." -42" );$accentColorLight = CssCrush\fn__a_adjust( $accentColor." -30" );$alternateColorDark = CssCrush\fn__l_adjust( $alternateColor." -8" );$alternateColorVeryDark = CssCrush\fn__l_adjust( $alternateColor." -25" );$alternateColorLight = CssCrush\fn__l_adjust( $alternateColor." 5" );$css_override = sanitize_text_field("select,
input{font-family: {$bodyFont};}
.btContent a{color: {$accentColor};}
a:hover{
    color: {$accentColor};}
.btText a{color: {$accentColor};}
body{font-family: \"{$bodyFont}\",Arial,sans-serif;}
h1,
h2,
h3,
h4,
h5,
h6{font-family: \"{$headingFont}\";}
blockquote{
    font-family: \"{$headingFont}\";}
.btContentHolder table thead th{
    background-color: {$accentColor};}
.btAccentDarkHeader .btPreloader .animation > div:first-child,
.btLightAccentHeader .btPreloader .animation > div:first-child,
.btTransparentLightHeader .btPreloader .animation > div:first-child{
    background-color: {$accentColor};}
.btPreloader .animation .preloaderLogo{height: {$logoHeight}px;}
.btLoader{
    -webkit-box-shadow: 0 -34px 0 -28px {$accentColor},-10px -33px 0 -28px {$accentColor},-19px -29px 0 -28px {$accentColor},-26px -23px 0 -28px {$accentColor},-32px -15px 0 -28px {$accentColor},-34px -5px 0 -28px {$accentColor};
    box-shadow: 0 -34px 0 -28px {$accentColor},-10px -33px 0 -28px {$accentColor},-19px -29px 0 -28px {$accentColor},-26px -23px 0 -28px {$accentColor},-32px -15px 0 -28px {$accentColor},-34px -5px 0 -28px {$accentColor};}
.btErrorPage .bt_bb_row .bt_bb_column[data-width=\"6\"] .bt_bb_button a{background: {$accentColor};}
.btErrorPage .bt_bb_row .bt_bb_column[data-width=\"6\"] .bt_bb_button a:hover{background: {$accentColorDark};}
.btListingHeadline .bt_bb_listing_price{
    background: {$accentColor};}
.btSingleListHeaderStyle_standard_review .btListingHeadline .comments li > article .commentTxt .author{
    font-family: {$bodyFont};
    color: {$accentColor};}
.single-listing .btArticleHeadline .bt_bb_listing_price{
    background: {$accentColor};}
.mainHeader{
    font-family: \"{$menuFont}\";}
.mainHeader a:hover{color: {$accentColor};}
.menuPort{font-family: \"{$menuFont}\";}
.menuPort nav ul li a:hover{color: {$accentColor};}
.menuPort nav > ul > li > a{line-height: {$logoHeight}px;}
.btTextLogo{font-family: \"{$headingFont}\";
    line-height: {$logoHeight}px;}
.btLogoArea .logo img{height: {$logoHeight}px;}
.btTransparentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btTransparentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btAccentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btAccentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btLightDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btHasAltLogo.btStickyHeaderActive .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btTransparentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btTransparentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btAccentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btAccentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btLightDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btHasAltLogo.btStickyHeaderActive .btHorizontalMenuTrigger:hover .bt_bb_icon:after{border-top-color: {$accentColor};}
.btTransparentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btTransparentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btAccentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btAccentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btLightDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btHasAltLogo.btStickyHeaderActive .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before{border-top-color: {$accentColor};}
.btMenuHorizontal .menuPort nav > ul > li.current-menu-ancestor > a:after,
.btMenuHorizontal .menuPort nav > ul > li.current-menu-item > a:after{
    background-color: {$accentColor};}
.btMenuHorizontal .menuPort nav > ul > li.current-menu-ancestor li.current-menu-ancestor > a,
.btMenuHorizontal .menuPort nav > ul > li.current-menu-ancestor li.current-menu-item > a,
.btMenuHorizontal .menuPort nav > ul > li.current-menu-item li.current-menu-ancestor > a,
.btMenuHorizontal .menuPort nav > ul > li.current-menu-item li.current-menu-item > a{color: {$accentColor};}
.btMenuHorizontal .menuPort ul ul li a{
    -webkit-box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 0 {$accentColor};
    box-shadow: inset 0 0 0 3px #fff,inset 0 0 0 0 {$accentColor};}
.btMenuHorizontal .menuPort ul ul li a:hover{color: {$accentColor};}
body.btMenuHorizontal .subToggler{
    line-height: {$logoHeight}px;}
.btMenuHorizontal .menuPort > nav > ul > li > ul li a:hover{-webkit-box-shadow: inset 0 0 0 3px #fff,inset 5px 0 0 0 {$accentColor};
    box-shadow: inset 0 0 0 3px #fff,inset 5px 0 0 0 {$accentColor};}
html:not(.touch) body.btMenuHorizontal .menuPort > nav > ul > li.btMenuWideDropdown > ul > li > a{
    font-family: {$headingFont};}
.btMenuHorizontal .topBarInMenu{
    height: {$logoHeight}px;}
.btMenuHorizontal .topBarInMenu .topBarInMenuCell{line-height: -webkit-calc({$logoHeight}px/2 - 2px);
    line-height: -moz-calc({$logoHeight}px/2 - 2px);
    line-height: calc({$logoHeight}px/2 - 2px);}
.btAccentLightHeader .btBelowLogoArea,
.btAccentLightHeader .topBar{background-color: {$accentColor};}
.btAccentDarkHeader .btBelowLogoArea,
.btAccentDarkHeader .topBar{background-color: {$accentColor};}
.btLightAccentHeader .btLogoArea,
.btLightAccentHeader .btVerticalHeaderTop{background-color: {$accentColor};}
.btLightAccentHeader.btMenuHorizontal.btBelowMenu .mainHeader .btLogoArea{background-color: {$accentColor};}
.btStickyHeaderActive.btMenuHorizontal .mainHeader .btLogoArea .logo img{height: -webkit-calc({$logoHeight}px*0.6);
    height: -moz-calc({$logoHeight}px*0.6);
    height: calc({$logoHeight}px*0.6);}
.btStickyHeaderActive.btMenuHorizontal .mainHeader .btLogoArea .btTextLogo{
    line-height: -webkit-calc({$logoHeight}px*0.6);
    line-height: -moz-calc({$logoHeight}px*0.6);
    line-height: calc({$logoHeight}px*0.6);}
.btStickyHeaderActive.btMenuHorizontal .mainHeader .btLogoArea .menuPort nav > ul > li > a,
.btStickyHeaderActive.btMenuHorizontal .mainHeader .btLogoArea .menuPort nav > ul > li > .subToggler{line-height: -webkit-calc({$logoHeight}px*0.6);
    line-height: -moz-calc({$logoHeight}px*0.6);
    line-height: calc({$logoHeight}px*0.6);}
.btStickyHeaderActive.btMenuHorizontal .mainHeader .btLogoArea .topBarInMenu{height: -webkit-calc({$logoHeight}px*0.6);
    height: -moz-calc({$logoHeight}px*0.6);
    height: calc({$logoHeight}px*0.6);}
.btTransparentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btTransparentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btAccentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btAccentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btLightDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btHasAltLogo.btStickyHeaderActive .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btTransparentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btTransparentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btAccentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btAccentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btLightDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btHasAltLogo.btStickyHeaderActive .btVerticalMenuTrigger:hover .bt_bb_icon:after{border-top-color: {$accentColor};}
.btTransparentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btTransparentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btAccentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btAccentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btLightDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btHasAltLogo.btStickyHeaderActive .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before{border-top-color: {$accentColor};}
.btMenuVertical .mainHeader .btCloseVertical:before:hover{color: {$accentColor};}
.btMenuHorizontal .topBarInLogoArea{
    height: {$logoHeight}px;}
.btMenuHorizontal .topBarInLogoArea .topBarInLogoAreaCell{border: 0 solid {$accentColor};}
.bt_favorites_button .bt_bb_listing_marker_add_favourite{
    background: {$accentColor};}
.bt_favorites_button .bt_bb_listing_marker_add_favourite:hover{background: {$accentColor};}
.bt_favorites_button .bt_bb_listing_marker_add_favourite.bt_bb_listing_favourite_on{
    background: {$accentColor};}
.bt_favorites_button .bt_bb_listing_marker_add_favourite.bt_bb_listing_favourite_on:hover{background: {$accentColorDark};}
.btPostSingleItemStandard .btArticleShareEtc > div.btReadMoreColumn .bt_bb_button a{background: {$accentColor};}
.btPostSingleItemStandard .btArticleShareEtc > div.btReadMoreColumn .bt_bb_button a:hover{background: {$accentColorDark};}
.btAboutAuthor .aaTxt h1 .btArticleAuthor:hover,
.btAboutAuthor .aaTxt h2 .btArticleAuthor:hover,
.btAboutAuthor .aaTxt h3 .btArticleAuthor:hover,
.btAboutAuthor .aaTxt h4 .btArticleAuthor:hover,
.btAboutAuthor .aaTxt h5 .btArticleAuthor:hover,
.btAboutAuthor .aaTxt h6 .btArticleAuthor:hover,
.btAboutAuthor .aaTxt h7 .btArticleAuthor:hover,
.btAboutAuthor .aaTxt h8 .btArticleAuthor:hover{color: {$accentColor};}
.btMediaBox.btQuote:before,
.btMediaBox.btLink:before{
    background-color: {$accentColor};}
.sticky.btArticleListItem .btArticleHeadline h1 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h2 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h3 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h4 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h5 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h6 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h7 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h8 .bt_bb_headline_content span a:after{
    color: {$accentColor};}
.rtl .sticky.btArticleListItem .btArticleHeadline h1 .bt_bb_headline_content span a:before,
.rtl .sticky.btArticleListItem .btArticleHeadline h2 .bt_bb_headline_content span a:before,
.rtl .sticky.btArticleListItem .btArticleHeadline h3 .bt_bb_headline_content span a:before,
.rtl .sticky.btArticleListItem .btArticleHeadline h4 .bt_bb_headline_content span a:before,
.rtl .sticky.btArticleListItem .btArticleHeadline h5 .bt_bb_headline_content span a:before,
.rtl .sticky.btArticleListItem .btArticleHeadline h6 .bt_bb_headline_content span a:before,
.rtl .sticky.btArticleListItem .btArticleHeadline h7 .bt_bb_headline_content span a:before,
.rtl .sticky.btArticleListItem .btArticleHeadline h8 .bt_bb_headline_content span a:before{
    color: {$accentColor};}
.post-password-form p:first-child{color: {$alternateColor};}
.post-password-form p:nth-child(2) input[type=\"submit\"]{
    background: {$accentColor};}
.post-password-form p:nth-child(2) input[type=\"submit\"]:hover{background: {$accentColorDark};}
.btPagination{font-family: \"{$headingFont}\";}
.btPagination .paging a:after{
    background: {$accentColor};}
.btPagination .paging a:before{
    border: 2px solid {$accentColor};}
.btPagination .paging a:hover{color: {$accentColor};}
.btPagination .paging a:hover:after{background: {$accentColorDark};}
.btPrevNextNav .btPrevNext .btPrevNextImage:after{
    background: {$accentColor};}
.btPrevNextNav .btPrevNext .btPrevNextItem .btPrevNextTitle{font-family: {$headingFont};}
.btPrevNextNav .btPrevNext:hover .btPrevNextTitle{color: {$accentColor};}
.btLinkPages ul a{
    background: {$accentColor};}
.btLinkPages ul a:hover{background: {$accentColorDark};}
.btArticleCategories a:hover{color: {$accentColor};}
.btLightSkin .btArticleAuthor:hover,
.btLightSkin .btArticleComments:hover,
.bt_bb_color_scheme_2 .btArticleAuthor:hover,
.bt_bb_color_scheme_2 .btArticleComments:hover,
.bt_bb_color_scheme_4 .btArticleAuthor:hover,
.bt_bb_color_scheme_4 .btArticleComments:hover,
.bt_bb_color_scheme_5 .btArticleAuthor:hover,
.bt_bb_color_scheme_5 .btArticleComments:hover,
.bt_bb_color_scheme_8 .btArticleAuthor:hover,
.bt_bb_color_scheme_8 .btArticleComments:hover,
.bt_bb_color_scheme_9 .btArticleAuthor:hover,
.bt_bb_color_scheme_9 .btArticleComments:hover{color: {$accentColor};}
.btLightSkin .btArticleAuthor:hover:before,
.btLightSkin .btArticleComments:hover:before,
.bt_bb_color_scheme_2 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_2 .btArticleComments:hover:before,
.bt_bb_color_scheme_4 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_4 .btArticleComments:hover:before,
.bt_bb_color_scheme_5 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_5 .btArticleComments:hover:before,
.bt_bb_color_scheme_8 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_8 .btArticleComments:hover:before,
.bt_bb_color_scheme_9 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_9 .btArticleComments:hover:before{color: {$accentColor};}
.btDarkSkin .btArticleAuthor:hover,
.btDarkSkin .btArticleComments:hover,
.bt_bb_color_scheme_1 .btArticleAuthor:hover,
.bt_bb_color_scheme_1 .btArticleComments:hover,
.bt_bb_color_scheme_3 .btArticleAuthor:hover,
.bt_bb_color_scheme_3 .btArticleComments:hover,
.bt_bb_color_scheme_6 .btArticleAuthor:hover,
.bt_bb_color_scheme_6 .btArticleComments:hover,
.bt_bb_color_scheme_7 .btArticleAuthor:hover,
.bt_bb_color_scheme_7 .btArticleComments:hover,
.bt_bb_color_scheme_10 .btArticleAuthor:hover,
.bt_bb_color_scheme_10 .btArticleComments:hover{color: {$accentColor};}
.btDarkSkin .btArticleAuthor:hover:before,
.btDarkSkin .btArticleComments:hover:before,
.bt_bb_color_scheme_1 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_1 .btArticleComments:hover:before,
.bt_bb_color_scheme_3 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_3 .btArticleComments:hover:before,
.bt_bb_color_scheme_6 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_6 .btArticleComments:hover:before,
.bt_bb_color_scheme_7 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_7 .btArticleComments:hover:before,
.bt_bb_color_scheme_10 .btArticleAuthor:hover:before,
.bt_bb_color_scheme_10 .btArticleComments:hover:before{color: {$accentColor};}
.btReviewHolder h5.btReviewHeadingOverview,
.btReviewHolder h5.btReviewHeadingSummary{color: {$accentColor};
    font-family: {$headingSuperTitleFont};
    background: {$accentColor};}
.btReviewHolder h5.btReviewHeadingSummary{background: {$alternateColor};}
.btReviewHolder .btReviewOverviewSegment .bt_bb_size_small.bt_bb_progress_bar .bt_bb_progress_bar_inner{
    border-bottom-color: {$accentColor};}
.btReviewHolder .btReviewOverviewSegment .bt_bb_size_small.bt_bb_progress_bar .bt_bb_progress_bar_inner .bt_bb_progress_bar_text{font-family: {$headingFont};}
.btReviewHolder .btReviewScore .btReviewPercentage{
    background: {$accentColor};}
.btReviewHolder .btReviewScore .btReviewPercentage strong{
    font-family: {$headingFont};}
.btCommentsBox ul.comments li.pingback .edit-link a:before{
    color: {$accentColor};}
.btLightSkin .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_2 .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_4 .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_5 .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_8 .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_9 .btCommentsBox ul.comments li.pingback .edit-link a:hover{color: {$accentColor};}
.btDarkSkin .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_1 .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_3 .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_6 .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_7 .btCommentsBox ul.comments li.pingback .edit-link a:hover,
.bt_bb_color_scheme_10 .btCommentsBox ul.comments li.pingback .edit-link a:hover{color: {$accentColor};}
.btCommentsBox .vcard h1.author,
.btCommentsBox .vcard h2.author,
.btCommentsBox .vcard h3.author,
.btCommentsBox .vcard h4.author,
.btCommentsBox .vcard h5.author,
.btCommentsBox .vcard h6.author,
.btCommentsBox .vcard h7.author,
.btCommentsBox .vcard h8.author{
    font-family: {$bodyFont};}
.btCommentsBox .vcard .posted:before{
    color: {$accentColor};}
.btCommentsBox .commentTxt p.edit-link a:before,
.btCommentsBox .commentTxt p.reply a:before{
    color: {$accentColor};}
.btLightSkin .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_2 .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_4 .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_5 .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_8 .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_9 .btCommentsBox .commentTxt p.edit-link a:hover,
.btLightSkin .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_2 .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_4 .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_5 .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_8 .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_9 .btCommentsBox .commentTxt p.reply a:hover{color: {$accentColor};}
.btDarkSkin .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_1 .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_3 .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_6 .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_7 .btCommentsBox .commentTxt p.edit-link a:hover,
.bt_bb_color_scheme_10 .btCommentsBox .commentTxt p.edit-link a:hover,
.btDarkSkin .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_1 .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_3 .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_6 .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_7 .btCommentsBox .commentTxt p.reply a:hover,
.bt_bb_color_scheme_10 .btCommentsBox .commentTxt p.reply a:hover{color: {$accentColor};}
.comment-awaiting-moderation{color: {$accentColor};}
a#cancel-comment-reply-link{
    color: {$accentColor};
    font-family: {$bodyFont};}
a#cancel-comment-reply-link:after{
    border: 2px solid {$accentColor};}
a#cancel-comment-reply-link:hover{border-color: {$accentColorDark};
    color: {$accentColorDark};}
.btCommentSubmit{
    background: {$accentColor};}
.btCommentSubmit:after{
    border: 2px solid {$accentColor};}
.btCommentSubmit:hover{background: {$accentColorDark};}
.woocommerce-noreviews{
    font-family: {$headingFont};
    color: {$accentColor};}
.btBox ul li a:before,
.btCustomMenu ul li a:before,
.btTopBox ul li a:before{
    background: {$accentColor};}
.btBox ul li.current-menu-item > a,
.btCustomMenu ul li.current-menu-item > a,
.btTopBox ul li.current-menu-item > a{color: {$accentColor};}
.widget_calendar table caption{background: {$accentColor};
    background: {$accentColor};
    font-family: \"{$headingFont}\";}
.widget_rss li a.rsswidget{font-family: \"{$headingFont}\";}
.fancy-select ul.options li:hover{
    color: {$accentColor};
    -webkit-box-shadow: inset 3px 0 0 0 #fff,inset 5px 0 0 0 {$accentColor};
    box-shadow: inset 3px 0 0 0 #fff,inset 5px 0 0 0 {$accentColor};}
.rtl .fancy-select ul.options li:hover{-webkit-box-shadow: inset -3px 0 0 0 #fff,inset -5px 0 0 0 {$accentColor};
    box-shadow: inset -3px 0 0 0 #fff,inset -5px 0 0 0 {$accentColor};}
.widget_shopping_cart .total strong{
    font-family: {$headingFont};}
.widget_shopping_cart .buttons .button{
    background: {$accentColor};}
.widget_shopping_cart .widget_shopping_cart_content .mini_cart_item .ppRemove a.remove{
    color: {$accentColor};}
.widget_shopping_cart .widget_shopping_cart_content .mini_cart_item .ppRemove a.remove:hover{color: {$alternateColor};}
.menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon,
.topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon,
.topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon{
    background: {$accentColor};}
.menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon:after,
.topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon:after,
.topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon:after{
    border: 2px solid {$accentColor};}
.menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon:hover,
.topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon:hover,
.topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon:hover{background: {$accentColorDark};}
.menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon span.cart-contents,
.topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon span.cart-contents,
.topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon span.cart-contents{
    background-color: {$alternateColor};
    font: normal 10px/1 {$menuFont};}
.menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent,
.topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent,
.topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent{
    top: -webkit-calc({$logoHeight}px - (({$logoHeight}px/2 + 2px)/2));
    top: -moz-calc({$logoHeight}px - (({$logoHeight}px/2 + 2px)/2));
    top: calc({$logoHeight}px - (({$logoHeight}px/2 + 2px)/2));}
.btMenuHorizontal.btStickyHeaderActive .menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent,
.btMenuHorizontal.btStickyHeaderActive .topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent,
.btMenuHorizontal.btStickyHeaderActive .topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent{top: -webkit-calc({$logoHeight}px*.6 - ({$logoHeight}px*.6 - {$logoHeight}px/2 + 2px)/2);
    top: -moz-calc({$logoHeight}px*.6 - ({$logoHeight}px*.6 - {$logoHeight}px/2 + 2px)/2);
    top: calc({$logoHeight}px*.6 - ({$logoHeight}px*.6 - {$logoHeight}px/2 + 2px)/2);}
.btMenuVertical .menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent .verticalMenuCartToggler,
.btMenuVertical .topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent .verticalMenuCartToggler,
.btMenuVertical .topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent .verticalMenuCartToggler{
    color: {$accentColor};}
.widget_recent_reviews{font-family: {$headingFont};}
.widget_price_filter .price_slider_wrapper .ui-slider .ui-slider-handle{
    background-color: {$accentColor};}
.btBox .tagcloud a,
.btTags ul a{
    font-family: {$headingSuperTitleFont};
    background: {$accentColor};}
.btBox .tagcloud a:hover,
.btTags ul a:hover{background: {$accentColor};}
.topTools .btIconWidget:not(div)hover,
.topBarInMenu .btIconWidget:not(div)hover{color: {$accentColor};}
.mainHeader .btIconWidget .btIconWidgetIcon{color: {$accentColor};}
.mainHeader .btAccentIconWidget.btIconWidget{background: {$accentColor};}
.mainHeader .btAccentIconWidget.btIconWidget:not(div):after{
    border: 2px solid {$accentColor};}
.mainHeader .btAccentIconWidget.btIconWidget:not(div):hover{background: {$accentColorDark};}
.mainHeader .topBarInLogoArea .btIconWidget.btAccentIconWidget .btIconWidgetIcon{color: {$accentColor};}
.mainHeader .topBarInLogoArea .btIconWidget.btAccentIconWidget:not(div):hover{color: {$accentColor};}
.btAccentLightHeader .mainHeader .topBarInLogoArea .btIconWidget.btAccentIconWidget .btIconWidgetIcon{color: {$accentColor};}
.btAccentLightHeader .mainHeader .topBarInLogoArea .btIconWidget.btAccentIconWidget:not(div):hover{color: {$accentColor};}
.btAccentLightHeader .mainHeader .topBarInLogoArea .btIconWidget.btAccentIconWidget:not(div):hover .btIconWidgetIcon{color: {$accentColor} !important;}
.btAccentLightHeader.btMenuHorizontal:not(.btMenuBelowLogo) .mainHeader .topBarInMenu .btAccentIconWidget.btIconWidget:not(div):hover{background: {$accentColor};}
.btAccentDarkHeader .mainHeader .btBelowLogoArea .btAccentIconWidget.btIconWidget .btIconWidgetIcon,
.btAccentDarkHeader .mainHeader .topBar .btAccentIconWidget.btIconWidget .btIconWidgetIcon{color: {$accentColor};}
.btLightSkin .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_2 .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_4 .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_5 .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_8 .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_9 .btSiteFooterWidgets .btSearch button:hover,
.btDarkSkin .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_1 .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_3 .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_6 .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_7 .btSiteFooterWidgets .btSearch button:hover,
.bt_bb_color_scheme_10 .btSiteFooterWidgets .btSearch button:hover,
.btLightSkin .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_2 .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_4 .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_5 .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_8 .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_9 .btSidebar .btSearch button:hover,
.btDarkSkin .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_1 .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_3 .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_6 .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_7 .btSidebar .btSearch button:hover,
.bt_bb_color_scheme_10 .btSidebar .btSearch button:hover,
.btLightSkin .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_2 .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_4 .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_5 .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_8 .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_9 .btSidebar .widget_product_search button:hover,
.btDarkSkin .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_1 .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_3 .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_6 .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_7 .btSidebar .widget_product_search button:hover,
.bt_bb_color_scheme_10 .btSidebar .widget_product_search button:hover{background: {$accentColor} !important;
    border-color: {$accentColor} !important;}
.widget_search .btSearch .bt_bb_icon a,
.widget_bt_header_search_widget .btAdvancedSearch .bt_bb_icon a{
    background: {$alternateColor};}
.widget_search .btSearch .bt_bb_icon a:after,
.widget_bt_header_search_widget .btAdvancedSearch .bt_bb_icon a:after{
    border: 2px solid {$alternateColor};}
.widget_search .btSearch .bt_bb_icon a:hover,
.widget_bt_header_search_widget .btAdvancedSearch .bt_bb_icon a:hover{background: {$alternateColorLight};}
.btSearchInner.btFromTopBox .btSearchInnerClose .bt_bb_icon a.bt_bb_icon_holder{color: {$accentColor};}
.btSearchInner.btFromTopBox .btSearchInnerClose .bt_bb_icon:hover a.bt_bb_icon_holder{color: {$accentColorDark};}
.btSearchInner.btFromTopBox button:hover:before{color: {$accentColor};}
.widget_bt_bb_listing_marker_options ul li a:before{
    color: {$accentColor};}
.widget_bt_bb_listing_marker_options ul li a .bt_bb_listing_marker_small_circle{background: {$alternateColor};}
.widget_bt_bb_listing_marker_options ul li .simplefavorite-button.active a.bt_bb_listing_marker_add_favourite:before{background: {$accentColor} !important;}
.widget_contact_details > span:before,
.widget_contact_details > a:before{color: {$accentColor};}
.widget_contact_details > a:hover{color: {$accentColor};}
.widget_bt_claim_widget .bt_claim_pending:before{
    color: {$accentColor};}
.widget_bt_bb_listing_marker_working_hours ul li > span:before{color: {$accentColor};}
.widget_bt_bb_listing_marker_working_hours ul li.bt_bb_listing_marker_meta_working_hours.bt_bb_listing_marker_meta_now_working > span{color: {$accentColor};}
.widget_bt_listing_form_widget .submitMessage .error{
    color: {$accentColor};}
.widget_bt_listing_form_widget .widget_form_wrapper .btMessageSubmit{
    background: {$alternateColor};}
.widget_bt_listing_form_widget .widget_form_wrapper .btMessageSubmit:after{
    border: 2px solid {$alternateColor};}
.widget_bt_listing_form_widget .widget_form_wrapper .btMessageSubmit:hover{background: {$alternateColorLight};}
.widget_opentable .btOpenTableReservation .btOpenTableReservationColumnSubmit .otreservations-submit{
    background: {$alternateColor};}
.widget_opentable .btOpenTableReservation .btOpenTableReservationColumnSubmit .otreservations-submit:after{
    border: 2px solid {$alternateColor};}
.widget_opentable .btOpenTableReservation .btOpenTableReservationColumnSubmit .otreservations-submit:hover{background: {$alternateColorLight};}
.widget_opentable .btOpenTableReservation .btOpenTableReservationColumnError{
    color: {$accentColor};}
.btBox .timekit .bookingjs .bookingjs-loading .bookingjs-loading-icon svg path{fill: {$accentColor};}
.btBox .timekit .bookingjs .bookingjs-calendar .fc-view-container .fc-body .fc-scroller .fc-row .fc-content-skeleton td .fc-event:hover{border-left-color: {$accentColorDark};
    background: {$accentColor};}
.btBox .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-box .bookingjs-form-fields .bookingjs-form-field input,
.btBox .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-box .bookingjs-form-fields .bookingjs-form-field textarea{font-family: {$bodyFont};}
.btBox .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-box .bookingjs-form-success-message .booked-email{color: {$accentColor};}
.btBox .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-button{
    background: {$alternateColor};
    font-family: {$bodyFont};}
.btBox .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-button:hover{background: {$alternateColorLight};}
.btBox .timekit .bookingjs .bookingjs-bookpage-close .bookingjs-closeicon path{fill: {$accentColor};}
.btBox .timekit .bookingjs .bookingjs-poweredby a svg path{fill: {$accentColor};
    stroke: {$accentColor};}
.btBox .timekit .bookingjs .bookingjs-poweredby a:hover{color: {$accentColor};}
.btBox .timekit .bookingjs .bookingjs-timezonehelper svg path{fill: {$accentColor};}
.btBox .timekit .bookingjs .bookingjs-displayname{
    font-family: {$headingFont};
    color: {$accentColor};}
#resurva_popup button.mfp-close:before{color: {$accentColor};}
#resurva_popup button.mfp-close:hover:before{color: {$accentColorDark};}
.bt_bb_listing_checkbox span:before{
    background: {$accentColor};}
.widget_bt_listing_widget .bt_bb_slider .slick-dots li.slick-active{
    -webkit-box-shadow: 0 0 0 1em {$accentColor} inset;
    box-shadow: 0 0 0 1em {$accentColor} inset;}
.widget_bt_listing_widget .bt_bb_slider .slick-dots li:hover{
    -webkit-box-shadow: 0 0 0 1em {$accentColor} inset;
    box-shadow: 0 0 0 1em {$accentColor} inset;}
.widget_bt_listing_widget .bt_bb_listing_email > span:before,
.widget_bt_listing_widget .bt_bb_listing_email > a:before,
.widget_bt_listing_widget .bt_bb_listing_url > span:before,
.widget_bt_listing_widget .bt_bb_listing_url > a:before,
.widget_bt_listing_widget .bt_bb_listing_phone > span:before,
.widget_bt_listing_widget .bt_bb_listing_phone > a:before,
.widget_bt_listing_widget .bt_bb_listing_time > span:before,
.widget_bt_listing_widget .bt_bb_listing_time > a:before,
.widget_bt_listing_widget .bt_bb_listing_date > span:before,
.widget_bt_listing_widget .bt_bb_listing_date > a:before,
.widget_bt_listing_widget .bt_bb_listing_datetime > span:before,
.widget_bt_listing_widget .bt_bb_listing_datetime > a:before{color: {$accentColor};}
.widget_bt_listing_widget .bt_bb_listing_email > a:hover,
.widget_bt_listing_widget .bt_bb_listing_url > a:hover,
.widget_bt_listing_widget .bt_bb_listing_phone > a:hover,
.widget_bt_listing_widget .bt_bb_listing_time > a:hover,
.widget_bt_listing_widget .bt_bb_listing_date > a:hover,
.widget_bt_listing_widget .bt_bb_listing_datetime > a:hover{color: {$accentColor};}
.widget_bt_listing_widget .bt_bb_listing_text ul li:before{
    color: {$accentColor};}
::-webkit-scrollbar-thumb:hover{background-color: {$accentColor};}
.bt_bb_headline .bt_bb_headline_superheadline{
    font-family: {$headingSuperTitleFont};}
.bt_bb_headline.bt_bb_subheadline .bt_bb_headline_subheadline{font-family: {$headingSubTitleFont};}
.bt_bb_headline .bt_bb_headline_content b{color: {$accentColor};}
.bt_bb_headline .bt_bb_headline_content em{
    color: {$alternateColor};}
.bt_bb_dash_bottom.bt_bb_headline .bt_bb_headline_content > span:after,
.bt_bb_dash_top_bottom.bt_bb_headline .bt_bb_headline_content > span:after{
    border-bottom: 2px solid {$accentColor};}
.bt_bb_dash_top.bt_bb_headline .bt_bb_headline_content > span:before,
.bt_bb_dash_top_bottom.bt_bb_headline .bt_bb_headline_content > span:before{
    border-bottom: 2px solid {$accentColor};}
.bt_bb_latest_posts_item .bt_bb_latest_posts_item_date{font-family: {$headingSuperTitleFont};}
.bt_bb_latest_posts_item .bt_bb_latest_posts_item_title{
    color: {$accentColor};}
.bt_bb_color_scheme_5.bt_bb_button.bt_bb_style_filled a:hover{background: {$accentColorDark} !important;}
.bt_bb_color_scheme_6.bt_bb_button.bt_bb_style_filled a:hover{background: {$accentColorDark} !important;}
.bt_bb_color_scheme_9.bt_bb_button.bt_bb_style_filled a:hover{background: {$alternateColorLight} !important;}
.bt_bb_color_scheme_10.bt_bb_button.bt_bb_style_filled a:hover{background: {$alternateColorLight} !important;}
.bt_bb_service .bt_bb_service_content .bt_bb_service_content_title{
    font-family: {$headingFont};}
.bt_bb_service:hover .bt_bb_service_content_title a{color: {$accentColor};}
.slick-dots li.slick-active{
    -webkit-box-shadow: 0 0 0 1em {$accentColor} inset;
    box-shadow: 0 0 0 1em {$accentColor} inset;}
.slick-dots li:hover{
    -webkit-box-shadow: 0 0 0 1em {$accentColor} inset;
    box-shadow: 0 0 0 1em {$accentColor} inset;}
.btLightSkin button.slick-arrow:hover,
.bt_bb_color_scheme_2 button.slick-arrow:hover,
.bt_bb_color_scheme_4 button.slick-arrow:hover,
.bt_bb_color_scheme_5 button.slick-arrow:hover,
.bt_bb_color_scheme_8 button.slick-arrow:hover,
.bt_bb_color_scheme_9 button.slick-arrow:hover,
.btDarkSkin button.slick-arrow:hover,
.bt_bb_color_scheme_1 button.slick-arrow:hover,
.bt_bb_color_scheme_3 button.slick-arrow:hover,
.bt_bb_color_scheme_6 button.slick-arrow:hover,
.bt_bb_color_scheme_7 button.slick-arrow:hover,
.bt_bb_color_scheme_10 button.slick-arrow:hover{background-color: {$accentColorDark};}
.btLightSkin button.slick-arrow:hover:after,
.bt_bb_color_scheme_2 button.slick-arrow:hover:after,
.bt_bb_color_scheme_4 button.slick-arrow:hover:after,
.bt_bb_color_scheme_5 button.slick-arrow:hover:after,
.bt_bb_color_scheme_8 button.slick-arrow:hover:after,
.bt_bb_color_scheme_9 button.slick-arrow:hover:after,
.btDarkSkin button.slick-arrow:hover:after,
.bt_bb_color_scheme_1 button.slick-arrow:hover:after,
.bt_bb_color_scheme_3 button.slick-arrow:hover:after,
.bt_bb_color_scheme_6 button.slick-arrow:hover:after,
.bt_bb_color_scheme_7 button.slick-arrow:hover:after,
.bt_bb_color_scheme_10 button.slick-arrow:hover:after{border-color: {$accentColor};}
.bt_bb_custom_menu div ul a:hover{color: {$accentColor};}
ul.bt_bb_tabs_header li{font-family: {$headingFont};}
.bt_bb_color_scheme_1.bt_bb_tabs.bt_bb_style_simple .bt_bb_tabs_header li.on{border-color: {$accentColor};}
.bt_bb_color_scheme_2.bt_bb_tabs.bt_bb_style_simple .bt_bb_tabs_header li.on{border-color: {$accentColor};}
.bt_bb_color_scheme_3.bt_bb_tabs.bt_bb_style_simple .bt_bb_tabs_header li.on{border-color: {$accentColor};}
.bt_bb_color_scheme_4.bt_bb_tabs.bt_bb_style_simple .bt_bb_tabs_header li.on{border-color: {$accentColor};}
.bt_bb_style_simple ul.bt_bb_tabs_header li.on{border-color: {$accentColor};}
.bt_bb_accordion .bt_bb_accordion_item .bt_bb_accordion_item_title{font-family: {$headingFont};}
.wpcf7-form .wpcf7-submit{
    background: {$accentColor} !important;}
.wpcf7-form .wpcf7-submit:hover{background: {$accentColorDark} !important;}
.bt_bb_price_list .bt_bb_price_list_title{font-family: {$headingFont};}
.bt_bb_price_list .bt_bb_price_list_subtitle{
    font-family: {$headingFont};}
button.mfp-close{
    color: {$accentColor};}
button.mfp-close:hover{
    color: {$accentColorDark};}
button.mfp-arrow:hover{background: {$accentColorDark};}
button.mfp-arrow:hover:after{border-color: {$accentColor};}
.bt_bb_required:after{
    color: {$accentColor} !important;}
.required{color: {$accentColor} !important;}
.bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_category{
    font-family: {$headingSuperTitleFont};}
.bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_category .post-categories li a:hover{
    color: {$accentColor};}
.bt_bb_color_scheme_3 .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_title a{color: {$accentColor} !important;}
.bt_bb_color_scheme_4 .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_title a{color: {$accentColor} !important;}
.bt_bb_color_scheme_7 .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_title a{color: {$alternateColor} !important;}
.bt_bb_color_scheme_8 .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_title a{color: {$alternateColor} !important;}
.bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_title a:hover{color: {$accentColor};}
.bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_meta > span a:hover{color: {$accentColor} !important;}
.bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_meta .bt_bb_latest_posts_item_date{font-family: {$bodyFont};}
.bt_bb_post_grid_loader{
    -webkit-box-shadow: 0 -34px 0 -28px {$accentColor},-10px -33px 0 -28px {$accentColor},-19px -29px 0 -28px {$accentColor},-26px -23px 0 -28px {$accentColor},-32px -15px 0 -28px {$accentColor},-34px -5px 0 -28px {$accentColor};
    box-shadow: 0 -34px 0 -28px {$accentColor},-10px -33px 0 -28px {$accentColor},-19px -29px 0 -28px {$accentColor},-26px -23px 0 -28px {$accentColor},-32px -15px 0 -28px {$accentColor},-34px -5px 0 -28px {$accentColor};}
.bt_bb_post_grid_filter{
    font-family: {$menuFont};}
.bt_bb_post_grid_filter .bt_bb_post_grid_filter_item:after{
    background: {$accentColor};}
.bt_bb_post_grid_filter .bt_bb_post_grid_filter_item:hover{color: {$accentColor};}
.bt_bb_masonry_post_grid .bt_bb_grid_item_post_thumbnail:before{
    background: {$accentColor};}
.bt_bb_masonry_post_grid .bt_bb_grid_item_post_content .bt_bb_grid_item_category{
    font-family: {$headingSuperTitleFont};}
.bt_bb_masonry_post_grid .bt_bb_grid_item_post_content .bt_bb_grid_item_category .post-categories li a:hover{
    color: {$accentColor};}
.bt_bb_masonry_post_grid .bt_bb_grid_item_post_content .bt_bb_grid_item_meta > span a:hover{color: {$accentColor} !important;}
.bt_bb_color_scheme_3 .bt_bb_masonry_post_grid .bt_bb_grid_item_post_content .bt_bb_grid_item_post_title a{color: {$accentColor} !important;}
.bt_bb_color_scheme_4 .bt_bb_masonry_post_grid .bt_bb_grid_item_post_content .bt_bb_grid_item_post_title a{color: {$accentColor} !important;}
.bt_bb_color_scheme_7 .bt_bb_masonry_post_grid .bt_bb_grid_item_post_content .bt_bb_grid_item_post_title a{color: {$alternateColor} !important;}
.bt_bb_color_scheme_8 .bt_bb_masonry_post_grid .bt_bb_grid_item_post_content .bt_bb_grid_item_post_title a{color: {$alternateColor} !important;}
.bt_bb_masonry_post_grid .bt_bb_grid_item_post_content .bt_bb_grid_item_post_title a:hover{color: {$accentColor};}
.bt_bb_listing_map{
    height: -webkit-calc(100vh - {$logoHeight}px);
    height: -moz-calc(100vh - {$logoHeight}px);
    height: calc(100vh - {$logoHeight}px);}
.btMenuBelowLogo .bt_bb_listing_map{height: -webkit-calc(100vh - {$logoHeight}px - 50px);
    height: -moz-calc(100vh - {$logoHeight}px - 50px);
    height: calc(100vh - {$logoHeight}px - 50px);}
.bt_bb_layout_wide .bt_bb_listing_search_parameters .bt_bb_listing_search_switch.on{background: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row [type=\"checkbox\"]:checked + label{color: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row [type=\"checkbox\"]:checked + label:before{border-color: {$accentColor};
    background: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container input[type=range]::-webkit-slider-thumb{
    background: {$accentColor};
    -webkit-box-shadow: 0 0 0 0 {$accentColorDark} inset,0 0 0 rgba(24,24,24,.3);
    box-shadow: 0 0 0 0 {$accentColorDark} inset,0 0 0 rgba(24,24,24,.3);}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container input[type=range]:focus::-webkit-slider-thumb{-webkit-box-shadow: 0 0 0 2px {$accentColorDark} inset,0 3px 5px rgba(24,24,24,.5);
    box-shadow: 0 0 0 2px {$accentColorDark} inset,0 3px 5px rgba(24,24,24,.5);}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container input[type=range]::-moz-range-thumb{
    background: {$accentColor};
    -webkit-box-shadow: 0 0 0 0 {$accentColorDark} inset,0 0 0 rgba(24,24,24,.3);
    box-shadow: 0 0 0 0 {$accentColorDark} inset,0 0 0 rgba(24,24,24,.3);}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container input[type=range]:focus::-moz-range-thumb{-webkit-box-shadow: 0 0 0 2px {$accentColorDark} inset,0 3px 5px rgba(24,24,24,.5);
    box-shadow: 0 0 0 2px {$accentColorDark} inset,0 3px 5px rgba(24,24,24,.5);}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container input[type=range]::-ms-thumb{
    background: {$accentColor};
    -webkit-box-shadow: 0 0 0 0 {$accentColorDark} inset,0 0 0 rgba(24,24,24,.3);
    box-shadow: 0 0 0 0 {$accentColorDark} inset,0 0 0 rgba(24,24,24,.3);}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container input[type=range]:focus::-ms-thumb{-webkit-box-shadow: 0 0 0 2px {$accentColorDark} inset,0 3px 5px rgba(24,24,24,.5);
    box-shadow: 0 0 0 2px {$accentColorDark} inset,0 3px 5px rgba(24,24,24,.5);}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container input[type=range]::-ms-fill-lower{background: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container input[type=range]:focus::-ms-fill-lower{background: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container .bt_bb_show_location_help:hover{
    color: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container .bt_bb_show_location_help.location_detected{color: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"map\"] .bt_bb_column_content .bt_bb_control_container .bt_bb_show_location_help.location_detected:hover{color: {$accentColorDark};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_search_fields[data-control-type=\"working_time\"] .bt_bb_column_content input[type=\"checkbox\"]:checked + label:before{background: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_options .bt_bb_listing_options_results span{color: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_options .bt_bb_listing_options_view_on_map a:before{
    color: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_options .bt_bb_listing_options_view_on_map a:hover{color: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_viewing_options .bt_bb_listing_options_additional_filters span{
    background: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_viewing_options .bt_bb_listing_options_additional_filters span:hover{background: {$accentColorDark};}
.bt_bb_listing_search_parameters .bt_bb_row .bt_bb_listing_viewing_options .bt_bb_listing_options_view_as ul li a.on{color: {$accentColor};}
.bt_bb_listing_box_empty{
    font-family: {$headingFont};
    color: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_listing_view_as_list .bt_bb_listing_box .bt_bb_listing_box_inner .bt_bb_listing_details .bt_bb_listing_bottom_meta .bt_bb_listing_price{
    color: {$accentColor};}
.bt_bb_listing_search_parameters .bt_bb_listing_view_as_list .bt_bb_listing_box .bt_bb_listing_box_inner .bt_bb_listing_details .bt_bb_listing_bottom_meta .bt_bb_listing_price:before{color: {$accentColor} !important;}
.bt_bb_listing_box.bt_bb_listing_featured:before{
    background: {$accentColor};}
.bt_bb_listing_box .bt_bb_listing_image .bt_bb_listing_top_meta .bt_bb_latest_posts_item_category{font-family: {$headingSuperTitleFont};}
.bt_bb_listing_box .bt_bb_listing_image .bt_bb_listing_top_meta .bt_bb_listing_favourite span.bt_bb_listing_favourite_on:before{-webkit-box-shadow: 0 0 0 1em {$accentColor} inset;
    box-shadow: 0 0 0 1em {$accentColor} inset;}
.bt_bb_listing_box .bt_bb_listing_image .bt_bb_listing_top_meta .bt_listing_close_details a.bt_bb_listing_favourite_on:before{-webkit-box-shadow: 0 0 0 1em {$accentColor} inset;
    box-shadow: 0 0 0 1em {$accentColor} inset;}
.bt_bb_listing_box .bt_bb_listing_image .bt_bb_listing_photo .bt_bb_listing_photo_overlay:before{
    background: {$accentColor};}
.bt_bb_listing_featured.bt_bb_listing_box .bt_bb_listing_image .bt_bb_listing_photo .bt_bb_listing_photo_overlay > span{
    -webkit-box-shadow: 0 0 0 3px {$accentColor} inset;
    box-shadow: 0 0 0 3px {$accentColor} inset;}
.bt_bb_listing_box .bt_bb_listing_details .bt_bb_listing_title small{font-family: {$bodyFont};}
.bt_bb_listing_box .bt_bb_listing_details .bt_bb_listing_subtitle{font-family: {$headingFont};}
.bt_bb_listing_view_as_grid.bt_bb_listing_view_as_list .bt_bb_listing_box .bt_bb_listing_details .bt_bb_listing_information .bt_bb_listing_phone > a:hover,
.bt_bb_listing_view_as_grid.bt_bb_listing_view_as_list .bt_bb_listing_box .bt_bb_listing_details .bt_bb_listing_information .bt_bb_listing_distance > a:hover{color: {$accentColor};}
.bt_bb_listing_box .bt_bb_listing_details .bt_bb_listing_information .bt_bb_listing_phone:before,
.bt_bb_listing_box .bt_bb_listing_details .bt_bb_listing_information .bt_bb_listing_distance:before{
    color: {$accentColor};}
.bt_bb_listing_box .bt_bb_listing_details .bt_bb_listing_bottom_meta .bt_bb_listing_price{background: {$accentColor};}
.bt_bb_listing_marker_data .bt_bb_listing_marker_options ul li a:before{
    color: {$accentColor};}
.bt_bb_listing_marker_data .bt_bb_listing_marker_options ul li a.bt_bb_listing_marker_add_favourite.added_favourite:before{background: {$accentColor};}
.bt_bb_listing_marker_data .bt_bb_listing_marker_meta_data ul li > span:before,
.bt_bb_listing_marker_data .bt_bb_listing_marker_meta_data ul li > a:before{color: {$accentColor};}
.bt_bb_listing_marker_data .bt_bb_listing_marker_meta_data ul li > a:hover{color: {$accentColor};}
.bt_bb_listing_marker_data .bt_bb_listing_marker_meta_data ul li.bt_bb_listing_marker_meta_working_hours.bt_bb_listing_marker_meta_now_working > span{color: {$accentColor};}
.bt_bb_listing_marker_data .bt_bb_listing_marker_reviews .comments li.comment article .commentTxt .vcard h5{font-family: {$bodyFont};
    color: {$accentColor};}
.btPostListingItem .btSingleListingItem{font-family: {$headingFont};}
.btPostListingItem .btSingleListingAmenities .btAmenities ul li:before{
    background: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_email > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_email > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_url > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_url > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_phone > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_phone > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_time > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_time > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_date > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_date > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_datetime > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_datetime > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_address > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_address > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_phone > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_phone > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_mobile > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_mobile > a:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_price > span:before,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_price > a:before{color: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_email > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_email > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_url > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_url > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_phone > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_phone > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_time > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_time > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_date > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_date > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_datetime > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_datetime > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_address > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_address > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_phone > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_phone > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_mobile > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_mobile > span a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_price > a:hover,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text.contact_price > span a:hover{color: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .bt_bb_listing_text ul li:before{
    color: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .btOpenTableReservationForm .btOpenTableReservationColumnSubmit .otreservations-submit{
    background: {$alternateColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .btOpenTableReservationForm .btOpenTableReservationColumnSubmit .otreservations-submit:after{
    border: 2px solid {$alternateColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .btOpenTableReservationForm .btOpenTableReservationColumnSubmit .otreservations-submit:hover{background: {$alternateColorLight};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .btOpenTableReservationForm .btOpenTableReservationColumnError{
    color: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-loading .bookingjs-loading-icon svg path{fill: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-calendar .fc-view-container .fc-body .fc-scroller .fc-content-skeleton td .fc-event:hover{border-left-color: {$accentColorDark} !important;
    background: {$accentColor} !important;}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-box .bookingjs-form-fields .bookingjs-form-field input,
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-box .bookingjs-form-fields .bookingjs-form-field textarea{font-family: {$bodyFont};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-box .bookingjs-form-success-message .booked-email{color: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-button{
    background: {$alternateColor};
    font-family: {$bodyFont};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-bookpage .bookingjs-form .bookingjs-form-button:hover{background: {$alternateColorLight};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-bookpage-close .bookingjs-closeicon path{fill: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-poweredby a svg path{fill: {$accentColor};
    stroke: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-poweredby a:hover{color: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-timezonehelper svg path{fill: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .timekit .bookingjs .bookingjs-displayname{
    font-family: {$headingFont};
    color: {$accentColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .widget_form_wrapper .btMessageSubmit{
    background: {$alternateColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .widget_form_wrapper .btMessageSubmit:after{
    border: 2px solid {$alternateColor};}
.btPostListingItem .btListingContentWidgets .btListingContentWidget .widget_form_wrapper .btMessageSubmit:hover{background: {$alternateColorLight};}
.btComments.btReviews .vcard h1.author,
.btComments.btReviews .vcard h2.author,
.btComments.btReviews .vcard h3.author,
.btComments.btReviews .vcard h4.author,
.btComments.btReviews .vcard h5.author,
.btComments.btReviews .vcard h6.author,
.btComments.btReviews .vcard h7.author,
.btComments.btReviews .vcard h8.author{color: {$accentColor};}
.btComments.btReviews ul.comments li > article .commentTxt .comment-read-further span{color: {$accentColor};}
.btComments.btReviews ul.comments li > article .commentTxt p.posted:not(:empty) span:before{
    color: {$accentColor};}
.btComments.btReviews .comment-form .author{font-family: {$bodyFont};
    color: {$accentColor};}
.btComments.btReviews .comment-form .review-by p.overall-rating strong{color: {$accentColor};}
.btLightSkin .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_2 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_4 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_5 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_8 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_9 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before{color: {$accentColor};}
.btDarkSkin .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_1 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_3 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_6 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_7 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before,
.bt_bb_color_scheme_10 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating input[type=\"radio\"]:checked + label:before{color: {$accentColor};}
.btLightSkin .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_2 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_4 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_5 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_8 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_9 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before{color: {$accentColor};}
.btDarkSkin .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_1 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_3 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_6 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_7 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before,
.bt_bb_color_scheme_10 .btComments.btReviews .comment-form .review-by .commentratingbox .commentrating:hover input[type=\"radio\"] + label:before{color: {$accentColor};}
.btNearbyLocations .btNearbyLocationsContent .btNearbyLocationsTitle .bt_bb_listing_options_view_on_map a:hover{color: {$accentColor};}
.bt-bb-ckecked li:before{
    color: {$accentColor};}
.bt_bb_masonry_image_grid .bt_bb_grid_item_inner > .bt_bb_grid_item_inner_image:before{
    background: {$accentColor};}
.bt_favorites_list ul li a{
    font-family: {$headingFont};}
.bt_favorites_list ul li a:hover{color: {$accentColor};}
.bt_favorites_list .bt_favorites_empty{font-family: {$headingFont};
    color: {$accentColor};}
.bt_favorites_list .bt_favorites_clear{
    background: {$accentColor};}
.bt_favorites_list .bt_favorites_clear:after{
    border: 2px solid {$accentColor};}
.bt_favorites_list .bt_favorites_clear:hover{
    background: {$accentColorDark};}
.bt_bb_listing_search_form .bt_bb_column.bt_bb_listing_search_col .bt_bb_listing_search_element .bt_bb_show_location_help:hover{
    color: {$accentColor};}
.bt_bb_listing_search_form .bt_bb_column.bt_bb_listing_search_col .bt_bb_listing_search_element .bt_bb_show_location_help.location_detected{color: {$accentColor};}
.bt_bb_listing_search_form .bt_bb_column.bt_bb_listing_search_col .bt_bb_listing_search_element .bt_bb_show_location_help.location_detected:hover{color: {$accentColorDark};}
.btSiteFooter .bt_bb_listing_search_form .bt_bb_listing_search_button .bt_bb_button a{
    color: {$alternateColor};}
.bt_bb_loadmore{
    background: {$accentColor};}
.bt_bb_loadmore:after{
    border: 2px solid {$accentColor};}
.bt_bb_loadmore:hover{background: {$accentColorDark};}
.bt_bb_loadmore_box a.next,
.bt_bb_loadmore_box a.prev{background: {$accentColor};}
.bt_bb_loadmore_box a.next:after,
.bt_bb_loadmore_box a.prev:after{
    border-color: {$accentColor};}
.bt_bb_loadmore_box a.next:hover,
.bt_bb_loadmore_box a.prev:hover{background: {$accentColorDark};}
#bt_listing_loading:after{
    -webkit-box-shadow: 0 -34px 0 -28px {$accentColor},-10px -33px 0 -28px {$accentColor},-19px -29px 0 -28px {$accentColor},-26px -23px 0 -28px {$accentColor},-32px -15px 0 -28px {$accentColor},-34px -5px 0 -28px {$accentColor};
    box-shadow: 0 -34px 0 -28px {$accentColor},-10px -33px 0 -28px {$accentColor},-19px -29px 0 -28px {$accentColor},-26px -23px 0 -28px {$accentColor},-32px -15px 0 -28px {$accentColor},-34px -5px 0 -28px {$accentColor};}
.pac-item{
    -webkit-box-shadow: inset 0 0 0 0 #fff,inset 0 0 0 0 {$accentColor};
    box-shadow: inset 0 0 0 0 #fff,inset 0 0 0 0 {$accentColor};}
.pac-item:hover{
    -webkit-box-shadow: inset 3px 0 0 0 #fff,inset 5px 0 0 0 {$accentColor};
    box-shadow: inset 3px 0 0 0 #fff,inset 5px 0 0 0 {$accentColor};}
.rtl .pac-item:hover{-webkit-box-shadow: inset -3px 0 0 0 #fff,inset -5px 0 0 0 {$accentColor};
    box-shadow: inset -3px 0 0 0 #fff,inset -5px 0 0 0 {$accentColor};}
.pac-matched{
    color: {$accentColor};}
.pac-icon:before{
    color: {$accentColor};}
.bt_bb_featured_listings .bt_bb_featured_listing_list .bt_bb_listing_box .bt_bb_listing_box_inner .bt_bb_listing_details .bt_bb_listing_bottom_meta .bt_bb_listing_price{
    color: {$accentColor};}
.bt_bb_featured_listings .bt_bb_featured_listing_list .bt_bb_listing_box .bt_bb_listing_box_inner .bt_bb_listing_details .bt_bb_listing_bottom_meta .bt_bb_listing_price:before{color: {$accentColor} !important;}
.bt_bb_featured_listings .bt_bb_featured_listing_image_content .bt_bb_listing_box .bt_bb_listing_box_inner .bt_bb_listing_details .bt_bb_listing_bottom_meta .bt_bb_listing_price{
    color: {$accentColor};}
.bt_bb_featured_listings .bt_bb_featured_listing_image_content .bt_bb_listing_box .bt_bb_listing_box_inner .bt_bb_listing_details .bt_bb_listing_bottom_meta .bt_bb_listing_price:before{color: {$accentColor} !important;}
body .woo-login-popup-sc-modal .woo-login-popup-sc-close a:before,
body .woo-login-popup-sc-modal .woo-login-popup-sc-close a:after{
    background: {$accentColor};}
body .woo-login-popup-sc-modal .woo-login-popup-sc-close a:hover:before,
body .woo-login-popup-sc-modal .woo-login-popup-sc-close a:hover:after{background: {$accentColorDark};}
body .woo-login-popup-sc-toggle{color: {$accentColor};}
body .woocommerce input[type=\"checkbox\"]:checked{background: {$accentColor};}
.products ul li.product .btWooShopLoopItemInner .bt_bb_image:before,
ul.products li.product .btWooShopLoopItemInner .bt_bb_image:before{
    background: {$accentColor};}
.products ul li.product .btWooShopLoopItemInner .price,
ul.products li.product .btWooShopLoopItemInner .price{
    background: {$accentColor};}
.products ul li.product .btWooShopLoopItemInner .added:after,
.products ul li.product .btWooShopLoopItemInner .loading:after,
ul.products li.product .btWooShopLoopItemInner .added:after,
ul.products li.product .btWooShopLoopItemInner .loading:after{
    background-color: {$alternateColor};}
.products ul li.product .btWooShopLoopItemInner .added_to_cart,
ul.products li.product .btWooShopLoopItemInner .added_to_cart{
    color: {$accentColor};}
.products ul li.product .onsale,
ul.products li.product .onsale{
    background: {$alternateColor};}
.products ul li.product .onsale:after,
ul.products li.product .onsale:after{
    border: 3px solid {$alternateColor};}
nav.woocommerce-pagination ul li a.next,
nav.woocommerce-pagination ul li a.prev{background: {$accentColor};}
nav.woocommerce-pagination ul li a.next:after,
nav.woocommerce-pagination ul li a.prev:after{
    border-color: {$accentColor};}
nav.woocommerce-pagination ul li a.next:hover,
nav.woocommerce-pagination ul li a.prev:hover{background: {$accentColorDark};}
div.product .onsale{
    background: {$alternateColor};}
div.product div.images .woocommerce-product-gallery__trigger:after{
    -webkit-box-shadow: 0 0 0 2em {$accentColor} inset,0 0 0 2em rgba(255,255,255,0) inset;
    box-shadow: 0 0 0 2em {$accentColor} inset,0 0 0 2em rgba(255,255,255,0) inset;}
div.product div.images .woocommerce-product-gallery__trigger:hover:after{-webkit-box-shadow: 0 0 0 1px {$accentColor} inset,0 0 0 2em rgba(255,255,255,1) inset;
    box-shadow: 0 0 0 1px {$accentColor} inset,0 0 0 2em rgba(255,255,255,1) inset;
    color: {$accentColor};}
table.shop_table .coupon .input-text{
    color: {$accentColor};}
table.shop_table td.product-remove a.remove{
    color: {$accentColor};
    border: 1px solid {$accentColor};}
table.shop_table td.product-remove a.remove:hover{color: {$alternateColor};
    border-color: {$alternateColor};}
ul.wc_payment_methods li .about_paypal{
    color: {$accentColor};}
.woocommerce-MyAccount-navigation ul{font-family: {$menuFont};}
.woocommerce-MyAccount-navigation ul li a:after{
    background: {$accentColor};}
.woocommerce-MyAccount-navigation ul li a:hover{color: {$accentColor};}
form fieldset legend{
    font-family: {$headingFont};}
.select2-container .select2-results .select2-results__option:hover,
.select2-container .select2-results .select2-results__option--highlighted{background: {$accentColor};}
.woocommerce-info a: not(.button),
.woocommerce-message a: not(.button){color: {$accentColor};}
.woocommerce-message:before,
.woocommerce-info:before{
    color: {$accentColor};}
.woocommerce-MyAccount-content .edit:before{
    color: {$accentColor};}
.btLightSkin .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_2 .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_4 .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_5 .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_8 .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_9 .woocommerce-MyAccount-content .edit:hover{color: {$accentColor};}
.btDarkSkin .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_1 .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_3 .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_6 .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_7 .woocommerce-MyAccount-content .edit:hover,
.bt_bb_color_scheme_10 .woocommerce-MyAccount-content .edit:hover{color: {$accentColor};}
.woocommerce .btSidebar a.button,
.woocommerce .btContent a.button,
.woocommerce-page .btSidebar a.button,
.woocommerce-page .btContent a.button,
.woocommerce .btSidebar input[type=\"submit\"],
.woocommerce .btContent input[type=\"submit\"],
.woocommerce-page .btSidebar input[type=\"submit\"],
.woocommerce-page .btContent input[type=\"submit\"],
.woocommerce .btSidebar button[type=\"submit\"],
.woocommerce .btContent button[type=\"submit\"],
.woocommerce-page .btSidebar button[type=\"submit\"],
.woocommerce-page .btContent button[type=\"submit\"],
.woocommerce .btSidebar input.button,
.woocommerce .btContent input.button,
.woocommerce-page .btSidebar input.button,
.woocommerce-page .btContent input.button,
div.woocommerce a.button,
div.woocommerce input[type=\"submit\"],
div.woocommerce button[type=\"submit\"],
div.woocommerce input.button{
    background: {$accentColor};}
.woocommerce .btSidebar a.button:after,
.woocommerce .btContent a.button:after,
.woocommerce-page .btSidebar a.button:after,
.woocommerce-page .btContent a.button:after,
.woocommerce .btSidebar input[type=\"submit\"]:after,
.woocommerce .btContent input[type=\"submit\"]:after,
.woocommerce-page .btSidebar input[type=\"submit\"]:after,
.woocommerce-page .btContent input[type=\"submit\"]:after,
.woocommerce .btSidebar button[type=\"submit\"]:after,
.woocommerce .btContent button[type=\"submit\"]:after,
.woocommerce-page .btSidebar button[type=\"submit\"]:after,
.woocommerce-page .btContent button[type=\"submit\"]:after,
.woocommerce .btSidebar input.button:after,
.woocommerce .btContent input.button:after,
.woocommerce-page .btSidebar input.button:after,
.woocommerce-page .btContent input.button:after,
div.woocommerce a.button:after,
div.woocommerce input[type=\"submit\"]:after,
div.woocommerce button[type=\"submit\"]:after,
div.woocommerce input.button:after{border-color: {$accentColor};}
.woocommerce .btSidebar a.button:hover,
.woocommerce .btContent a.button:hover,
.woocommerce-page .btSidebar a.button:hover,
.woocommerce-page .btContent a.button:hover,
.woocommerce .btSidebar input[type=\"submit\"]:hover,
.woocommerce .btContent input[type=\"submit\"]:hover,
.woocommerce-page .btSidebar input[type=\"submit\"]:hover,
.woocommerce-page .btContent input[type=\"submit\"]:hover,
.woocommerce .btSidebar button[type=\"submit\"]:hover,
.woocommerce .btContent button[type=\"submit\"]:hover,
.woocommerce-page .btSidebar button[type=\"submit\"]:hover,
.woocommerce-page .btContent button[type=\"submit\"]:hover,
.woocommerce .btSidebar input.button:hover,
.woocommerce .btContent input.button:hover,
.woocommerce-page .btSidebar input.button:hover,
.woocommerce-page .btContent input.button:hover,
div.woocommerce a.button:hover,
div.woocommerce input[type=\"submit\"]:hover,
div.woocommerce button[type=\"submit\"]:hover,
div.woocommerce input.button:hover{background: {$accentColorDark};}
.woocommerce .btSidebar input.alt,
.woocommerce .btContent input.alt,
.woocommerce-page .btSidebar input.alt,
.woocommerce-page .btContent input.alt,
.woocommerce .btSidebar a.button.alt,
.woocommerce .btContent a.button.alt,
.woocommerce-page .btSidebar a.button.alt,
.woocommerce-page .btContent a.button.alt,
.woocommerce .btSidebar .button.alt,
.woocommerce .btContent .button.alt,
.woocommerce-page .btSidebar .button.alt,
.woocommerce-page .btContent .button.alt,
.woocommerce .btSidebar button.alt,
.woocommerce .btContent button.alt,
.woocommerce-page .btSidebar button.alt,
.woocommerce-page .btContent button.alt,
div.woocommerce input.alt,
div.woocommerce a.button.alt,
div.woocommerce .button.alt,
div.woocommerce button.alt{
    background: {$alternateColor};}
.woocommerce .btSidebar input.alt:after,
.woocommerce .btContent input.alt:after,
.woocommerce-page .btSidebar input.alt:after,
.woocommerce-page .btContent input.alt:after,
.woocommerce .btSidebar a.button.alt:after,
.woocommerce .btContent a.button.alt:after,
.woocommerce-page .btSidebar a.button.alt:after,
.woocommerce-page .btContent a.button.alt:after,
.woocommerce .btSidebar .button.alt:after,
.woocommerce .btContent .button.alt:after,
.woocommerce-page .btSidebar .button.alt:after,
.woocommerce-page .btContent .button.alt:after,
.woocommerce .btSidebar button.alt:after,
.woocommerce .btContent button.alt:after,
.woocommerce-page .btSidebar button.alt:after,
.woocommerce-page .btContent button.alt:after,
div.woocommerce input.alt:after,
div.woocommerce a.button.alt:after,
div.woocommerce .button.alt:after,
div.woocommerce button.alt:after{border-color: {$alternateColor};}
.woocommerce .btSidebar input.alt:hover,
.woocommerce .btContent input.alt:hover,
.woocommerce-page .btSidebar input.alt:hover,
.woocommerce-page .btContent input.alt:hover,
.woocommerce .btSidebar a.button.alt:hover,
.woocommerce .btContent a.button.alt:hover,
.woocommerce-page .btSidebar a.button.alt:hover,
.woocommerce-page .btContent a.button.alt:hover,
.woocommerce .btSidebar .button.alt:hover,
.woocommerce .btContent .button.alt:hover,
.woocommerce-page .btSidebar .button.alt:hover,
.woocommerce-page .btContent .button.alt:hover,
.woocommerce .btSidebar button.alt:hover,
.woocommerce .btContent button.alt:hover,
.woocommerce-page .btSidebar button.alt:hover,
.woocommerce-page .btContent button.alt:hover,
div.woocommerce input.alt:hover,
div.woocommerce a.button.alt:hover,
div.woocommerce .button.alt:hover,
div.woocommerce button.alt:hover{background: {$alternateColorLight};}
.star-rating span:before{
    color: {$accentColor};}
p.stars a[class^=\"star-\"].active:after,
p.stars a[class^=\"star-\"]:hover:after{color: {$accentColor};}
.product-category a:hover{color: {$accentColor};}
.out-of-stock{
    font-family: {$headingFont};
    color: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-heading-wrapper h4{
    color: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field .description{font-family: {$headingFont};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field .description:before{
    color: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-map-wrapper .rwmb-map-goto-address-button{
    background: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-map-wrapper .rwmb-map-goto-address-button:after{
    border-color: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-map-wrapper .rwmb-map-goto-address-button:hover{background: {$accentColorDark};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-taxonomy-wrapper .rwmb-label ~ .rwmb-input .select2-container .select2-selection .select2-selection__rendered .select2-selection__clear{
    color: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-checkbox-wrapper .rwmb-label > label:hover{color: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-checkbox-wrapper .rwmb-input input[type=checkbox]:checked{background: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-form-submit button.rwmb-button{
    background: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-form-submit button.rwmb-button:after{
    border-color: {$accentColor};}
.woocommerce-MyAccount-content .rwmb-form .rwmb-field.rwmb-form-submit button.rwmb-button:hover{background: {$accentColorDark};}
.woocommerce-MyAccount-content .bt_my_listings_list li > div.bt_single_listing_thumb a{background: {$accentColor};}
.woocommerce-MyAccount-content .bt_my_listings_list li span.bt_single_listing_title{
    font-family: {$headingFont};}
.woocommerce-MyAccount-content .bt_my_listings_list li span.bt_single_listing_title .bt_single_package{
    font-family: {$bodyFont};}
.woocommerce-account.woocommerce-page .ui-autocomplete .ui-menu-item{
    -webkit-box-shadow: inset 0 0 0 0 #fff,inset 0 0 0 0 {$accentColor};
    box-shadow: inset 0 0 0 0 #fff,inset 0 0 0 0 {$accentColor};}
.woocommerce-account.woocommerce-page .ui-autocomplete .ui-menu-item:hover{
    -webkit-box-shadow: inset 3px 0 0 0 #fff,inset 5px 0 0 0 {$accentColor};
    box-shadow: inset 3px 0 0 0 #fff,inset 5px 0 0 0 {$accentColor};}
.rtl .woocommerce-account.woocommerce-page .ui-autocomplete .ui-menu-item:hover{-webkit-box-shadow: inset -3px 0 0 0 #fff,inset -5px 0 0 0 {$accentColor};
    box-shadow: inset -3px 0 0 0 #fff,inset -5px 0 0 0 {$accentColor};}
.btQuoteBooking .btContactNext{
    background: {$accentColor};}
.btQuoteBooking .btContactNext:before{
    border: 2px solid {$accentColor};}
.btQuoteBooking .btContactNext:focus,
.btQuoteBooking .btContactNext:hover{background: {$accentColorDark};}
.btQuoteBooking .btQuoteSwitch.on .btQuoteSwitchInner{
    background: {$accentColor};}
.btQuoteBooking .dd.ddcommon.borderRadiusTp .ddTitleText,
.btQuoteBooking .dd.ddcommon.borderRadiusBtm .ddTitleText{
    -webkit-box-shadow: 5px 0 0 {$accentColor} inset,0 2px 10px rgba(0,0,0,.2);
    box-shadow: 5px 0 0 {$accentColor} inset,0 2px 10px rgba(0,0,0,.2);}
.btQuoteBooking .ui-slider .ui-slider-handle{
    background: {$accentColor};}
.btQuoteBooking .btQuoteBookingForm .btQuoteTotal{
    background: {$accentColor};}
.btQuoteBooking .btQuoteTotalText{
    font-family: {$headingFont};}
.btQuoteBooking .btContactFieldMandatory.btContactFieldError input,
.btQuoteBooking .btContactFieldMandatory.btContactFieldError textarea{-webkit-box-shadow: 0 0 0 1px {$accentColor} inset;
    box-shadow: 0 0 0 1px {$accentColor} inset;
    border-color: {$accentColor};}
.btQuoteBooking .btContactFieldMandatory.btContactFieldError .dd.ddcommon.borderRadius .ddTitleText{-webkit-box-shadow: 0 0 0 2px {$accentColor} inset;
    box-shadow: 0 0 0 2px {$accentColor} inset;}
.btQuoteBooking .btSubmitMessage{color: {$accentColor};}
.btDatePicker .ui-datepicker-header{
    background-color: {$accentColor};}
.btQuoteBooking .ddChild ul li:hover,
.btQuoteBooking .ddChild ul li.selected:hover{color: {$accentColor};}
.btQuoteBooking .btContactSubmit{
    background: {$accentColor};}
.btQuoteBooking .btContactSubmit:before{
    border: 2px solid {$accentColor};}
.btQuoteBooking .btContactSubmit:focus,
.btQuoteBooking .btContactSubmit:hover{background: {$accentColorDark};}
.btPayPalButton:hover{-webkit-box-shadow: 0 0 0 {$accentColor} inset,0 1px 5px rgba(0,0,0,.2);
    box-shadow: 0 0 0 {$accentColor} inset,0 1px 5px rgba(0,0,0,.2);}
.bt_cc_email_confirmation_container [type=\"checkbox\"]:checked + label:before{border-color: {$accentColor};
    background: {$accentColor};}
.wp-block-button__link:hover{color: {$accentColor} !important;}
", array() );