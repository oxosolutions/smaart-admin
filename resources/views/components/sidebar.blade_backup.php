<aside class="main-sidebar">
    <section class="sidebar">
        <!-- search form -->
        <form action="#" method="GET" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" id="filter" placeholder="Search..." autocomplete="off">
                <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                  </button>
                </span>
          </div>
        </form>
     
      <ul class="sidebar-menu filtered" style="padding-bottom: 50px">
        <li class="header">MAIN NAVIGATION</li>
      
        <?php
         $index = 0;
        ?>

    @if(Auth::user()->role_id ==null)
        
        @foreach(App\Permisson::allRoute() as $route)
          @if($route->name =='Dashboard')
                <li class="{{{(Request::is('/')?'active':'')}}}">
                  <a href="{{url('/')}}">
                    <i class="fa {{$route->icon}}"></i> <span>Dashboard</span>
                  </a>
                </li> 
                @elseif($route->name =='Api Config')
                 <li class="{{{(Request::is('config')?'active':'')}}}">
                  <a href="{{url('/config')}}">
                    <i class="fa {{$route->icon}}"></i> <span>Api Config</span>
                  </a>
                </li> 
              @else
                  <li class="treeview {{ (Request::is(<?php echo $roots->route_name; ?>) ? 'active' : '') }}">
                      <a href="#">
                        <i class="fa {{$route->icon}}"></i>
                        <span>  {{$route->name}}</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                     <ul class="treeview-menu">
                      @foreach($route->routeMapping as $roots)
                        @if($roots->route_for!='delete')
                          <li class="{{ (Request::is(<?php echo $roots->route_name; ?>) ? 'active' : '') }}">
                           <a href="{{ url($roots->route) }}"><i class="fa fa-circle-o"></i>  {{$roots->route_name}}</a>
                          </li>
                        @endif
                      @endForeach
                    </ul>
                  </li>
              @endif    
        @endforeach 
       @else

      @foreach(App\Role::rolePermisson()->permisson as $permissonRole)
      
         @if($permissonRole->read == 1 || $permissonRole->write ==1 || $permissonRole->other ==1)

            @if($permissonRole->permissons->name =='Dashboard')
                <li class="{{{(Request::is('/')?'active':'')}}}">
                  <a href="{{url('/')}}">
                    <i class="fa {{$permissonRole->permissons->icon}}"></i> <span>Dashboard</span>
                  </a>
                </li> 
                @elseif($permissonRole->permissons->name =='Api Config')
                 <li class="{{{(Request::is('config')?'active':'')}}}">
                  <a href="{{url('/config')}}">
                    <i class="fa {{$permissonRole->permissons->icon}}"></i> <span>Api Config</span>
                  </a>
                </li> 
              @else  
                <li class="treeview {{in_array(Request::path(),array('api_users/create','api_users'))?'active':''}}">
                  <a href="#">
                    <i class="fa {{$permissonRole->permissons->icon}}"></i>
                    <span>  {{$permissonRole->permissons->name}}</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    @foreach($permissonRole->permissons->routeMapping as $routes)
                          
                        @if($routes->route_for !='delete')
                          <?php  $route_for = $routes->route_for; ?>
                          @if($permissonRole->$route_for ==true)
                            <li class="{{ (Request::is(<?php echo $routes->route; ?>) ? 'active' : '') }}"><a href="{{ url($routes->route) }}"><i class="fa fa-circle-o"></i>  {{$routes->route_name}}</a></li>
                          @endif
                      @endif
                    @endforeach
                   
                  </ul>
                </li>
              @endif
          @endif 
          <?php $index++; ?> 
      @endforeach
    
      </ul> 
@endif
    

        <!-- <li class="treeview {{in_array(Request::path(),array('api_users/create','api_users'))?'active':''}}">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Api Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('api_users')?'active':''}}"><a href="{{ route('api.users') }}"><i class="fa fa-circle-o"></i> List Users</a></li>
            <li class="{{Request::is('api_users/create')?'active':''}}"><a href="{{route('api.create_users')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
            <li class="{{Request::is('api_users/create')?'active':''}}"><a href="{{route('api.create_users_meta')}}"><i class="fa fa-circle-o"></i>Add User Meta</a></li>
          </ul>
        </li>
        <li class="treeview {{in_array(Request::path(),array('pages/create','pages'))?'active':''}}">
          <a href="#">
            <i class="fa fa-file-o"></i>
            <span>App Pages</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('pages')?'active':''}}"><a href="{{ route('pages.list') }}"><i class="fa fa-circle-o"></i> List Pages</a></li>
            <li class="{{Request::is('pages/create')?'active':''}}"><a href="{{ route('pages.create') }}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>
        <li class="treeview {{in_array(Request::path(),array('designations/create','designations'))?'active':''}}">
          <a href="#">
            <i class="fa fa-child"></i>
            <span>Designations</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('designations')?'active':''}}"><a href="{{ route('designations.list') }}"><i class="fa fa-circle-o"></i> List Designations</a></li>
            <li class="{{Request::is('designations/create')?'active':''}}"><a href="{{route('designations.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('dataset/create','dataset'))?'active':''}}">
          <a href="#">
            <i class="fa fa-life-ring"></i>
            <span>Datasets</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('dataset')?'active':''}}"><a href="{{ route('datasets.list') }}"><i class="fa fa-circle-o"></i> List Datasets</a></li>
            <li class="{{Request::is('dataset/create')?'active':''}}"><a href="{{route('dataset.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('ministries/create','ministries'))?'active':''}}">
          <a href="#">
            <i class="fa fa-building-o"></i>
            <span>Ministries</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('ministries')?'active':''}}"><a href="{{ route('ministries.list') }}"><i class="fa fa-circle-o"></i> List Ministries</a></li>
            <li class="{{Request::is('ministries/create')?'active':''}}"><a href="{{route('ministries.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('departments/create','departments'))?'active':''}}">
          <a href="#">
            <i class="fa fa-cubes"></i>
            <span>Department</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('departments')?'active':''}}"><a href="{{ route('department.list') }}"><i class="fa fa-circle-o"></i> List Department</a></li>
            <li class="{{Request::is('departments/create')?'active':''}}"><a href="{{route('department.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('goals/create','goals'))?'active':''}}">
          <a href="#">
            <i class="fa fa-gg"></i>
            <span>Goals</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('goals')?'active':''}}"><a href="{{ route('goals.list') }}"><i class="fa fa-circle-o"></i> List Goals</a></li>
            <li class="{{Request::is('goals/create')?'active':''}}"><a href="{{route('goals.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('schema/create','schema'))?'active':''}}">
          <a href="#">
            <i class="fa fa-bullseye"></i>
            <span>Goal Schemes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('schema')?'active':''}}"><a href="{{ route('schema.list') }}"><i class="fa fa-circle-o"></i> List Goal Schemes</a></li>
            <li class="{{Request::is('schema/create')?'active':''}}"><a href="{{route('schema.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('indicators/create','indicators'))?'active':''}}">
          <a href="#">
            <i class="fa fa-lightbulb-o"></i>
            <span>Manage Indicators</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('indicators')?'active':''}}"><a href="{{ route('indicators.list') }}"><i class="fa fa-circle-o"></i> List Indicators</a></li>
            <li class="{{Request::is('indicators/create')?'active':''}}"><a href="{{route('indicators.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('target/create','target'))?'active':''}}">
          <a href="#">
            <i class="fa fa-dot-circle-o"></i>
            <span>Goal Targets</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('target')?'active':''}}"><a href="{{ route('target.list') }}"><i class="fa fa-circle-o"></i> List Goal Targets</a></li>
            <li class="{{Request::is('target/create')?'active':''}}"><a href="{{route('target.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('intervention/create','intervention'))?'active':''}}">
          <a href="#">
            <i class="fa fa-sun-o"></i>
            <span>Goal Interventions</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('intervention')?'active':''}}"><a href="{{ route('intervention.list') }}"><i class="fa fa-circle-o"></i> List Goal Interventions</a></li>
            <li class="{{Request::is('intervention/create')?'active':''}}"><a href="{{ route('intervention.create') }}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

      <!-- role -->
        <!-- <li class="treeview {{in_array(Request::path(),array('visualisation/create','visualisation'))?'active':''}}">
          <a href="#">
            <i class="fa fa-wrench"></i>
            <span>Roles </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('visualisation')?'active':''}}"><a href="{{ route('role.list') }}"><i class="fa fa-circle-o"></i> List Roles</a></li>
            <li class="{{Request::is('visualisation/create')?'active':''}}"><a href="{{route('role.create')}}"><i class="fa fa-circle-o"></i> Add Role</a></li>
          </ul>
        </li> -->
      <!-- role end -->
      <!-- permisson -->

      <!-- <li class="treeview {{in_array(Request::path(),array('visualisation/create','visualisation'))?'active':''}}">
          <a href="#">
            <i class="fa fa-wrench"></i>
            <span>Permissons</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           
            <li class="{{Request::is('visualisation')?'active':''}}"><a href="{{ route('permisson.list') }}"><i class="fa fa-circle-o"></i> List Permissons</a></li>
            <li class="{{Request::is('visualisation/create')?'active':''}}"><a href="{{route('permisson.create')}}"><i class="fa fa-circle-o"></i> Add Pernisson</a></li>
          </ul>
      </li>   -->
       <!-- permisson End-->
         <!-- <li class="treeview {{in_array(Request::path(),array('visualisation/create','visualisation'))?'active':''}}">
          <a href="#">
            <i class="fa fa-wrench"></i>
            <span>Settings </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('visualisation')?'active':''}}"><a href="{{ route('setting.list') }}"><i class="fa fa-circle-o"></i> List Settings</a></li> -->
           <!--  <li class="{{Request::is('visualisation/create')?'active':''}}"><a href="{{route('setting.create')}}"><i class="fa fa-circle-o"></i> Add Setting</a></li> -->
         <!--  </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('visualisation/create','visualisation'))?'active':''}}">
          <a href="#">
            <i class="fa fa-arrows-h"></i>
            <span>Manage Visualisations</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('visualisation')?'active':''}}"><a href="{{ route('visualisation.list') }}"><i class="fa fa-circle-o"></i> List Visualisations</a></li>
            <li class="{{Request::is('visualisation/create')?'active':''}}"><a href="{{route('visualisation.create')}}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="treeview {{in_array(Request::path(),array('resource/create','resource'))?'active':''}}">
          <a href="#">
            <i class="fa fa-minus-square"></i>
            <span>Goal Resources</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('resource')?'active':''}}"><a href="{{ route('resource.list') }}"><i class="fa fa-circle-o"></i> List Goal Resources</a></li>
            <li class="{{Request::is('resource/create')?'active':''}}"><a href="{{ route('resource.create') }}"><i class="fa fa-circle-o"></i> Add New</a></li>
          </ul>
        </li>

        <li class="{{Request::is('config')?'active':''}}">
          <a href="{{url('config')}}">
            <i class="fa fa-gears"></i> <span>API Config</span>
          </a>
        </li>  -->
      
    </section>
    <!-- /.sidebar -->
  </aside>
