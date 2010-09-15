<?php
/**
 * Album
 *
 *
 * @category Model
 * @package  Croogo
 * @version  1.3
 * @author   Edinei L. Cipriani <phpedinei@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.edineicipriani.com.br
 */
class Album extends AppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    var $name = 'Album';
	
	
    var $validate = array(
        'slug' => array(
            'rule' => 'isUnique',
            'message' => 'Slug is alredy in use.',
        ),
    );

/**
 * Model associations: hasMany
 *
 * @var array
 * @access public
 */
    var $hasMany = array(            
            'Photo' => array('className' => 'Gallery.photo',
                                'foreignKey' => 'album_id',
                                'dependent' => true,
                                'conditions' => '',
                                'fields' => '',
								'order' => 'Photo.title ASC',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),

    );	


}
?>