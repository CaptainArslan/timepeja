<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="h-100" data-simplebar>
        <div class="user-box text-center">
            <img src="{{asset('images/users/user-1.jpg')}}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block" data-bs-toggle="dropdown">Geneva Kennedy</a>
                <div class="dropdown-menu user-pro-dropdown">
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li>
                    <a href="{{ route('home') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Dashboards </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('vehicle.index') }}">
                        <i class="fas fa-car-alt"></i>
                        <span> Vehicles </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('routes.index') }}">
                        <i class="fas fa-route"></i>
                        <span> Routes </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('passenger.index') }}">
                        <i class="fas fa-users"></i>
                        <span> Passenger </span>
                    </a>
                </li>
                <li>
                    <a href="#sidebarOrganization" data-bs-toggle="collapse">
                        <i class="fas fa-user-circle"></i>
                        <span> Manager </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarOrganization">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('manager.index') }}">
                                    <span> Managers </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('schedule.index') }}">Create Schedule</a>
                            </li>
                            <li>
                                <a href="{{ route('schedule.published') }}">Published Schedule</a>
                            </li>
                            <li>
                                <a href="{{ route('log.reports') }}">History Report</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebartransportuser" data-bs-toggle="collapse">
                        <i class="fas fa-users"></i>
                        <span> Transport Users </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebartransportuser">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('user.awaiting') }}">Awaiting Approvals</a>
                            </li>
                            <li>
                                <a href="{{ route('user.approved') }}">Approved User</a>
                            </li>
                            <li>
                                <a href="{{ route('user.disapproved') }}">Disapproved User</a>
                            </li>
                            <li>
                                <a href="{{ route('user.pastuser') }}">Past User</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarDriver" data-bs-toggle="collapse">
                        <i class="fas fa-user-tie"></i>
                        <span> Driver </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarDriver">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('driver.index') }}">
                                    <span> Drivers </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('driver.upcomingTrips') }}">Upcoming Trips</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#request" data-bs-toggle="collapse">
                        <i class="fas fa-users"></i>
                        <span> Request</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="request">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('request') }}">
                                    <span> Add Request </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('request.list') }}">
                                    <span> Request List </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Reports -->
                <!-- <li>
                    <a href="#reports" data-bs-toggle="collapse">
                        <i class=" fas fa-file-alt"></i>
                        <span> Reports </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="reports">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('revenue') }}">Revenue</a>
                            </li>
                            <li>
                                <a href="{{ route('expense') }}">Expense</a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <li>
                    <a href="#settings" data-bs-toggle="collapse">
                        <i class="fas fa-cogs"></i>
                        <span> Setting </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="settings">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('setting.google') }}">Google Map Api</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>