 <?php
    $mainMenu = $this->uri->segment(1);
    $subMenu = $this->uri->segment(2);
    //$permissions = $this->db->select('permission')->get_where($this->bdp . 'admin', array('id' => $this->session->userdata('rudra_admin_id')))->row();
    //$permisssion = ($permissions->permission != NULL ? explode(',', $permissions->permission) : array('all'));
    $menus = $this->db->get_where($this->bdp . 'menu', array('mn_status' => 'Active', 'fc_id' => 0))->result();
    ?>

 <!-- [ navigation menu ] start -->
 <nav class="pcoded-navbar menupos-static brand-red">
     <div class="navbar-wrapper">
         <div class="navbar-brand header-logo" style="background:#EA7825;">
             <a href="<?= base_url('admin') ?>" class="b-brand">
                 <div class="b-bg">
                     <!-- <i class="feather icon-trending-up"></i> -->
                     <img src="<?= siteLogo(); ?>" height="40px;" />
                 </div>
                 <span class="b-title"><?= siteName(); ?></span>
             </a>
             <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
         </div>
         <div class="navbar-content scroll-div">
             <ul class="nav pcoded-inner-navbar">
                 <li class="nav-item pcoded-menu-caption">
                     <label>Navigation</label>
                 </li>
                 <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item  <?= ($mainMenu == 'dashboard' ? 'active' : '')  ?> pcoded-trigger">
                     <a href="<?= base_url('admin') ?>" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>

                 </li>

                 <?php
                    if (!empty($menus)) {
                        foreach ($menus as $menu) {
                            $sub_menus = $this->db->get_where($this->bdp . 'menu', array('mn_status' => 'Active', 'fc_id' => $menu->id))->result();
                            if (!empty($sub_menus)) {
                    ?>
                             <li data-username="widget Statistic Data Table User card Chart" class="nav-item pcoded-hasmenu <?= ($mainMenu ==  $menu->mn_controller ? 'active  pcoded-trigger' : '')  ?>">
                                 <a href="javascript:void(0)" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layers"></i></span><span class="pcoded-mtext"><?= ucwords($menu->mn_name) ?></span></a>
                                 <ul class="pcoded-submenu">
                                     <?php
                                        foreach ($sub_menus as $smenu) {
                                            $params = ($smenu->mn_params != NULL ? json_decode($smenu->mn_params) : array());

                                        ?>
                                         <li class="<?= ($subMenu == $params[0] ? 'active' : '')  ?>"><a href="<?= base_url() . $smenu->mn_controller . '/' . $smenu->mn_method ?>" class=""><?= ucwords($smenu->mn_name) ?></a></li>
                                     <?php
                                        }
                                        ?>
                                 </ul>
                             </li>

                         <?php
                            } else {
                            ?>
                             <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item <?= ($mainMenu == $menu->mn_controller ? 'active' : '')  ?> ">
                                 <a href="<?= base_url() . $menu->mn_controller . '/' . $menu->mn_method ?>" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layout"></i></span><span class="pcoded-mtext"><?= ucwords($menu->mn_name) ?></span></a>

                             </li>

                 <?php
                            }
                        }
                    }
                    ?>

                 <!--li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item <?= ($mainMenu == 'profile' ? 'active' : '')  ?> ">
                        <a href="<?= base_url('profile') ?>" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layout"></i></span><span class="pcoded-mtext">Profile</span></a>
                        
                    </li>
					
					 <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item <?= ($mainMenu == 'packages' ? 'active' : '')  ?> ">
                        <a href="<?= base_url('packages') ?>" class="nav-link"><span class="pcoded-micon"><i class="feather icon-shopping-cart"></i></span><span class="pcoded-mtext">Packages</span></a>
                        
                    </li>
					
					 <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item <?= ($mainMenu == 'services' ? 'active' : '')  ?> ">
                        <a href="<?= base_url('services') ?>" class="nav-link"><span class="pcoded-micon"><i class="feather icon-shopping-cart"></i></span><span class="pcoded-mtext">Services</span></a>
                        
                    </li>
					
					  <li data-username="widget Statistic Data Table User card Chart" class="nav-item pcoded-hasmenu <?= ($mainMenu == 'stats' ? 'active  pcoded-trigger' : '')  ?>">
                        <a href="javascript:void(0)" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layers"></i></span><span class="pcoded-mtext">Stats</span></a>
                        <ul class="pcoded-submenu">
                            <li class="<?= ($subMenu == 'charges' ? 'active' : '')  ?>"><a href="<?= base_url('stats/charges') ?>" class="">Charges</a></li>
                            <li class="<?= ($subMenu == 'payments' ? 'active' : '')  ?>"><a href="<?= base_url('stats/payments') ?>" class="">Payments</a></li>
                           
                        </ul>
                    </li>
                    <!--li data-username="widget Statistic Data Table User card Chart" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layers"></i></span><span class="pcoded-mtext">Widget</span><span class="pcoded-badge label label-info">100+</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="./widget-statistic.html" class="">Statistic</a></li>
                            <li class=""><a href="./widget-data.html" class="">Data</a></li>
                            <li class=""><a href="./widget-table.html" class="">Table</a></li>
                            <li class=""><a href="./widget-user-card.html" class="">User</a></li>
                            <li class=""><a href="./widget-chart.html" class="">Chart</a></li>
                        </ul>
                    </li-->
                 <li class="nav-item pcoded-menu-caption">
                     <a href="<?= base_url('logout') ?>"> <label>Logout</label></a>
                 </li>

             </ul>
         </div>
     </div>
 </nav>
 <!-- [ navigation menu ] end -->