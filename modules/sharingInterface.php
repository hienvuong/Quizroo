<script src="http://connect.facebook.net/en_US/all.js"></script>
<div id="fb-root"></div>
<script>
  FB.init({ appId:<?php echo $FB_APPID ?>, cookie:true, xfbml:true });
</script>
<div id='user-actions-container' class='frame rounded clear'>
	<div class='recommend-dialog'>
		<fb:serverFbml width="625px">
		  <script type="text/fbml">
		  <fb:fbml>
		   <fb:request-form
		    method='POST' 
		    action='http://apps.facebook.com/quizroo/'
			invite=false
			type='Quiz'
			content='Try this quiz out.' >
			<fb:multi-friend-selector cols=5 rows=3 
			actiontext="Recommend this quiz to your friends"
			bypass="cancel"
			max=20 />
			
			</fb:request-form>
		   </fb:fbml>
		   </script>
		</fb:serverFbml>
	</div>
</div>
<script type="text/javascript" src="../webroot/js/Share.js"></script>
<script type="text/javascript">
	Share.recommend($('#user-actions-container'),{'quiz_id': <?php echo $quiz_id ?>});
	Share.results($('#user-actions-container'),{'quiz_id': <?php echo $quiz_id ?>,'result_id':<?php echo $row_getResults['fk_result'] ?>});
</script>