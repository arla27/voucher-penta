<!-- Begin Page Content -->
<div class="page-content d-flex align-items-stretch">
    <div class="default-sidebar">
        <!-- Begin Side Navbar -->
        <nav class="side-navbar box-scroll sidebar-scroll">
            <!-- Begin Main Navigation -->
            <ul class="list-unstyled" id="sidebar-menu">
                <li id="dashboard-sid"><a href="<?= $base_url; ?>users/dashboard.php"><i class="la ion-speedometer"></i><span>Dashboard</span></a>
                </li>
                <!-- <li id="reply-sid"><a href="<?= $base_url; ?>users/auto_responder.php"><i class="la ion-reply-all"></i><span>Auto Reply</span></a>
                </li>
                <li id="message-sid"><a href="#dropdown-message" aria-expanded="false" data-toggle="collapse"><i class="la ion-paper-airplane"></i><span>Kirim Pesan</span></a>
                    <ul id="dropdown-message" class="collapse list-unstyled pt-0">
                        <li><a href="<?= $base_url; ?>users/send_msg.php">Send Message</a></li>
                        <li><a href="<?= $base_url; ?>users/broadcast.php">Broadcast</a></li>
                    </ul>
                </li> -->
                <li id="costumer-sid"><a href="#dropdown-contact" aria-expanded="false" data-toggle="collapse"><i class="la la-share-alt"></i><span>Data Costumer</span></a>
                    <ul id="dropdown-contact" class="collapse list-unstyled pt-0">
                        <li><a href="<?= $base_url; ?>users/save_number.php">Nomor Tersimpan</a></li>
                        <li><a href="<?= $base_url; ?>users/saved_contact.php">Kontak</a></li>
                    </ul>
                </li>
                <li id="rest-sid"><a href="#dropdown-icons" aria-expanded="false" data-toggle="collapse"><i class="la la-code"></i><span>Rest API</span></a>
                    <ul id="dropdown-icons" class="collapse list-unstyled pt-0">
                        <li><a href="<?= $base_url; ?>users/doc_api.php">Dokumentasi</a></li>
                    </ul>
                </li>
                <li id="setting-sid"><a href="<?= $base_url; ?>users/setting.php?blast=ApiKey"><i class="la la-cog"></i><span>Settings</span></a>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li><a href="#" data-target="#logoutModal" data-toggle="modal"><i class="la la-power-off"></i><span>Logout</span></a>
                </li>
            </ul>
            <!-- End Main Navigation -->
        </nav>
        <!-- End Side Navbar -->
    </div>
    <!-- End Left Sidebar -->