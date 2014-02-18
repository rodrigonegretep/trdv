<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

/* Imagenes por contenido de categoria regalo */
/*
$catid_gift = 9;
$db = JFactory::getDbo();
$query = $db->getQuery(true);

$query->select('*');
$query->from('#__content');

$query->where('catid ='.$catid_gift);
$db->setQuery($query);
$results = $db->loadObjectList();
//dump($results,"gifts");
$regalos = array();
$txt_opciones = "";
foreach ($results as $key=>$result) {
  //$opciones[$result->id] = $result->title;
  //$txt_opciones .= '<option value="'.$result->id.'">'.$result->title.'</option>';
  $tmp_arr = array();
  $tmp_arr['id'] = $result->id;
  $tmp_arr['titulo'] = $result->title;
  $txt_image = $result->images;
  //dump($txt_image,"txt image");
  $tmp_images = json_decode($result->images);
  //dump($tmp_images,"images");
  $tmp_arr['imagen'] = $tmp_images->image_fulltext;
$regalos[] = $tmp_arr;
}
*/

/* imagenes por album gift */
/*TO GET THUMBNAILS FOR JOMSOCIAL PHOTOS*/
//first get photo ids from local database
$album_id = 1;
$regalos = array();
$db = JFactory::getDbo();
$query="SELECT  a.id, a.thumbnail FROM  jos_community_photos AS a where albumid = ".$album_id;
$db->setQuery($query);
$row  = $db->loadObjectList();
//JS API - this is used or getting thumbnails for JS photos
CFactory::load( 'models' , 'photos' );
$photo =& JTable::getInstance( 'Photo' , 'CTable' );
if(!empty($row))
{
   	foreach($row as $data)
   	{
   		$photo->load( $data->id );
   		$thumbnail    = $photo->getOriginalURI();
   		$data->thumbnail=$thumbnail;
        $regalos[] = array("id"=>$data->id, "imagen"=>$thumbnail, "titulo"=>"titulo imagen");
   	}
}
//now $row will be having thumnails from local OR amazon s3 whatever applicable


?>
<script type="text/javascript">
//<![CDATA[

(function($) {

var Creator;

joms.status.Creator['gift'] =
{
  attachment: {},

  initialize: function()
  {
    Creator = this;

    Creator.Preview = Creator.View.find('.creator-preview');

    Creator.Form = Creator.View.find('.creator-form');

    Creator.Hint = Creator.View.find('.creator-hint');

  },

  focus: function()
  {
    this.Message.defaultValue("<?php echo JText::_('PLG_ETRCOM_GIFTCREATOR_HINT'); ?>", 'hint');
  },

  submit: function()
  {
    var idSelect = 0;
    if(joms.jQuery(".gifts-container input:radio[name=giftSelected]:checked").length > 0){
      idSelect  = joms.jQuery(".gifts-container input:radio[name=giftSelected]:checked").val();
    }
    if((Creator.attachment.id==undefined) || (Creator.attachment.id!=idSelect) )
    {
      Creator.attachment.id = idSelect;
      if(this.Message.hasClass('hint'))
      {
        Creator.Hint
          .html("<?php echo JText::_('PLG_ETRCOM_GIFTCREATOR_ERROR'); ?>")
          .show();
      }
    }

    return Creator.attachment.id!=undefined;
  },

  getAttachment: function()
  {
    var attachment = {
      type: 'photo',
      id:  Creator.attachment.id
    }
    return attachment;
  }
};

})(joms.jQuery);

//]]>
</script>

<div class="creator-view type-gift">
	<div class="creator-hint"></div>

	<div class="creator-preview"></div>

	<div class="creator-form">
		<div class="gifts-container" style="width: 100%;">
          <?php foreach($regalos as $key=>$regalo){ ?>
            <span class="gift-item" style="float: left;width: 100px;">
              <label for="giftSelected_<?php echo $regalo['id']; ?>">
                <img src="<?php echo $regalo['imagen']; ?>" title="<?php echo $regalo['titulo']; ?>" width="100" height="100" class="img-thumbnail" onclick="">
              </label>
              <input type="radio" name="giftSelected" class="giftSelected" id="giftSelected_<?php echo $regalo['id']; ?>" value="<?php echo $regalo['id']; ?>">
            </span>
          <?php } ?>
		</div>
	</div>
</div>