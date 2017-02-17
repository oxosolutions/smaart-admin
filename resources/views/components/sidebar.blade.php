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

        <li class="{{Request::is('/')?'active':''}}">
          <a href="{{url('/')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li> 
         <li class="{{{(Request::is('organization')?'active':'')}}}">
            <a href="{{url('organization')}}">
              <i class="fa fa-sitemap"></i> <span>Organization</span>
            </a>
          </li> 

        <li class="treeview {{in_array(Request::path(),array('api_users/create','api_users','api_users_meta/create'))?'active':''}}">
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
            <li class="{{Request::is('visualisation')?'active':''}}"><a href="{{ route('setting.list') }}"><i class="fa fa-circle-o"></i> List Settings</a></li> 
           <li class="{{Request::is('visualisation/create')?'active':''}}"><a href="{{route('setting.create')}}"><i class="fa fa-circle-o"></i> Add Setting</a></li>
          </ul>
        </li> -->

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

        <li class="treeview {{in_array(Request::path(),array('visual/create','visual','visual/queries'))?'active':''}}">
          <a href="#">
            <i class="fa fa-line-chart"></i>
            <span>Create Visual Charts</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('visual')?'active':''}}"><a href="{{ route('list.visual') }}"><i class="fa fa-circle-o"></i> List Charts</a></li>
            <li class="{{Request::is('visual/create')?'active':''}}"><a href="{{route('create.visual')}}"><i class="fa fa-circle-o"></i> Create New</a></li>
            <li class="{{Request::is('visual/queries')?'active':''}}"><a href="{{route('create.visual')}}"><i class="fa fa-circle-o"></i> List Queries</a></li>
            <li class="{{Request::is('visual/create')?'active':''}}"><a href="{{route('visual.query.create')}}"><i class="fa fa-circle-o"></i> Create New Query</a></li>
          </ul>
        </li>

        
        <li class="treeview {{in_array(Request::path(),array('map/create','maps'))?'active':''}}">
                  <a href="#">
                    <i class="fa  fa-map-pin"></i>
                    <span>Maps</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('maps')?'active':''}}"><a href="{{ route('map.list') }}"><i class="fa fa-circle-o"></i> List Maps</a></li>
                    <li class="{{Request::is('map/create')?'active':''}}"><a href="{{ route('map.create') }}"><i class="fa fa-circle-o"></i> Add New</a></li>
                  </ul>
        </li>
        <li class="treeview {{in_array(Request::path(),array('surrvey/add','surrveys'))?'active':''}}">
                  <a href="#">
                    <i class="fa  fa-hospital-o"></i>
                    <span>Surrvey Form</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                <ul class="treeview-menu">
                    <li class="{{Request::is('surrveys')?'active':''}}"><a href="{{ route('surrvey.index') }}"><i class="fa fa-circle-o"></i> List Surrveys</a></li>
                    <li class="{{Request::is('surrvey/add')?'active':''}}"><a href="{{ route('surrvey.add') }}"><i class="fa fa-circle-o"></i> Add New</a></li>
                  </ul>
        </li>
        <li class="{{Request::is('config')?'active':''}}">
          <a href="{{url('config')}}">
            <i class="fa fa-gears"></i> <span>API Config</span>
          </a>
        </li> 

        <li class="{{Request::is('settings')?'active':''}}">
          <a href="{{url('settings')}}">
            <i class="fa fa-gear"></i> <span>Settings</span>
          </a>
        </li> 
        <li class="{{Request::is('view_log')?'active':''}}">
          <a href="{{url('view_log')}}">
            <i class="fa fa-history"></i> <span>Log System</span>
          </a>
        </li> 
        
     </section>
    <!-- /.sidebar -->
  </aside>
