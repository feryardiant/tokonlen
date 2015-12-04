<?php defined('ROOT') or die ('Not allowed!'); ?>
<p><?php echo is_array($message) ? implode('</p><p>', $message) : $message ?></p>
