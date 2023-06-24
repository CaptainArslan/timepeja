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
                        <i class="fas fa-users-cog"></i>
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
                            <!-- <li>
                                <a href="{{-- route('manager.create') --}}">
                                    <span> Managers List </span>
                                </a>
                            </li> -->
                            <li>
                                <a href="{{ route('schedule.index') }}">Create Schedule</a>
                            </li>
                            <li>
                                <a href="{{ route('schedule.published') }}">Published Schedule</a>
                            </li>
                            <li>
                                <a href="{{ route('log.reports') }}">History Report</a>
                            </li>
                            <!-- <li>
                                    <a href="{{-- route('active_vehicle') --}}">Active Vehicle</a>
                                </li> -->
                            <!-- <li>
                                    <a href="{{-- route('transpot.users') --}}">Transport Users</a>
                                </li> -->
                            <!-- <li>
                                    <a href="{{-- route('user.disapproved') --}}">Add user</a>
                                </li> -->
                            <!-- <li>
                                    <a href="{{-- route('history') --}}">History</a>
                                </li> -->
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
                    <a href="{{ route('vehicle.index') }}">
                        <i class="fas fa-car-alt"></i>
                        <span> Vehicles </span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#sidebarvehicles" data-bs-toggle="collapse">
                        <i class="fas fa-car-alt"></i>
                        <span> Vehicles </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarvehicles">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{-- route('vehicle.create') --}}">Add Vehicle</a>
                            </li>
                            <li>
                                <a href="{{-- route('vehicle.index') --}}">Vehicles</a>
                            </li>
                        </ul>
                    </div>
                </li> -->

                <li>
                    <a href="{{ route('routes.index') }}">
                        <i class="fas fa-route"></i>
                        <span> Routes </span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#sidebarRoute" data-bs-toggle="collapse">
                        <i class="fas fa-route"></i>
                        <span> Routes </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarRoute">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{-- route('routes.create') --}}">
                                    <span> Add Route </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{-- route('routes.index') --}}">
                                    <span> Routes </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> -->

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
                    <a href="#sidebarPassenger" data-bs-toggle="collapse">
                        <i class="fas fa-users"></i>
                        <span> Request</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarPassenger">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('passenger') }}">
                                    <span> Add Request </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('passenger_list') }}">
                                    <span> Request List </span>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ route('trans_schdule')}}">Transport Schedule</a>
                            </li>
                            <li>
                                <a href="{{ route('trans_routes')}}">Transport Routes</a>
                            </li> -->
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#reports" data-bs-toggle="collapse">
                        <i class="fas fa-dollar-sign"></i>
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
                </li>
                <li>
                    <a href="#passenger" data-bs-toggle="collapse">
                        <i class="fas fa-dollar-sign"></i>
                        <span> Passenger </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="passenger">
                        <ul class="nav-second-level">
                            <li>
                                <a href="">Passengers</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Settings -->
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
                <!-- <li>
                    <a href="#sidebarhistory" data-bs-toggle="collapse">
                        <i class="fas fa-history"></i>
                        <span> History </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarhistory">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('customer.trip') }}">Customer Trip</a>
                            </li>
                            <li>
                                <a href="{{ route('bus.passenger') }}">Bus Passenger</a>
                            </li>
                            <li>
                                <a href="{{ route('passenger.to.passenger') }}">Passenger to Passenger</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarsupport" data-bs-toggle="collapse">
                        <i class="fas fa-headset"></i>
                        <span> Support </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarsupport">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('support.chat') }}">Support Chat</a>
                            </li>
                            <li>
                                <a href="{{ route('support') }}">Queries List</a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <!--<li>
                    <a href="{{ route('wallet') }}">
                        <i class="fas fa-wallet"></i>
                        <span> Wallets </span>
                    </a>
                </li>-->
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->