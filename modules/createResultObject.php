<?php // get result number
$result = $_GET['resultNumber']+1;
$checkTextField = $_GET['checkTextField'];
$checkTextArea = $_GET['checkTextArea'];
$widgetCount = $_GET['widgetCount'];
$count = 1;
?>
<div id="r<?php echo $result; ?>">
<table width="95%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th colspan="2" valign="top" scope="row"><a href="javascript:;" onclick="removeField('r<?php echo $result; ?>', 'r', <?php echo $result; ?>);"><img src="images/delete.png" alt="" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a> Result <?php echo $result; ?></th>
  </tr>
  <tr>
    <th width="120" valign="top" scope="row"><label for="result_title_<?php echo $result; ?>">Title</label></th>
    <td><span id="sprytextfield<?php echo $checkTextField; ?>"><input type="text" name="result_title_<?php echo $result; ?>" id="result_title_<?php echo $result; ?>" onBlur="updateResult(<?php echo $result; ?>)" /><span class="textfieldRequiredMsg">A value is required.</span></span>
    <span class="desc">Provide a title for this result!</span></td>
  </tr>
  <tr>
    <th width="120" valign="top" scope="row"><label for="result_description_<?php echo $result; ?>">Description</label></th>
    <td><span id="sprytextarea<?php echo $checkTextArea; ?>"><textarea name="result_description_<?php echo $result; ?>" id="result_description_<?php echo $result; ?>" cols="45" rows="5"></textarea><span class="textareaRequiredMsg">Description should not be blank!</span></span>
    <span class="desc">Tell the quiz taker what this result means</span></td>
  </tr>
  <tr>
    <th width="120" rowspan="4" valign="top" scope="row"><label>Picture</label>
      <input name="result_picture_<?php echo $result; ?>" type="hidden" id="result_picture_<?php echo $result; ?>" value="" /></th>
    <td><div id="swfupload-control-<?php echo $widgetCount; ?>" class="swfupload-control">
      <script>initUploader("result_picture_<?php echo $result; ?>")</script>
      <table border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td><input name="uploader-<?php echo $widgetCount; ?>" type="button" id="uploader-<?php echo $widgetCount; ?>" /></td>
          <td valign="middle" class="formDesc">Upload a new picture (jpg, gif only)</td>
          </tr>
    </table>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><div id="selected-image-<?php echo $widgetCount; ?>" class="selected-image"></div></td>
    <td><p id="queuestatus-<?php echo $widgetCount; ?>"></p></td>
  </tr>
</table>
      <ol id="log-<?php echo $widgetCount; ?>" class="log">
      </ol>
    </div></td>
  </tr>
  <tr>
    <td><div id="pictureChoser_<?php echo $result; ?>"><table border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td><span class="formDesc">OR click on a picture below to use it as the result picture</span></td>
  </tr>
  <tr>
    <td><?php // return uploaded images
foreach(glob("../quiz_images/".$_GET['unikey']."*") as $filename){ ?>
<a href="javascript:;" onClick="selectImage(<?php echo $result; ?>, '<?php echo basename($filename); ?>', 'r<?php echo $result; ?>i<?php echo $count; ?>')"><img src="../quiz_images/imgcrop.php?w=80&h=60&f=<?php echo basename($filename); ?>" width="80" height="60" id="r<?php echo $result; ?>i<?php echo $count; ?>" class="selectImage"></a>
<?php $count++; } ?></td>
  </tr>
</table></div></td>
  </tr>
</table>
</div>