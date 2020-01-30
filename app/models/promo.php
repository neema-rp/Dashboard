<?php
class Promo extends AppModel {
	var $name = 'Promo';
        var $belongsTo = array('Client');
        var $hasMany = array('PromoData');
        
}//end class
