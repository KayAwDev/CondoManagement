<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $tabTitle }}</title>
    <link href="{!! asset('images/favicon.ico') !!}" rel="shortcut icon" type="image/x-icon" />
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset('AdminLTE/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Font Awesome Icons -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Ionicons -->
    <link href="{{ asset('css/ionicons.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('AdminLTE/dist/css/skins/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />

    <!--js-->
    <script src="{{ asset('js/jquery/1.11.0/jquery.min.js') }}"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables/dataTables.bootstrap.css') }}">
    <script src=" {{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>

    <!-- moment.js  -->
    <script src=" {{ asset('js/moment.js') }}"></script>

    <!-- jquery ui -->
    <link rel="stylesheet" href="{{ asset('css/1.12.1/jquery-ui.css') }}"/>
    <script src="{{ asset('js/jquery/1.12.1/jquery-ui.js') }}"></script>

    <!-- jquery validate plugin -->
    <script src="{{ asset('js/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/additional-methods.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/dropdown.min.css') }}">
    <script src="{{ asset('js/dropdown.min.js') }}"></script>

    <!-- semantic -->
    <link rel="stylesheet" href="{{ asset('css/semantic.min.css') }}">
    <script src="{{ asset('js/semantic.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/transition.min.css') }}">
    <script src="{{ asset('js/transition.min.js') }}"></script>

    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker3.css') }}">
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <!-- end datepicker-->

    <!-- bootstrap datetimepicker -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <script src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>

    <!-- css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<style>
#alertFlashMessage{
  min-width: auto;
  max-width: 25%;
  position: fixed;
  right: 0px;
  bottom: 0px;
  z-index: 9999;
  font-size: 11px;
  display: none;
}
</style>

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header">
            <!-- Logo -->
            <a href="{{URL::to('/')}}" style="cursor: pointer;" class="logo">
                <span class="logo-mini"><b>{{ $masterTitleMini }}</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>{{ $masterTitle1 }}</b>{{ $masterTitle2 }}</span>
            </a>
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <li class="dropdown user user-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            {{ $userInfo->name }}
                                        <i class="fa fa-fw fa-caret-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <!-- User image -->
                                        <li class="user-header" style="height: auto;">
                                            <p>
                                            {{ $userInfo->name }}
                                            <small>{{ $userInfo->userProfile }}</small>
                                            </p>
                                        </li>
                                        <!-- Menu Footer-->
                                        <li class="user-footer">
                                            <div class="pull-right">
                                                <a href="{{ URL::to('logout') }}" class="btn btn-default btn-flat">Log Out</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <?php
                $activeFlag = false;
                $subActive = false;
                $subActive2 = false;
                $programActive = null;
                $parentProgramActive = null;
                $subParentProgramActive = null;
                foreach($menu as $tempMenu){
                    if(Route::currentRouteName() == $tempMenu->ProgramName){
                        $active = true;
                        $programActive = $tempMenu->ProgramName;
                        $parentProgramActive = $tempMenu->ParentProgramName;
                        $subParentProgramActive = null;
                        break;
                    }else{
                        foreach($tempMenu->subProgram as $tempSubProgram){
                            if(Route::currentRouteName() == $tempSubProgram->ProgramName){
                                $subActive = true;
                                $programActive = $tempSubProgram->ProgramName;
                                $parentProgramActive = $tempSubProgram->ParentProgramName;
                                $subParentProgramActive = null;
                                break;
                            }else{
                                foreach($tempSubProgram->subProgram as $tempSubProgram2){
                                    if(Route::currentRouteName() == $tempSubProgram2->ProgramName){
                                        $subActive2 = true;
                                        $programActive = $tempSubProgram2->ProgramName;
                                        $parentProgramActive = $tempSubProgram2->ParentProgramName;
                                        $subParentProgramActive = $tempSubProgram->ParentProgramName;
                                        break;
                                    }
                                }
                                if($subActive2 == true)
                                    break;
                            }
                        }
                        if($subActive == true)
                            break;
                    }
                }
            ?>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                    @foreach($menu as $m)
                    	@if(count($m->subProgram) == 0)
                        <li class="{{ (($programActive == $m->ProgramName || $parentProgramActive == $m->ProgramName || $subParentProgramActive == $m->ProgramName) ? 'active' : '') }}">
                            <a href="{{ URL::to($m->ProgramName) }}">
                                <i class="fa fa-{{$m->ProgramName}}"></i>
                                <span>{{ $m->MenuName }}</span>
                            </a>
                        </li>
                        @else
                        <li class="treeview {{ (($programActive == $m->ProgramName || $parentProgramActive == $m->ProgramName || $subParentProgramActive == $m->ProgramName) ? 'active': '') }}">
                            <a href="#">
                                <i class="fa fa-{{ $m->ProgramName }}"></i>
                                <span>{{ $m->MenuName }}</span>
                                <span class="pull-right-container">
              						<i class="fa fa-angle-left pull-right"></i>
            					</span>
                            </a>
                            <ul class="treeview-menu">
                            	@foreach($m->subProgram as $sub)
                                    @if(count($sub->subProgram) == 0)
                    					<li class="{{ (($programActive == $sub->ProgramName || $parentProgramActive == $sub->ProgramName || $subParentProgramActive == $sub->ProgramName) ? 'active' : '') }}">
                                            <a href="{{ URL::to($m->ProgramName.'/'.$sub->ProgramName) }}">
                                                <i class="fa fa-circle-o"></i>
                                                <span>{{ $sub->MenuName }}</span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="treeview {{ (($programActive == $sub->ProgramName || $parentProgramActive == $sub->ProgramName || $subParentProgramActive == $sub->ProgramName) ? 'active' : '') }}">
                                            <a href="#">
                                                <i class="fa fa-circle-o"></i>
                                                <span>{{ $sub->MenuName }}</span>
                                                <span class="pull-right-container">
                                                    <i class="fa fa-angle-left pull-right"></i>
                                                </span>
                                            </a>
                                            <ul class="treeview-menu">
                                                @foreach($sub->subProgram as $sub2)
                                                    <li class="{{ (($programActive == $sub2->ProgramName || $parentProgramActive == $sub2->ProgramName || $subParentProgramActive == $sub2->ProgramName) ? 'active' : '') }}">
                                                        <a href="{{ URL::to($m->ProgramName.'/'.$sub->ProgramName.'/'.$sub2->ProgramName) }}">
                                                            <i class="fa fa-circle-o"></i>
                                                            <span>{{ $sub2->MenuName }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
            					@endforeach
          					</ul>
                        </li>
                        @endif
                    @endforeach
                    </ul>
                </section>
            </aside>
            <div class="content-wrapper" style="background-color:white;">
                <div class="fixed">
                    <section class="content">
                        <div>
                            @if(Session::has('message'))
                                <div class="alert alert-warning">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                        </div>
                        @yield('content')
                        <div class="alert alert-success" id="alertFlashMessage">
                            <span></span>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        {{ HTML::script( asset('AdminLTE/bootstrap/js/bootstrap.min.js')) }}
        {{ HTML::script( asset('AdminLTE/dist/js/app.min.js')) }}
    </body>
<script>
var APP_URL = {!! json_encode(url('/')) !!};
var idleTimer = null;
var idleState = false;
var idleWait = '<?php echo $idleTime; ?>';

function alertDialog(string){
    $('#alertFlashMessage span').text(string);
    $('#alertFlashMessage').fadeIn('normal', function() {
      $(this).delay(2500).fadeOut();
   });
}

function logout(){
    $.ajax({
      url: APP_URL+"/logout",
      type: 'GET',
      data: {'sessionFlag': 1},
      success: function(data){
        var url = APP_URL+'/login';
        window.location.replace(url);
      },error: function(){
        //error
      }
    });
}

function mask(string){
    string = string.substring(0, string.length - Math.ceil(string.length/2)).replace(/[a-z\d]/gi,"*") +
    string.substring(string.length - Math.ceil(string.length/2), string.length);

    return string;
}

</script>
</html>
