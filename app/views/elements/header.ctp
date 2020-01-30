<?php ?>
<style>
    .ace-nav .nav-user-photo {
     margin: 0 0 0 0;
    border-radius: 4px;
    background: #fff;
     border: none;
     max-width: 100px!important; 
    height: 40px;
}
.ace-nav>li.light-blue {
    background: none;
}
.bigger-130 {
    font-size: 156%;
}
body:before{
    position: absolute;
}
</style>
<div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
                <div class="container-fluid">
                        <a href="#" class="brand">
                                <small>
                                        <i class="icon-signal"></i>
                                        MyDashboard
                                </small>
                        </a><!--/.brand-->
                        <?php
                        $img = '';
                        $name = 'RP';
                        $profile_url = '';
                        $logout_url = '';
                        if ($this->Session->check('Auth.Client')) {
                            //Client
                            $name = $this->Session->read('Auth.Client.firstname').'&nbsp;'.$this->Session->read('Auth.Client.lastname');
                            
                            if(!empty($_SESSION['Auth']['Client']['logo'])){
                                //$img = '/files/clientlogos'. DS . $_SESSION['Auth']['Client']['logo'];
                                $img = '/files/clientlogos'. DS . $_SESSION['Auth']['Client']['logo'];
                            }
                            $profile_url = '/clients/edit';
                            $logout_url = '/clients/logout/';
                        }elseif ($this->Session->check('Auth.User')) {
                            //Staff
                            $client_obj = ClassRegistry::init('Client');
                            $client_data = $client_obj->find('first',array('conditions'=>array('Client.id'=>$_SESSION['Auth']['User']['client_id'])));
	
                            if(!empty($client_data['Client']['logo'])){
                                //$img = '/files/clientlogos'. DS . $client_data['Client']['logo'];
                                $img = '/files/clientlogos'. DS . $client_data['Client']['logo'];
                            }
                            $name = $this->Session->read('Auth.User.firstname').'&nbsp;'.$this->Session->read('Auth.User.lastname');
                            
                            $profile_url = '/users/edit';
                            $logout_url = '/users/logout/';
                        }elseif ($this->Session->check('Auth.Admin')) {
                            if($this->Session->check('Auth.Admin.IsSubAdmin') == '1'){
                                //subadmin
                            }else{
                                //Admin
                            }
                            $img = 'https://myrevenuedashboard.net/img/RP-logo.png';
                            $name = $this->Session->read('Auth.Admin.firstname').'&nbsp;'.$this->Session->read('Auth.Admin.lastname');
                            $profile_url = '/admins/edit/';
                            $logout_url = '/admins/logout/';
                        }
                        ?>

                        <ul class="nav ace-nav pull-right">
                                <li class="light-blue">
                                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                                <?php if($img != ''){ ?>
                                                    <img class="nav-user-photo" src="<?php echo $img; ?>" alt="logo" />
                                                <?php } ?>
                                                <span class="user-info">
                                                        <small>Welcome,</small>
                                                        <?php echo $name; ?>
                                                </span>

                                                <i class="icon-caret-down"></i>
                                        </a>

                                        <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
<!--                                                <li>
                                                        <a href="#">
                                                                <i class="icon-cog"></i>
                                                                Settings
                                                        </a>
                                                </li>-->

                                                <li>
                                                    <a href="<?php echo $profile_url; ?>">
                                                                <i class="icon-user"></i>
                                                                Profile
                                                        </a>
                                                </li>

                                                <li class="divider"></li>

                                                <li>
                                                    <a href="<?php echo $logout_url; ?>">
                                                                <i class="icon-off"></i>
                                                                Logout
                                                        </a>
                                                </li>
                                        </ul>
                                </li>
                        </ul>
                        <!--/.ace-nav-->
                </div><!--/.container-fluid-->
        </div><!--/.navbar-inner-->
</div>

<style>
    #flashMessage{  text-align: center; color:#fff; background-color: green;font-weight:bold; width:100%; height:20px; }
</style>
<script>
$(document).ready(function(){
	//setTimeout( "$('#flashMessage').hide('slideUp');",10000 );
});
</script>