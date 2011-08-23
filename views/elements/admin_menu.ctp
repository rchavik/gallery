<?php if ($this->Layout->getRoleId() !== '1'): return; endif; ?>
<a href="#"><?php __d('gallery','Gallery'); ?></a>
<ul>
   <li><?php echo $html->link(__d('gallery','List albums', true), array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'index')); ?></li>
   <li><?php echo $html->link(__d('gallery','New album', true), array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'add')); ?></li>
   <li><?php echo $html->link(__d('gallery','Gallery settings', true), array('plugin' => '', 'controller' => 'settings', 'action' => 'prefix', 'Gallery')); ?></li>
</ul>
