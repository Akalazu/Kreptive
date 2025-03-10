<?php
$pageName = 'Activity Log';

include_once "portal_settings.php";



?>


<div class="outer__inner">
    <div class="container">
        <div class="row">
            <!-- end col -->

            <div class="card">
                <div class="card-body table__container">
                    <!-- end user-panel-title-box -->
                    <div class="profile-setting-panel-wrap">
                        <ul class="nav nav-tabs nav-tabs-s1 nav-tabs-mobile-size" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#logins" type="button" role="tab" aria-controls="logins" aria-selected="true">
                                    Login
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="bonus-tab" data-bs-toggle="tab" data-bs-target="#bonus" type="button" role="tab" aria-controls="bonus" aria-selected="false">
                                    Bonus
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="deposit-tab" data-bs-toggle="tab" data-bs-target="#deposits" type="button" role="tab" aria-controls="deposits" aria-selected="false">
                                    Deposit
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="withdrawal-tab" data-bs-toggle="tab" data-bs-target="#withdrawals" type="button" role="tab" aria-controls="withdrawals" aria-selected="false">
                                    Withdrawal
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="commission-tab" data-bs-toggle="tab" data-bs-target="#commission" type="button" role="tab" aria-controls="commission" aria-selected="false">
                                    Brokerage
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="kyc-tab" data-bs-toggle="tab" data-bs-target="#kyc" type="button" role="tab" aria-controls="kyc" aria-selected="false">
                                    KYC
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab" aria-controls="sales" aria-selected="false">
                                    Sales
                                </button>
                            </li>


                        </ul>
                        <div class="tab-content mt-4" id="myTabContent">
                            <div class="tab-pane fade" id="bonus" role="tabpanel" aria-labelledby="bonus-tab">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body table__container">
                                            <!-- <h6 class="text-center"><b>Activity Logs</b></h6> -->
                                            </p>
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Reference ID</th>
                                                        <th>Type</th>
                                                        <th>Activity</th>
                                                        <th>Date | Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM `activities_db` WHERE `type` = 'bonus' AND `created_by` = :cb ORDER BY `id` DESC";

                                                    $statement = $pdo->prepare($sql);
                                                    $statement->bindParam(':cb', $currUser->code);
                                                    $statement->execute();
                                                    $j = 1;
                                                    while ($activity = $statement->fetch(PDO::FETCH_OBJ)) {

                                                        echo '
                                                                        <tr>
                                                                            <td>' . $j . '</td>
                                                                            <td class="text-success">' . $activity->reference_id . '</td>
                                                                            <td> Royalties ' . ucfirst($activity->type) . '</td>
                                                                            <td>' . $activity->activity . '</td>
                                                                            <td>' . $activity->time_created . '</td>
                                                                        </td>
                                                                        </tr>
                                                                    ';
                                                        $j++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END -->
                            </div>
                            <div class="tab-pane fade show active" id="logins" role="tabpanel" aria-labelledby="login-tab">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body table__container">
                                            <!-- <h6 class="text-center"><b>Activity Logs</b></h6> -->
                                            </p>
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Reference ID</th>
                                                        <th>Type</th>
                                                        <th>Activity</th>
                                                        <th>Date | Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM `activities_db` WHERE `type` = 'login' AND `created_by` = :cb ORDER BY `id` DESC";

                                                    $statement = $pdo->prepare($sql);
                                                    $statement->bindParam(':cb', $currUser->code);
                                                    $statement->execute();
                                                    $j = 1;
                                                    while ($activity = $statement->fetch(PDO::FETCH_OBJ)) {

                                                        echo '
                                                                        <tr>
                                                                            <td>' . $j . '</td>
                                                                            <td class="text-success">' . $activity->reference_id . '</td>
                                                                            <td> ' . ucfirst($activity->type) . '</td>
                                                                            <td>' . $activity->activity . '</td>
                                                                            <td>' . $activity->time_created . '</td>
                                                                        </td>
                                                                        </tr>
                                                                    ';
                                                        $j++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- END -->
                            </div>
                            <!-- end tab-pane -->
                            <div class="tab-pane fade" id="deposits" role="tabpanel" aria-labelledby="deposit-tab">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body table__container">
                                            <!-- <h6 class="text-center"><b>Activity Logs</b></h6> -->
                                            </p>
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Reference ID</th>
                                                        <th>Type</th>
                                                        <th>Activity</th>
                                                        <th>Date | Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM `activities_db` WHERE `type` = 'deposit' AND `created_by` = :cb ORDER BY `id` DESC";

                                                    $statement = $pdo->prepare($sql);
                                                    $statement->bindParam(':cb', $currUser->code);
                                                    $statement->execute();
                                                    $j = 1;
                                                    while ($activity = $statement->fetch(PDO::FETCH_OBJ)) {

                                                        echo '
                                                                        <tr>
                                                                            <td>' . $j . '</td>
                                                                            <td class="text-success">' . $activity->reference_id . '</td>
                                                                            <td> ' . ucfirst($activity->type) . '</td>
                                                                            <td>' . $activity->activity . '</td>
                                                                            <td>' . $activity->time_created . '</td>
                                                                        </td>
                                                                        </tr>
                                                                    ';
                                                        $j++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab-pane -->
                            <div class="tab-pane fade" id="withdrawals" role="tabpanel" aria-labelledby="withdrawal-tab">

                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body table__container">
                                            <!-- <h6 class="text-center"><b>Activity Logs</b></h6> -->
                                            </p>
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Reference ID</th>
                                                        <th>Type</th>
                                                        <th>Activity</th>
                                                        <th>Date | Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM `activities_db` WHERE `type` = 'withdraw' AND `created_by` = :cb ORDER BY `id` DESC";

                                                    $statement = $pdo->prepare($sql);
                                                    $statement->bindParam(':cb', $currUser->code);
                                                    $statement->execute();
                                                    $j = 1;
                                                    while ($activity = $statement->fetch(PDO::FETCH_OBJ)) {

                                                        echo '
                                                                        <tr>
                                                                            <td>' . $j . '</td>
                                                                            <td class="text-success">' . $activity->reference_id . '</td>
                                                                            <td> ' . ucfirst($activity->type) . '</td>
                                                                            <td>' . $activity->activity . '</td>
                                                                            <td>' . $activity->time_created . '</td>
                                                                        </td>
                                                                        </tr>
                                                                    ';
                                                        $j++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end profile-setting-panel -->
                            <div class="tab-pane fade" id="commission" role="tabpanel" aria-labelledby="withdrawal-tab">

                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body table__container">
                                            <!-- <h6 class="text-center"><b>Activity Logs</b></h6> -->
                                            </p>
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Reference ID</th>
                                                        <th>Type</th>
                                                        <th>Activity</th>
                                                        <th>Date | Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM `activities_db` WHERE (`type` = 'brokerage' OR `type` = 'Brokerage') AND `created_by` = :cb ORDER BY `id` DESC";

                                                    $statement = $pdo->prepare($sql);
                                                    $statement->bindParam(':cb', $currUser->id);
                                                    $statement->execute();
                                                    $j = 1;

                                                    while ($activity = $statement->fetch(PDO::FETCH_OBJ)) {

                                                        echo '
                                                                        <tr>
                                                                            <td>' . $j . '</td>
                                                                            <td class="text-success">' . $activity->reference_id . '</td>
                                                                            <td> ' . ucfirst($activity->type) . '</td>
                                                                            <td>' . $activity->activity . '</td>
                                                                            <td>' . $activity->time_created . '</td>
                                                                        </td>
                                                                        </tr>
                                                                    ';
                                                        $j++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end profile-setting-panel -->
                            <div class="tab-pane fade" id="kyc" role="tabpanel" aria-labelledby="withdrawal-tab">

                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body table__container">
                                            <!-- <h6 class="text-center"><b>Activity Logs</b></h6> -->
                                            </p>
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Reference ID</th>
                                                        <th>Type</th>
                                                        <th>Activity</th>
                                                        <th>Date | Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM `activities_db` WHERE `type` = 'kyc' AND `created_by` = :cb ORDER BY `id` DESC";

                                                    $statement = $pdo->prepare($sql);
                                                    $statement->bindParam(':cb', $currUser->code);
                                                    $statement->execute();
                                                    $j = 1;
                                                    while ($activity = $statement->fetch(PDO::FETCH_OBJ)) {

                                                        echo '
                                                                        <tr>
                                                                            <td>' . $j . '</td>
                                                                            <td class="text-success">' . $activity->reference_id . '</td>
                                                                            <td> ' . ucfirst($activity->type) . '</td>
                                                                            <td>' . $activity->activity . '</td>
                                                                            <td>' . $activity->time_created . '</td>
                                                                        </td>
                                                                        </tr>
                                                                    ';
                                                        $j++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end profile-setting-panel -->
                            <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="withdrawal-tab">

                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body table__container">
                                            <!-- <h6 class="text-center"><b>Activity Logs</b></h6> -->
                                            </p>
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Reference ID</th>
                                                        <th>Type</th>
                                                        <th>Activity</th>
                                                        <th>Date | Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM `activities_db` WHERE `type` = 'sales' AND `created_by` = :cb ORDER BY `id` DESC";

                                                    $statement = $pdo->prepare($sql);
                                                    $statement->bindParam(':cb', $currUser->code);
                                                    $statement->execute();
                                                    $j = 1;
                                                    while ($activity = $statement->fetch(PDO::FETCH_OBJ)) {

                                                        echo '
                                                                        <tr>
                                                                            <td>' . $j . '</td>
                                                                            <td class="text-success">' . $activity->reference_id . '</td>
                                                                            <td> ' . ucfirst($activity->type) . '</td>
                                                                            <td>' . $activity->activity . '</td>
                                                                            <td>' . $activity->time_created . '</td>
                                                                        </td>
                                                                        </tr>
                                                                    ';
                                                        $j++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end profile-setting-panel -->
                        </div>
                        <!-- end tab-pane -->
                    </div>
                    <!-- end tab-content -->
                </div>
            </div>
            <!-- end profile-setting-panel-wrap-->
        </div>
        <!-- end col -->
    </div>

    <!-- end container -->
    <!-- </section> -->
    <!-- end profile-section -->

</div>
<!-- end col -->
</div>



<?php require_once 'portal_footer.php' ?>