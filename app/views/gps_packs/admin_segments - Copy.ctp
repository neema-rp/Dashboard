<?php ?>
<script>
$(document).ready(function(){
	//$("#GpAdminAddForm").validationEngine();
});
</script>

<style type="text/css">
input { font-size:100%; border:1px solid #ccc; }
</style>

<div class="Gps form">
    
<?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'segments')));?>
    
    <?php
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$GpsPack['GpsPack']['client_id']));
    echo $this->Form->input('id',array('type'=>'hidden','value'=>$GpsPack['GpsPack']['id']));
    
    //echo '<pre>'; print_r($GpsPack); print_r($gps_settings); echo '</pre>';
    
    ?>
    
	<fieldset>
 		<legend><?php __('GPS - Market Segments'); ?></legend>
                <div class="input">
                        <?php
                        $market_seg_ids = array();
                        if(!empty($GpsPack['GpsPack']['market_segments'])){
                            //echo 'here';
                            $market_seg_ids = explode(',',$GpsPack['GpsPack']['market_segments']);
                        }else if(!empty($gps_settings['GpsSetting']['market_segments'])){
                            //echo 'second sec';
                            $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                        }
                         echo $this->Form->input('MarketSegment', array('multiple' => "multiple",'style' => "height:300px", 'div' => false, 'label' => 'Select MarketSegment','class'=>'validate[required]','options'=>$marketsegments,'value'=>$market_seg_ids));
                        ?>
                    
                            <input type="text" name="tags" id="tags" style="display: none;" />
                    
                </div>
        </fieldset>
<div>
<?php
echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
echo '&nbsp;&nbsp;&nbsp;&nbsp;';
echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'index',$GpsPack['GpsPack']['client_id']), array('class' => 'btn btn-danger', 'escape' => false));
echo $this->Form->end(); ?>
</div>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">

<script>
    $(function() {
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#tags" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  });
  </script>