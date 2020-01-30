<div class="widget-body">
    <div class="widget-main">
    <h4 class="header blue lighter bigger"> <i class="icon-coffee green"></i> Hotel Login </h4>
    <div class="space-6"></div>
        <?php echo $this->Form->create('Client');?>
        <fieldset>
                <label>
                        <span class="block input-icon input-icon-right">
                                <?php echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required] span12','placeholder'=>'Username')); ?>
                                <i class="icon-user"></i>
                        </span>
                </label>
                <label>
                        <span class="block input-icon input-icon-right">
                                <?php echo $this->Form->input('password',array('id'=>'password','class'=>'validate[required] span12','placeholder'=>'Password')); ?>
                                <i class="icon-lock"></i>
                        </span>
                </label>
                <div class="space"></div>
                <div class="clearfix">
                        <button onclick="$(form).submit();" class="width-35 pull-right btn btn-small btn-primary">
                                <i class="icon-key"></i> Login
                        </button>
                </div>
                <div class="space-4"></div>
        </fieldset>
    <?php echo $this->Form->end();?>
    </div><!--/widget-main-->

    <div class="toolbar clearfix">
        <div>
            <?php echo $this->Html->link("Login As Staff?",array('prefix'=>'staff','staff'=>true,'controller' => 'users', 'action' => 'alllogin', 'client'=>false),array('style'=>'color:#ccc;')); ?> 
<!--            <a href="/clients/forget_password" class="forgot-password-link">
                    <i class="icon-arrow-left"></i>
                    I forgot my password
            </a>-->
        </div>
    </div>
</div><!--/widget-body-->