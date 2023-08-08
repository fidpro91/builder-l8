<?php

use Illuminate\Support\Facades\Session;
?>
<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!-- User box -->
        <div class="user-box text-center">
            <img src="{{asset('assets/themes/image/patient.png')}}" alt="user-img" title="Mat Helme" class="rounded-circle img-thumbnail avatar-lg">
            <p class="text-muted">
                
            </p>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu side-menu">
                <li class="menu-title">Navigation</li>
                <li>
                    <a href="{{url('documentation/index')}}">INSTALASI</a>
                </li>
                <li>
                    <a href="{{url('documentation/basic')}}">FORM BUILDER</a>
                </li>
                <li>
                    <a href="{{url('documentation/widget')}}">WIDGET</a>
                </li>
                <li>
                    <a href="{{url('documentation/bootstrap')}}">BOOTSTRAP COMPONENT</a>
                </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->