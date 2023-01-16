<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="{{asset('images/users/user-1.jpg')}}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block" data-bs-toggle="dropdown">Geneva Kennedy</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul id="side-menu">
                <!-- <li class="menu-title">Navigation</li> -->
                <li>
                    <a href="{{ route('home') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Dashboards </span>
                    </a>
                </li>
                <li>
                    <a href="#usermanagement" data-bs-toggle="collapse">
                        <i class="fas fa-users"></i>
                        <span> User Management </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="usermanagement">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('modules.index') }}">Modules</a>
                            </li>
                            <li>
                                <a href="{{ route('module-groups.index') }}">Module Groups</a>
                            </li>
                            <li>
                                <a href="{{ route('users.index') }}">All system Users</a>
                            </li>
                            <li>
                                <a href="{{ route('roles.index') }}">User Role</a>
                            </li>
                            <li>
                                <a href="{{ route('permissions.index') }}">Permissions</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarOrganization" data-bs-toggle="collapse">
                        <i class="fas fa-users"></i>
                        <span> Organization </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarOrganization">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('manager') }}">
                                    <span> All Organizations </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('schedule.creation') }}">Creation Schedule</a>
                            </li>
                            <li>
                                <a href="{{ route('schedule.publishes') }}">Published Schedule</a>
                            </li>
                            <li>
                                <a href="{{ route('transpot.schedule') }}">Transport Scheduled</a>
                            </li>
                            <li>
                                <a href="{{ route('transpot.users') }}">Transport Users</a>
                            </li>
                            <li>
                                <a href="{{ route('log.reports') }}">LOG Report</a>
                            </li>
                            <li>
                                <a href="{{ route('awaiting.approvals') }}">Awaiting Approvals</a>
                            </li>
                            <li>
                                <a href="{{ route('history') }}">History</a>
                            </li>
                        </ul>
                    </div>

                </li>
                <li>
                    <a href="{{ route('vehicle') }}">
                        <i class=" fas fa-car-alt"></i>
                        <span> Vehicles </span>
                    </a>
                </li>
                <li>
                    <a href="#sidebarDriver" data-bs-toggle="collapse">
                        <i class="fas fa-users"></i>
                        <span> Driver </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarDriver">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('driver') }}">
                                    <span> All Drivers </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('driver.trips') }}">Upcoming Trips</a>
                            </li>
                            <li>
                                <a href="{{ route('driver.notification') }}">Notification</a>
                            </li>
                            <li>
                                <a href="{{ route('driver.tripstatus') }}">Trip Status</a>
                            </li>
                            <li>
                                <a href="{{ route('driver.triphistory') }}">Trip History</a>
                            </li>
                        </ul>
                    </div>

                </li>
                <li>
                    <a href="#sidebarPassenger" data-bs-toggle="collapse">
                        <i class="fas fa-users"></i>
                        <span> Passenger </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarPassenger">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('passenger') }}">
                                    <span> All Passengers </span>
                                </a>
                            </li>
                            <li>
                                <a href="trans_schdule">Transport Schedule</a>
                            </li>
                            <li>
                                <a href="trans_routes">Transport Routes</a>
                        </ul>
                    </div>

                </li>
                <li>
                    <a href="{{ route('route') }}">
                        <i class="fas fa-route"></i>
                        <span> Route </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('route') }}">
                        <i class="fas fa-bell"></i>
                        <span> Notifications </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('route') }}">
                        <i class="fas fa-wallet"></i>
                        <span> Wallets </span>
                    </a>
                </li>
                <li>
                    <a href="#sidebarTickets" data-bs-toggle="collapse">
                        <i class="fas fa-dollar-sign"></i>
                        <span> Reports </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarTickets">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('revenue') }}">Revenue</a>
                            </li>
                            <li>
                                <a href="{{ route('expense') }}">Expense</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarhistory" data-bs-toggle="collapse">
                        <i class="fas fa-history"></i>
                        <span> History </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarhistory">
                        <ul class="nav-second-level">
                            <li>
                                <a href="#">Customer Trip History</a>
                            </li>
                            <li>
                                <a href="#">Bus Passenger History</a>
                            </li>
                            <li>
                                <a href="#">Passenger to Passenger History</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarSupport" data-bs-toggle="collapse">
                        <i class="fas fa-headset"></i>
                        <span> Support </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSupport">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('active') }}">Active</a>
                            </li>
                            <li>
                                <a href="{{ route('pending') }}">Pending</a>
                            </li>
                            <li>
                                <a href="{{ route('closed') }}">Closed</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->