<?php if ($this->Layout->getRoleId() !== '1'): return; endif; ?>
<a href="#"><?php echo __d('gallery','Gallery'); ?></a>
<ul>
   <li><?php echo $this->Html->link(__d('gallery','List albums'), array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'index')); ?></li>
   <li><?php echo $this->Html->link(__d('gallery','New album'), array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'add')); ?></li>
   <li><?php echo $this->Html->link(__d('gallery','Gallery settings'), array('plugin' => '', 'controller' => 'settings', 'action' => 'prefix', 'Gallery')); ?></li>
</ul>
