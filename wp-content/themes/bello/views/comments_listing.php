<a id="btCommentsAll"></a>
<?php
	echo '<section class="btComments gutter">';
		echo '<div class="port">';
			echo '<div class="btCommentsContent">';

			comments_template( '/comments-listing.php', false );

			echo '</div><!-- /bt_bb_column_content -->' ;
	
		echo '</div><!-- port -->';		
	echo '</section><!-- btComments -->';

?>