<?php
/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Required variables
//$stream = stdClass();

/*
  {
  "actor":5,
  "target": 6,
  "message":"string",
  "group" : "JTableGroup object",
  "event" : "JTableEvent object",
  "headline" : "string",
  "location" : "Kuala Lumpur",
  "attachments":
  [
  {"type":"media"},
  {"type":"video", "id":0, "title":"", "description":"", "duration": "string"},
  {"type":"quote"}
  ]
  }
 */
?>
<?php if(is_object($stream->actor)):?>
    <a class="cStream-Avatar cFloat-L" href="<?php echo ((int)$stream->actor->id !== 0) ? CUrlHelper::userLink($stream->actor->id) : 'javascript:void(0);'; ?>">
        <img class="cAvatar" data-author="<?php echo $stream->actor->id; ?>" src="<?php echo $stream->actor->getThumbAvatar(); ?>">
    </a>
<?php else: ?>
    <span class="cStream-Avatar cFloat-L">
        <img class="cAvatar" src="components/com_community/assets/user-Male-thumb.png" />
    </span>
<?php endif;?>
<div class="cStream-Content">
    <div class="cStream-Headline">
        <?php echo $stream->headline; ?>
        <?php if (!empty($stream->groupid)) { ?>
            <span class="cStream-Reference">
                ➜ <a class="cStream-Reference" href="<?php echo $stream->group->getLink(); ?>"><?php echo $stream->group->name; ?></a>
            </span>
        <?php } ?>
    </div>

    <?php
    // Contain message ?
    if ($stream->message) {
        ?>
        <div class="cStream-Attachment">
            <?php echo $stream->message; ?>
        </div>
    <?php } ?>

    <?php
    if (!empty($stream->attachments)) {
        foreach ($stream->attachments as $attachment) {

            switch ($attachment->type) {
                case 'media':
                    ?>
                    <div class="cStream-Attachment">
                        <div class="cStream-Photo">
                            <div class="cStream-PhotoRow row-fluid">
                                <div class="span3">
                                    <a class="cPhoto-Thumb" href="#"><img src="<?php echo (isset($attachment->thumbnail)) ? $attachment->thumbnail : ''; ?>" alt="photo"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    break;

                case 'album':
                    ?>
                    <div class="cStream-Attachment">
                        <div class="cStream-Photo">
                            <div class="cStream-PhotoRow row-fluid">
                                <div class="span3">
                                    <a class="cPhoto-Thumb" href="#"><img src="<?php echo (isset($attachment->thumbnail)) ? $attachment->thumbnail : ''; ?>" alt="photo"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    break;

                case 'video':
                    // the id is optional. If avaiable, it linked to internal video id
                    $link = (!empty($attachment->id)) ? "javascript:joms.walls.showVideoWindow('{$attachment->id}')" : "#";
                    ?>
                    <div class="cStream-Attachment">
                        <div class="cStream-Video row-fluid">

                        <div class="pull-left video-avatar">
                            <!-- video thumb -->
                            <a href="<?php echo $link; ?>" class="cVideo-Thumb">
                                <img src="<?php echo $attachment->thumbnail; ?>" alt="<?php echo $this->escape($attachment->title); ?>">
                                    <b><?php echo $attachment->duration; ?></b>
                            </a>
                        </div>

                        <div class="video-description">
                            <!-- video description -->
                            <b class="cVideo-Title">
                                <a href="<?php echo $link; ?>"><?php echo $attachment->title; ?></a>
                            </b>
                            <p>
                                <?php echo JHTML::_('string.truncate', $attachment->description, $config->getInt('streamcontentlength'), true, false); ?>
                            </p>
                        </div>

                        </div>
                    </div>
                    <?php
                    break;

                case 'quote':
                    ?>
                    <div class="cStream-Attachment">
                        <div class="cStream-Quote">
                            <?php echo $attachment->message; ?>
                        </div>
                    </div>
                    <?php
                    break;
                default:
                    # code...
                    break;
            }
        } // end foreach
    } // end if

    $this->load('activities.actions');
    ?>

</div>