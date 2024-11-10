<div class="col-lg-3">
    <div class="sidebar-head d-flex flex-wrap align-items-center justify-content-between">
        <h3 class="sidebar-head-title"><?= ucwords($pageName) ?></h3>
        <div class="sidebar-head-action d-flex align-items-center">
            <div class="sidebar-drop">
                <a class="icon-btn menu-toggler-user-open" href="#"><em class="ni ni-menu"></em></a>
            </div>
        </div>
        <!-- end sidebar-head-action -->
    </div>
    <!-- end sidebar-head -->
    <div class="sidebar sidebar-user-mobile">
        <a href="#" class="icon-btn menu-toggler-user-close">
            <em class="ni ni-cross"></em>
        </a>
        <div class="sidebar-widget">
            <ul class="user-nav">

                <li class="<?= $pageName == 'dashboard' ? 'active' : '' ?>">
                    <a href="dashboard"><em class="ni ni-home me-2"></em> Dashboard </a>
                </li>
                <li class="<?= $pageName == 'deposit' ? 'active' : '' ?>">
                    <a href="deposit_logs"><em class="ni ni-money me-2"></em>Fund Account</a>
                </li>
                <li class="<?= $pageName == 'create NFT' ? 'active' : '' ?>">
                    <a href="nft_logs"><em class="ni ni-file-text me-2"></em>Create NFT</a>
                </li>
                <li class="<?= $pageName == 'purchase NFT' ? 'active' : '' ?>">
                    <a href="purchase_nft"><em class="ni ni-money me-2"></em>Purchase NFT</a>
                </li>
                <li class="<?= $pageName == 'my collections' ? 'active' : '' ?>">
                    <a href="my_collections"><em class="ni ni-folder me-2"></em>My Collections</a>
                </li>
                <li class="<?= $pageName == 'payout' ? 'active' : '' ?>">
                    <a href="payout_logs"><em class="ni ni-exchange me-2"></em>Payouts</a>
                </li>
                <li class="<?= $pageName == 'Activity Log' ? 'active' : '' ?>">
                    <a href="activity.php"><em class="ni ni-exchange me-2"></em>Activity Logs</a>
                </li>
                <li class="<?= $pageName == 'Account Settings' ? 'active' : '' ?>">
                    <a href="account"><em class="ni ni-user me-2"></em>Account Settings</a>
                </li>
                <?php
                if ($currUser->role == 'admin') {
                    $currState = $pageName == "Superadmin" ? "active" : "";
                    $currrState = $pageName == "Manage Users" ? "active" : "";
                    echo '
                    <li class="' . $currState . ' ">
                    <a href="superadmin"><em class="ni ni-lock me-2"></em>Admin</a>
                </li>
                
                    <li class="' . $currrState . ' ">
                    <a href="manage_users"><em class="ni ni-users me-2"></em>Manage Users</a>
                </li>
                        
                        ';
                }
                ?>
                <li>
                    <a href="logout"><em class="me-2" style="width: 20px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg></em>
                        Logout</a>
                </li>

            </ul>
        </div>
        <!-- end sidebar-widget -->
    </div>
    <!-- end sidebar -->
</div>